<?php

if(!isset($doNotEcho)){
	header("Content-type: text/css");
}

//Here you can set the path to the AVChat theme
$pathToTheme = "themes/light/style.xml";

$cssFile = simplexml_load_file($pathToTheme);

foreach ($cssFile->children() as $property) {
	
	$values=array();
	foreach ($property->children() as $tag) {
	   	$values[$tag->getName()] = $tag;
	}
		
		
	
		echo '.'.$property->getName()."{"."\n";
		foreach ($values AS $tag) {
			echo "\t".$tag->getName().":".$tag.";"."\n";
		}
		echo "} \n";

}


?>
