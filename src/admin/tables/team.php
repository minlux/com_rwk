<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * Table class for data base tabel #__rwkmatch
 */
class RWKTableTeam extends JTable
{
   var $id = null;
   var $name = null;
   var $disciplin = null;
   var $league = null;
   var $publish = null;
   var $updating = null;
   var $xml = null;
   var $ordering = null;

   /**
    * Constructor
    *
    * @param object Database connector object
    */
   function __construct(&$db)
   {
      parent::__construct('#__rwkteam', 'id', $db);
   }

   //for debugging only!
   public function debug()
   {
      echo 'Class RWKTableTeam';
      echo 'id: "' . $id . '"';
      echo 'name: "' . $name . '"';
      echo 'disciplin: "' . $disciplin . '"';
      echo 'league: "' . $league . '"';
   }
}
