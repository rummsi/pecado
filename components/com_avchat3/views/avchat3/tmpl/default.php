<?php
//Copyright 2015 NuSoft
//This Joomla Component is distributed under the terms of the GNU General Public License.
//you can redistribute it and/or modify it under the terms of the GNU General Public License 
//as published by the Free Software Foundation, either version 3 of the License, or any 
//later version.

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
$base_URI = JURI::root();

$base_PATH = dirname(__FILE__);

$curdir = getcwd();

//GET USER INFOS
$user = JFactory::getUser();

//GET COMPONENT GENERAL SETTINGS
$config = JComponentHelper::getParams('com_avchat3');

//DEFINE OPEN METHOD AND POPUP DIMENSIONS
$open_method = $config->get('avchat3_open_method');
$popup_height = $config->get('avchat3_popup_height');
$popup_width = $config->get('avchat3_popup_width');
$popup_button_image = $config->get('avchat3_popup_button_image');

//GET AVCONFIG admin.swf name
$avchat_admin_swf = $config->get('avchat3_adminswf');

//GET FACEBOOK ID
$avchat_FB_app_ID = $config->get('avchat3_FBappID');
	
	//DETERMINE IF USER IS ADMIN
	if($user->get('id') != 0 && $user->authorise('avchat3.allow_admin_interface', 'com_avchat3')){
	
	$swf=$avchat_admin_swf;
	
	}else{
	
	$swf="index.swf";
	
	} 

//GET THE ROOM ID
if(isset($_GET['room']) && $_GET['room'] != ''){
	$room_id = $_GET['room'];
}else{
	$room_id = '';
}

