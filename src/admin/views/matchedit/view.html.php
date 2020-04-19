<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');


class RWKViewMatchEdit extends JView
{
   function display($tpl = null)
   {
      //get model
      $model =& $this->getModel('CtrlPanel');

      //get match
      $id = JRequest::getInt('mid');
      $match = new stdClass(); //prepare default data
      if ($id > 0)
      {
         $match = $model->getMatch($id);
      }
      $this->match = $match; //assign to view

      //get team list
      $teamList = $model->getTeamList();
      $this->teamList = $teamList; //assign to view

      //add title to content
      if ($id == 0)
      {
         JToolBarHelper::title('Wettkampf Administration [Add]', 'generic.png');
      }
      else
      {
         JToolBarHelper::title('Wettkampf Administration [Edit]', 'generic.png');
      }
      JToolBarHelper::apply();
      JToolBarHelper::save();
      JToolBarHelper::cancel();

      // Display the template
      parent::display($tpl);
   }
}
