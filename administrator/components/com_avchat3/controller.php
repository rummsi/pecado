<?php


//Copyright 2015 NuSoft
//This Joomla Component is distributed under the terms of the GNU General Public License.
//you can redistribute it and/or modify it under the terms of the GNU General Public License 
//as published by the Free Software Foundation, either version 3 of the License, or any 
//later version.

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * General Controller of HelloWorld component
 */
class Avchat3Controller extends JControllerLegacy
{
        /**
         * display task
         *
         * @return void
         */
        function display($cachable = false, $urlparams = false) 
        {
                
				// set default view if not set
                JRequest::setVar('view', JRequest::getCmd('view', 'Avchat3'));
 
                // call parent behavior
                parent::display($cachable);
        }
}