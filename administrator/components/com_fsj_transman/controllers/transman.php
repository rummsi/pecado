<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_fsj_transman'.DS.'helpers'.DS.'general_helper.php');
jimport("joomla.filesystem.folder");
jimport("joomla.filesystem.file");
class Fsj_transmanControllerTransman extends JControllerLegacy
{
	public function languages()
	{
		$this->setredirect('index.php?option=com_fsj_transman&view=languages');
	}
// 
}
