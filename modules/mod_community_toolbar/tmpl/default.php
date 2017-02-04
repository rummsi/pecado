<?php
/**
 * @package		JomSocial
 * @subpackage 	Template
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 *
 */
defined('_JEXEC') or die();
$viewName = JRequest::getCmd( 'view');
$taskName = JRequest::getCmd( 'task');
$uri      = CRoute::_('index.php?option=com_community' , false );
$uri      = base64_encode($uri);
?>

<?php if($my->id) : ?>
<!--Toolbar view-->
    <div id="community-tb-wrap" class="community-toolbar cModule <?php echo $params->get('moduleclass_sfx'); ?> clearfix">

		<div class="container">

		<div class="navbar js-toolbar">
			<div class="navbar-inner">

				<div class="row-fluid">

					<div class="span8 col-xs-8 mobile-menu">

						<a class="btn btn-navbar js-bar-collapse-btn" data-toggle="collapse" data-target=".nav-collapse">
							<span class="fa fa-bars"></span>
						</a>

						<ul class="nav">
                            <?php if(isset($toolbar_left_logo) && !empty($toolbar_left_logo)){?>
                            <li>
                                <a href="<?php echo $logo_url;?>" class="community-toolbar-logo" ><img src="<?php echo $toolbar_left_logo;?>" alt="<?php echo $logo_url.' logo';?>"/></a>
                            </li>
                            <?php }?>
							<li>
								<a href="<?php echo CRoute::_( 'index.php?option=com_community&view=frontpage' );?>">
								<i class="fa fa-home"></i></a>
							</li>

							<li>
								<a rel="tooltip" data-placement="bottom" class="joms-module-global-notif" href="javascript:joms.notifications.showWindow();" title="<?php echo JText::_( 'COM_COMMUNITY_NOTIFICATIONS_GLOBAL' );?>">
									<i class="fa fa-globe"></i>
									<span class="counter"><?php if( $newNotificationCount ) { echo $newNotificationCount; } ?></span>
								</a>
							</li>

							<li>
								<a rel="tooltip" data-placement="bottom" class="joms-module-friend-invite-notif" href="<?php echo CRoute::_( 'index.php?option=com_community&view=friends&task=pending' );?>" onclick="joms.notifications.showRequest();return false;" title="<?php echo JText::_( 'COM_COMMUNITY_NOTIFICATIONS_INVITE_FRIENDS' );?>">
									<i class="fa fa-users"></i>
									<span class="counter"><?php if( $newFriendInviteCount ){ echo $newFriendInviteCount; }?></span>
								</a>
							</li>

							<li>
								<a rel="tooltip" data-placement="bottom" class="joms-module-new-message-notif"  href="<?php echo CRoute::_( 'index.php?option=com_community&view=inbox' );?>"  onclick="joms.notifications.showInbox();return false;" title="<?php echo JText::_( 'COM_COMMUNITY_NOTIFICATIONS_INBOX' );?>">
									<i class="fa fa-envelope"></i>
									<span class="counter"><?php if( $newMessageCount ){ echo $newMessageCount;} ?></span>
								</a>
							</li>

							<li>
				    		<form class="toolbar-search visible-desktop" name="search" id="cFormSearch" method="get" action="<?php echo CRoute::_('index.php?option=com_community&view=search');?>">
								<div class="input-append input-block-level">
									<input type="text" id="keyword" name="q" />
									<a class="btn" href="javascript:void(0);" onclick="joms.jQuery('#cFormSearch').submit();">
										<i class="fa fa-search"></i>
									</a>
								</div>

								<input type="hidden" name="option" value="com_community" />
								<input type="hidden" name="view" value="search" />
							</form>
							</li>

						</ul>

						<div class="hidden-desktop nav-collapse collapse">
							<div class="mobile-search clearfix">
					    		<form class="toolbar-search" name="search" id="cFormSearch" method="get" action="<?php echo CRoute::_('index.php?option=com_community&view=search');?>">
									<div class="input-append input-block-level">
										<input type="text" id="keyword" name="q" />
										<a class="btn" href="javascript:void(0);" onclick="joms.jQuery('#cFormSearch').submit();">
											<i class="fa fa-search"></i>
										</a>
									</div>

									<input type="hidden" name="option" value="com_community" />
									<input type="hidden" name="view" value="search" />
								</form>
							</div>

							<div class="mobile-user clearfix">
								<ul class="nav">
									<li class="user-info clearfix">
										<a href="<?php echo CRoute::_('index.php?option=com_community&view=profile')?>">
											<img class="toolbar-avatar pull-left" src="<?php echo $my->getAvatar(); ?>" alt="<?php echo $my->getDisplayName(); ?>" />
											<h3><?php echo $my->getDisplayName(); ?></h3>
										</a>
									</li>
									<li>
										<a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&task=preferences'); ?>">
											<i class="fa fa-cog"></i> Settings
										</a>
									</li>
									<li>
										<a href="javascript:void(0);" title="<?php echo JText::_('COM_COMMUNITY_LOGOUT'); ?>" onclick="document.communitylogout3.submit();">
											<i class="fa fa-sign-out"></i> Logout
										</a>
									</li>
						        </ul>
							</div>
						</div>

					</div>

                    <div class="span4 col-xs-4 clearfix visible-desktop">
                        <ul class="nav pull-right">
                            <li class="user-info clearfix">
                                <a href="<?php echo CRoute::_('index.php?option=com_community&view=profile')?>">
                                    <img class="toolbar-avatar pull-left img-circle" src="<?php echo $my->getAvatar(); ?>" alt="<?php echo $my->getDisplayName(); ?>" />
                                    <h3><?php echo $my->getDisplayName(); ?></h3>
                                </a>
                            </li>
                            <li class="dropdown">
                                <a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&task=preferences'); ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i> <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&task=uploadAvatar'); ?>"><?php echo JText::_('MOD_HELLOME_CHANGE_PROFILE_PICTURE'); ?></a></li>
                                    <li><a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&task=edit'); ?>"><?php echo JText::_('MOD_HELLOME_CHANGE_EDIT_PROFILE'); ?></a></li>
                                    <li><a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&task=preferences'); ?>"><?php echo JText::_('MOD_HELLOME_CHANGE_EDIT_PRIVACY'); ?></a></li>
                                </ul>
                            </li>
                            <li class="visible-desktop" >
                                <a href="javascript:void(0);" title="<?php echo JText::_('COM_COMMUNITY_LOGOUT'); ?>" onclick="document.communitylogout3.submit();">
                                    <i class="fa fa-sign-out"></i>
                                </a>
                                <form class="cForm" action="<?php echo JRoute::_('index.php');?>" method="post" name="communitylogout3" id="communitylogout3">
                                    <input type="hidden" name="option" value="<?php echo COM_USER_NAME ; ?>" />
                                    <input type="hidden" name="task" value="<?php echo COM_USER_TAKS_LOGOUT ; ?>" />
                                    <input type="hidden" name="return" value="<?php echo $logoutLink; ?>" />
                                    <?php echo JHtml::_('form.token'); ?>
                                </form>
                            </li>
                        </ul>
                    </div>
				</div><!-- .row-fluid -->

			</div><!-- /navbar-inner -->
		</div>

	</div><!-- .container -->

