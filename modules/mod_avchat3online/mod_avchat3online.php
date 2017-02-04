<?php
//Copyright 2013 AVChat Software
//This Joomla Module is distributed under the terms of the GNU General Public License.
//you can redistribute it and/or modify it under the terms of the GNU General Public License 
//as published by the Free Software Foundation, either version 3 of the License, or any 
//later version.
defined('DS')?  null : define('DS',DIRECTORY_SEPARATOR);

// No direct access.
defined('_JEXEC') or die;

// Initialise variables.
$config	= JFactory::getConfig();
$user	= JFactory::getUser();
$db		= JFactory::getDbo();
$lang	= JFactory::getLanguage();

$app	= JFactory::getApplication();



//IMPORT JOOMLA UTILITIES
jimport( 'joomla.utilities.simplexml' );
jimport( 'joomla.methods');



//GET COMPONENT GENERAL SETTINGS
$config = JComponentHelper::getParams('com_avchat3');

//GET INSTANCE NAME; NEEDED FOR USERS__INSTANCE_NAME_.XML
$rtmp_connectionstring = $config->get('avchat3_rtmp_connection');
$rtmp_connectionstring_array = explode('/', $rtmp_connectionstring);
$instance = $rtmp_connectionstring_array[count($rtmp_connectionstring_array) - 1];


$avchat3_logged_users = 'None';

//INITIATE ROOMS AND USERS INFO ARRAY
$available_rooms_info = array();
$avchat3_online_users = array();

$avchat3_guests = 0;


//GET COMMUNITY SCRIPT
$prefered_community_script = $config->get('avchat3_community_script');

