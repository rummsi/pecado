<?php
/**
 * @package        akeebasubs
 * @copyright      Copyright (c)2010-2015 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die();

JLoader::import('joomla.plugin.plugin');

use FOF30\Container\Container;
use Akeeba\Subscriptions\Admin\Model\Subscriptions;

class plgSystemAsexpirationcontrol extends JPlugin
{
	/**
	 * Should this plugin be allowed to run? True if FOF can be loaded and the Akeeba Subscriptions component is enabled
	 *
	 * @var  bool
	 */
	private $enabled = true;

	/**
	 * Public constructor. Overridden to load the language strings.
	 */
	public function __construct(& $subject, $config = array())
	{
		if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
		{
			$this->enabled = false;
		}

		// Do not run if Akeeba Subscriptions is not enabled
		JLoader::import('joomla.application.component.helper');

		if (!JComponentHelper::isEnabled('com_akeebasubs'))
		{
			$this->enabled = false;
		}

		if (!is_object($config['params']))
		{
			JLoader::import('joomla.registry.registry');
			$config['params'] = new JRegistry($config['params']);
		}

		parent::__construct($subject, $config);

		// Timezone fix; avoids errors printed out by PHP 5.3.3+ (thanks Yannick!)
		if (function_exists('date_default_timezone_get') && function_exists('date_default_timezone_set'))
		{
			if (function_exists('error_reporting'))
			{
				$oldLevel = error_reporting(0);
			}
			$serverTimezone = @date_default_timezone_get();
			if (empty($serverTimezone) || !is_string($serverTimezone))
			{
				$serverTimezone = 'UTC';
			}
			if (function_exists('error_reporting'))
			{
				error_reporting($oldLevel);
			}
			@date_default_timezone_set($serverTimezone);
		}

		// Load the language files
		$jlang = JFactory::getLanguage();
		$jlang->load('com_akeebasubs', JPATH_ADMINISTRATOR, 'en-GB', true);
		$jlang->load('com_akeebasubs', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
		$jlang->load('com_akeebasubs', JPATH_ADMINISTRATOR, null, true);

		$jlang->load('com_akeebasubs', JPATH_SITE, 'en-GB', true);
		$jlang->load('com_akeebasubs', JPATH_SITE, $jlang->getDefault(), true);
		$jlang->load('com_akeebasubs', JPATH_SITE, null, true);
	}

	/**
	 * Called when Joomla! is booting up and checks for expired subscriptions.
	 */
	public function onAfterInitialise()
	{
		if (!$this->enabled)
		{
			return;
		}

		// Check if we need to run
		if (!$this->doIHaveToRun())
		{
			return;
		}

		$this->onAkeebasubsCronTask('expirationcontrol');
	}

	public function onAkeebasubsCronTask($task, $options = array())
	{
		if (!$this->enabled)
		{
			return;
		}

		if ($task != 'expirationcontrol')
		{
			return;
		}

		// Get today's date
		JLoader::import('joomla.utilities.date');
		$jNow = new JDate();

		// Load a list of subscriptions which have to expire -- F0F does the rest magically!
		/** @var Subscriptions $subsModel */
		$subsModel = Container::getInstance('com_akeebasubs')->factory->model('Subscriptions')->tmpInstance();
		$subs = $subsModel
			->enabled(1)
			->expires_to($jNow->toSql())
			->get();

		// Update the last run info and quit
		$this->setLastRunTimestamp();
	}

	/**
	 * Fetches the com_akeebasubs component's parameters as a JRegistry instance
	 *
	 * @return JRegistry The component parameters
	 */
	private function getComponentParameters()
	{
		JLoader::import('joomla.registry.registry');

		$component = JComponentHelper::getComponent('com_akeebasubs');

		if ($component->params instanceof JRegistry)
		{
			$cparams = $component->params;
		}
		elseif (!empty($component->params))
		{
			$cparams = new JRegistry($component->params);
		}
		else
		{
			$cparams = new JRegistry('{}');
		}

		return $cparams;
	}

	/**
	 * "Do I have to run?" - the age old question. Let it be answered by checking the
	 * last execution timestamp, stored in the component's configuration.
	 */
	private function doIHaveToRun()
	{
		$params      = $this->getComponentParameters();
		$lastRunUnix = $params->get('plg_akeebasubs_asexpirationcontrol_timestamp', 0);
		$dateInfo    = getdate($lastRunUnix);
		$nextRunUnix = mktime(0, 0, 0, $dateInfo['mon'], $dateInfo['mday'], $dateInfo['year']);
		$nextRunUnix += 24 * 3600;
		$now = time();

		return ($now >= $nextRunUnix);
	}

	/**
	 * Saves the timestamp of this plugin's last run
	 */
	private function setLastRunTimestamp()
	{
		$lastRun = time();
		$params  = $this->getComponentParameters();
		$params->set('plg_akeebasubs_asexpirationcontrol_timestamp', $lastRun);

		$db   = JFactory::getDBO();
		$data = $params->toString();

		$query = $db->getQuery(true)
		            ->update($db->qn('#__extensions'))
		            ->set($db->qn('params') . ' = ' . $db->q($data))
		            ->where($db->qn('element') . ' = ' . $db->q('com_akeebasubs'))
		            ->where($db->qn('type') . ' = ' . $db->q('component'));
		$db->setQuery($query);
		$db->execute();
	}
}