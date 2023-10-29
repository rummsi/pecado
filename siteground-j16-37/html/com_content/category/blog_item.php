<?php
/**
 * @version		$Id: blog_item.php 17224 2010-05-23 09:14:11Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$params = & $this->item->params;
$app = Factory::getApplication();
$templateparams = $app->getTemplate(true)->params;
$canEdit = $this->item->params->get('access-edit');

if ($templateparams->get('html5') != 1) {
    require(JPATH_BASE . '/components/com_content/tmpl/category/blog_item.php');
    //evtl. ersetzen durch JPATH_COMPONENT.'/tmpl/...'
} else {
    HTMLHelper::addIncludePath(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'helpers');
    ?>


    <?php if ($this->item->state == 0) : ?>
        <div class="system-unpublished">
    <?php endif; ?>
        <?php if ($params->get('show_title')) : ?>
            <h2>
            <?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
                    <a href="<?php echo Route::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>">
                    <?php echo $this->escape($this->item->title); ?></a>
                    <?php else : ?>
                        <?php echo $this->escape($this->item->title); ?>
                <?php endif; ?>
            </h2>
            <?php endif; ?>

        <?php if ($params->get('show_print_icon') || $params->get('show_email_icon') || $canEdit) : ?>
            <ul class="actions">
            <?php if ($params->get('show_print_icon')) : ?>
                    <li class="print-icon">
                    <?php echo HTMLHelper::_('icon.print_popup', $this->item, $params); ?>
                    </li>
                    <?php endif; ?>
                <?php if ($params->get('show_email_icon')) : ?>
                    <li class="email-icon">
                    <?php echo HTMLHelper::_('icon.email', $this->item, $params); ?>
                    </li>
                    <?php endif; ?>
                <?php if ($canEdit) : ?>
                    <li class="edit-icon">
                    <?php echo HTMLHelper::_('icon.edit', $this->item, $params); ?>
                    </li>
                    <?php endif; ?>
            </ul>
            <?php endif; ?>

        <?php if (!$params->get('show_intro')) : ?>
            <?php echo $this->item->event->afterDisplayTitle; ?>
        <?php endif; ?>

        <?php echo $this->item->event->beforeDisplayContent; ?>

        <?php // to do not that elegant would be nice to group the params ?>

        <?php if (($params->get('show_author')) or ($params->get('show_category')) or ($params->get('show_create_date')) or ($params->get('show_modify_date')) or ($params->get('show_publish_date')) or ($params->get('show_parent_category')) or ($params->get('show_hits'))) : ?>
            <dl class="article-info">
                <dt class="article-info-term"><?php echo Text::_('COM_CONTENT_ARTICLE_INFO'); ?></dt>
    <?php endif; ?>
            <?php if ($params->get('show_parent_category')) : ?>
                <dd class="parent-category-name">
                <?php $title = $this->escape($this->item->parent_title);
                $url = '<a href="' . Route::_(ContentHelperRoute::getCategoryRoute($this->item->parent_id)) . '">' . $title . '</a>';
                ?>
                    <?php if ($params->get('link_parent_category')) : ?>
                        <?php echo Text::sprintf('COM_CONTENT_PARENT', $url); ?>
                    <?php else : ?>
                        <?php echo Text::sprintf('COM_CONTENT_PARENT', $title); ?>
                    <?php endif; ?>
                </dd>
            <?php endif; ?>
            <?php if ($params->get('show_category')) : ?>
                <dd class="category-name">
                    <?php $title = $this->escape($this->item->category_title);
                    $url = '<a href="' . Route::_(ContentHelperRoute::getCategoryRoute($this->item->catid)) . '">' . $title . '</a>';
                    ?>
                    <?php if ($params->get('link_category')) : ?>
                        <?php echo Text::sprintf('COM_CONTENT_CATEGORY', $url); ?>
                    <?php else : ?>
                        <?php echo Text::sprintf('COM_CONTENT_CATEGORY', $title); ?>
                    <?php endif; ?>
                </dd>
            <?php endif; ?>
            <?php if ($params->get('show_create_date')) : ?>
                <dd class="create">
                    <?php echo Text::sprintf('COM_CONTENT_CREATED_DATE_ON', HTMLHelper::_('date', $this->item->created, Text::_('DATE_FORMAT_LC2'))); ?>
                </dd>
            <?php endif; ?>
            <?php if ($params->get('show_modify_date')) : ?>
                <dd class="modified">
                    <?php echo Text::sprintf('COM_CONTENT_LAST_UPDATED', HTMLHelper::_('date', $this->item->modified, Text::_('DATE_FORMAT_LC2'))); ?>
                </dd>
            <?php endif; ?>
            <?php if ($params->get('show_publish_date')) : ?>
                <dd class="published">
                    <?php echo Text::sprintf('COM_CONTENT_PUBLISHED_DATE', HTMLHelper::_('date', $this->item->publish_up, Text::_('DATE_FORMAT_LC2'))); ?>
                </dd>
            <?php endif; ?>
            <?php if ($params->get('show_author') && !empty($this->item->author)) : ?>
                <dd class="createdby"> 
                    <?php $author = $this->item->author; ?>
                    <?php $author = ($this->item->created_by_alias ? $this->item->created_by_alias : $author); ?>

                    <?php if (!empty($this->item->contactid) && $params->get('link_author') == true): ?>
                        <?php echo Text::sprintf('COM_CONTENT_WRITTEN_BY',
                                HTMLHelper::_('link', Route::_('index.php?option=com_contact&view=contact&id=' . $this->item->contactid), $author));
                        ?>

                    <?php else : ?>
                        <?php echo Text::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
                <?php endif; ?>
                </dd>
            <?php endif; ?>	
                <?php if ($params->get('show_hits')) : ?>
                <dd class="hits">
                <?php echo Text::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?>
                </dd>
            <?php endif; ?>
        <?php if (($params->get('show_author')) or ($params->get('show_category')) or ($params->get('show_create_date')) or ($params->get('show_modify_date')) or ($params->get('show_publish_date')) or ($params->get('show_parent_category')) or ($params->get('show_hits'))) : ?>
            </dl>
        <?php endif; ?>

        <?php echo $this->item->introtext; ?>

        <?php
        if ($params->get('show_readmore') && $this->item->readmore) :
            if ($params->get('access-view')) :
                $link = Route::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
            else :
                $menu = JSite::getMenu();
                $active = $menu->getActive();
                $itemId = $active->id;
                $link1 = Route::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
                $returnURL = Route::_(ContentHelperRoute::getArticleRoute($this->item->slug));
                $link = new JURI($link1);
                $link->setVar('return', base64_encode($returnURL));
            endif;
            ?>
            <p class="readmore">
                <a href="<?php echo $link; ?>">
                    <?php
                    if (!$params->get('access-view')) :
                        echo Text::_('COM_CONTENT_REGISTER_TO_READ_MORE');
                    elseif ($readmore = $this->item->alternative_readmore) :
                        echo $readmore;
                        echo HTMLHelper::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
                    elseif ($params->get('show_readmore_title', 0) == 0) :
                        echo Text::sprintf('COM_CONTENT_READ_MORE_TITLE');
                    else :
                        echo Text::_('COM_CONTENT_READ_MORE');
                        echo HTMLHelper::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
                    endif;
                    ?></a>
            </p>
    <?php endif; ?>

    <?php if ($this->item->state == 0) : ?>
        </div>
    <?php endif; ?>

    <div class="item-separator"></div>
    <?php echo $this->item->event->afterDisplayContent; ?>

<?php } ?>
