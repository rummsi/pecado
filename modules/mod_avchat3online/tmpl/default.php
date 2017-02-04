<?php
//Copyright 2013 AVChat Software
//This Joomla Module is distributed under the terms of the GNU General Public License.
//you can redistribute it and/or modify it under the terms of the GNU General Public License 
//as published by the Free Software Foundation, either version 3 of the License, or any 
//later version.

// No direct access.
defined('_JEXEC') or die;

$output = array();



$output[] = '<p><strong>'.count($avchat3_online_users).'</strong> Membro(s) e <strong>'.count($avchat3_guests).'</strong> Visitante(s) ligado(s) e <strong>'.count($available_rooms_info).'</strong> sala disponível.</p>';
$output[] = '<p><a href="'.$avchat_link.'">Entrar no Video Chat</a></p>';
$output[] = '<h3>Salas disponíveis no Video Chat:</h3> ';
$i = 0;

if(!empty($available_rooms_info)){
	foreach($available_rooms_info as $room_id => $room_info){
		if($room_info['passworded'] == 'true'){
			
			if($user->authorise('avchat3.allow_admin_interface', 'com_avchat3')){
				if($user->authorise('avchat3.admin_can_join_pr_without_permission', 'com_avchat3')){
					$pos = strpos($avchat_link, '?');
					$delimiter = '&';
					if ($pos === false) { // if we have friendly URLs , then we don't have '?' in the link
						$delimiter = '?';
					}
					$output[] = '<a href="'.$avchat_link.$delimiter.'room='.$room_id.'">'.$room_info['room_name'].' ('.$room_info['users_count'].') <img src="'.JURI::root().'/modules/mod_avchat3online/images/lock.png" title="Room is private" style="border:none;height:14px" /></a>';
				}else{
					$output[] = $room_info['room_name'].' ('.$room_info['users_count'].') <img src="'.JURI::root().'/modules/mod_avchat3online/images/lock.png" title="Room is private" style="border:none;height:14px" />';
				}
			}else{
				$output[] = $room_info['room_name'].' ('.$room_info['users_count'].') <img src="'.JURI::root().'/modules/mod_avchat3online/images/lock.png" title="Room is private" style="border:none;height:14px" />';
			}
			
		}else{
			if($user->authorise('avchat3.allow_room_join','com_avchat3')){
				$pos = strpos($avchat_link, '?');
				$delimiter = '&';
				if ($pos === false) { // if we have friendly URLs , then we don't have '?' in the link
					$delimiter = '?';
				}
				$output[] = '<a href="'.$avchat_link.$delimiter.'room='.$room_id.'">'.$room_info['room_name'].' ('.$room_info['users_count'].')</a>';
			}else{
				$output[] = $room_info['room_name'];
			}
		}
		
		if($i < count($available_rooms_info) - 1){
			$output[] = ', ';
		}
		
		$i++;
	}
}else{
	$output[] = 'Sem salas disponíveis';
}



$output[] = '<h3>Utilizadores ligados no Video Chat:</h3>';
if(!empty($avchat3_online_users)){
	$p = 0;
	if($community_script != 'none'){
		$output[] = '<div style="max-height:400px; overflow: auto">';
		foreach($avchat3_online_users as $user_id => $avchat3_online_user){
			$output[] = '<div style="text-align:center;float:left;margin:1px;border:1px solid #ccc;padding:1px"><a style="text-decoration:none;" href="'.$avchat3_online_user['profile_url'].'"><img style="width:60px;border:none;" src="'.$avchat3_online_user['profile_image'].'" /><br />';
			
			if($avchat3_online_user['cam']){
				$output[] = '<img src="'.JURI::root().'/modules/mod_avchat3online/images/camera.png" title="Webcam started" style="border:none" />';
			}
			
			if($avchat3_online_user['mic']){
				$output[] = '<img src="'.JURI::root().'/modules/mod_avchat3online/images/audio.png" title="Microphone started" style="border:none" />';
			}
			
		if($avchat3_online_user['private_stream'] == 'true' && ((int)$avchat3_online_user['cam'] == 1 || (int)$avchat3_online_user['mic'] == 1)) 
            {           
                       $output[] = '<img src="'.JURI::root().'/modules/mod_avchat3online/images/lock.png" title="The stream is private" style="border:none" />';
            }
			
			$output[] = '<br />';
			
			if(strlen($avchat3_online_user['user_name']) >=10 ){
				$trimmed_username = substr($avchat3_online_user['user_name'], 0, 8).'...';
			}else{
				$trimmed_username = $avchat3_online_user['user_name'];
			}
			$output[] = $trimmed_username.'</a><br />';
			
			
			
			$output[] = '</div>';
			
			
		}
		$output[] = '</div>';
		
	}else{
		$output[] = '<div>';
		foreach($avchat3_online_users as $avchat3_online_user){
			$output[] = $avchat3_online_user['user_name'];
			
			if($p < (count($avchat3_online_users) - 1)){
				$output[] = ', ';
			}
			$p++;
		}
		
		$output[] = '</div>';
	}
	$output[] = '<p style="text-align:right;clear:both;">e '.count($avchat3_guests).' convidado(s).</p>';
	
}else{
	if(count($avchat3_guests) == 0){
		$output[] = 'Nenhum utilizador no chat';
	}else{
		$output[] = count($avchat3_guests).' convidado(s) no chat!';
	}
}

$output[] = '<div style="clear:both;"></div>';


// Reverse rendering order for rtl display.
if ($lang->isRTL()) :
	$output = array_reverse($output);
endif;

// Output the items.
foreach ($output as $item) :
	echo $item;
endforeach;