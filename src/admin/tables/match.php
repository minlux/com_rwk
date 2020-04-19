<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * Table class for data base tabel #__rwkteam
 */
class RWKTableMatch extends JTable
{
   var $id = null;
   var $team = null;
   var $pass = null;
   var $uri = null;
   var $publish = null;
   var $updating = null;
   var $xml = null;

   /**
    * Constructor
    *
    * @param object Database connector object
    */
   function __construct(&$db)
   {
      parent::__construct('#__rwkmatch', 'id', $db);
   }

   //for debugging only!
   public function debug()
   {
      echo 'Class RWKTableMatch';
      echo 'id: "' . $id . '"';
      echo 'team: "' . $team . '"';
      echo 'pass: "' . $pass . '"';
      echo 'uri: "' . $uri . '"';
   }
}
