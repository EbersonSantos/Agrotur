<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template.system
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');

include ('includes/includes.php');
require_once 'includes/Mobile_Detect.php';
$detect = new Mobile_Detect;

require_once JPATH_ADMINISTRATOR . '/components/com_users/helpers/users.php';

$twofactormethods = UsersHelper::getTwoFactorMethods();

?>
 
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
  <head>
    <jdoc:include type="head" />
    <?php
      $doc->addStyleSheet(JURI::base().'templates/'.$this->template.'/css/template.css');
      if ((int)$detect->version('IE') == ''){
        $doc->addStyleSheet(JURI::base().'templates/'.$this->template.'/css/all-hovers.css');
      }
      else if ((int)$detect->version('IE') <= '8'){
        $doc->addStyleSheet(JURI::base().'templates/'.$this->template.'/css/ie8.css');
      }
      else {
        $doc->addStyleSheet(JURI::base().'templates/'.$this->template.'/css/ie-hover.css');
      }
      $doc->addScript(JURI::base() . 'templates/'.$template.'/js/jquery.validate.min.js');
      $doc->addScript(JURI::base() . 'templates/'.$template.'/js/additional-methods.min.js');
      $doc->addScript(JURI::base().'templates/'.$this->template.'/js/scripts.js');
    ?>
    <!--[if lt IE 8]>
    <div style=' clear: both; text-align:center; position: relative;'>
      <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
        <img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
      </a>
    </div>
    <![endif]-->
    <!--[if lt IE 9]>
    <link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/ie.css" />
    <script src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/html5shiv.js"></script>
    <script src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/html5shiv-printshiv.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="offline_container">
      <div class="container">
        <div class="row">
          <div class="span12">
            <jdoc:include type="message" />
            <div class="well">
              <?php if ($app->getCfg('offline_image')) : ?>
              <img src="<?php echo $app->getCfg('offline_image'); ?>" alt="<?php echo htmlspecialchars($app->getCfg('sitename')); ?>" />
              <?php endif; ?>
              <div id="logo">
                <a href="<?php echo JURI::base(); ?>">
                  <?php if(isset($logo)) : ?>
                  <img src="<?php echo $logo;?>" alt="<?php echo $sitename; ?>">
                  <h1><?php echo $sitename; ?></h1>
                  <?php else : ?><h1><?php echo wrap_chars_with_span($sitename); ?></h1><?php endif; ?>
                  <span class="hightlight"></span>
                </a>
              </div>
              <?php if ($app->getCfg('display_offline_message', 1) == 1 && str_replace(' ', '', $app->getCfg('offline_message')) != ''): ?>
              <p><?php echo $app->getCfg('offline_message'); ?></p>
              <?php elseif ($app->getCfg('display_offline_message', 1) == 2 && str_replace(' ', '', JText::_('JOFFLINE_MESSAGE')) != ''): ?>
              <p><?php echo JText::_('JOFFLINE_MESSAGE'); ?></p>
              <?php  endif; ?>
              <form action="<?php echo JRoute::_('index.php', true); ?>" method="post" id="form-login" novalidate>
                <fieldset class="input">
                  <p id="form-login-username">
                    <input name="username" id="username" type="text" class="inputbox" alt="<?php echo JText::_('JGLOBAL_USERNAME') ?>" size="18" placeholder="<?php echo JText::_('JGLOBAL_USERNAME') ?>" required>
                  </p>
                  <p id="form-login-password">
                    <input type="password" name="password" class="inputbox" size="18" alt="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" id="passwd" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" required>
                  </p>
                  <?php if (count($twofactormethods) > 1) : ?>
                  <p id="form-login-secretkey">
                    <input type="text" name="secretkey" class="inputbox" size="18" alt="<?php echo JText::_('JGLOBAL_SECRETKEY') ?>" id="secretkey" placeholder="<?php echo JText::_('JGLOBAL_SECRETKEY') ?>">
                  </p>
                  <?php endif; ?>
                  <button type="submit" name="Submit" class="btn btn-primary" value="<?php echo JText::_('JLOGIN') ?>"><?php echo JText::_('JLOGIN') ?></button>
                  <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
                  <p id="form-login-remember">
                    <label class="checkbox" for="remember">
                      <input type="checkbox" name="remember" class="inputbox" value="yes" alt="<?php echo JText::_('JGLOBAL_REMEMBER_ME') ?>" id="remember" />
                      <?php echo JText::_('JGLOBAL_REMEMBER_ME') ?>
                    </label>
                  </p>
                  <?php endif; ?>
                  <input type="hidden" name="option" value="com_users">
                  <input type="hidden" name="task" value="user.login">
                  <input type="hidden" name="return" value="<?php echo base64_encode(JURI::base()) ?>">
                  <?php echo JHtml::_('form.token'); ?>
                </fieldset>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script>
      jQuery(function($){
        $('#form-login').validate({
          errorPlacement: function(error, element){ 
            error.insertAfter(element).hide().slideDown(500);
           }
        });
      })
    </script>
  </body>
</html>