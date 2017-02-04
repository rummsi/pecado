<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Getting params from template
$params = JFactory::getApplication()->getTemplate(true)->params;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->getCfg('sitename');

if($task == "edit" || $layout == "form" )
{
	$fullWidth = 1;
}
else
{
	$fullWidth = 0;
}

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');

// Add current user information
$user = JFactory::getUser();
$sitename  = $params->get('sitename') ? $params->get('sitename') : JFactory::getConfig()->get('sitename');
$slogan    = $params->get('slogan');
$logotype  = $params->get('logotype', 'text');
$logoimage = $logotype == 'image' ? $params->get('logoimage', 'templates/' . T3_TEMPLATE . '/images/logo.png') : '';
$logoimgsm = ($logotype == 'image' && $params->get('enable_logoimage_sm', 0)) ? $params->get('logoimage_sm', '') : false;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $this->title; ?> <?php echo htmlspecialchars($this->error->getMessage()); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php
		// Use of Google Font
		if ($params->get('googleFont'))
		{
	?>
		<link href='//fonts.googleapis.com/css?family=<?php echo $params->get('googleFontName');?>' rel='stylesheet' type='text/css' />
		<style type="text/css">
			h1,h2,h3,h4,h5,h6,.site-title{
				font-family: '<?php echo str_replace('+', ' ', $params->get('googleFontName'));?>', sans-serif;
			}
		</style>
	<?php
		}
	?>
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" />

	<?php
		$debug = JFactory::getConfig()->get('debug_lang');
		if ((defined('JDEBUG') && JDEBUG) || $debug)
		{
	?>
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/cms/css/debug.css" type="text/css" />
	<?php
		}
	?>
	<?php
	// If Right-to-Left
	if ($this->direction == 'rtl')
	{
	?>
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/jui/css/bootstrap-rtl.css" type="text/css" />
	<?php
	}
	?>
	<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
	<style type="text/css">
        html {
            height: 100%;
        }
        body{
            padding-top: 0;
            background-color: #D6D6D6;
            background-image: -webkit-gradient(radial,center center,0,center center,460,from(#AAAAAA),to(#D6D6D6));
            background-image: -webkit-radial-gradient(circle,#AAAAAA,#D6D6D6);
            background-image: -moz-radial-gradient(circle,#AAAAAA,#D6D6D6);
            background-image: -o-radial-gradient(circle,#AAAAAA,#D6D6D6);
            background-repeat: no-repeat;
        }

        h1.page-header{
            color: #f5f5f5;
            border: none;
        }

		.navbar-inner, .nav-list > .active > a, .nav-list > .active > a:hover, .dropdown-menu li > a:hover, .dropdown-menu .active > a, .dropdown-menu .active > a:hover, .nav-pills > .active > a, .nav-pills > .active > a:hover
		{
			background: <?php echo $params->get('templateColor');?>;
		}
		.navbar-inner
		{
			-moz-box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2);
			-webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2);
			box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2);
		}

        .view-login .container {
            width: 350px;
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -240px;
            margin-left: -175px;
        }
        .well {
            min-height: 20px;
            padding: 19px;
            margin-bottom: 20px;
            background-color: #f5f5f5;
            border: 1px solid #e3e3e3;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
            -moz-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
            box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
        }
        .view-login .well {
            padding-bottom: 0;
            -webkit-box-shadow: 0px 0px 60px rgba(0, 0, 0, 0.5), 0px 1px 0px rgba(255, 255, 255, 0.9) inset;
            -moz-box-shadow: 0px 0px 60px rgba(0, 0, 0, 0.5), 0px 1px 0px rgba(255, 255, 255, 0.9) inset;
            box-shadow: 0px 0px 60px rgba(0, 0, 0, 0.5), 0px 1px 0px rgba(255, 255, 255, 0.9) inset;
        }
        .logo {
            width: 100%;
            text-align: center;
        }
        .btn.go-home{
            padding: 4px 14px;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            color: #fff;
             text-shadow: none;
            background-color: #142849;
            background-image: -moz-linear-gradient(top,#142849,#142849);
            background-image: -webkit-gradient(linear,0 0,0 100%,from(#142849),to(#142849));
            background-image: -webkit-linear-gradient(top,#142849,#142849);
            background-image: -o-linear-gradient(top,#142849,#142849);
            background-image: linear-gradient(to bottom,#142849,#142849);
            background-repeat: repeat-x;
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#142849', endColorstr='#142849', GradientType=0);
            border-color: #eee #eee #c8c8c8;
            border-color: rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);
            filter: progid:DXImageTransform.Microsoft.gradient(enabled = false);
            box-shadow: 1px 1px 5px 0px;
        }
        .footer{
            position: absolute;
            left: 0;
            bottom: 0;
        }
        hr {
             margin: 0;
        }
	</style>
	<!--[if lt IE 9]>
		<script src="<?php echo $this->baseurl ?>/media/jui/js/html5.js"></script>
	<![endif]-->
</head>

<body class="site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($params->get('fluidContainer') ? ' fluid' : '');
?> view-login">

	<!-- Body -->
    <div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : '');?>">
        <!-- Header -->
        <div class="header">
            <div class="header-inner clearfix">
                <div class="span12 logo">
                    <div class="logo-<?php echo $logotype, ($logoimgsm ? ' logo-control' : '') ?>">
                        <a href="<?php echo JURI::base(true) ?>" title="<?php echo strip_tags($sitename) ?>">
                            <?php if($logotype == 'image'): ?>
                                <img class="logo-img" src="<?php echo JURI::base(true) . '/' . $logoimage ?>" alt="<?php echo strip_tags($sitename) ?>" />
                            <?php endif ?>
                            <?php if($logoimgsm) : ?>
                                <img class="logo-img-sm" src="<?php echo JURI::base(true) . '/' . $logoimgsm ?>" alt="<?php echo strip_tags($sitename) ?>" />
                            <?php endif ?>
                            <span><?php echo $sitename ?></span>
                        </a>
                        <small class="site-slogan hidden-phone"><?php echo $slogan ?></small>
                    </div>
                </div>
                <div class="header-search pull-right">
                    <?php
                    // Display position-0 modules
                    echo $doc->getBuffer('modules', 'position-0', array('style' => 'none'));
                    ?>
                </div>
            </div>
        </div>
        <div class="navigation">
            <?php
            // Display position-1 modules
            echo $doc->getBuffer('modules', 'position-1', array('style' => 'none'));
            ?>
        </div>
        <!-- Banner -->
        <div class="banner">
            <?php echo $doc->getBuffer('modules', 'banner', array('style' => 'xhtml')); ?>
        </div>
        <div class="row-fluid">
            <div id="content" class="span12">
                <!-- Begin Content -->
                <h1 class="page-header"><?php echo $this->error->getCode(); ?> : <?php echo $this->error->getMessage();?></h1>
                <div class="well">
                    <div class="row-fluid">
                        <div class="span6">
                            <p><strong><?php echo JText::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST'); ?></strong></p>
                            <p><?php echo JText::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?></p>
                            <ul>
                                <li><?php echo JText::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE'); ?></li>
                                <li><?php echo JText::_('JERROR_LAYOUT_MIS_TYPED_ADDRESS'); ?></li>
                                <li><?php echo JText::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING'); ?></li>
                                <li><?php echo JText::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE'); ?></li>
                            </ul>
                        </div>
                        <div class="span6">
                            <?php if (JModuleHelper::getModule('search')) : ?>
                                <p><strong><?php echo JText::_('JERROR_LAYOUT_SEARCH'); ?></strong></p>
                                <p><?php echo JText::_('JERROR_LAYOUT_SEARCH_PAGE'); ?></p>
                                <?php echo $doc->getBuffer('module', 'search'); ?>
                            <?php endif; ?>
                            <p><?php echo JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?></p>
                            <p><a href="<?php echo $this->baseurl; ?>/index.php" class="btn go-home"><?php echo JText::_('JERROR_LAYOUT_HOME_PAGE'); ?></a></p>
                        </div>
                    </div>
                    <hr />
                    <p><?php echo JText::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR'); ?></p>

                </div>
                <!-- End Content -->
            </div>
        </div>
    </div>
	<?php echo $doc->getBuffer('modules', 'debug', array('style' => 'none')); ?>
</body>
</html>
