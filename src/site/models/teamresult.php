<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
//set include path to "tables"
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rwk'.DS.'tables');

//import "helper class" to ...
require_once(JPATH_COMPONENT_SITE.DS.'utilities'.DS.'webquery.php'); //...query the match results from 'rwk-onlinemelder.de'
//import "helper class" to do XSLT transformation
require_once(JPATH_COMPONENT_SITE.DS.'utilities'.DS.'xsltransform.php');



/**
 * RWK-Team Model
 */
class RWKModelTeamResult extends JModelItem
{
   private function _saveXML(&$match, &$xml)
   {
      //get table
      $table = &$this->getTable('Match', 'RWKTable');

      //assemble record
      $record = array();   //default record
      $record['id'] = $match->id;
      $record['xml'] = $xml;

      //bind  and store record
      $table->bind($record);
      $table->store();
   }


   private function _updateXML(&$match, $debug = 0)
   {
      //get component parameters
      $params =& JComponentHelper::getParams('com_rwk');
      $cookie = $params->get('cookie', ''); //get "cookie" parameter

      //create query object to request "rwk-onlinemelder"
      $webQuery = new C_RwkWebQuery($cookie); //set cookie
      $htmlString = $webQuery->getHTMLString($match->uri, $debug);
      if ($htmlString != null)
      {
         $htmlDom = new DomDocument();
         $xsltProcessor = new C_XslTransform(); //create new "helper object" for xslt transformation

         //do transformation
         $htmlDom->loadHTML($htmlString); //load html string into DOM object
         $xslFile = JPATH_COMPONENT_SITE.DS.'models'.DS.'html2xml.xsl'; //set path to xsl file
         $xmlDom = $xsltProcessor->transformToDOC($htmlDom, $xslFile);

         //check if web query and transformation leads to the desired xml content
         $rootNode = $xmlDom->documentElement;
         $isValid = $rootNode->hasChildNodes();
         if ($isValid != false)
         {
            //set output format and save xml into string
            $xmlDom->formatOutput = true;
            $xmlString = $xmlDom->saveXML();

            //save xml string into match object and into database
            $match->xml = $xmlString;
            $this->_saveXML($match, $xmlString);
         }
      }
   }


   public function getTeam($tid)
   {
      //get database
      $db =& JFactory::getDBO();
      $tid = intval($tid);

      //query database
      $query = 'SELECT * FROM #__rwkteam WHERE id = ' . $tid . ' AND publish = 1';
      $db->setQuery($query);

      //get array of database entries (rows)
      $teamList = $db->loadObjectList();
      return $teamList[0];
   }


   public function getTeamMatches($tid, $debug = 0)
   {
      //get database
      $db =& JFactory::getDBO();
      $tid = intval($tid);

      //query database
      $query = 'SELECT * FROM #__rwkmatch WHERE team = ' . $tid . ' AND publish = 1 ORDER BY pass ASC';
      $db->setQuery($query);

      //precess all matches assigned to the selected team
      $matchList = $db->loadObjectList();
      foreach ($matchList as &$match)
      {
         //check if it is necessary to start a web request
         if ((($match->updating == 1) && ($match->xml == null)) || //updating == "once" AND not already read
             ($match->updating == 2)) //or updating == "always"
         {
            if ($debug > 0)
            {
               echo '<!-- updating is set to "' . $match->updating . '" -> do update -->';
            }
            //try to update XML in database
            $this->_updateXML($match, $debug);
         }
         else
         {
            if ($debug > 0)
            {
               echo '<!-- do not update XML as updating is set to "' . $match->updating . '" -->';
               if ($match->xml != null)
               {
                  echo '<!-- furthermore there is already a XML in the database! -->';
               }
            }
         }
      }
      //return match object
      return $matchList;
   }
}