//CHECK IF USER HAS ACCESS TO AUTOMATICALLY JOIN ROOMS
if(!$user->authorise('avchat3.allow_room_join','com_avchat3')){
	$room_id = '';
}

	if($avchat_FB_app_ID != "") {
			$FB_appId = $avchat_FB_app_ID;
		}
		else {
			$FB_appId = "";
		}
	
	//SET PATH TO AVCHAT CHAT FOLDER	
	$chat_path = $base_URI."components/com_avchat3/chat";
		
 if(file_exists('components/com_avchat3/chat/index.swf')){ 
   		 if($user->authorise('avchat3.allow_admin_interface', 'com_avchat3')){ 
			$final_admin_swf=$avchat_admin_swf;
		 }else{
		 	$final_admin_swf="index.swf";
		 }
		 
		//MOBILE DETECTION
		require_once('components/com_avchat3/chat/Mobile_Detect.php');
		$mobilecheck = new Mobile_Detect();
		if($mobilecheck->isMobile() || $mobilecheck->isTablet()) {
?>
			<a href="javascript:void0(0);" onclick="window.open('<?php echo $base_URI;?>components/com_avchat3/chat/ws/m.php', 'FlashVideoChat', 'height=<?php echo $popup_height; ?>, width=<?php echo $popup_width;?>')"  style="display:block;background:#f0f0f0; border:1px solid #ccc;text-align:center; padding:5px;">Open Mobile Chat</a>
<?php } else {?>

		<h1><?php echo $this->msg; ?></h1>
		
		<?php //INCLUDE ALL EXTERNAL LIBRARIES ?>
		<input type="hidden" name="open_method" id="open_method" value="<?php echo $open_method ?>" />
		
		<input type="hidden" name="FB_appId" id="FB_appId" value="<?php echo $FB_appId ?>" />
				
		<script type="text/javascript" src="<?php echo $chat_path ?>/tinycon.min.js"></script>
		<script type="text/javascript" src="<?php echo $chat_path ?>/codebird-js/sha1.js"></script>
		<script type="text/javascript" src="<?php echo $chat_path ?>/codebird-js/codebird.js"></script>
		<script type="text/javascript" src="<?php echo $chat_path ?>/facebook_integration.js"></script>
		<script type="text/javascript" src="<?php echo $chat_path ?>/twitter_integration.js"></script>
		<script type="text/javascript" src="<?php echo $chat_path ?>/swfobject.js"></script>
		<script type="text/javascript" src="<?php echo $chat_path ?>/new_message.js"></script>
		
		<script type="text/javascript">
				var chat_path = "<?php echo $chat_path; ?>"; 
				var embed = "<?php echo $open_method; ?>";
		</script>
		
		<?php //DISPLAYS ERROR MESSAGE IF JAVASCRIPT IS DISABLED OR FLASH PLAYER IS MISSING ?>
		
			<div id="myContent">
				<div id="av_message" style="color:#ff0000"> 
				JavaScript is not enabled or Flash Player 11.1 is missing. To install Flash Player go to 
				<a href="http://get.adobe.com/flashplayer/">http://get.adobe.com/flashplayer/</a>
				</div>
			</div>
			
		<?php //IF OPEN METHOD IS EMBED ?>	
		<?php if($open_method == 'embed'){ ?>
					<script type="text/javascript">
				var flashvars = {
					lstext : "Loading Settings...",
					sscode : "php"
					
				};
				var params = {
					wmode	: "transparent", 
					quality : "high",
					bgcolor : "#272727",
					play : "true",
					loop : "false",
					allowFullScreen : "true",
					base : "<?php echo $chat_path ?>/",
					userId : "room-<?php echo $room_id ?>"
				};
				var attributes = {
					name : "index_embed",
					id :   "index_embed",
					align : "middle"
				};

				var chatPathTwitter = "<?php echo $chat_path;?>";
				var twitterKey = "<?php echo $config->get('avchat3_twitter_key')?>";
				var twitterSecret = "<?php echo $config->get('avchat3_twitter_secret')?>";
			</script>
			<script type="text/javascript">
			swfobject.embedSWF("<?php echo $chat_path."/".$swf; ?>", "myContent", "100%", "600", "11.1.0", "", flashvars, params, attributes);</script>

	<?php }else{ 
	//IF OPEN METHOD IS POPUP
	?>
	<?php 
		//IF popup_button_image IS SET
		if($popup_button_image != "")
		{
		?>
		
		<script type="text/javascript">document.getElementById("av_message").innerHTML = '<a href="javascript:void(0);" onclick="window.open(\'<?php echo $base_URI;?>components/com_avchat3/chat/chat.php\', \'Video Chat\', \'height=<?php echo $popup_height; ?>, width=<?php echo $popup_width;?>\')"> <center><img src="<?php echo $popup_button_image ?>" width="" height="" align="middle"></center> </a>' </script>
		
		<?php }else{  
		//NORMAL POPUP
		if ($avchat_admin_swf==""){ ?>
		
		<p style="text-align:center;color:#11D63F;font-size:20px">Please go to AVChat administration menu and complete the installation process.</p> <p style="text-align:center ; font-size:16px">For more details please follow the <a href="http://avchat.net/support/documentation/joomla#123" target="_blank"> installation instructions(Step 3). </a></p>		
		
		<?php
		} else{
		?>
		
		<script type="text/javascript">document.getElementById("av_message").innerHTML = '<a href="javascript:void(0);" onclick="window.open(\'<?php echo $base_URI;?>components/com_avchat3/chat/chat.php\', \'Video Chat\', \'height=<?php echo $popup_height; ?>, width=<?php echo $popup_width;?>\')"  style="display:block;background:#f0f0f0; border:1px solid #ccc;text-align:center; padding:5px;">Open Chat in Pop Up</a>' </script>
		
		<?php } ?>					
			<?php } ?>
		
		<?php } ?>
	<?php } ?>

<?php }else{ ?>
	<p style="text-align:center;color:#11D63F;font-size:20px">The AVChat 3 Files are missing from components/com_avchat3/chat/</p> <p style="text-align:center ; font-size:16px">For more details please follow the <a href="http://avchat.net/support/documentation/joomla#124" target="_blank"> installation instructions. </a></p>		
<?php } ?>