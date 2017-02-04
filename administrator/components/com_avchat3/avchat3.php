<?php

//Copyright 2015 NuSoft
//This Joomla Component is distributed under the terms of the GNU General Public License.
//you can redistribute it and/or modify it under the terms of the GNU General Public License 
//as published by the Free Software Foundation, either version 3 of the License, or any 
//later version.
	
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import joomla controller library
jimport('joomla.application.component.controller');
 
// Get an instance of the controller prefixed by Avchat3
$controller = JControllerLegacy::getInstance('Avchat3');

 
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
 
 
// Redirect if set by the controller
$controller->redirect();