if(file_exists(JPATH_BASE.DS.'components'.DS.'com_avchat3'.DS.'chat'.DS.'users_'.$instance.'.xml')){
	
	//$xml = new JSimpleXML;
	
	//LOAD XML FILE
	//echo $xml_source;
	$xml_source = file_get_contents(JPATH_BASE.DS.'components'.DS.'com_avchat3'.DS.'chat'.DS.'users_'.$instance.'.xml');
	
	$xml = simplexml_load_string($xml_source);
	//var_dump($xml);
	$i=0;
	
	
	
	//Check if jomsocial is installed
	$query = "SELECT name FROM #__extensions WHERE `element` LIKE 'com_community' and enabled = '1'";
	$db->setQuery( $query );
	$is_jomsocial = $db->loadResult();
	
	//Check if community builder is installed
	$query = "SELECT name FROM #__extensions WHERE `element` LIKE 'com_comprofiler' and enabled = '1'";
	$db->setQuery( $query );
	$is_cb = $db->loadResult();
	
	if($is_jomsocial && $is_cb )
	{
		if($prefered_community_script == 'jomsocial')
		{
			$community_script = 'jomsocial';
		}
	
		if($prefered_community_script == 'cb')
		{
			$community_script = 'cb';
		}
	
		if($prefered_community_script == 'auto')
		{
			$community_script = 'none';
		}
	
	
	} else {
		if($is_jomsocial)
		{
			$community_script = 'jomsocial';
		} else
	
		if($is_cb)
		{
			$community_script = 'cb';
	
		}else{
	
			$community_script = 'none';
		}
	}
	
	$easySocialInstalled = false;
	$easySocialInstalled = JComponentHelper::isEnabled('com_easysocial', true);
	if ($easySocialInstalled) {
		$community_script = 'easysocial';
	}
	
	//if($is_jomsocial && $prefered_community_script == 'jomsocial'){
	//	$community_script = 'jomsocial';
	//}else
	//if($is_cb && $prefered_community_script == 'cb'){
	//	$community_script = 'cb';
	//}else{
	//	$community_script = 'none';
	//}
	
	//echo $xml_source;
	//die("nu");
	
	//GET ROOMS FROM XML
	//$rooms = $xml->getName();
	//var_dump($rooms); 
	$rooms = $xml->children();
	
	//$rooms = $xml->getName();
	//$not_in_rooms = $xml->getName();
	//var_dump($rooms);
	//die("sa");
	
	//echo JPATH_BASE;
	
	if(!empty($rooms)){
		
		
		foreach ($rooms as $room){
			switch($room->getName()){
				case "not_in_rooms" : break;
				case "room" : 
					//GET CONNECTED USERS IN ROOM
					$users_in_room = $room->children();
					
					//GET ROOM ATTRIBUTES
					$room_attributes = $room->attributes();
					
					
					
					
					//ASSIGN ROOM ATTRIBUTES TO THE ROOMS INFO ARRAY
					$available_rooms_info[(string)$room_attributes->id]['room_name'] = $room_attributes->name;
					
					$available_rooms_info[(string)$room_attributes['id']]['users_count'] = count($room->children());
					$available_rooms_info[(string)$room_attributes['id']]['passworded'] = $room_attributes['passworded'];
					
					//CHECK IF USERS ARE CONNECTED INTO THIS ROOM
					if(!empty($users_in_room)){
					
						foreach($users_in_room as $user_in_room){
								
							
							$user_details = $user_in_room->attributes();
							
							$user_name = (string)$user_details->name;
							$user_id = (string)$user_details->siteId;
						
							if ($user_id == '' || $user_id == null) { continue; }
							//var_dump($user_details);
							
							
							if($user_id == '-1'){
								$avchat3_guests++;
							}else{
								
								$avchat3_online_users[$user_id]["user_name"]= $user_name;
								$avchat3_online_users[$user_id]["cam"] = (string)$user_details->cam;
								$avchat3_online_users[$user_id]["mic"] = (string)$user_details->mic;
								
								if(isset($user_details['camIsPrivate'])){
								$avchat3_online_users[$user_id]["private_stream"] = (string)$user_details['camIsPrivate'];
								}else{
								$avchat3_online_users[$user_id]["private_stream"] = 0;
								}
								
								$avchat3_online_users[$user_id]["profile_image"] = '';
								$avchat3_online_users[$user_id]["profile_url"] = '';
								
								//Assign avatar if jomsocial is installed
								if ($community_script == 'jomsocial'){
									
									//get avatar from jos_community_users
									$query = 'SELECT thumb FROM #__community_users where userid='.$user_id;
									$db->setQuery( $query );
									$avatar = $db->loadResult();
									
									
									
									$profile_url = JRoute::_('index.php?option=com_community&view=profile&userid='.$user_id);
									
									if ($avatar != "" || $avatar != null){
										$profile_image = $avatar;
									}else{
									$array = array("2.8.0", "2.8.2", "2.8.3", "2.8.4", "2.8.4.2");
										if(in_array($version, $array))
										{
											//echo"you have an older version";
											$profile_image = JURI::root().'components/com_community/assets/user.png';
										}else{
										$query = "SELECT value FROM #__community_fields_values where user_id='$user_id' and field_id = '2'";
										$db->setQuery( $query );
										$gender = $db->loadResult();
										
										if($gender =='Male')
										{
											$profile_image = JURI::root().'components/com_community/assets/user-Male-thumb.png';
										}else
										if($gender =='Female')
										{
									
											$profile_image = JURI::root().'components/com_community/assets/user-Female-thumb.png';
										}else{
	
											$profile_image = JURI::root().'components/com_community/assets/user-Male-thumb.png';
										}
											}
									}
									$avchat3_online_users[$user_id]["profile_image"] = $profile_image;
									$avchat3_online_users[$user_id]["profile_url"] = $profile_url;
									
								}else
								if ($community_script == 'cb'){
									//Assign avatar if comprofiler is installed
									
									//get avatar from com_profiler
									$query = 'SELECT avatar FROM #__comprofiler where id='.$user_id;
									$db->setQuery( $query );
									$cb_avatar = $db->loadResult();
									
									
									
									$profile_url = JRoute::_('index.php?option=com_comprofiler&task=userProfile&user='.$user_id);
									if ($cb_avatar != "" || $cb_avatar != null){
										$profile_image = JURI::root().'images/comprofiler/'.$cb_avatar;
									}else{
										$profile_image = JURI::root().'components/com_comprofiler/plugin/templates/default/images/avatar/nophoto_n.png';
									}
									$avchat3_online_users[$user_id]["profile_image"] = $profile_image;
									$avchat3_online_users[$user_id]["profile_url"] = $profile_url;
								}
								
								//Detect if EasySocial is intalled
								if ($community_script == 'easysocial') {
									require_once( JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php' );
								
									//get current user
									$easySocialUserInfo = Foundry::user($user_id);
								
									//get user name
									$easySocialUserName = $easySocialUserInfo->getName();
								
									//get user stream name
									$easySocialUserStreamName = $easySocialUserInfo->getStreamName();
								
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
									
									$avchat3_online_users[$user_id]["profile_url"] = $rootPath . $easySocialUserProfileURL;
									$avchat3_online_users[$user_id]["profile_image"] = $easySocialUserAvatar;
									
									$avconfig['changegender'] = 0;
									if ($generValue == '1') {
										//'male';
									} else if ($generValue == '2') {
										//'female';
									}
								
								}
								
								$i++;
							}
						}
					};
					break;
			}
			
		}
	}
	
		
}

//AVOID SHOWING THE SAME USERS MULTIPLE TIMES IF HE IS IN MORE THAN ONE ROOM
//$avchat3_online_users = array_unique($avchat3_online_users);

$avchat_link = JRoute::_('index.php?option=com_avchat3');

require JModuleHelper::getLayoutPath('mod_avchat3online', $params->get('layout', 'default'));