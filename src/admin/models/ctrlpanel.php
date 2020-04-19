<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
//set include path to "tables"
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rwk'.DS.'tables');

/**
 * Backend Model
 */
class RWKModelCtrlPanel extends JModel
{

   public function getTeamMatchTree()
   {
      //get database
      $db = &JFactory::getDBO();

      //query database
      $query = 'SELECT * FROM #__rwkteam ORDER BY ordering ASC';
      $db->setQuery($query);

      //get array of team records from database
      $teamMatchTree = $db->loadObjectList();

      //for each team: add matches
      foreach ($teamMatchTree as &$teamMatch)
      {
         //query database for all "matches" of the "team"
         $query = 'SELECT * FROM #__rwkmatch WHERE team=' . $teamMatch->id . ' ORDER BY pass ASC';
         $db->setQuery($query);

         //get array of team records from database
         $matchList = $db->loadObjectList();
         $teamMatch->match = $matchList;
      }

      //return hierarchical tree of teams and their matches
      return $teamMatchTree;
   }


   public function getTeamList()
   {
      //get database
      $db = &JFactory::getDBO();

      //query database
      $query = 'SELECT * FROM #__rwkteam ORDER BY ordering ASC';
      $db->setQuery($query);

      //get array of team records from database
      $teamList = $db->loadObjectList();
      return $teamList;
   }


   public function getTeam($id)
   {
      //preconditional check
      if ($id == null)
      {
         return null;
      }

      //get database
      $db = &JFactory::getDBO();

      //query database
      $query = 'SELECT * FROM #__rwkteam WHERE id = ' . $id;
      $db->setQuery($query);

      //get array of database entries (rows)
      $teamList = $db->loadObjectList();
      return $teamList[0];
   }


   public function getMatch($id)
   {
      //preconditional check
      if ($id == null)
      {
         return null;
      }

      //get database
      $db = &JFactory::getDBO();

      //query database
      $query = 'SELECT * FROM #__rwkmatch WHERE id = ' . $id;
      $db->setQuery($query);

      //get array of database entries (rows)
      $matchList = $db->loadObjectList();
      return $matchList[0];
   }


   public function moveTeam($id, $direction)
   {
      //get table
      $table = &$this->getTable('Team', 'RWKTable');

      //load table entry and move
      $table->load($id);
      $table->move($direction);
   }


   public function saveTeam(&$record)
   {
//      echo "executing saveTeam<br/>";
//      var_dump($record); //debug only

      //get table
      $table = &$this->getTable('Team', 'RWKTable');
      //preselect ordering for new entries
      if (($record['id'] == null) && ($record['ordering'] == null))
      {
         $record['ordering'] = 0x7FFF;
      }
      //bind record to table
      $retVal = $table->bind($record);
      if ($retVal == false)
      {
         $errMsg = $table->getError();
         JError::raiseWarning(500, $errMsg);
         return null;
      }
      //store data
      $table->store();
      if ($retVal == false)
      {
         $errMsg = $table->getError();
         JError::raiseWarning(500, $errMsg);
         return null;
      }
      //get id of record
      $id = $table->id;
      //reorder table
      $table->reorder();

      //retrun id of record
      return $id;
   }


   public function saveMatch(&$record)
   {
//      echo "executing saveMatch";
//      var_dump($record); //debug only


      //get table
      $table = &$this->getTable('Match', 'RWKTable');
      //bind record to table
      $retVal = $table->bind($record);
      if ($retVal == false)
      {
         $errMsg = $table->getError();
         JError::raiseWarning(500, $errMsg);
         return null;
      }
      //store data
      $table->store();
      if ($retVal == false)
      {
         $errMsg = $table->getError();
         JError::raiseWarning(500, $errMsg);
         return null;
      }

      //return the id
      return $table->id;
   }


   public function publishTeam(&$idArray, $state)
   {
      //get database
      $db = &JFactory::getDBO();

      //set publish state for all "addressed" teams
      foreach ($idArray as $id)
      {
         $query = 'UPDATE #__rwkteam SET publish = ' . $state . ' WHERE id = ' . $id;
         $db->setQuery($query);
         $db->query();
      }
   }


   public function publishMatch(&$idArray, $state)
   {
      //get database
      $db = &JFactory::getDBO();

      //set publish state for all "addressed" matches
      foreach ($idArray as $id)
      {
         $query = 'UPDATE #__rwkmatch SET publish = ' . $state . ' WHERE id = ' . $id;
         $db->setQuery($query);
         $db->query();
      }
   }


   public function deleteTeam(&$idArray)
   {
      //get database
      $db = &JFactory::getDBO();

      //for each team
      foreach ($idArray as $id)
      {
         if ($id > 0)
         {
            //delete all matches related to the team id
            $query = 'DELETE FROM #__rwkmatch WHERE team = ' . $id;
            $db->setQuery($query);
            $db->query();

            //delete "addressed" team
            $query = 'DELETE FROM #__rwkteam WHERE id = ' . $id;
            $db->setQuery($query);
            $db->query();
         }
      }
   }


   public function deleteMatch(&$idArray)
   {
      //get database
      $db = &JFactory::getDBO();

      //for each match
      foreach ($idArray as $id)
      {
         if ($id > 0)
         {
/* geht irgendwie nicht ...
            //get table
            $table = &$this->getTable('Match', 'RWKTable');
            //delete table entry
            $table->delete($id);
*/

            //delete "addressed" match
            $query = 'DELETE FROM #__rwkmatch WHERE id = ' . $id;
            $db->setQuery($query);
            $db->query();
         }
      }
   }
}
