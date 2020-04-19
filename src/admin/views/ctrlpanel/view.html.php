<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');


class RWKViewCtrlPanel extends JView
{
   function display($tpl = null)
   {
      //get model
      $model =& $this->getModel('CtrlPanel');

      //get Team-Match-Tree
      $teamMatchTree = $model->getTeamMatchTree();
      $this->teamMatchTree = $teamMatchTree; //assign to view

      //add title to content
      JToolBarHelper::title('Rundenwettkampf Administration', 'generic.png');
      JToolBarHelper::addNew('addTeam', 'New Team');
      JToolBarHelper::addNew('addMatch', 'Add Match');
      JToolBarHelper::editList();
      JToolBarHelper::deleteList();
      JToolBarHelper::divider();
      JToolBarHelper::publish();
      JToolBarHelper::unpublish();
      JToolBarHelper::divider();
      JToolBarHelper::custom('downloadTOC', 'copy', 'copy', 'TOC', false);
      JToolBarHelper::divider();
      JToolBarHelper::preferences('com_rwk'); //options icon

      // Display the template
      parent::display($tpl);
   }
}
