<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
	
<input type="hidden" name="task" value="" />
<input type="hidden" name="tmpl" value="<?php echo JRequest::getVar('tmpl'); ?>" />
<input type="hidden" name="popup" value="<?php echo JRequest::getVar('popup'); ?>" />
<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />

<?php echo JHtml::_('form.token'); ?>
	