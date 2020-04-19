<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

//import "helper class" to ...
require_once(JPATH_COMPONENT_SITE.DS.'utilities'.DS.'webquery.php'); //...query the match results from 'rwk-onlinemelder.de'


class C_TocLoader
{
   private $_cookie;


   // constructor
   public function __construct($cookie)
   {
      //set cookie data
      $this->_cookie = $cookie;
   }


   //parse select options
   private function _parseSelectOptions($htmlString, $selectName)
   {
      //create DOM object and load html string
      $htmlDom = new DomDocument();
      $htmlDom->loadHTML($htmlString); //load html string into DOM object

      //creat XPath object and load DOm object
      $domxpath = new DOMXPath($htmlDom);

      //do xpath query
      $path = '//option[@value]';
      $optionNodeList = $domxpath->query($path);

      //get node data
      $optionList = array();
      foreach ($optionNodeList as $optionNode)
      {
         //set assoziative array
         $option = array();
         $option['name'] = trim($optionNode->nodeValue);
         $option['value'] = trim($optionNode->getAttribute('value'));

         //add array to container
         $optionList[] = $option;
      }

      //return option list
      return $optionList;
   }


   public function downloadCSV($url, $disciplines)
   {
      //set header - this leads to a download instead of "displaying"
      header("Content-type: application/csv; charset=utf-8");
      header("Content-Disposition: attachment; ; charset=utf-8; filename=rwk.csv");
      header("Pragma: no-cache");
      header("Expires: 0");


      //create query object to request "rwk-onlinemelder"
      $webQuery = new C_RwkWebQuery($this->_cookie); //set cookie

      //print CSV header
      echo "Disziplin;Klasse;Datum;Paarung;URL\n";

      //for each Disziplin
      $disciplineList = explode(',', $disciplines);
      foreach ($disciplineList as $discipline)
      {
         //separate id and description
         $disciplinMap = explode('=', $discipline);

         //query page and parse for html <option value='...'> nodes
         $get = $url . '?discipline=' . $disciplinMap[0];
         $htmlString = $webQuery->getHTMLString($get);
         $classList = this->_parseSelectOptions($htmlString);

         //for each Klasse
         foreach ($classList as $class)
         {
            //query page
            $post = 'xjxfun=load_date&xjxargs[]=' . $class['value'];
            $htmlString = $webQuery->getHTMLString($get, $post);

            //parse for html <option value='...'> nodes
            $htmlSelect = array();
            preg_match("/(<select.*select>)/s", $htmlString, $htmlSelect); //clean up by regular expression before
            $lapList = this->_parseSelectOptions($htmlSelect[0]);

            //for each Runde
            foreach ($lapList as $lap)
            {
               //query page
               $post = 'xjxfun=load_class&xjxargs[]=' . $class['value'] . '&xjxargs[]=' . urlencode($lap['value']);
               $htmlString = $webQuery->getHTMLString($get, $post);

               //parse for html <option value='...'> nodes
               $htmlSelect = array();
               preg_match("/(<select.*select>)/s", $htmlString, $htmlSelect); //clean up by regular expression before
               $competitionList = this->_parseSelectOptions($htmlSelect[0]);

               //for each Paarung
               foreach ($competitionList as $competition)
               {
                  //assemble resulting query url
                  $qurl = $url . 'show_sent_competitions.php';
                  $qurl .= '?sel_discipline_id=' . $disciplinMap[0];
                  $qurl .= '&sel_class_id=' . $class['value'];
                  $qurl .= '&sel_turn_date=' . $lap['value'];
                  $qurl .= '&sel_combination_id=' . $competition['value'];
                  $competitionName = preg_replace('/"/', '', utf8_decode($competition['name']));

                  echo $disciplinMap[1] . ';' . $class['name'] . ';' . $lapMap[1] . ';' . $competitionName . ';' . $qurl;
                  echo "\n";
               }
            }
         }
      }
   }


