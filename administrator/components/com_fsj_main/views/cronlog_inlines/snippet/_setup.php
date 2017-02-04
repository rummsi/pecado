<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHTML::addIncludePath(array(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_fsj_main'.DS.'html')); 

JHtml::_('behavior.multiselect');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

	$ordering 	= $listOrder == 'a.ordering';
	$saveOrder	= $listOrder == 'a.ordering';

?>
