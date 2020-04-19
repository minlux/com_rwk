<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

//import "helper class" to ...
require_once(JPATH_COMPONENT_SITE.DS.'utilities'.DS.'tocloader.php'); //...download the "TableOfContent from 'rwk-onlinemelder.de'

/**
 * Rundenwettkampf Component Controller (Admin)
 */
class RWKController extends JController
{
   public function __construct()
   {
      parent::__construct();

      //get model
      $model =& $this->getModel('CtrlPanel');

      //set model for "teamedit" view
      $view = $this->getView('teamedit', 'html', 'RWKView');
      $view->setModel($model, true);

      //set model for "matchedit" view
      $view = $this->getView('matchedit', 'html', 'RWKView');
      $view->setModel($model, true);
   }


   private function _getIntArray($varName)
   {
      $intArray = array();
      $varArray = JRequest::getVar($varName, array(), 'post', 'array');
      foreach ($varArray as $var)
      {
         $intArray[] = intval($var);
      }
      return $intArray;
   }


   private function _save()
   {
      //get objects
      $record = array();   //default record
      $model =& $this->getModel('CtrlPanel');   //model
      $view = JRequest::getCmd('view', 'ctrlpanel');  //view

      //switch by the view
      switch ($view)
      {
      case 'teamedit':
         //assemble record
         $id = JRequest::getInt('tid', null);
         $record['id'] = $id;
         $name = JRequest::getVar('name');
         $record['name'] = $name;
         $disciplin = JRequest::getVar('disciplin');
         $record['disciplin'] = $disciplin;
         $league = JRequest::getVar('league');
         $record['league'] = $league;
         //save into database
         $id = $model->saveTeam($record);
         //set teamId and View
         JRequest::setVar('tid', $id);
         JRequest::setVar('view', 'teamedit');
         break;

      case 'matchedit':
         //assemble record
         $id = JRequest::getInt('mid', null);
         $record['id'] = $id;
         $team = JRequest::getInt('team');
         $record['team'] = $team;
         $pass = JRequest::getInt('pass');
         $record['pass'] = $pass;
         $uri = JRequest::getVar('uri');
         $record['uri'] = $uri;
         $updating = JRequest::getInt('updating');
         $record['updating'] = $updating;
         $reset = JRequest::getInt('reset');
         if ($reset == 1)
         {
            $record['xml'] = '';
         }
         //save into database
         $id = $model->saveMatch($record);
         //set teamId and View
         JRequest::setVar('mid', $id);
         JRequest::setVar('view', 'matchedit');
         break;

      default:
         break;
      }
   }


   public function display()
   {
      $view = JRequest::getCmd('view', 'ctrlpanel');
      JRequest::setVar('view', $view);
      parent::display();
   }


   public function addTeam()
   {
      //display
      JRequest::setVar('tid', 0);
      JRequest::setVar('view', 'teamedit');
      parent::display();
   }


   public function addMatch()
   {
      //display
      JRequest::setVar('mid', 0);
      JRequest::setVar('view', 'matchedit');
      parent::display();
   }


   /* switch between teamedit and matchedit by the value of cid[0] */
   public function edit()
   {
      //set default view
      JRequest::setVar('view', 'ctrlpanel');

      //get 1st team id
      $tidArray = JRequest::getVar('tid', array(), 'default', 'array');
      $tid = (($tidArray != null) ? intval($tidArray[0]) : 0);
      if ($tid > 0)
      {
         //set teamId and View
         JRequest::setVar('tid', $tid);
         JRequest::setVar('view', 'teamedit');
      }
      else
      {
         //get 1st match id
         $midArray = JRequest::getVar('mid', array(), 'default', 'array');
         $mid = (($midArray != null) ? intval($midArray[0]) : 0);
         if ($mid > 0)
         {
            //set matchId and View
            JRequest::setVar('mid', $mid);
            JRequest::setVar('view', 'matchedit');
         }
      }

      //display view
      parent::display();
   }


   /* save */
   public function apply()
   {
      //save data
      $this->_save();
      //display view
      parent::display();
   }


   /* save and close */
   public function save()
   {
      //save data
      $this->_save();
      //set redirection
      $this->setRedirect('index.php?option=com_rwk');
   }


   /* close */
   public function cancel()
   {
      //set redirection
      $this->setRedirect('index.php?option=com_rwk');
   }


   /* delete the selected table entries */
   public function remove()
   {
      //get model
      $model =& $this->getModel('CtrlPanel');

      //delete matches from database
      $midArray = $this->_getIntArray('mid');
      $model->deleteMatch($midArray);

      //delete selected teams from database
      $tidArray = $this->_getIntArray('tid');
      $model->deleteTeam($tidArray);

      //set redirection
      $this->setRedirect('index.php?option=com_rwk');
   }


   public function publish($state = 1)
   {
      //get model
      $model =& $this->getModel('CtrlPanel');
      $state = (($state != 0) ? 1 : 0); //do binarization

      //set results 'published' in database
      $tidArray = $this->_getIntArray('tid');
      $model->publishTeam($tidArray, $state);

      //set matches 'published' in database
      $midArray = $this->_getIntArray('mid');
      $model->publishMatch($midArray, $state);

      //set redirection
      $this->setRedirect('index.php?option=com_rwk');
   }

   public function unpublish()
   {
      $this->publish(0);
   }


   //reorder team
   private function _moveTeam($direction) //-1, +1
   {
      //get model
      $model =& $this->getModel('CtrlPanel');

      //get 1st match id
      $idArray = JRequest::getVar('tid', array(), 'default', 'array');
      $id = (($idArray != null) ? intval($idArray[0]) : 0);
      if ($id > 0)
      {
         //move data base row up / down
         $model->moveTeam($id, $direction);
      }

      //set redirection
      $this->setRedirect('index.php?option=com_rwk');
   }

   //swap selected row with the row above
   public function orderUp()
   {
      $this->_moveTeam(-1);
   }

   //swap selected row with the row below
   public function orderDown()
   {
      $this->_moveTeam(1);
   }


   //download CSV file
   public function downloadTOC()
   {
      //get component parameters
      $params =& JComponentHelper::getParams('com_rwk');
      $cookie = $params->get('cookie', ''); //get "cookie" parameter
      $tocurl = $params->get('tocurl', ''); //get "url of table of content"
      $disciplines = $params->get('disciplines', '4=Luftpistole,5=Jugend,6=Luftgewehr'); //get disciplines in form of id=desc,id=desc,id=desc... 

      //get instance of tocloader
      $tocloader = new C_TocLoader($cookie); //initialize cookie data
      $tocloader->downloadCSV($tocurl, $disciplines); //download TOC as CSV
      $tocloader->downloadXML($tocurl, $disciplines); //download TOC as XML
      exit; //exit script execution, because i don't want to have the joomla output in the csv file
   }
}