</div>

<?php else: ?>
<!--user login view-->
<div id="community-tb-wrap" class="community-toolbar toolbar-login logged-out not-logged-in cModule <?php echo $params->get('moduleclass_sfx'); ?> clearfix">
    <div class="container">
		<div class="navbar">
			<div class="navbar-inner">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"></a>
				<div class="nav-collapse collapse">
                    <?php if(isset($toolbar_left_logo) && !empty($toolbar_left_logo)){?>
                    <a href="<?php echo $logo_url;?>" class="community-toolbar-logo"><img src="<?php echo $toolbar_left_logo;?>" alt="<?php echo $logo_url.' logo';?>"/></a>
                    <?php }?>
                    <?php if($fbHtml){?>
                    <div class="fbloginbutton">
                        <?php
                        echo $fbHtml;
                        ?>
                    </div>
                    <?php }?>
                    <form class="form-inline pull-right" action="<?php echo CRoute::_( 'index.php?option='.COM_USER_NAME.'&task='.COM_USER_TAKS_LOGIN ); ?>" method="post" name="form-login" id="form-login" >
                        <?php echo $params->get('pretext'); ?>

                        <fieldset class="form-list pull-left">
                            <p id="form-login-username">
                                <span for="username" class="clearfix">
                                    <label><?php echo JText::_('COM_COMMUNITY_USERNAME') ?></label>
                                    <input tabindex="11" name="username" id="username" type="text" class="inputbox" alt="username" size="18" />
                                    <a rel="tooltip" data-placement="bottom" title="<?php echo JText::_('COM_COMMUNITY_FORGOT_USERNAME') ?>" href="<?php echo CRoute::_( 'index.php?option=com_users&view=remind');?>"><i class="fa fa-question-circle"></i></a>
                                </span>

                            </p>
                            <p id="form-login-password">
                                <span for="passwd" class="clearfix;">
                                    <label><?php echo JText::_('COM_COMMUNITY_PASSWORD') ?></label>
                                    <input tabindex="12" type="password" name="<?php echo COM_USER_PASSWORD_INPUT;?>" id="passwd" class="inputbox" size="18" alt="password" />
                                    <a rel="tooltip" data-placement="bottom" title="<?php echo JText::_('COM_COMMUNITY_FORGOT_PASSWORD') ?>" href="<?php echo CRoute::_( 'index.php?option=com_users&view=reset');?>"><i class="fa fa-question-circle"></i></a>
                                </span>

                            </p>
                            <?php if (JPluginHelper::isEnabled('system', 'remember')) { ?>
                            <p id="form-login-remember">
                                <span for="remember">
                                    <?php echo JText::_('COM_COMMUNITY_REMEMBER_MY_DETAILS') ?>
                                    <input tabindex="3" class="checkbox" type="checkbox" name="remember" id="remember" value="yes" alt="Remember Me" />
                                </span>
                            </p>
                            <?php } ?>
                            <button tabindex="4" type="submit" name="Submit" class="btn btn-orange"><?php echo JText::_('MOD_HELLOME_MY_LOGIN') ?></button>
                        </fieldset>

                        <div class="register-btn pull-left">
                            <div style="display: none;">
                                <a href="<?php echo JRoute::_( 'index.php?option='.COM_USER_NAME.'&view=reset' ); ?>">
                                <?php echo JText::_('MOD_HELLOME_FORGOT_YOUR_PASSWORD'); ?>
                                </a>
                            </div>
                            <div style="display: none;">
                                <a href="<?php echo JRoute::_( 'index.php?option='.COM_USER_NAME.'&view=remind' ); ?>">
                                <?php echo JText::_('MOD_HELLOME_FORGOT_YOUR_USERNAME'); ?>
                                </a>
                            </div>
                            <div style="display: none;">
                                <a href="<?php echo CRoute::_( 'index.php?option=com_community&view=register&task=activation' ); ?>" class="login-forgot-username">
                                    <span><?php echo JText::_('MOD_HELLOME_RESEND_ACTIVATION_CODE'); ?></span>
                                </a>
                            </div>
                            <?php

                            $usersConfig=JComponentHelper::getParams( 'com_users' );

                            if ($usersConfig->get('allowUserRegistration'))
                            { ?>
                            <div>
                                <a class="btn btn-green" href="<?php echo CRoute::_( 'index.php?option=com_community&view=register' ); ?>">
                                    <?php echo JText::_('MOD_HELLOME_REGISTER'); ?>
                                </a>
                            </div>
                            <?php } ?>
                        </div>

                        <?php echo $params->get('posttext'); ?>

                        <input type="hidden" name="option" value="<?php echo COM_USER_NAME;?>" />
                        <input type="hidden" name="task" value="<?php echo COM_USER_TAKS_LOGIN;?>" />
                        <input type="hidden" name="return" value="<?php echo $uri; ?>" />
                        <?php echo JHTML::_('form.token'); ?>
                    </form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
<script type="text/javascript">
    jQuery('#community-tb-wrap').tooltip({
        selector: "a[rel=tooltip]"
    })
</script>