   public function downloadXML($url, $disciplines)
   {
      //set header - this leads to a download instead of "displaying"
      header("Content-type: application/xml; charset=utf-8");
      header("Content-Disposition: attachment; charset=utf-8; filename=rwk.xml");
      header("Pragma: no-cache");
      header("Expires: 0");

      //create query object to request "rwk-onlinemelder"
      $webQuery = new C_RwkWebQuery($this->_cookie); //set cookie

      //create xml document
      $xmlDom = new DomDocument('1.0', 'utf-8');
      $xmlDom->formatOutput = true; //used for indentation and linebreaks
      //creat root node <rwk>
      $rwkNode = $xmlDom->createElement('rwk');
      $xmlDom->appendChild($rwkNode);

      //for each Disziplin
      $disciplineList = explode(',', $disciplines);
      foreach ($disciplineList as $discipline)
      {
         //separate id and description
         $disciplinMap = explode('=', $discipline);

         //add <disziplin id='x' name='...'> node
         $disziplinNode = $xmlDom->createElement('disziplin');
         $disziplinNode->setAttribute('id', $disciplinMap[0]);
         $disziplinNode->setAttribute('name', $disciplinMap[1]);
         $rwkNode->appendChild($disziplinNode);

         //query page and parse for html <option value='...'> nodes
         $get = $url . '?discipline=' . $disciplinMap[0];
         $htmlString = $webQuery->getHTMLString($get);
         $classList = this->_parseSelectOptions($htmlString);

         //for each Klasse
         foreach ($classList as $class)
         {
            //add <klasse id='x' name='...'> node
            $klasseNode = $xmlDom->createElement('klasse');
            $klasseNode->setAttribute('id', $class['value']);
            $klasseNode->setAttribute('name', $class['name']);
            $disziplinNode->appendChild($klasseNode);

            //query page
            $post = 'xjxfun=load_date&xjxargs[]=' . $class['value'];
            $htmlString = $webQuery->getHTMLString($get, $post);

            //parse for html <option value='...'> nodes
            $htmlSelect = array();
            preg_match("/(<select.*select>)/s", $htmlString, $htmlSelect); //clean up by regular expression before
            $lapList = this->_parseSelectOptions($htmlSelect[0]);

            //for each Runde
            foreach ($lapList as $lap)
            {
               //separate idx and datum
               $lapMap = explode('|', $lap['value']);

               //add <runde id='x' idx='y' datum='...'> node
               $rundeNode = $xmlDom->createElement('runde');
               $rundeNode->setAttribute('id', $lap['value']);
               $rundeNode->setAttribute('idx', $lapMap[0]);
               $rundeNode->setAttribute('datum', $lapMap[1]);
               $klasseNode->appendChild($rundeNode);

               //query page
               $post = 'xjxfun=load_class&xjxargs[]=' . $class['value'] . '&xjxargs[]=' . urlencode($lap['value']);
               $htmlString = $webQuery->getHTMLString($get, $post);

               //parse for html <option value='...'> nodes
               $htmlSelect = array();
               preg_match("/(<select.*select>)/s", $htmlString, $htmlSelect); //clean up by regular expression before
               $competitionList = this->_parseSelectOptions($htmlSelect[0]);

               //for each Paarung
               foreach ($competitionList as $competition)
               {
                  //assemble resulting query url
                  $qurl = $url . 'show_sent_competitions.php';
                  $qurl .= '?sel_discipline_id=' . $disciplinMap[0];
                  $qurl .= '&sel_class_id=' . $class['value'];
                  $qurl .= '&sel_turn_date=' . $lap['value'];
                  $qurl .= '&sel_combination_id=' . $competition['value'];

                  //add <paarung id='x' name='...'> node
                  $paarungNode = $xmlDom->createElement('paarung');
                  $paarungNode->setAttribute('id', $competition['value']);
                  $competitionName = preg_replace('/"/', '', utf8_decode($competition['name']));
                  $paarungNode->setAttribute('name', $competitionName);
                  $rundeNode->appendChild($paarungNode);

                  //add url as CDATA section
                  $cdata = $xmlDom->createCDATASection($qurl);
                  $paarungNode->appendChild($cdata);
               }
            }
         }
      }

      //return xml document data
      echo $xmlDom->saveXML();
   }

}
?>

