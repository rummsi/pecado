<?php
/**
 * @package        akeebasubs
 * @copyright      Copyright (c)2010-2015 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die();

/**
 * Sample Akeeba Subscriptions plugin which demonstrates the interaction with user data.
 *
 * This plugin will try to pre-populate the country and city fields of the user record based on the IP address of the
 * client. Read the comments to understand what is going on.
 */
class plgAkeebasubsAutocity extends JPlugin
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
		// If the city and country fields are already filled in, we have nothing
		// to do here.
		if (
			!empty($userData->city) &&
			!empty($userData->country)
		)
		{
			return false;
		}

		// Get our IP address
		$ip = \FOF30\Utils\Ip::getIp();

		if (strpos($ip, '::') === 0)
		{
			$ip = substr($ip, strrpos($ip, ':') + 1);
		}

		// No point continuing if we can't get an address, right?
		if (empty($ip))
		{
			return false;
		}

		// Get the GeoLocation information
		$url = 'http://api.hostip.info/?ip=' . urlencode($ip);
		$ch = curl_init($url);
		@curl_setopt($ch, CURLOPT_MAXREDIRS, 20);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		// Pretend we are IE7, so that webservers play nice with us
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)');
		$result = curl_exec($ch);
		curl_close($ch);

		// If no data came through, forget about it
		if (($result === false) || empty($result))
		{
			return false;
		}

		// If that was a private IP address (e.g. 127.0.0.1) ignore
		if (strstr($result, '(Private Address)'))
		{
			return false;
		}

		// Parse the geolocation response
		$ret = array();

		preg_match("@<Hostip>(\s)*<gml:name>(.*?)</gml:name>@si", $result, $match);

		if (empty($userData->city) && is_array($match) && (count($match) >= 2))
		{
			$ret['city'] = $match[2];
		}

		preg_match("@<countryAbbrev>(.*?)</countryAbbrev>@si", $result, $cc_match);

		if (empty($userData->country) && is_array($cc_match) && count($cc_match))
		{
			$ret['country'] = $cc_match[1];
		}

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
	 * @param   array  $data  The user data being saved
	 *
	 * @return  bool  Return false to cancel the user data saving
	 */
	public function onAKUserSaveData(array &$data)
	{
		// Fetch some data from the $row object, e.g.:
		/*
		$city = $row['city'];
		$country = $row['country'];
		*/
		// Do something with that data... You get the picture :)

		// And finally return true to let Akeeba Subs save the data
		return true;
	}
}