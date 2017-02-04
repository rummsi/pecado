<?php
/**
 * @package        akeebasubs
 * @copyright      Copyright (c)2010-2015 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

namespace Akeeba\Subscriptions\Admin\CustomField;

defined('_JEXEC') or die();

/**
 * A multiple selection list field
 *
 * @author Nicholas K. Dionysopoulos
 * @since  2.6.0
 */
class Multiselect extends Dropdown
{
	public function __construct(array $config = array())
	{
		parent::__construct($config);

		$this->input_type = 'multiselect';
	}
}