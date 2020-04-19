<?php
/* This is the component's main file and entry point for the frontend part.
   It must redirect to the default controller (or to a proper special controller - if available) */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by RWK
$controller = JController::getInstance('RWK');

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
