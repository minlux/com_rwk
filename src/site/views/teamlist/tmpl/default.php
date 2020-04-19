<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

//add custom stylesheet
JHtml::stylesheet('components/com_rwk/views/style/style.css');
?>

<div id="rwk">
   <div id="rwkteamlist">
   <h2>Mannschaft Liste</h2>
   <ul>
   <?php
      foreach($this->var_TeamList as $var_Team)
      {
         echo '<div class="mannschaft">';
         $var_Link = JRoute::_('index.php?option=com_rwk&view=teamresult&tid=' . $var_Team->id);
         print '<li><a href="' . $var_Link . '">' . $var_Team->name . '</a></li>';
         echo '</div>';
      }
   ?>
   </ul>
   </div>
</div>

