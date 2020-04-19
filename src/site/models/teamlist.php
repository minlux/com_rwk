<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

/**
 * RWK-Team Model
 */
class RWKModelTeamList extends JModelItem
{
   public function getTeams()
   {
      //get database
      $db =& JFactory::getDBO();

      //query database
      $query = 'SELECT * FROM #__rwkteam WHERE publish = 1 ORDER BY ordering ASC';
      $db->setQuery($query);

      //get array of database entries (rows)
      $rows = $db->loadObjectList();
      return $rows;
   }
}
