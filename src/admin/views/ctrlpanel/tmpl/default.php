<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// load tooltip behavior
JHtml::_('behavior.tooltip');

//add custom stylesheet
JHtml::stylesheet('administrator/components/com_rwk/views/style/style.css');
?>

<div id="rwk">
<form action="<?php echo JRoute::_('index.php?option=com_rwk'); ?>" method="post" name="adminForm">
   <table class="adminlist">
      <thead>
         <tr>
            <th class="chkbox">
               <?php
                  $count = 0;
                  foreach($this->teamMatchTree as $team)
                  {
                     $count++;
                     $count += count($team->match);
                  }
               ?>
               <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo $count; ?>);" />
            </th>
            <th class="name">
               <?php echo JText::_('COM_RWK_TEAM_HEADING_NAME'); ?>
            </th>
            <th class="publish">
               <?php echo JText::_('Publish'); ?>
            </th>
            <th class="order">
               <?php echo JText::_('Updating'); ?>
            </th>
         </tr>
      </thead>
      <tbody>
         <?php $count = 0; ?>
         <?php foreach($this->teamMatchTree as $team): ?>
            <tr class="team <?php echo 'row' . ($count % 2); ?>">
               <td class="chkbox">
                  <?php echo JHtml::_('grid.id', $count, $team->id, false, 'tid'); ?>
               </td>
               <td>
                  <a href="<?php echo JRoute::_('index.php?option=com_rwk&task=edit&tid[]=' . $team->id); ?>">
                     <?php echo $team->name; ?>
                  </a>
               </td>
               <td class="publish">
                  <?php echo JHtml::_('jgrid.published', $team->publish, $count); ?>
               </td>
               <td class="order">
               <?php
                  echo JHtml::_('jgrid.orderup', $count, 'orderup');
                  echo JHtml::_('jgrid.orderdown', $count, 'orderdown');
               ?>
               </td>
            </tr>
            <?php $count++; ?>
            <?php foreach($team->match as $match): ?>
            <tr class="match <?php echo 'row' . ($count % 2); ?>">
               <td class="chkbox">
                  <?php echo JHtml::_('grid.id', $count, $match->id, false, 'mid'); ?>
               </td>
               <td class="name">
                  <a href="<?php echo JRoute::_('index.php?option=com_rwk&task=edit&mid[]=' . $match->id); ?>">
                     <?php echo $match->uri; ?>
                  </a>
               </td>
               <td class="publish">
                  <?php echo JHtml::_('jgrid.published', $match->publish, $count); ?>
               </td>
               <td class="order">
                  <?php echo $match->updating; ?>
               </td>
            </tr>
            <?php $count++; ?>
            <?php endforeach; ?>
         <?php endforeach; ?>
      </tbody>
   </table>
   <div>
      <input type="hidden" name="task" value="" />
      <input type="hidden" name="boxchecked" value="0" />
      <?php echo JHtml::_('form.token'); ?>
   </div>
</form>
</div>
