<?php
/* This file holds the default frontend controller, which is a class called {ComponentName}Controller.
   This class must extend the base class JController. */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * Rundenwettkampf Component Controller
 */
class RWKController extends JController
{
   function display()
   {
      $viewName = JRequest::getCmd('view', 'teamlist'); //use teamlist as fallback
      JRequest::setVar('view', $viewName);
      parent::display();
   }
}

