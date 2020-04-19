<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// load tooltip behavior
JHtml::_('behavior.tooltip');

//import "helper class" to do XSLT transformation
require_once(JPATH_COMPONENT_SITE.DS.'utilities'.DS.'xsltransform.php');
//add custom stylesheet
JHtml::stylesheet('components/com_rwk/views/style/teamresult.css');
?>

<form action="<?php echo JRoute::_('index.php?option=com_rwk'); ?>" method="post" name="adminForm">
   <table>
      <tr>
         <td>Parent Team</td>
         <td>
            <select name="team">
            <?php
               foreach ($this->teamList as $team)
               {
                  $selected = '';
                  if ($team->id == $this->match->team)
                  {
                     $selected = ' selected="selected"';
                  }
                  echo '<option value="' . $team->id . '"' . $selected . '>' . $team->name . '</option>';
               }
            ?>
            </select>
         </td>
      </tr>
      <tr>
         <td>Durchgang</td>
         <td><input class="text_area" type="text" name="pass" value="<?php echo $this->match->pass; ?>" /></td>
      </tr>
      <tr>
         <td>URI</td>
         <td><input class="text_area" type="text" name="uri" value="<?php echo $this->match->uri; ?>" /></td>
      </tr>
      <tr>
         <td>Update</td>
         <td>
            <select name="updating">
            <?php
               $selected = (($this->match->updating == 0) ? ' selected="selected"' : '');
               echo '<option value="0"' . $selected . '>Nie</option>';
               $selected = (($this->match->updating == 1) ? ' selected="selected"' : '');
               echo '<option value="1"' . $selected . '>Einmal</option>';
               $selected = (($this->match->updating == 2) ? ' selected="selected"' : '');
               echo '<option value="2"' . $selected . '>Immer</option>';
            ?>
            </select>
         </td>
      </tr>
      <tr>
         <td>XML Reset</td>
         <td><input type="checkbox" name="reset" value="1" /></td>
      </tr>
   </table>
   <div id="rwkteamresult">
   <?php
      if ($this->match->xml != null)
      {
         $matchXml = new DomDocument();
         $xsltProcessor = new C_XslTransform(); //create new "helper object" for xslt transformation
         $xslFile = JPATH_COMPONENT_SITE.DS.'views'.DS.'teamresult'.DS.'xml2html.xsl'; //set path to XSL stylesheet file

         //load XML string into DOM
         $matchXml->loadXML($this->match->xml);

         //transform DOM into XHTML (to get detail information)
         $xhtml = $xsltProcessor->transformToXML($matchXml, $xslFile);
         echo substr($xhtml, 143); //strip XHTML header and echo output
      }
   ?>
   </div>
   <div>
      <input type="hidden" name="task" value="" />
      <input type="hidden" name="view" value="matchedit" />
      <input type="hidden" name="mid" value="<?php echo $this->match->id; ?>" />
   </div>
</form>
