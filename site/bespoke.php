<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_bespoke
 *
 * @copyright   Copyright (C) NPEU 2018.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

// There's no routing needed for this component, but we need to handle extra URL segments and throw
// a 404 if there are any:
$menu_item = JFactory::getApplication()->getMenu()->getActive();
$uri       = JFactory::getURI();
$route     = $menu_item->route;
$path      = trim($uri->getPath(), '/');

if (($route == 'home' && $path != '') || ($route != 'home' && $route != $path)) {
    JError::raiseError(404, JText::_("Page Not Found"));
    return;
}

// Import Joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by Bespoke
$controller = JControllerLegacy::getInstance('Bespoke');

// Perform the Request task
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));

// Redirect if set by the controller
$controller->redirect();