<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('text');
jimport("joomla.filesystem.folder");

class JFormFieldfsjtmmakepackage extends JFormField
{
	protected function getInput()
	{
		return "";	
	}
	
	function AdminDisplay($value, $name, $item)
	{
		$link = JRoute::_('index.php?option=com_fsj_transman&task=transman.download&id=' . $item->id);
		return "<a class='btn' href='" . $link . "'><i class='icon-download'></i>&nbsp;".JText::_('FSJ_TM_DOWNLOAD')."</a>";
	}
}
