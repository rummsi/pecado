<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport('fsj_core.lib.utils.database');
JFormHelper::loadFieldClass('list');

if (!FSJ_Helper::IsJ3())
{
	//require_once(JPATH_ROOT.DS.'libraries'.DS.'fsj_core'.DS.'html'.DS.'field'.DS.'joomla25'.DS.'tag.php');
}

class JFormFieldFSJTag extends JFormFieldList
{
	protected function getInput()
	{
		$id    = isset($this->element['id']) ? $this->element['id'] : null;
		$cssId = '#' . $this->getId($id, $this->element['name']);
		
		if (!FSJ_Helper::IsJ3())
			JHtml::addIncludePath(JPATH_ROOT.DS.'libraries'.DS.'fsj_core'.DS.'html'.DS.'html'.DS.'joomla25');
		JHtml::_('tag.ajaxfield', $cssId, true);
			
		$par_id = $this->form->getValue("id");
		
		if (!$par_id)
		{			
			$this->value = array();
		} else {
			
			$db	= JFactory::getDbo();
			$qry = "SELECT " . $this->element['fsjtag_text'] . " as text FROM " . $this->element['fsjtag_table'] . " WHERE " . $this->element['fsjtag_pri_key'] . " = " . (int)$db->escape($par_id);	
			$db->setQuery($qry);
			
			$items = $db->loadObjectList();
			
			$this->value = array();			
			foreach ($items as $item)
				$this->value[] = $item->text;
		}
		
		return parent::getInput();
	}
	
	protected function getOptions()
	{

		$db	= JFactory::getDbo();
		
		// select all items from tags where type all fields match
		$query	= $db->getQuery(true)
			->select('id, ' . $this->element['fsjtag_text'] . ' as text, ' . $this->element['fsjtag_value'] . ' as value')
			->from($this->element['fsjtag_table']);
		
		if ($this->element['fsjtag_filter'])
		{
			$query->where($this->element['fsjtag_filter'] . " = " . $db->escape($this->form->getValue($this->element['fsjtag_filter'])));
		}
		
		// group by tag
		$query = (string)$query . " GROUP BY title";

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();
		$options = array_merge(parent::getOptions(), $options);
		
		return $options;
	}
	
	function doAfterSave($field, &$data)
	{
		$db	= JFactory::getDbo();
	
		$tags = $data->array[$field];
		$pri_id = $data->id;

		$table = $this->fsjtag->table;
		$pri_key = $this->fsjtag->pri_key;
		
		$qry = "DELETE FROM {$table} WHERE {$pri_key} = " . $db->escape($pri_id);
		$db->setQuery($qry);
		$db->Query();		
		
		foreach($tags as $tag)
		{
			$tag = str_replace("#new#", "", $tag);
	
			$insert_data = array();
			$insert_data['title'] = $tag;
			$insert_data[$pri_key] = $pri_id;
			
			$fields = explode(",", $this->fsjtag->fields);
			foreach ($fields as $name)
				$insert_data[$name] = $data->$name;
			
			if ($this->fsjtag->state)
				$insert_data['state'] = $data->state;
			
			if ($this->fsjtag->access)
				$insert_data['access'] = $data->access;
			
			if ($this->fsjtag->language)
				$insert_data['language'] = $data->language;
			
			$insert_data['alias'] = JApplication::stringURLSafe($insert_data['title']);

			FSJ_Database::Insert($table, $insert_data);				
		}
	}
	
	function doAfterDelete($field, $pk)
	{	
		if ($pk > 0)
		{
			$db	= JFactory::getDbo();
			$qry = "DELETE FROM " . $this->fsjtag->table . " WHERE " . $this->fsjtag->pri_key . " = " . $db->escape($pk);
			$db->setQuery($qry);
			$db->Query();
		}
	}
	
	function doAfterPublish($field, $pks, $state)
	{
		if (count($pks) < 1)
			return;
		
		$db	= JFactory::getDbo();

		$qry = "UPDATE " . $this->fsjtag->table . " SET state = " . $db->escape($state) . " WHERE " . $this->fsjtag->pri_key . " IN (";
		$qry .= implode(" ,", $pks) . ")";
		
		$db->setQuery($qry);
		$db->Query();
	}
}