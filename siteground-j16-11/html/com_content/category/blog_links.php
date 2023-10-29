<?php
/**
 * @version		$Id: blog_links.php 17017 2010-05-13 10:48:48Z eddieajau $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$params = & $this->item->params;
$app = Factory::getApplication();
$templateparams = $app->getTemplate(true)->params;

if ($templateparams->get('html5') != 1) {
    require(JPATH_BASE . '/components/com_content/tmpl/category/blog_links.php');
    //evtl. ersetzen durch JPATH_COMPONENT.'/tmpl/...'
} else {
    HTMLHelper::addIncludePath(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'helpers');
    ?>
    <div class="items-more">
        <h3><?php echo Text::_('COM_CONTENT_MORE_ARTICLES'); ?></h3>
        <ol>
            <?php
            foreach ($this->link_items as &$item) :
                ?>
                <li>
                    <a href="<?php echo Route::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid)); ?>">
                        <?php echo $item->title; ?></a>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
<?php } ?>
