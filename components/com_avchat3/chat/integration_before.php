<?php

//Copyright 2015 NuSoft
//This Joomla Component is distributed under the terms of the GNU General Public License.
//you can redistribute it and/or modify it under the terms of the GNU General Public License 
//as published by the Free Software Foundation, either version 3 of the License, or any 
//later version.

define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);
define('JPATH_BASE', '../../../' );
	
if (!defined('_JDEFINES')) {

	require_once JPATH_BASE.'includes/defines.php';
}

require_once JPATH_BASE.'includes/framework.php';

//SETUP APPLICATION

if (isset($_GET['admin']) && $_GET['admin'] == 'true') {

	$app = JFactory::getApplication('administrator');

} else {

	$app = JFactory::getApplication('site');
}

//INITIALISE THE APPLICATION
$app->initialise();


//GET USER INFOS
$user = JFactory::getUser();


//var_dump($user);

//Initiate db object
$db = JFactory::getDBO();


//GET COMPONENT GENERAL SETTINGS
$config = JComponentHelper::getParams('com_avchat3');

//GET THE ROOM ID
if (isset($_GET['room']) && $_GET['room'] != '') {

	$room_id = $_GET['room'];

} else {

	$room_id = '';
}

//SETUP MAIN community_script. Possible values 'jomsocial' and 'cb'
$community_script = $config->get('avchat3_community_script');

//Check if jomsocial is installed
$query = "SELECT name FROM #__extensions WHERE `element` LIKE 'com_community' and enabled = '1'";
$db->setQuery( $query );
$jomsocial = $db->loadResult();

//Check if community builder is installed
$query = "SELECT name FROM #__extensions WHERE `element` LIKE 'com_comprofiler' and enabled = '1'";
$db->setQuery( $query );
$cb = $db->loadResult();

//Assign avatar if jomsocial is installed
if ($jomsocial != "" || $jomsocial != null) {

	// JomSocial specific
//	require_once (JPATH_BASE.'/components/com_community/libraries/core.php');
//
//	$jomsocial_user = CFactory::getUser($user->get('id'));
//	$jomsocial_avatar = $jomsocial_user->getThumbAvatar();

	//get avatar from jos_community_users
	$query = 'SELECT avatar FROM #__community_users where userid='.$user->get('id');
	$db->setQuery( $query );

	// images/avatar/abf9596ee7cbe831751e0261.gif
	$raw_avatar = $db->loadResult();
	$new_avatar = explode("/", $raw_avatar);

	// Append a 'thumb_' to the file abf9596ee7cbe831751e0261.gif
	$avatar = 'images/avatar/thumb_' . $new_avatar[2];


	//GET GENDER
	$query = "SELECT value FROM #__community_fields_values where user_id=".$user->get('id')." AND field_id = '2'";
	$db->setQuery( $query );
	$gender = $db->loadResult();
	
	//GET BIRTHDAY
	$query = "SELECT value FROM #__community_fields_values where user_id=".$user->get('id')." AND field_id = '3'";
	$db->setQuery( $query );
	$birthday = $db->loadResult();
	$is_jomsocial = true;
}

//Assign avatar if community builder is installed
if ($cb != "" || $cb != null) {

	//get avatar from jos_community_users
	$query = 'SELECT avatar FROM #__comprofiler where avatarapproved="1" AND id='.$user->get('id');
	$db->setQuery( $query );
	$cb_avatar = $db->loadResult();
	$is_cb = true;
}	


//Detect if EasySocial is intalled
$easySocialInstalled = false;
$easySocialInstalled = JComponentHelper::isEnabled('com_easysocial', true);

if ($easySocialInstalled) {

	require_once( JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php' );
	
	//get current user
	$easySocialUserInfo = Foundry::user();
	
	//get user name
	$easySocialUserName = $easySocialUserInfo->getName();
	
	//get user avatar
	$easySocialUserAvatar = $easySocialUserInfo->getAvatar(SOCIAL_AVATAR_MEDIUM);
	
	//get user profile url
	$easySocialUserProfileURL = $easySocialUserInfo->getPermalink();
	
	//get gender field id
	$query = 'SELECT id FROM #__social_fields WHERE unique_key="GENDER"';
	$db->setQuery($query);
	$genderFieldId = $db->loadResult();
	
	//get gender value
	$query = 'SELECT data FROM #__social_fields_data WHERE field_id=' . $genderFieldId . ' AND uid=' . $easySocialUserInfo->id;
	$db->setQuery($query);
	$generValue = $db->loadResult();

}

?>