<?php
/**
 * @package        akeebasubs
 * @copyright      Copyright (c)2010-2015 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die();

use Akeeba\Subscriptions\Admin\Helper\Select;
use FOF30\Container\Container;

/**
 * A sample plugin which creates two extra fields, age group and gender.
 * The former is mandatory, the latter is not
 */
class plgAkeebasubsJoomlaprofilesync extends JPlugin
{
	/**
	 * This method is called whenever a user starts a new subscription and
	 * Akeeba Subscriptions wants to fetch user data. You can use it to fetch
	 * user information from additional sources and return them in an array.
	 * The values in the array will replace the values stored in the user's
	 * profile.
	 *
	 * @param   object  $userData  The already fetched user information
	 *
	 * @return  array  A key/value array with user information overrides
	 */
	public function onAKUserGetData($userData)
	{
		if (empty($userData->username))
		{
			return array();
		}

		$user_id = JFactory::getUser($userData->username)->id;

		$db = JFactory::getDbo();

		// Load existing #__user_profiles records
		$query = $db->getQuery(true)
			->select(array(
				$db->qn('profile_key'),
				$db->qn('profile_value'),
			))
			->from($db->qn('#__user_profiles'))
			->where($db->qn('user_id') . '=' . $db->q($user_id));
		$db->setQuery($query);
		$rows = $db->loadRowList(0);

		// If we don't have profile records just quit
		if (empty($rows))
		{
			return array();
		}

		// Initialise return value
		$ret = array();

		// Special case: country
		if (isset($rows['profile.country']))
		{
			$country = json_decode($rows['profile.country'][1]);
			if (in_array($country, Select::$countries))
			{
				$country = array_search($country, Select::$countries);
			}
			else
			{
				$country = 'US';
			}

			$rows['profile.country'][1] = json_encode($country);
		}

		// Special case: region
		if (isset($rows['profile.region']))
		{
			if (isset($rows['profile.country']))
			{
				$country = json_decode($rows['profile.country'][1]);
			}
			else
			{
				$country = 'US';
			}

			$state = json_decode($rows['profile.region'][1]);
			$cname = Select::$countries[$country];
			$states = isset(Select::$states[$country]) ? Select::$states[$country] : null;
			if (!is_array($states))
			{
				$states = array();
			}

			if (in_array($state, $states))
			{
				$state = array_search($state, $states);
			}
			else
			{
				$state = '';
			}

			$rows['profile.region'][1] = json_encode($state);
		}

		// Special case: state
		if (isset($rows['profile.region']))
		{
			if (isset($rows['profile.country']))
			{
				$country = json_decode($rows['profile.country'][1]);
			}
			else
			{
				$country = 'US';
			}

			$state = json_decode($rows['profile.region'][1], true);
			$cname = Select::$countries[$country];
			$states = isset(Select::$states[$country]) ? Select::$states[$country] : null;

			if (!is_array($states))
			{
				$states = array();
			}

			if (in_array($state, $states))
			{
				$state = array_search($state, $states);
				$ret['state'] = $state;
			}
		}

		// Rename the postal_code field to zip
		if (isset($rows['profile.postal_code']))
		{
			$rows['profile.zip'] = $rows['profile.postal_code'];
		}

		// Check for basic information
		$basic_keys = [
			'isbusiness', 'businessname', 'occupation', 'vatnumber', 'viesregistered', 'taxauthority', 'address1',
			'address2', 'city', 'zip', 'country'
		];

		foreach ($basic_keys as $key)
		{
			if (isset($rows['profile.' . $key]))
			{
				$ret[$key] = json_decode($rows['profile.' . $key][1], true);

				unset($rows['profile.' . $key]);
			}
		}

		// Special case: tos must be renamed to agreetotos
		if (isset($rows['profile.tos']))
		{
			$rows['akeebasubs.agreetotos'] = $rows['profile.tos'];

			unset($rows['tos']);
		}

		// The rest of the records is treated as extra fields
		$params = array();

		if (!empty($rows))
		{
			foreach ($rows as $key => $row)
			{
				if (substr($key, 0, 11) != 'akeebasubs.')
				{
					continue;
				}

				$key = substr($key, 11);
				$params[$key] = json_decode($row[1]);
			}
		}

		$ret['params'] = $params;

		// Return result
		return $ret;
	}

