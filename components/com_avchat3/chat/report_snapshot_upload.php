<?php
//this file is called by index.swf when a report is made
$siteId  = $_GET["siteId"];//user's id as sent by the siteID var in avc_settings.xxx
$type = $_GET["type"];//snapshot type (text-chat snapshot or camera snapshot);

//make the folder if it's missing
if(!file_exists("report_snaps")){
    mkdir("report_snaps",0777);
}

//check for malicious $type values
if($type!="TEXT.jpg" && $type !="CAM.jpg"){
    die("save=failed");
}

$image = fopen("report_snaps/".$siteId."_".$type,"wb");
if ($image){
	if (fwrite($image , file_get_contents("php://input"))){
		fclose($image);
		echo "save=ok";
	}else{
		echo "save=failed";
	}
}else{
	echo "save=failed";
}
?>