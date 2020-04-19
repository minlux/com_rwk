<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');


class RWKViewTeamEdit extends JView
{
   function display($tpl = null)
   {
      //get team id
      $id = JRequest::getInt('tid');

      //prepare default data
      $team = new stdClass();
      if ($id > 0)
      {
         //get model
         $model =& $this->getModel('CtrlPanel');
         $team = $model->getTeam($id);
      }
      $this->team = $team; //assign to view

      //add title to content
      if ($id == 0)
      {
         JToolBarHelper::title('Mannschaft Administration [New]', 'generic.png');
      }
      else
      {
         JToolBarHelper::title('Mannschaft Administration [Edit]', 'generic.png');
      }
      JToolBarHelper::apply();
      JToolBarHelper::save();
      JToolBarHelper::cancel();

      // Display the template
      parent::display($tpl);
   }
}
