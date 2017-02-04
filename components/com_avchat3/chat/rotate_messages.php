<?php
/* 
this script is called by the swf files every X seconds to obtain a message to show in the chat 
X is controlled trough the rotatingMessageTime option in avc_settings.xxx
the path to this script is specified trough the rotatingMessageUrl option in avc_settings.xxx
the message returned by this script can contain the following HTML tags:http://help.adobe.com/en_US/FlashPlatform/reference/actionscript/3/flash/text/TextField.html
1 variable is sent to this file via GET/query string:
the count variable is a number that contains the number of times the chat/user logged in the chat has executed this script, the 1st value is 1
*/

$count = $_GET["count"];
$userSiteId = $_GET["siteId"];
$roomId = $_GET["roomId"];
$images=array();
$images[0]="http://upload.wikimedia.org/wikipedia/commons/thumb/5/5d/White_Tiger.png/800px-White_Tiger.png";
$images[1]="http://upload.wikimedia.org/wikipedia/commons/thumb/4/43/ESO-VLT-Laser-phot-33a-07.jpg/800px-ESO-VLT-Laser-phot-33a-07.jpg";
$images[2]="http://upload.wikimedia.org/wikipedia/commons/thumb/8/8e/Domestic_cat_watching_an_alaskan_malamute.jpg/520px-Domestic_cat_watching_an_alaskan_malamute.jpg";
$images[3]="http://upload.wikimedia.org/wikipedia/commons/3/3b/Gato_enervado_pola_presencia_dun_can.jpg";
$images[4]="http://upload.wikimedia.org/wikipedia/commons/thumb/5/56/Scarlett_Johansson_by_Gage_Skidmore.jpg/508px-Scarlett_Johansson_by_Gage_Skidmore.jpg";
$images[5]="http://upload.wikimedia.org/wikipedia/commons/thumb/7/7a/Scarlet.png/800px-Scarlet.png";
$images[6]="http://upload.wikimedia.org/wikipedia/en/c/c7/Ironmonger_2008film.jpg";

switch ($count%6) {
	case 1:
		echo "<font color='#009933' face='Courier New' size='14' >RM R0B0T: Hi, I am the <b>Rotating Messages R0B0T.</b></font>";
		break;
	case 2:
		echo "<font color='#009933' face='Courier New' size='14' >RM R0B0T: I can also show various chat rules like: DON'T SPAM.</font>";		
		break;
	case 3:
		echo "<font color='#009933' face='Courier New' size='14' >RM R0B0T: The <font color='#ff0000'> <b>styling</b></font> <font color='#0000ff'>of these messages</font> <font color='#ffff00'>can also<font><font color='#00ffff'> be <i>changed</i>.</font>";

		break;
	case 4:
		echo "<font color='#009933' face='Courier New' size='14' ><b>RM R0B0T: I show up every X seconds to bring you thew news of the world.</b></font>";
		break;
	case 5:
		$rand = rand(0, count($images)-1);
		echo $images[$rand];
		break;
	case 0:
		echo "<font color='#009933' face='Courier New' size='14' >RM R0B0T: By default this feature is enabled. YAY!</font>";
		break;
}
?>