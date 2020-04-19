<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<form action="<?php echo JRoute::_('index.php?option=com_rwk'); ?>" method="post" name="adminForm">
   <table>
      <tr>
         <td>Name</td>
         <td><input class="text_area" type="text" name="name" value="<?php echo $this->team->name; ?>" /></td>
      </tr>
      <tr>
         <td>Disziplin</td>
         <td><input class="text_area" type="text" name="disciplin" value="<?php echo $this->team->disciplin; ?>" /></td>
      </tr>
      <tr>
         <td>Klasse</td>
         <td><input class="text_area" type="text" name="league" value="<?php echo $this->team->league; ?>" /></td>
      </tr>
   </table>
   <div>
      <input type="hidden" name="task" value="" />
      <input type="hidden" name="view" value="teamedit" />
      <input type="hidden" name="tid" value="<?php echo $this->team->id; ?>" />
      <?php echo JHtml::_('form.token'); ?>
   </div>
</form>
