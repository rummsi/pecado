<?php
/**
 * @version		$Id: index.php $
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2009 - 2011 SiteGround.com - All Rights Reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt

 * 	This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

// Including jQuery
// jQuery is included by default in Joomla 4

/* The following line gets the application object for things like displaying the site name */
$app = Factory::getApplication();
$tplparams = $app->getTemplate(true)->params;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
    <head>
        <jdoc:include type="head" />
        <!-- The following line loads the template CSS file located in the template folder. -->
        <!-- Use the HTMLHelper class to add stylesheets -->
<?php HTMLHelper::_('stylesheet', 'template.css', ['version' => 'auto', 'relative' => true], ['id' => 'template-style']); ?>
    </head>
    <body id="page_bg">
        <div id="sidewrap">
            <div id="header">

                <div class="top-menu">
                    <div id="sgmenu">
                        <jdoc:include type="modules" name="menuload" />
                    </div>
                </div>


                <div id="search">
                    <jdoc:include type="modules" name="position-0" />
                </div>

                <h1><a href="<?php echo $this->baseurl ?>"><?php echo $app->getCfg('sitename'); ?></a></h1>					

            </div>
            <div id="wrapper">
                <div id="content_m">
                    <?php if ($this->countModules('position-7 and position-4')) : ?>
                        <div class="maincol">			 	
                        <?php elseif ($this->countModules('position-7')) : ?>
                            <div class="maincol_w_left">
                            <?php elseif ($this->countModules('position-4')) : ?>
                                <div class="maincol_w_right">
                                <?php else: ?>
                                    <div class="maincol_full">
                                    <?php endif; ?>

                                    <?php if ($this->countModules('position-7')) : ?>
                                        <div class="leftcol">
                                            <div class="innercol">
                                                <jdoc:include type="modules" name="position-7" style="rounded"/>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="cont">
                                        <jdoc:include type="component" />
                                    </div>

                                    <?php if ($this->countModules('position-4')) : ?>
                                        <div class="rightcol">
                                            <div class="innercol">
                                                <jdoc:include type="modules" name="position-4" style="rounded"/>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="clr"></div>
                                    <div id="footer">
                                    </div>
                                    <div class="clr"></div>

                                </div>
                            </div>		
                        </div>
                        <div id="menufooter">
						<?php if ($this->countModules('position-footer')): ?>
							<jdoc:include type="modules" name="position-footer" style="none" />
						<?php endif; ?>
                        </div>
						</div>
				</div>
                    </div>
                </div>
                </body>
                </html>