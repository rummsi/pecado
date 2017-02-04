<?php
/**
 * @category	Model
 * @package		JomSocial
 * @subpackage	Groups
 * @copyright (C) 2008 by iJoomla Inc - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
class ModCommunity_ToolbarHelper
{
    public static function loadJquery($mconfig){
        $doc = JFactory::getDocument();    //document object
        $app = JFactory::getApplication(); //application object

        static $jqLoaded;

        if ($jqLoaded) {
            return;
        }

        if($mconfig->get('load_jquery') AND !$app->get('jQuery')){
            $file = JURI::root(true).'/modules/mod_community_toolbar/assets/jquery/jquery-1.11.1.min.js';
            $app->set('jQuery','1.11.1');
            $doc->addScript($file);
            $doc->addScriptDeclaration("jQuery.noConflict();");
            $jqLoaded = TRUE;
        }
    }

    public static function loadFontAwesome($mconfig){
        static $faLoaded;

        if ($faLoaded) {
            return;
        }

        $doc = JFactory::getDocument();    //document object
        $load_fontawesome = $mconfig->get('load_fontawesome','0');
        if($load_fontawesome){
            $doc->addStyleSheet(rtrim(JURI::root(), '/').'/modules/mod_community_toolbar/assets/fonts/font-awesome-4.0.3/css/font-awesome.min.css');
            $faLoaded = true;
        }
    }

    public static function loadBootstrap($mconfig){
        static $bsLoaded;

        if ($bsLoaded) {
            return;
        }

        $doc = JFactory::getDocument();    //document object
        $load_bootstrap = $mconfig->get('load_bootstrap','2.3.2');

        if($load_bootstrap){
            switch($load_bootstrap){
                case '2.3.2':
                    $doc->addStyleSheet(rtrim(JURI::root(), '/').'/modules/mod_community_toolbar/assets/bootstrap-2.3.2/css/bootstrap.min.css');
                    $doc->addStyleSheet(rtrim(JURI::root(), '/').'/modules/mod_community_toolbar/assets/bootstrap-2.3.2/css/bootstrap-responsive.min.css');
                    $doc->addScript(rtrim(JURI::root(), '/').'/modules/mod_community_toolbar/assets/bootstrap-2.3.2/js/bootstrap.min.js');
                    break;
                case '3.2.0':
                    $doc->addStyleSheet(rtrim(JURI::root(), '/').'/modules/mod_community_toolbar/assets/bootstrap-3.2.0/css/bootstrap.min.css');
                    $doc->addStyleSheet(rtrim(JURI::root(), '/').'/modules/mod_community_toolbar/assets/bootstrap-3.2.0/css/bootstrap-theme.min.css');
                    $doc->addScript(rtrim(JURI::root(), '/').'/modules/mod_community_toolbar/assets/bootstrap-3.2.0/js/bootstrap.min.js');
                    break;
            }
            $bsLoaded = true;
        }

    }
}