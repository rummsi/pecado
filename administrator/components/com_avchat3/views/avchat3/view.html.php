<?php

//Copyright 2015 NuSoft
//This Joomla Component is distributed under the terms of the GNU General Public License.
//you can redistribute it and/or modify it under the terms of the GNU General Public License 
//as published by the Free Software Foundation, either version 3 of the License, or any 
//later version.

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HelloWorlds View
 */
class Avchat3ViewAvchat3 extends JViewLegacy
{

        /**
         * HelloWorlds view display method
         * @return void
         */
        function display($tpl = null) 
        {          
			  // Get data from the model
                $items = $this->get('Items');
                $pagination = $this->get('Pagination');
 
                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }
                // Assign data to the view
                $this->items = $items;
                $this->pagination = $pagination;
 				
				// Set the toolbar
                $this->addToolBar();
				
				
                // Display the template
                parent::display($tpl);
        }
		
		protected function addToolBar() 
        {
                JToolBarHelper::title(JText::_('AVChat 3 Admin Interface'));
				JToolBarHelper::preferences('com_avchat3');
        }
}