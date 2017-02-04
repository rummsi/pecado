<?php
$type = 'image/'.$_GET['type'];
$url = urldecode($_GET['url']);
/*if (filter_var($url, FILTER_VALIDATE_URL) === FALSE){
    die('Not a valid URL');
}else{*/
	
//}
if(strpos($url,".jpeg") != false || strpos($url,".jpg") != false || strpos($url,".png") != false || strpos($url,".gif") != false || (strpos($url,"facebook") != false && strpos($url,".php") == false && strpos($url,".asp") == false && strpos($url,".aspx") == false && strpos($url,".cgi") == false)){
	$file = file_get_contents($url);
}else{
	 die('Not a valid URL');
}

header('Content-Type:'.$type);
echo $file;
?>