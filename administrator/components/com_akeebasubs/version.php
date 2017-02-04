<?php
defined('_JEXEC') or die();

define('AKEEBASUBS_VERSION', '5.0.2');
define('AKEEBASUBS_DATE', '2015-12-22');
define('AKEEBASUBS_VERSIONHASH', md5(AKEEBASUBS_VERSION.AKEEBASUBS_DATE.JFactory::getConfig()->get('secret','')));