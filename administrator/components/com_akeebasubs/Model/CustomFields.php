<?php
/**
 * @package   AkeebaSubs
 * @copyright Copyright (c)2010-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\Subscriptions\Admin\Model;

defined('_JEXEC') or die;

use FOF30\Container\Container;
use FOF30\Model\DataModel;

/**
 * Model for custom field defintions
 *
 * @property  int     $akeebasubs_customfield_id  Primary key
 * @property  string  $title          Field title
 * @property  string  $slug           Field alias
 * @property  string  $show           One of 'all', 'level', 'notlevel'
 * @property  array   $akeebasubs_level_id  The subscription levels where this field is show (show=level) or not show (show=notlevel)
 * @property  string  $type           Field type
 * @property  string  $options        Field options
 * @property  string  $default        Default value
 * @property  bool    $allow_empty    Should we allow empty values?
 * @property  string  $valid_label    Translation key to show next to a valid field
 * @property  string  $invalid_label  Translation key to show next to an invalid field
 * @property  string  $params         Field parameters
 *
 * Filters:
 *
 * @method  $this  akeebasubs_customfield_id()  akeebasubs_customfield_id(int $v)
 * @method  $this  title()                      title(string $v)
 * @method  $this  slug()                       slug(string $v)
 * @method  $this  show()                       show(string $v)
 * @method  $this  akeebasubs_level_id()        akeebasubs_level_id(string $v)
 * @method  $this  type()                       type(string $v)
 * @method  $this  options()                    options(string $v)
 * @method  $this  default()                    default(string $v)
 * @method  $this  allow_empty()                allow_empty(bool $v)
 * @method  $this  valid_label()                valid_label(string $v)
 * @method  $this  invalid_label()              invalid_label(string $v)
 * @method  $this  enabled()                    enabled(bool $v)
 * @method  $this  ordering()                   ordering(int $v)
 * @method  $this  created_by()                 created_by(int $v)
 * @method  $this  created_on()                 created_on(string $v)
 * @method  $this  modified_by()                modified_by(int $v)
 * @method  $this  modified_on()                modified_on(string $v)
 */
class CustomFields extends DataModel
{
	use Mixin\Assertions, Mixin\ImplodedArrays, Mixin\ImplodedLevels;

	/**
	 * Public constructor.
	 *
	 * @param   Container  $container  The configuration variables to this model
	 * @param   array      $config     Configuration values for this model
	 *
	 * @throws \FOF30\Model\DataModel\Exception\NoTableColumns
	 */
	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);

		$this->addBehaviour('Filters');
	}

	/**
	 * Check the data for validity.
	 *
	 * @return  static  Self, for chaining
	 *
	 * @throws \RuntimeException  When the data bound to this record is invalid
	 */
	public function check()
	{
		$this->assertNotEmpty($this->slug, 'COM_AKEEBASUBS_ERR_SLUG_EMPTY');

		$pattern = '/^[a-z_][a-z0-9_\-]*$/';

		$this->assert(preg_match($pattern, $this->slug), 'COM_AKEEBASUBS_ERR_SLUG_INVALID');

		$this->slug = str_replace('-', '_', $this->slug);

		parent::check();
	}

	/**
	 * Converts the loaded comma-separated list of subscription levels into an array
	 *
	 * @param   string $value The comma-separated list
	 *
	 * @return  array  The exploded array
	 */
	protected function getAkeebasubsLevelIdAttribute($value)
	{
		return $this->getAttributeForImplodedArray($value);
	}

	/**
	 * Converts the array of subscription levels into a comma separated list
	 *
	 * @param   array $value The array of values
	 *
	 * @return  string  The imploded comma-separated list
	 */
	protected function setAkeebasubsLevelIdAttribute($value)
	{
		return $this->setAttributeForImplodedLevels($value);
	}
}