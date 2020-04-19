<?php
//no direct access to this file
defined('_JEXEC') or die('Restricted access');
//import "helper class" to do XSLT transformation
require_once(JPATH_COMPONENT_SITE.DS.'utilities'.DS.'xsltransform.php');
//add custom stylesheet
JHtml::stylesheet('components/com_rwk/views/style/style.css');
?>

<div id="rwkteamresult">
<?php
   //print headline
   echo '<h1>' . $this->team->name . '</h1>';

   $brief = array();
   $details = array();
   $matchXml = new DomDocument();
   $xsltProcessor = new C_XslTransform(); //create new "helper object" for xslt transformation
   $xslFile = JPATH_COMPONENT_SITE.DS.'views'.DS.'teamresult'.DS.'xml2html.xsl'; //set path to XSL stylesheet file

   //for each match: load xml, get brief info and detail information
   foreach ($this->matchList as &$match)
   {
      if ($match->xml != null)
      {
         if ($this->debug > 0)
         {
            echo '<!-- ';
            echo $match->xml;
            echo ' -->';
         }

         //load XML string into DOM
         $matchXml->loadXML($match->xml);

         //transform DOM into XHTML (to get detail information)
         $xhtml = $xsltProcessor->transformToXML($matchXml, $xslFile);
         $details[] = substr($xhtml, 143); //strip XHTML header and store remaining string into details array

         //get brief info
         //get root node <rwk>
         $rootNode = $matchXml->documentElement;
         $wettkampfNodeList = $rootNode->getElementsByTagName('wettkampf'); //get child nodes <wettkampf>
         foreach ($wettkampfNodeList as $wettkampfNode)
         {
            $wettkampf = array();

            //get wettkampf details to be used in brief table
            $mannschaftNodeList = $wettkampfNode->getElementsByTagName('mannschaft'); //get child nodes <mannschaft>
            foreach ($mannschaftNodeList as $mannschaftNode)
            {
               $mannschaft = array();

               //get name attribute
               $mannschaft['name'] = $mannschaftNode->getAttribute('name');
               //get total node
               $totalNodeList = $mannschaftNode->getElementsByTagName('total');
               $mannschaft['total'] = $totalNodeList->item(0)->nodeValue;

               //add mannschaft to wettkampf
               $wettkampf[] = $mannschaft;
            }

            //add wettkampf to rwk
            $brief[] = $wettkampf;
         }
      }
   }


   //print "Tabelle"
   echo '<div class="brief">';
      echo '<span>' . $this->team->league . '</span>';
      echo '<span>Disziplin: ' . $this->team->disciplin . '</span>';
      echo '<table class="tabelle">';
         echo '<tr>';
            echo '<th>Heim-Mannschaft</th>';
            echo '<th>Ringe</th>';
            echo '<th>Ringe</th>';
            echo '<th>Gast-Mannschaft</th>';
         echo '</tr>';
         foreach ($brief as &$wettkampf)
         {
            $heim = &$wettkampf[0];
            $gast = &$wettkampf[1];

            echo '<tr>';
               echo '<td>' . $heim['name'] . '</td>';
               echo '<td>' . $heim['total'] . '</td>';
               echo '<td>' . $gast['total'] . '</td>';
               echo '<td>' . $gast['name'] . '</td>';
            echo '</tr>';
         }
      echo '</table>';
   echo '</div>';

   //print detail information of wettkampf
   foreach ($details as &$wettkampf)
   {
      echo $wettkampf;
   }

/*
   // transform DOM into XHTML
   $xsltProcessor = new C_XslTransform(); //create new "helper object" for xslt transformation
   $xslFile = JPATH_COMPONENT_SITE.DS.'views'.DS.'teamresult'.DS.'xml2html.xsl'; //set path to XSL stylesheet file
   $xhtml = $xsltProcessor->transformToXML($this->xmlDOM, $xslFile);

   //print XHTML
   $xhtml = substr($xhtml, 143); //strip XHTML header
   echo $xhtml;
*/
   //print "back link"
   $link = JRoute::_('index.php?option=com_rwk&view=teamlist');
   echo '<a href="'.$link.'">&lt; Ergebnisse</a>';
?>
</div>
