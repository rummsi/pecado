<?php

//Copyright 2015 NuSoft
//This Joomla Component is distributed under the terms of the GNU General Public License.
//you can redistribute it and/or modify it under the terms of the GNU General Public License 
//as published by the Free Software Foundation, either version 3 of the License, or any 
//later version.

	//GET GENERAL SETTINGS
	$rtmp_connectionstring = $config->get('avchat3_rtmp_connection');
	$invite_link = $config->get('avchat3_invite_link');
	$disconnect_button_link = $config->get('avchat3_disconnect_button_link');
	$login_page_url = $config->get('avchat3_login_page_url');
	$register_page_url = $config->get('avchat3_register_page_url');
	$upgrade_url = $config->get('avchat3_upgrade_url');
	$drop_in_room = $config->get('avchat3_drop_in_room');
	$max_upload_filesize = $config->get('avchat3_max_upload_filesize');
	$text_char_limit = $config->get('avchat3_text_char_limit');
	//$background_image_url = $config->get('avchat3_background_image_url');
	$history_length = $config->get('avchat3_history_lenght');
	$history_length_admin = $config->get('avchat3_history_lenght_admin');
	//$allow_visitors = $config->get('avchat3_allow_visitors');
	$add_age = $config->get('avchat3_add_age');
	$country_flag_url = $config->get('avchat3_country_flag_url');
	
	$lang = JFactory::getLanguage();

	//Change language file to file for adjusted language, if available
	$testlangfile = 'translations/'.$lang->get('tag').'.xml';
	if(file_exists("$testlangfile")){
		$avconfig['languagefile']=$testlangfile;
	}
	
	
	//SET GENERAL SETTINGS
	if($rtmp_connectionstring != '' && $rtmp_connectionstring != 'rtmp://'){
		$avconfig['connectionstring'] = $rtmp_connectionstring;
	}
	
	$avconfig['enableOtherAccountOptions'] = '0';
	if ($config->get('avchat3_allow_fb_twitter_connection') == '1') {
		$avconfig['enableOtherAccountOptions'] = '1';
	}
	
	if($invite_link != ''){
		$avconfig['inviteLink'] = $invite_link;
	}
	
	if($disconnect_button_link != '' && $disconnect_button_link != '/'){
		$avconfig['disconnectButtonLink'] = $disconnect_button_link;
	}
	
	if($login_page_url != '' && $login_page_url != '/'){
		$avconfig['loginPageURL'] = $login_page_url;
	}
	
	if($register_page_url != '' && $register_page_url != '/'){
		$avconfig['registerPageURL'] = $register_page_url;
	}
	
	if($upgrade_url != ''){
		$avconfig['upgradeUrl'] = $upgrade_url;
	}
	
	if($drop_in_room != ''){
		$avconfig['dropInRoom'] = $drop_in_room;
	}
	
	if($max_upload_filesize != '' && is_numeric($max_upload_filesize)){
		$avconfig['maxUploadFileSize'] = (int)$max_upload_filesize * 1024;
	}
	
	if($text_char_limit != '' && is_numeric($text_char_limit)){
		$avconfig['textChatCharLimit'] = $text_char_limit;
	}
	
	// if($background_image_url != ''){
		// $avconfig['backgroundImageUrl'] = $background_image_url;
	// }
	
	if($history_length != '' && is_numeric($history_length)){
		$avconfig['historyLength'] = $history_length;
	}
	
	if($history_length_admin != '' && is_numeric($history_length_admin)){
		$avconfig['historyLengthForAdmin'] = $history_length_admin;
	}
	
	//define user list type
	$avconfig['usersListType'] = $config->get('avchat3_users_list_stype');
	
	if($user->authorise('avchat3.allow_user_interface', 'com_avchat3') || $user->authorise('avchat3.allow_admin_interface', 'com_avchat3')){
		
		
		//CHECK IF USER HAS ACCESS TO THE CHAT AS ADMIN
		if(isset($_GET['admin']) && $_GET['admin'] == 'true'){
			if($user->authorise('avchat3.allow_admin_interface', 'com_avchat3')){
				$avconfig['showLoginError'] = 0;
			}else{
				$avconfig['showLoginError'] = 1;
			}
		}
		
		//Hide "Register and Sign In" tab from registered users: 0 disabled 1 enabled
		if($user->get('id') != 0){
			$avconfig['enableRegisterTab'] = 0;
		} else {
			$avconfig['enableRegisterTab'] = 1; }		
		
		//CHECK IF USER CAN STREAM AUDIO & VIDEO
		if($user->authorise('avchat3.allow_streaming', 'com_avchat3')){
			$avconfig['allowVideoStreaming'] = 1;
			$avconfig['allowAudioStreaming'] = 1;
		}else{
			$avconfig['allowVideoStreaming'] = 0;
			$avconfig['allowAudioStreaming'] = 0;
		}
		
		//CHECK IF USER CAN WATCH OTHER USERS STREAM AUDIO & VIDEO
		if($user->authorise('avchat3.allow_watching_stream', 'com_avchat3')){
			$avconfig['maxStreams'] = 4;
		}else{
			$avconfig['maxStreams'] = 0;
		}
		
		//CHECK IF USER CAN STREAM PRIVATE
		if($user->authorise('avchat3.allow_private_streaming', 'com_avchat3')){
			$avconfig['allowPrivateStreaming'] = 1;
		}else{
			$avconfig['allowPrivateStreaming'] = 0;
		}
		
		//CHECK IF USER CAN POST YOUTUBE VIDEOS
		if($user->authorise('avchat3.allow_youtube', 'com_avchat3')){
			$avconfig['youTubeVideosEnabled'] = 1;
		}else{
			$avconfig['youTubeVideosEnabled'] = 2;
		}
		
		//CHECK IF USER CAN SEND FILES TO ROOMS
		if($user->authorise('avchat3.allow_send_file_to_rooms', 'com_avchat3')){
			$avconfig['sendFileToRoomsEnabled'] = 1;
		}else{
			$avconfig['sendFileToRoomsEnabled'] = 2;
		}
		
		//CHECK IF USER CAN SEND FILES TO OTHER USERS
		if($user->authorise('avchat3.allow_send_file_to_users', 'com_avchat3')){
			$avconfig['sendFileToUserEnabled'] = 1;
		}else{
			$avconfig['sendFileToUserEnabled'] = 2;
		}

		//CHECK IF USER CAN SEND POST EMAILS IN TEXT CHAT AREA
		if($user->authorise('avchat3.allow_emails', 'com_avchat3')){
			$avconfig['allowEmails'] = 1;
		}else{
			$avconfig['allowEmails'] = 0;
		}
		
		//CHECK IF USER CAN POST URLS IN TEXT CHAT AREA
		if($user->authorise('avchat3.allow_urls', 'com_avchat3')){
			$avconfig['allowUrls'] = 1;
		}else{
			$avconfig['allowUrls'] = 0;
		}
		
		//CHECK IF USER CAN SEND PRIVATE MESSAGES
		if($user->authorise('avchat3.allow_pm', 'com_avchat3')){
			$avconfig['pmEnabled'] = 1;
		}else{
			$avconfig['pmEnabled'] = 2;
		}
		
		//CHECK IF USER CAN CREATE ROOMS
		if($user->authorise('avchat3.allow_create_room', 'com_avchat3')){
			$avconfig['createRoomsEnabled'] = 1;
		}else{
			$avconfig['createRoomsEnabled'] = 2;
		}

		//---------------------------------------
		//ADMIN PERMISSIONS
		//---------------------------------------
		
		//CHECK IF ADMIN CAN DELETE ROOMS
		if($user->authorise('avchat3.admin_allow_delete_rooms', 'com_avchat3')){
			$avconfig['adminCanDeleteRooms'] = 1;
		}else{
			$avconfig['adminCanDeleteRooms'] = 2;
		}
		
		//CHECK IF ADMIN CAN EDIT ROOMS
		if($user->authorise('avchat3.admin_allow_edit_rooms', 'com_avchat3')){
			$avconfig['adminCanEditRooms'] = 1;
		}else{
			$avconfig['adminCanEditRooms'] = 2;
		}
		
		//CHECK IF ADMIN CAN BAN
		if($user->authorise('avchat3.admin_can_ban', 'com_avchat3')){
			$avconfig['adminCanBan'] = 1;
		}else{
			$avconfig['adminCanBan'] = 2;
		}
		
		//CHECK IF ADMIN CAN KICK
		if($user->authorise( 'avchat3.admin_can_kick', 'com_avchat3')){
			$avconfig['adminCanKick'] = 1;
		}else{
			$avconfig['adminCanKick'] = 2;
		}
		
		//CHECK IF ADMIN CAN REMOVE BAN
		if($user->authorise('avchat3.admin_can_remove_ban', 'com_avchat3')){
			$avconfig['adminCanRemoveBan'] = 1;
		}else{
			$avconfig['adminCanRemoveBan'] = 2;
		}
		
		//CHECK IF ADMIN CAN VIEW PRIVATE MESSAGE
		if($user->authorise('avchat3.admin_can_view_pm', 'com_avchat3')){
			$avconfig['adminCanViewPrivateMessages'] = 1;
		}else{
			$avconfig['adminCanViewPrivateMessages'] = 0;
		}

		//CHECK IF ADMIN CAN VIEW PRIVATE STREAMS WITHOUT PERMISSION
		if($user->authorise( 'avchat3.admin_can_view_ps_without_permission', 'com_avchat3')){
			$avconfig['adminCanViewPrivateStreamsWithoutPermission'] = 1;
		}else{
			$avconfig['adminCanViewPrivateStreamsWithoutPermission'] = 0;
		}
		
		//CHECK IF ADMIN CAN JOIN PRIVATE ROOMS WITHOUT PERMISSION 
		if($user->authorise('avchat3.admin_can_join_pr_without_permission', 'com_avchat3')){
			$avconfig['adminCanJoinPrivateRoomsWithoutPermission'] = 1;
		}else{
			$avconfig['adminCanJoinPrivateRoomsWithoutPermission'] = 0;
		}
		
		//CHECK IF ADMIN CAN ACCESS SETTINGS 
		if($user->authorise('avchat3.admin_can_access_settings', 'com_avchat3')){
			$avconfig['adminCanAccessSettings'] = 1;
		}else{
			$avconfig['adminCanAccessSettings'] = 0;
		}
		
		//CHECK IF USERS CAN JOIN OTHER ROOMS TOO
		if($user->authorise('avchat3.allow_room_join', 'com_avchat3')){
			$avconfig['joinRoomsEnabled']=1;
		}else{
			$avconfig['joinRoomsEnabled']=2;
		}
		
		//CHECK IF USERS CAN WRITE MESSAGES IN TEXT CHAT AREA
		if(!$user->authorise('avchat3.allow_send_messages', 'com_avchat3')){
			$avconfig['typingEnabled'] = 0;
 		}
 		
 		//CHECK IF ADMIN CAN VIEW USERS IPS
 		if(!$user->authorise('avchat3.admin_can_view_ips', 'com_avchat3')){
			$avconfig["adminCanViewIps"]=0;
			$avconfig['showToAdminsTheUserIpInTextChat']=0;
 		}
 		
 		//CHECK IF ADMINS CAN BAN OTHER ADMINS
 		if(!$user->authorise('avchat3.admin_can_ban_other_admins', 'com_avchat3')){
			$avconfig["adminCanBanOtherAdmins"]=0;
 		}
 		
		//CHECK IF ADMINS CAN KICK OTHER ADMINS
 		if(!$user->authorise('avchat3.admin_can_kick_other_admins', 'com_avchat3')){
			$avconfig["adminCanKickOtherAdmins"]=0;
 		}
 		
		//CHECK IF ADMINS CAN ACCESS THE BAN PANEL
 		if(!$user->authorise('avchat3.admin_can_access_ban_panel', 'com_avchat3')){
			$avconfig["adminCanAccessBannPanel"]=2;
 		}
		
 		//CHECK IF USERS CAN BUZZ
		if(!$user->authorise('avchat3.allow_buzz', 'com_avchat3')){
			$avconfig["buzzButtonEnabled"]=2;
 		}
 		
 		//CHECK IF USERS CAN HAVE PROFILE FLAGS
 		if(!$user->authorise('avchat3.allow_country_flags', 'com_avchat3')){
 			$avconfig['profileCountryFlag']= $country_flag_url;
 		}
		
		//CHECK IF ADMINS CAN STOP STREAMS
 		if(!$user->authorise('avchat3.admin_can_stop_streams', 'com_avchat3')){
 			$avconfig['adminCanStopStreams']= 0;
 		}
		
		//CHECK IF USERS CAN VIEW ONLINE TIME
 		if(!$user->authorise('avchat3.show_online_time', 'com_avchat3')){
 			$avconfig['showOnlineTime']= 0;
 		}
		
		//CHECK IF USERS CAN CONTROL the state of the "clear all rooms screens" button
 		if(!$user->authorise('avchat3.clear_all_rooms_screens_button_show', 'com_avchat3')){
 			$avconfig['clearAllRoomsScreensButtonShow']= 0;
 		}
		
		//CHECK IF ADMINS can silence other admins
 		if(!$user->authorise('avchat3.admin_can_silence_other_admins', 'com_avchat3')){
 			$avconfig['adminCanSilenceOtherAdmins']= 0;
 		}
		
		//CHECK IF ADMINS can silence users from room
 		if(!$user->authorise('avchat3.admin_can_silence_from_room', 'com_avchat3')){
 			$avconfig['adminCanSilenceFromRoom']= 0;
 		}
		
		//CHECK IF ADMINS can KICK FROM THIS ROOM 
 		if(!$user->authorise('avchat3.admin_can_kick_from_1_room', 'com_avchat3')){
 			$avconfig['adminCanKickFrom1Room']= 0;
 		}
		
	//CHECK IF USERS can see the "IgnorePMs" button
 		if($user->authorise('avchat3.showigpm', 'com_avchat3')){ 
 				$avconfig['showIgnorePMsButton']= 1;
 				}
 			else{
 				$avconfig['showIgnorePMsButton']= 2;
 				}
		
		//CHECK IF USERS can see the "Stop him/her from viewing you" button
 		if(!$user->authorise('avchat3.stop_viewer_button_enabled', 'com_avchat3')){
 			$avconfig['stopViewerButtonEnabled']= 0;
 		}
		
		//SETUP HIDDEN GENDER PERMISSION
		if(isset($_GET['admin']) && $_GET['admin'] == 'true'){
			if($user->authorise('avchat3.admin_can_login_hidden', 'com_avchat3')){
				$avconfig['hiddenGenderEnabled']=1;
			}else{
				$avconfig['hiddenGenderEnabled']=0;	
			}
			
		}
		
		//CHECK IF ROOM IS DEFINED
		if(isset($_GET['userId']) && $_GET['userId'] != ''){
			if(substr($_GET['userId'], 0,4) == 'room'){
				list($room_ttl, $room_id) = explode('-', $_GET['userId']);
				$avconfig['dropInRoom'] = $room_id;
			}
			
			
		}
		
		//SET USER SPECIFIC OPTIONS AND PERMISSIONS
		if($user->get('id') != 0){
			
			//SETUP USERNAME and AGE
			if ($config->get('avchat3_display_user_name_type') == 'real-name') {
				$username = $user->get('name');
			} else {
				$username = $user->get('username');
			}
			
			$avconfig['username'] = $username;
			
			
			$avconfig['changeuser'] = 0;
			$avconfig['siteId']=$user->get('id');
			
			
			//EasySocial Integration - BEGIN
			if ($easySocialInstalled) {
				$avconfig['profileUrl'] = rtrim(JURI::root(false, ''), '/') . str_replace('components/com_avchat3/chat/', '', $easySocialUserProfileURL);
				//$avconfig['usersListType']='thumbnail';
				$avconfig['thumbnailUrl'] = str_replace('components/com_avchat3/chat/', '', $easySocialUserAvatar);
				
				$avconfig['changegender'] = 0;
				if ($generValue == '1') {
					$avconfig['gender'] = 'male';
				} else if ($generValue == '2') {
					$avconfig['gender'] = 'female';
				} else {
					$avconfig['changegender'] = 1;
				}
				
				if($user->authorise('avchat3.allow_admin_interface', 'com_avchat3')) {
					$avconfig['gender'] = 'admin';
					$avconfig['changegender'] = 1;
				}
			}
			//EasySocial Integration - END
			
			
			//echo $this->getState('avchat3.allow_user_interface');
			
			
			//GET THE GROUPS THIS USER IS ASSIGNED TO 
			$groups = $user->get('_authGroups');

			if (($is_jomsocial == true)  || ($is_cb == true)) {
					
				//SETUP JOMSOCIAL AND COMMUNITY BUILDER
				if ($is_jomsocial && $community_script == 'jomsocial') {
			
					//if jomsocial is selected
					$avconfig['profileUrl'] = JPATH_BASE.'index.php?option=com_community&view=profile&userid='.$user->get('id');
					$avconfig['registerPageURL'] = JPATH_BASE.'index.php?option=com_community&view=register';

					if ($avatar != "" || $avatar != null) {

						//$avconfig['usersListType']='thumbnail';
						$avconfig['thumbnailUrl'] = JPATH_BASE . $avatar;

					} else {

						//$avconfig['usersListType']='thumbnail';
					}

					//SETUP GENDER AND AVATAR
					//XML DETECTION
					$pathtoxml = JPATH_BASE.'administrator/components/com_community/community.xml';

					if(file_exists($pathtoxml)) {

						$xmlstr = file_get_contents($pathtoxml);
						$xml	= new SimpleXMLElement($xmlstr);
						$version = $xml->version;

					} else {

						echo "Jomsocial files are missing";
					}


					//OPTIMIZED AVATAR DETECTION BY JOMSOCIAL VERSIONS

					$array = array(
						"2.8.0",
						"2.8.2",
						"2.8.3",
						"2.8.4",
						"2.8.4.2",
						"3.2.1.0",
					);

					if (in_array($version, $array)) {

						//echo"you have an older version";
						$avconfig['thumbnailUrl'] = JPATH_BASE.$avatar;

						if ($gender != "" || $gender != null) {

							$avconfig['gender'] = strtolower($gender);
							$avconfig['changegender'] = 0;

						} else

						if (isset($_GET['admin']) && $_GET['admin'] == 'true') {

							if($user->authorise('avchat3.allow_admin_interface', 'com_avchat3')) {

								$avconfig['gender'] = 'admin';
								//$avconfig['gender'] = null;
								$avconfig['changegender'] = 1;
							}
						}

					} else {

						//echo "you have a new version";
						if($gender != "" || $gender != null) {

							$avconfig['gender'] = strtolower($gender);
							$avconfig['changegender'] = 0;

							//-- FOR JOMSOCIAL VERSION 3.0.1+
							
							if($avconfig['gender'] =='male') {

								$avconfig['thumbnailUrl'] = JPATH_BASE.$avatar;
							} else

							if ($avconfig['gender'] =='female') {

								$avconfig['thumbnailUrl'] = JPATH_BASE.'components/com_community/assets/user-Female-thumb.png';
							}

						} else

						if (isset($_GET['admin']) && $_GET['admin'] == 'true') {


							if ($user->authorise('avchat3.allow_admin_interface', 'com_avchat3')) {

								$avconfig['gender'] = 'admin';
								//$avconfig['gender'] = null;
								$avconfig['changegender'] = 1;
								$avconfig['thumbnailUrl'] = JPATH_BASE.$avatar;
							}
					
						} else {

							$avconfig['thumbnailUrl'] = JPATH_BASE.$avatar;
						}
					}
					
				} else

					//if commnuity builder is selected
					if ($is_cb && $community_script == 'cb'){

						//if community builder
						$avconfig['profileUrl'] = JPATH_BASE.'index.php?option=com_comprofiler&task=userProfile&user='.$user->get('id');
						$avconfig['registerPageURL'] = JPATH_BASE.'index.php?option=com_comprofiler&task=registers';


						if ($cb_avatar != "" || $cb_avatar != null) {

							//$avconfig['usersListType']='thumbnail';
							$avconfig['thumbnailUrl'] = JPATH_BASE.'images/comprofiler/'.$cb_avatar;

						} else {

							//$avconfig['usersListType']='thumbnail';
							$avconfig['thumbnailUrl']= JPATH_BASE .'components/com_comprofiler/plugin/templates/default/images/avatar/nophoto_n.png';
						}

					} else {

						//default auto
						if ($community_script == 'auto') {

							//$avconfig['usersListType']='small';
							$avconfig['registerPageURL'] = JPATH_BASE.'index.php?option=com_user&task=register';
						}
					}

			} else {
			
				if ($is_jomsocial == true) {
			
					//if jomsocial is detected
					$avconfig['profileUrl'] = JPATH_BASE.'index.php?option=com_community&view=profile&userid='.$user->get('id');
					$avconfig['registerPageURL'] = JPATH_BASE.'index.php?option=com_community&view=register';
					$avconfig['thumbnailUrl'] = JPATH_BASE.$avatar;

					//SETUP GENDER AND AVATAR
					//XML DETECTION
					$pathtoxml = JPATH_BASE.'administrator/components/com_community/community.xml';

					if (file_exists($pathtoxml)) {

						$xmlstr = file_get_contents($pathtoxml);
						$xml	= new SimpleXMLElement($xmlstr);
						$version = $xml->version;

					} else {

						echo "Jomsocial files are missing";
					}


					//OPTIMIZED AVATAR DETECTION BY JOMSOCIAL VERSIONS
					
					$array = array(
						"2.8.0",
						"2.8.2",
						"2.8.3",
						"2.8.4",
						"2.8.4.2",
						"3.2.1.0",
						"3.2.1.1",
						"3.2.1.2",
						"3.2.1.3",
						"3.2.1.4",
						"3.2.1.5",
						"3.2.1.6",
						"3.2.1.7",
						"3.2.1.8",
						"3.2.1.9",
						"3.2.2.0",
					);

					if (in_array($version, $array)) {

						//echo"you have an older version";
						$avconfig['thumbnailUrl'] = JPATH_BASE.'components/com_community/assets/user.png';

						if($gender != "" || $gender != null) {

							$avconfig['gender'] = strtolower($gender);
							$avconfig['changegender'] = 0;

						} else

							if(isset($_GET['admin']) && $_GET['admin'] == 'true') {

								if($user->authorise('avchat3.allow_admin_interface', 'com_avchat3')) {

									$avconfig['gender'] = 'admin';
									//$avconfig['gender'] = null;
									$avconfig['changegender'] = 1;
								}
							}

					} else {

						//echo "you have a new version";
						if ($gender != "" || $gender != null) {

							$avconfig['gender'] = strtolower($gender);
							$avconfig['changegender'] = 0;

							//-- FOR JOMSOCIAL VERSION 3.0.1+
							if ($avconfig['gender'] =='male') {

								$avconfig['thumbnailUrl'] = JPATH_BASE.$avatar;
							} else

								if ($avconfig['gender'] =='female') {
									$avconfig['thumbnailUrl'] = JPATH_BASE.'components/com_community/assets/user-Female-thumb.png';
								}
						} else

							if (isset($_GET['admin']) && $_GET['admin'] == 'true') {

								if($user->authorise('avchat3.allow_admin_interface', 'com_avchat3')) {

									$avconfig['gender'] = 'admin';
									//$avconfig['gender'] = null;
									$avconfig['changegender'] = 1;
									$avconfig['thumbnailUrl'] = JPATH_BASE.$avatar;
							}
					
						} else {

							$avconfig['thumbnailUrl'] = JPATH_BASE.$avatar;
						}
					}
				}
			
				if ($is_cb == true) {

					//if community builder is detected
					$avconfig['profileUrl'] = JPATH_BASE.'index.php?option=com_comprofiler&task=userProfile&user='.$user->get('id');
					$avconfig['registerPageURL'] = JPATH_BASE.'index.php?option=com_comprofiler&task=registers';

					if ($cb_avatar != "" || $cb_avatar != null) {

						//$avconfig['usersListType']='thumbnail';
						$avconfig['thumbnailUrl'] = JPATH_BASE.'images/comprofiler/'.$cb_avatar;
			
					} else {

						//$avconfig['usersListType']='thumbnail';
						$avconfig['thumbnailUrl']= JPATH_BASE .'components/com_comprofiler/plugin/templates/default/images/avatar/nophoto_n.png';
					}
			
				}
				
				
			
			}

			if ($birthday != "" || $birthday != null) {

				if ($add_age == 1) {

					$age = get_age($birthday);
					$avconfig['username'] = $avconfig['username'] .' ('. $age . ')';
				}
			}
		}

	} else {

		$avconfig['showLoginError'] = 1;
	}


	function get_age($Birthdate) {
		if (empty($Birthdate)) {
			return false;
		}
	
		list($BirthYear,$BirthMonth,$BirthDay) = explode("-", $Birthdate);
		$YearDiff = date("Y") - $BirthYear;
		$MonthDiff = date("m") - $BirthMonth;
		$DayDiff = date("d") - $BirthDay;
	
		// If the birthday has not occured this year
		if ($MonthDiff == 0) {
			if ($DayDiff < 0)
				$YearDiff--;
		}
	
		if ($MonthDiff < 0) {
			$YearDiff--;
		}


	return $YearDiff;
	}



?>