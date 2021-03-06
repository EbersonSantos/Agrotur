<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Joomla.Config
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Base Display Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_config
 * @since       3.2
 * @note        Needed for front end view
 */
class ConfigControllerApplicationDisplay extends ConfigControllerDisplay
{
	/**
	 * Prefix for the view and model classes
	 *
	 * @var    string
	 * @since  3.2
	 */
	public $prefix = 'Config';
}