	/**
	 * This method is called whenever Akeeba Subscriptions is updating the user
	 * record with new information, either during sign-up or when you manually
	 * update this information in the back-end.
	 *
	 * In this plugin, it does nothing, but it serves as an example for any
	 * developer interested in creating, for example, a "bridge" with a social
	 * component like Community Builder or JomSocial.
	 *
	 *
	 * @param   array  $data  The user data being saved
	 *
	 * @return  bool  Return false to cancel the user data saving
	 */
	public function onAKUserSaveData(array &$data)
	{
		// Get the user ID, if available
		if (!isset($data['user_id']))
		{
			return true;
		}

		$user_id = $data['user_id'];

		// Remove the params field
		$params = array();

		if (isset($data['params']))
		{
			$params = $data['params'];

			if (is_string($params))
			{
				$params = json_decode($params, true);
			}
			elseif (is_object($params))
			{
				$params = (array)$params;
			}

			unset($data['params']);
		}

		// Remove some fields which must not be saved
		foreach (['akeebasubs_user_id', 'user_id', 'notes', 'input'] as $key)
		{
			if (isset($data[$key]))
			{
				unset($data[$key]);
			}
		}

		// Translate country and state
		if (isset($data['state']))
		{
			$data['state'] = Select::formatState($data['state']);
		}

		if (isset($data['country']))
		{
			$data['country'] = Select::formatCountry($data['country']);
		}

		// Rename the ZIP field
		if (isset($data['zip']))
		{
			$data['postal_code'] = $data['zip'];

			unset($data['zip']);
		}

		// Rename the state field
		if (isset($data['state']))
		{
			$data['region'] = $data['state'];

			unset($data['state']);
		}

		// Convert basic data
		foreach (array_keys($data) as $key)
		{
			$data['profile.' . $key] = json_encode($data[$key]);

			unset($data[$key]);
		}

		// Explode the params field (unless it's an array or object)
		if (!empty($params))
		{
			foreach ($params as $k => $v)
			{
				$data['akeebasubs.' . $k] = json_encode($v);
			}
		}

		$db = JFactory::getDbo();
		$result = true;

		// Loop through all keys, check if they already exist and create/replace them
		if (count($data))
		{
			foreach ($data as $k => $v)
			{
				// Check for an existing record
				$query = $db->getQuery(true)
					->select('*')
					->from($db->qn('#__user_profiles'))
					->where($db->qn('user_id') . '=' . $db->q($user_id))
					->where($db->qn('profile_key') . '=' . $db->q($k));
				$db->setQuery($query);
				$existing = $db->loadObject();

				if (is_object($existing))
				{
					// The record exists. Delete it.
					$query = $db->getQuery(true)
						->delete($db->qn('#__user_profiles'))
						->where($db->qn('user_id') . '=' . $db->q($user_id))
						->where($db->qn('profile_key') . '=' . $db->q($k));
					$db->setQuery($query);
					$db->execute();
				}

				// Insert the new record
				$o = array(
					'user_id'       => $user_id,
					'profile_key'   => $k,
					'profile_value' => $v,
					'ordering'      => 1
				);
				$o = (object)$o;

				$result = $result && $db->insertObject('#__user_profiles', $o);
			}
		}

		return $result;
	}

	/**
	 * Called whenever the administrator asks to refresh integration status.
	 *
	 * @param   int  $user_id  The Joomla! user ID to refresh information for.
	 *
	 * @return  void
	 */
	public function onAKUserRefresh($user_id)
	{
		$container = Container::getInstance('com_akeebasubs');

		/** @var \Akeeba\Subscriptions\Admin\Model\Users $usersModel */
		$usersModel = $container->factory->model('Users')->tmpInstance();

		$mergedData = $usersModel->getMergedData($user_id);

		if (!property_exists($mergedData, 'akeebasubs_user_id'))
		{
			return;
		}

		$akeebasubs_user_id = $mergedData->akeebasubs_user_id;

		try
		{
			$userData = $usersModel->findOrFail($akeebasubs_user_id)->toArray();

			$this->onAKUserSaveData($userData);
		}
		catch (\Exception $e)
		{
			// No user record found for this user. We can't continue.
		}
	}
}