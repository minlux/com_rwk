<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * RWK-TeamList View
 */
class RWKViewTeamList extends JView
{
   // Overwriting JView display method
   function display($var_Template = null)
   {
      //get model object
      $var_Model =& $this->getModel();

      /* get "mannschaften" */
      $var_TeamList = $var_Model->getTeams();
      $this->assignRef('var_TeamList', $var_TeamList); //assign the variable to a symbolic name

      //call parent function
      parent::display($var_Template);
   }
}
