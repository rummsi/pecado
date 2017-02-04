<?php
/**
 * @package        akeebasubs
 * @copyright      Copyright (c)2010-2015 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die();

JLoader::import('joomla.plugin.plugin');

use FOF30\Container\Container;
use Akeeba\Subscriptions\Admin\Model\Levels;
use Akeeba\Subscriptions\Admin\Model\Subscriptions;

class plgSystemAsexpirationnotify extends JPlugin
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
	}

	/**
	 * Called when Joomla! is booting up and checks the subscriptions statuses.
	 * If a subscription is close to expiration, it sends out an email to the user.
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

		$this->onAkeebasubsCronTask('expirationnotify');
	}

	public function onAkeebasubsCronTask($task, $options = array())
	{
		if (!$this->enabled)
		{
			return;
		}

		if ($task != 'expirationnotify')
		{
			return;
		}

		$defaultOptions = array(
			'time_limit' => 2,
		);

		$options = array_merge($defaultOptions, $options);

		// Get today's date
		JLoader::import('joomla.utilities.date');
		$jNow = new JDate();
		$now  = $jNow->toUnix();

		// Start the clock!
		$clockStart = microtime(true);

		// Get and loop all subscription levels
		/** @var Levels $levelsModel */
		$levelsModel = Container::getInstance('com_akeebasubs')->factory->model('Levels')->tmpInstance();
		$levels = $levelsModel
			->enabled(1)
			->get(true);

		// Update the last run info before sending any emails
		$this->setLastRunTimestamp();

		/** @var Levels $level */
		foreach ($levels as $level)
		{
			// Load the notification thresholds and make sure they are sorted correctly!
			$notify1     = $level->notify1;
			$notify2     = $level->notify2;
			$notifyAfter = $level->notifyafter;

			if ($notify2 > $notify1)
			{
				$tmp     = $notify2;
				$notify2 = $notify1;
				$notify1 = $tmp;
			}

			// Make sure we are asked to notify users, at all!
			if (($notify1 <= 0) && ($notify2 <= 0))
			{
				continue;
			}

			// Get the subscriptions expiring within the next $notify1 days for
			// users which we have not contacted yet.
			$jFrom = new JDate($now + 1);
			$jTo   = new JDate($now + $notify1 * 24 * 3600);

			/** @var Subscriptions $subsModel */
			$subsModel = Container::getInstance('com_akeebasubs')->factory->model('Subscriptions')->tmpInstance();

			$subs1 = $subsModel->getClone()
				 ->contact_flag(0)
				 ->level($level->akeebasubs_level_id)
				 ->enabled(1)
				 ->expires_from($jFrom->toSql())
				 ->expires_to($jTo->toSql())
				 ->get(true);

			// Get the subscriptions expiring within the next $notify2 days for
			// users which we have contacted only once
			$subs2 = array();

			if ($notify2 > 0)
			{
				$jFrom = new JDate($now + 1);
				$jTo   = new JDate($now + $notify2 * 24 * 3600);

				$subs2 = $subsModel->getClone()
					->contact_flag(1)
					->level($level->akeebasubs_level_id)
					->enabled(1)
					->expires_from($jFrom->toSql())
					->expires_to($jTo->toSql())
					->get(true);
			}

			// Get the subscriptions expired $notifyAfter days ago
			$subs3 = array();

			if ($notifyAfter > 0)
			{
				// Get all subscriptions expired $notifyAfter + 2 to $notifyAfter days ago. So, if $notifyAfter is 30
				// it will get all subscriptions expired 30 to 32 days ago. This allows us to send emails if the plugin
				// is triggered at least once every two days. Any site with less traffic than that required for the
				// plugin to be triggered every 48 hours doesn't need our software, it needs better marketing to get
				// some users!
				$jFrom = new JDate($now - ($notifyAfter + 2) * 24 * 3600);
				$jTo   = new JDate($now - $notifyAfter * 24 * 3600);

				$subs3 = $subsModel->getClone()
					->level($level->akeebasubs_level_id)
					->enabled(0)
					->expires_from($jFrom->toSql())
					->expires_to($jTo->toSql())
					->get(true);
			}

			// If there are no subscriptions, bail out
			$subs1count = is_object($subs1) ? $subs1->count() : 0;
			$subs2count = is_object($subs2) ? $subs2->count() : 0;
			$subs3count = is_object($subs3) ? $subs3->count() : 0;

			if (($subs1count + $subs2count + $subs3count) == 0)
			{
				continue;
			}

			// Check is some of those subscriptions have been renewed. If so, set their contactFlag to 2
			$realSubs = array();

			foreach (array($subs1, $subs2, $subs3) as $subs)
			{
				/** @var Subscriptions $sub */
				foreach ($subs as $sub)
				{
					// Skip the subscription if the contact_flag is already 3
					if ($sub->contact_flag == 3)
					{
						continue;
					}

					// Get the user and level, load similar subscriptions with start date after this subscription's expiry date
					$renewals = $subsModel->getClone()
						->enabled(1)
						->user_id($sub->user_id)
						->level($sub->akeebasubs_level_id)
						->publish_up($sub->publish_down)
						->get(true);

					if ($renewals->count())
					{
						// The user has already renewed. Don't send him an email; just update the row
						$subsModel->getClone()
						        ->find($sub->akeebasubs_subscription_id)
						        ->save([
							        'contact_flag' => 3
						        ]);

						// Timeout check -- Only if we did make a modification!
						$clockNow = microtime(true);
						$elapsed  = $clockNow - $clockStart;

						if (($options['time_limit'] > 0) && ($elapsed > $options['time_limit']))
						{
							return;
						}
					}
					else
					{
						// No renewals found. Let's nag our user.
						$realSubs[] = $sub;
					}
				}
			}

			// If there are no subscriptions, bail out
			if (empty($realSubs))
			{
				continue;
			}

			// Loop through subscriptions and send out emails, checking for timeout
			$jNow = new JDate();
			$mNow = $jNow->toSql();

			/** @var Subscriptions $sub */
			foreach ($realSubs as $sub)
			{
				// Is it the first or the second contact?
				if ($sub->enabled && ($sub->contact_flag == 0))
				{
					// First contact
					$data = array(
						'contact_flag'  => 1,
						'first_contact' => $mNow
					);

					$result = $this->sendEmail($sub, 'first');
				}
				elseif ($sub->enabled && ($sub->contact_flag == 1))
				{
					// Second and final contact
					$data = array(
						'contact_flag'   => 2,
						'second_contact' => $mNow
					);

					$result = $this->sendEmail($sub, 'second');
				}
				elseif (!$sub->enabled)
				{
					$data = array(
						'contact_flag'  => 3,
						'after_contact' => $mNow
					);

					$result = $this->sendEmail($sub, 'after');
				}
				else
				{
					continue;
				}

				if ($result)
				{
					$table = $subsModel->getClone();
					$table->find($sub->akeebasubs_subscription_id);
					$table->setState('_dontNotify', true);
					$table->save($data);
				}

				// Timeout check -- Only if we sent at least one email!
				$clockNow = microtime(true);
				$elapsed  = $clockNow - $clockStart;

				if (($options['time_limit'] > 0) && ($elapsed > $options['time_limit']))
				{
					// Unset last run timestamp and return
					$this->setLastRunTimestamp(0);

					return;
				}
			}
		}
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
		$lastRunUnix = $params->get('plg_akeebasubs_asexpirationnotify_timestamp', 0);
		$dateInfo    = getdate($lastRunUnix);
		$nextRunUnix = mktime(0, 0, 0, $dateInfo['mon'], $dateInfo['mday'], $dateInfo['year']);
		$nextRunUnix += 24 * 3600;
		$now = time();

		return ($now >= $nextRunUnix);
	}

	/**
	 * Saves the timestamp of this plugin's last run
	 */
	private function setLastRunTimestamp($timestamp = null)
	{
		$lastRun = is_null($timestamp) ? time() : $timestamp;
		$params  = $this->getComponentParameters();
		$params->set('plg_akeebasubs_asexpirationnotify_timestamp', $lastRun);

		$db = JFactory::getDBO();

		$data  = $params->toString('JSON');
		$query = $db->getQuery(true)
		            ->update($db->qn('#__extensions'))
		            ->set($db->qn('params') . ' = ' . $db->q($data))
		            ->where($db->qn('element') . ' = ' . $db->q('com_akeebasubs'))
		            ->where($db->qn('type') . ' = ' . $db->q('component'));
		$db->setQuery($query);
		$db->execute();
	}

	/**
	 * Notifies the component of the supported email keys by this plugin.
	 *
	 * @return  array
	 *
	 * @since 3.0
	 */
	public function onAKGetEmailKeys()
	{
		$this->loadLanguage();

		return array(
			'section' => $this->_name,
			'title'   => JText::_('PLG_SYSTEM_ASEXPIRATIONNOTIFY_EMAILSECTION'),
			'keys'    => array(
				'first'  => JText::_('PLG_SYSTEM_ASEXPIRATIONNOTIFY_EMAIL_FIRST'),
				'second' => JText::_('PLG_SYSTEM_ASEXPIRATIONNOTIFY_EMAIL_SECOND'),
				'after'  => JText::_('PLG_SYSTEM_ASEXPIRATIONNOTIFY_EMAIL_AFTER'),
			)
		);
	}

	/**
	 * Sends a notification email to the user
	 *
	 * @param   Subscriptions  $row   The subscription row
	 * @param   string         $type  Contact type (first, second, after)
	 *
	 * @return  bool
	 */
	private function sendEmail($row, $type)
	{
		// Get the user object
		$user = JFactory::getUser($row->user_id);

		// Get a preloaded mailer
		$key    = 'plg_system_' . $this->_name . '_' . $type;
		$mailer = \Akeeba\Subscriptions\Admin\Helper\Email::getPreloadedMailer($row, $key);

		if ($mailer === false)
		{
			return false;
		}

		$mailer->addRecipient($user->email);
		$result = $mailer->Send();
		$mailer = null;

		return $result;
	}
}