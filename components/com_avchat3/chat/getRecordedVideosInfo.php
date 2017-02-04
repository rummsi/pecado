<?php
error_reporting(E_ERROR | E_PARSE);

$streamName = urldecode($_POST["streamName"]);// the name of the recorded videostream
$siteId = urldecode($_POST["siteId"]);//the siteId of the user that made the recorded videostream
$username = urldecode($_POST["username"]);//the username of the user that made the recorded videostream

$result ="&result=".$streamName." ".$siteId." ".$username;
echo $result;

?>