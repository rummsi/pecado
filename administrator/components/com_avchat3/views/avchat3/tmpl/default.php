<?php

//Copyright 2015 NuSoft
//This Joomla Component is distributed under the terms of the GNU General Public License.
//you can redistribute it and/or modify it under the terms of the GNU General Public License 
//as published by the Free Software Foundation, either version 3 of the License, or any 
//later version.

//No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
//load tooltip behavior
JHtml::_('behavior.tooltip');
$base_URI = JURI::root();

//GET COMPONENT GENERAL SETTINGS
$config = JComponentHelper::getParams('com_avchat3');

//GET USER INFOS
$user =JFactory::getUser();

//THE PATH TO THE AVCHAT
$chat_path = $base_URI."components/com_avchat3/chat";

//GET FACEBOOK ID
$avchat_FB_app_ID = $config->get('avchat3_FBappID');

if($avchat_FB_app_ID != "") {
			$FB_appId = $avchat_FB_app_ID;
		}
		else {
			$FB_appId = "";
		}

//GET THE ROOM ID
if(isset($_GET['room']) && $_GET['room'] != ''){
	$room_id = $_GET['room'];
}else{
	$room_id = '';
}		


?>

<?php
if ($config->get('avchat3_adminswf')!=""){
		//This variable ($config->get('avchat3_adminswf')) is empty UNTIL we click the [SAVE] button in the Components->AVChat3->AVChat 3 General Settings in the Joomla! backend 
		$avchat_admin_swf = $config->get('avchat3_adminswf');
		
		if($user->authorise('avchat3.allow_admin_interface', 'com_avchat3')){
		$swf=$avchat_admin_swf;


			/* --------------*
				MOBILE OFFER
			 *---------------*/
				// if (file_exists($chat_path . '/sw')) {
				if (file_exists('../components/com_avchat3/chat/ws')) {
					// Do nothing. File exists.
				} else {

					// User clicked Hide
					if (isset($_GET['dismiss']) && $_GET['dismiss'] === 'true') {
						setcookie('avchatOffer', 'hide', time() + (86400 * 120), '/');
						$avchatOffer = null;

					// If the user opted to hide the offer, respect that
					} elseif ($_COOKIE['avchatOffer'] === 'hide') {
						setcookie('avchatOffer', 'hide', time() + (86400 * 920), '/');
						$avchatOffer = null;

					// Safety net
					} else {
						setcookie('avchatOffer', 'display', time() + (86400 * 1), '/');
						$avchatOffer = "Try the new AVChat for mobile. Faster, real time, low bandwidth. Use the promo code ILOVEAVCHAT on checkout for 30% off! <a href='{$_SERVER['REQUEST_URI']}&dismiss=true'>Hide</a>.";
					}

					echo ($avchatOffer != null) ? "<div class='alert alert-info'><strong>" . $avchatOffer . "</strong></div>" : '';
					//var_dump($_COOKIE['avchatOffer']);

				}
			/* --------------*
			  END MOBILE OFFER
			 *---------------*/

		}

		if(file_exists('../components/com_avchat3/chat/index.swf')){ 

			?>
		
		<script type="text/javascript">
		var chat_path = "<?php echo $chat_path; ?>"; 
		</script>
		
		<script type="text/javascript" src="<?php echo $chat_path ?>/swfobject.js"></script>
		<input type="hidden" name="FB_appId" id="FB_appId" value="<?php echo $FB_appId ?>" />
		<script type="text/javascript" src="<?php echo $chat_path ?>/facebook_integration.js"></script>
		<script type="text/javascript" src="<?php echo $chat_path ?>/tinycon.min.js"></script>
		<script type="text/javascript" src="<?php echo $chat_path ?>/new_message.js"></script>
		
		<div id="myContent">
				<div id="av_message" style="color:#ff0000"> 
				JavaScript is not enabled or Flash Player 11.1 is missing. To install Flash Player go to 
				<a href="http://get.adobe.com/flashplayer/">http://get.adobe.com/flashplayer/</a>
				</div>
			</div>
			<script type="text/javascript">
				var flashvars = {
					lstext : "Loading Settings...",
					sscode : "php",
					userId : "is_admin"
				};
				var params = {
					wmode	: "transparent",
					quality : "high",
					bgcolor : "#272727",
					play : "true",
					loop : "false",
					allowFullScreen : "true",
					base : "<?php echo $chat_path ?>/"
				};
				var attributes = {
					name : "index_embed",
					id :   "index_embed",
					align : "middle"
				};
			</script>
			<script type="text/javascript">
			swfobject.embedSWF("<?php echo $chat_path."/".$swf; ?>", "myContent", "100%", "600", "11.1.0", "", flashvars, params, attributes);</script>

		  <?php }else{ ?>
			<br />
			<p style="text-align:center;color:#11D63F;font-size:20px">The AVChat 3 Files are missing from components/com_avchat3/chat/</p><p style="text-align:center ; font-size:16px">For more details please follow the <a href="http://avchat.net/support/documentation/joomla#124" target="_blank"> installation instructions. </a></p>		
	
  <?php } ?>
<?php }else{ ?>
	<br />
	<p style="text-align:center;color:#11D63F;font-size:20px">The AVChat 3 Options (settings and permissions) are not present in the database, go to [Options] and click [Save & Close].</p><p style="text-align:center; font-size:16px"> For more details please follow the <a href="http://avchat.net/support/documentation/joomla#124" target="_blank"> installation instructions. </a></p>
<?php } ?>