<?php
###################### AVChat 3.0 Configuration file, DO NOT EDIT THE NEXT FEW LINES ###
//when tne admin interface, admin.swf will request this file from the http server it will send &admin=true via GET
if (isset($_GET['admin']) && $_GET['admin']=="true"){
	//integration_before_admin.php most commonly contains code for integrating with 3rd party cms systems
	if (file_exists("integration_before_admin.php")){include("integration_before_admin.php");}
}else{
	//integration_before.php most commonly contains code for integrating with 3rd party cms systems
	if (file_exists("integration_before.php")){ include("integration_before.php");}
}


/**
//This variable is sent to index.swf and admin.swf via GET and forwarded to this script. To edit it's value look in index.html and admin.html respectively.
if(isset($_GET["userId"])){
	$userId = $_GET["userId"];
}
*/

###################### AVChat 3.0 Configuration file, DO NOT EDIT ABOVE ###
//Loading avc_setting.xml

$avcSettings = simplexml_load_file('avc_settings.xml');
foreach ($avcSettings as $setting){
	$avconfig[(string)$setting->getName()]=(string)$setting->value;
	//echo $setting->getName()."=".$setting->value."<br/>";
}

$avconfig['preferredLanguage'] = GetLanguageCodeISO6391();

//Setting the unique identifier for the user, default we use the user's IP adress

// This first version sets the unique identifier to be the user's IP adress

function getTheIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    if ($ip=="::1"){
		$ip="127.0.0.1";
	}
	return (string)$ip;
}


//detect preferred language
function GetLanguageCodeISO6391()
{
	$hi_code = "";
	$hi_quof = 0;
	$langs = explode(",",$_SERVER['HTTP_ACCEPT_LANGUAGE']);
	foreach($langs as $lang)
	{
		@list($codelang,$quoficient) = explode(";",$lang);
		if($quoficient == NULL) $quoficient = 1;
		if($quoficient > $hi_quof)
		{
			$hi_code = substr($codelang,0,2);
			$hi_quof = $quoficient;
		}
	}
	return $hi_code;
}

// This second version sets the unique identifier to be a random generated token.

	if(empty($_COOKIE["clientUniqueIdentifier"])){
			setcookie("clientUniqueIdentifier", "id_".rand(100,999999));
		}

		
// Choose one of the 2 versions above to use, or implement your own unique identifier method.		
		
$avconfig["clientUniqueIdentifier"] = getTheIP();
//$avconfig["clientUniqueIdentifier"] = $_COOKIE["clientUniqueIdentifier"];

//Setting the timestamp
$avconfig["sessionTimeStamp"] = time();

##################### DO NOT EDIT BELOW ############################

//integration.php most commonly contains code for integrating with 3rd party cms systems


if (file_exists("integration.php")){ include("integration.php");}
if (file_exists("Mobile_Detect.php")){ include("Mobile_Detect.php");}


$detect = new Mobile_Detect();

	if($avconfig['enableEncryptedSettings'] == 1){
	
		if (file_exists("dataEncryption.php")){ include("dataEncryption.php");}
		$crypto = new DataEncryption();

		if(isset($doNotEcho)){ 
			if($doNotEcho != true){
				$theSettings = "a=b";//as3 won't allow the var/value air to start with & so we echo some dummy data first
				foreach ($avconfig as $key => $value){
					//echo '&'.$key.'='.urlencode($value);
					$theSettings.='&'.$key.'='.$crypto->encrypt($value);
				}
				
				echo "[start]";
				echo $crypto->encrypt($theSettings);
				echo "[end]";
			}
		}else if(!$detect->isMobile() && !$detect->isTablet()){
				$theSettings = "a=b";//as3 won't allow the var/value air to start with & so we echo some dummy data first
				foreach ($avconfig as $key => $value){
					//echo '&'.$key.'='.urlencode($value);
					$theSettings.='&'.$key.'='.$crypto->encrypt($value);
				}
				
				echo "[start]";
				echo $crypto->encrypt($theSettings);
				echo "[end]";
		}
	}else{
		 if(isset($doNotEcho)){ 
			if($doNotEcho != true){
				echo "a=b";//as3 won't allow the var/value air to start with & so we echo some dummy data first
				foreach ($avconfig as $key => $value){
					echo '&'.$key.'='.urlencode($value);
				}
				
			}
		}else if(!$detect->isMobile() && !$detect->isTablet()){
				 echo "a=b";//as3 won't allow the var/value air to start with & so we echo some dummy data first
				foreach ($avconfig as $key => $value){
					echo '&'.$key.'='.urlencode($value);
				}
				
		}
	}

?>