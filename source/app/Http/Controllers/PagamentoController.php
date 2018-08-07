<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

use Redirect;
use Session;
use URL;

class PagamentoController extends Controller {

  private $_api_context;

  public function __construct(){
      $paypal_conf = \Config::get('paypal');

      $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'],
                                                                    $paypal_conf['secret']));
      $this->_api_context->setConfig($paypal_conf['settings']);
  }

  public function pagarComPayPal(Request $request){

    $payer = new Payer();
    $payer->setPaymentMethod('paypal');

    $item_1 = new Item();
    $item_1->setName($request->get('descricao')) /** item name **/
           ->setCurrency('BRL')
           ->setQuantity(1)
           ->setPrice($request->get('valor')); /** unit price **/

    $item_list = new ItemList();
    $item_list->setItems(array($item_1));

    $amount = new Amount();
    $amount->setCurrency('BRL')
           ->setTotal($request->get('valor'));

    $transaction = new Transaction();
    $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription($request->get('descricao'));

    $redirect_urls = new RedirectUrls();
    $redirect_urls->setReturnUrl(URL::route('tran_sucesso')) /** Specify return URL **/
                  ->setCancelUrl(URL::route('tran_falha'));

    $payment = new Payment();
    $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

    /** dd($payment->create($this->_api_context));exit; **/
    try {
      $payment->create($this->_api_context);
    } catch (\PayPal\Exception\PPConnectionException $ex) {
      if (\Config::get('app.debug')) {

        \Session::put('error', 'Connection timeout');
        return Redirect::route('/contratarAnuncio');

      } else {

        \Session::put('error', 'Some error occur, sorry for inconvenient');
        return Redirect::route('/contratarAnuncio');

      }
    }

    foreach ($payment->getLinks() as $link) {
      if ($link->getRel() == 'approval_url') {
        $redirect_url = $link->getHref();
        break;
      }
    }

    /** add payment ID to session **/
    Session::put('paypal_payment_id', $payment->getId());

    if (isset($redirect_url)) {
      /** redirect to paypal **/
      return Redirect::away($redirect_url);
    }

    \Session::put('error', 'Unknown error occurred');
    return Redirect::route('/contratarAnuncio');
  }

  public function statusPagamento(){

    /** Get the payment ID before session clear **/
    $payment_id = Session::get('paypal_payment_id');

    /** clear the session payment ID **/
    Session::forget('paypal_payment_id');

    if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
      \Session::put('error', 'Payment failed');
      return Redirect::route('/contratarAnuncio');
    }

    $payment = Payment::get($payment_id, $this->_api_context);
    $execution = new PaymentExecution();
    $execution->setPayerId(Input::get('PayerID'));

    /**Execute the payment **/
    $result = $payment->execute($execution, $this->_api_context);

    if ($result->getState() == 'approved') {
      \Session::put('success', 'Payment success');
      return Redirect::route('home');
    }

    \Session::put('error', 'Payment failed');
    return Redirect::route('/contratarAnuncio');

  }
}
