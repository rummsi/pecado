<?php
/**
 * @category	Model
 * @package		JomSocial
 * @subpackage	Groups
 * @copyright (C) 2008 by iJoomla Inc - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');
if(is_file(JPATH_BASE.'/components/com_community/libraries/core.php'))
{
	require_once JPATH_BASE.'/components/com_community/libraries/core.php';
    CWindow::load();

    // Include the syndicate functions only once
    require_once (dirname(__FILE__).'/helper.php');

	$document = JFactory::getDocument();
	$document->addStyleSheet(rtrim(JURI::root(true), '/').'/components/com_community/templates/default/css/style.css');

	$toolbar = CToolbarLibrary::getInstance();

	$my = CFactory::getUser();

	// Logout link
	$config     = CFactory::getConfig();
	$logoutLink = base64_encode(CRoute::_('index.php?option=com_community&view='.$config->get('redirect_logout'), false));

	// Menu Toolbar
	$menus = $toolbar->getItems();
	$toolbar->addLegacyToolbars($menus);

	$model      = CFactory::getModel( 'Toolbar' );
	$notifModel = CFactory::getModel('notification');

    //fblogin
    /* Facebook login */
    $fbHtml = '';
    if ($config->get('fbconnectkey') && $config->get('fbconnectsecret') && !$config->get('usejfbc')) {
        $facebook = new CFacebook();
        $fbHtml = $facebook->getLoginHTML();
    }

    /* Joomla! Facebook Connect */
    if ($config->get('usejfbc')) {
        if (class_exists('JFBCFactory')) {
            $providers = JFBCFactory::getAllProviders();
            foreach ($providers as $p) {
                $fbHtml .= $p->loginButton();
            }
        }
    }

	// Notification count
	$newMessageCount      = $toolbar->getTotalNotifications( 'inbox' );
	$newEventInviteCount  = $toolbar->getTotalNotifications( 'events' );
	$newFriendInviteCount = $toolbar->getTotalNotifications( 'friends' );
	$newGroupInviteCount  = $toolbar->getTotalNotifications( 'groups' );

	$myParams             = $my->getParams();
	$newNotificationCount = $notifModel->getNotificationCount($my->id,'0',$myParams->get('lastnotificationlist',''));
	$toolbar_left_logo = $params->get('toolbar_logo','');
    $logo_url = $params->get('logo_url','');
    $toolbar_background_color = $params->get('toolbar_background_color','1');
    $toolbar_icon_color = $params->get('toolbar_icon_color','1');

    ModCommunity_ToolbarHelper::loadJquery($params);
    ModCommunity_ToolbarHelper::loadBootstrap($params);
    ModCommunity_ToolbarHelper::loadFontAwesome($params);

    $document->addStyleSheet(rtrim(JURI::root(), '/').'/modules/mod_community_toolbar/assets/css/style.css');
    $css='';
    if($toolbar_background_color!=1){
        $css .='
        #community-tb-wrap.community-toolbar{
            background: '.$toolbar_background_color.';
        }
        #community-tb-wrap.community-toolbar .navbar-inner {
            background: transparent;
        }';
    }

    if($toolbar_icon_color!=1){
        $css .='
        #community-tb-wrap.community-toolbar .navbar .nav > li > a i{
            color: '.$toolbar_icon_color.';
        }';
    }

    $document->addStyleDeclaration($css);
    require( JModuleHelper::getLayoutPath('mod_community_toolbar', $params->get('layout', 'default')) );
}
else
{
	echo 'This toolbar module requires JomSocial, please install it.';
}