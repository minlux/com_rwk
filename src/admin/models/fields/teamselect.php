<?php
// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * TeamSelect Form Field class for the RWK component
 */
class JFormFieldTeamSelect extends JFormFieldList
{
   protected $type = 'RWK';

   protected function getOptions()
   {
      //get database
      $db =& JFactory::getDBO();

      //query database
      $query = 'SELECT * FROM #__rwkteam ORDER BY id ASC';
      $db->setQuery($query);
      //get array of database entries (rows)
      $rows = $db->loadObjectList();

      //setup "select options" for each database entry
      $options = array();
      if ($rows)
      {
         foreach($rows as $team)
         {
            $options[] = JHtml::_('select.option', $team->id, $team->name);
         }
      }
      $options = array_merge(parent::getOptions(), $options);
      return $options;
   }
}
