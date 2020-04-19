<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');



/**
 * RWK-TeamErgebnis View
 */
class RWKViewTeamResult extends JView
{
   // Overwriting JView display method
   function display($var_Template = null)
   {
      //get model object
      $model =& $this->getModel();

      //get parameter from query string (_GET)
      $tid = JRequest::getInt('tid');
      $debug = JRequest::getInt('debug'); //get debug level from query string
      $this->debug = $debug;

      //get team
      $team = $model->getTeam($tid);
      if ($team != null)
      {
         $this->team = $team; //assign team to view

         //get matches of selected team
         $matchList = $model->getTeamMatches($tid, $debug);
         $this->matchList = $matchList;

//         //create a common xml and assign XML DOM to view
//         $this->xmlDOM = $this->_assembleDOM($matchList);

         //add breadcrumb
         $var_Application =& JFactory::getApplication();
         $var_Pathway = $var_Application->getPathway();
         $var_Pathway->addItem($team->name, '');
      }
      else
      {
//         JError::raiseError(404, JText::_('Invalid ID provided'));
         echo 'Error 404';
      }

      //display view
      parent::display($var_Template);
   }


   private function _assembleDOM(&$matchList)
   {
      //CREATE DOM DOCUMENT -> XML
      /* <rwk>
            <wettkampf durchgang="1" datum="tt.mm.yyyy">
               <mannschaft verein="123" name="xyz">
                 <schutze nummer="x" tag=".">
                   <name>tbd</name>
                   <ringe>123</ringe>
                 </schutze>
                 ...
                 <ersatz nummer="y" tag=".">
                   <name></name>
                   <ringe></ringe>
                 </ersatz>
                 ...
                 <total></total>
                 <bemerkung>...</bemerkung>
               </mannschaft>
              ....
            </wettkampf>
            ...
         </rwk>
      */
      $rwkXml = new DomDocument();
      $matchXml = new DomDocument();

      // create root element <rwk>
      $rwkNode = $rwkXml->createElement("rwk");
      $rwkNode = $rwkXml->appendChild($rwkNode);

      // import element <wettkampf> and all descendants
      foreach ($matchList as &$match)
      {
         if ($match->xml != null)
         {
            $matchXml->loadXML($match->xml);
            $nodeList = $matchXml->getElementsByTagName('wettkampf');
            foreach ($nodeList as $node)
            {
               $wettkampfNode = $rwkXml->importNode($node, true);
               $rwkNode->appendChild($wettkampfNode);
               break;
            }
         }
      }

      //return XML DOM object
      $rwkXml->formatOutput = true;
      return $rwkXml;
   }
}
