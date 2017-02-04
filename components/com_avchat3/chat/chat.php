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
$app = JFactory::getApplication('site');	

//INITIALISE THE APPLICATION
$app->initialise();

$base_URI = JURI::root();

//GET USER INFOS
$user = JFactory::getUser();

//Initiate db object
$db = JFactory::getDBO();

//GET COMPONENT GENERAL SETTINGS
$config = JComponentHelper::getParams('com_avchat3');

//get admin.swf filename
$avchat_admin_swf = $config->get('avchat3_adminswf');

//get FB app id
$avchat_FB_app_ID = $config->get('avchat3_FBappID');

//GET THE ROOM ID
if(isset($_GET['room']) && $_GET['room'] != ''){
	$room_id = $_GET['room'];
}else{
	$room_id = '';
}

//CHECK IF THE USER HAS ACCESS TO ADMIN.SWF
if($user->authorise('avchat3.allow_admin_interface', 'com_avchat3')){ 
	$swf = $avchat_admin_swf ; 
}else{
	$swf = 'index.swf';
}

//CHECK IF USER HAS ACCESS TO AUTOMATICALLY JOIN ROOMS
if(!$user->authorise('avchat3.allow_room_join'))
{
	$room_id = '';
}

if($avchat_FB_app_ID != "") {
	$FB_appId = $avchat_FB_app_ID;
} else {
	$FB_appId = "";
}	
?>		
<body style="padding:0px;margin:0px">
<input type="hidden" name="FB_appId" id="FB_appId" value="<?php echo $FB_appId ?>" />
	<script type="text/javascript" src="<?php echo $base_URI; ?>tinycon.min.js"></script>
	<script type="text/javascript" src="<?php echo $base_URI; ?>/codebird-js/sha1.js"></script>
	<script type="text/javascript" src="<?php echo $base_URI; ?>/codebird-js/codebird.js"></script>
	<script type="text/javascript" src="<?php echo $base_URI; ?>facebook_integration.js"></script>
	<script type="text/javascript" src="<?php echo $chat_path ?>/twitter_integration.js"></script>
	<script type="text/javascript" src="<?php echo $base_URI; ?>swfobject.js"></script>
	<script type="text/javascript" src="<?php echo $base_URI; ?>new_message.js"></script>	
	<script type="text/javascript">
	var flashvars = {
		lstext : "Loading Settings...",
		sscode : "php",
		userId : "room-<?php echo $room_id ?>"
		
	};
	var params = {
		wmode	: "transparent", 
		quality : "high",
		bgcolor : "#272727",
		play : "true",
		loop : "false",
		allowFullScreen : "true",
		base : "<?php echo $base_URI;?>",
	};
	var attributes = {
		name : "index_embed",
		id :   "index_embed",
		align : "middle"
	};
	
	var chatPathTwitter = "<?php echo $base_URI;?>";
	var twitterKey = "<?php echo $config->get('avchat3_twitter_key')?>";
	var twitterSecret = "<?php echo $config->get('avchat3_twitter_secret')?>";
	</script>
		
	<script type="text/javascript">
		swfobject.embedSWF("<?php echo $swf; ?>", "myContent", "100%", "100%", "11.1.0", "", flashvars, params, attributes);
	</script>
	<div id="myContent">
		<div id="av_message" style="color:#ff0000">JavaScript is not enabled or Flash Player 11.1 is missing. To install Flash Player go to <a href="http://get.adobe.com/flashplayer/">http://get.adobe.com/flashplayer/</a></div>
	</div>
</body>