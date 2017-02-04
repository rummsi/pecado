<?php
	//this file is called by AVChat every second after the PPV timer has started
	//ppvAmountLeft: the amount of time/money/credits left for a particular user.
	//ppvInitialAmount: the initial amount of time/money/credits that a user had.
	//ppvRatio: the rate at which the ppvAmountLeft is decreased.
	//userSiteId: the siteId value as sent by avc_settings.xxx
	//sessionTimeStamp: the timestamp from the last login
	
	$ppvAmountLeft = $_GET["ppvAmountLeft"];
	$ppvInitialAmount = $_GET["ppvInitialAmount"];
	$ppvRatio = $_GET["ppvRatio"];
	$userSiteId = $_GET["userSiteId"];
	$sessionTimeStamp = $_GET["sessionTimeStamp"];
	
	//echo $ppvAmountLeft." ".$ppvInitialAmount." ".$ppvRatio." ".$userSiteId." ".$sessionTimeStamp;
	
?>