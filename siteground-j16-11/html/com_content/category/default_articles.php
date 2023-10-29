<?php
/**
 * @version		$Id: default_articles.php 17298 2010-05-27 14:58:59Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$app = Factory::getApplication();
$templateparams = $app->getTemplate(true)->params;

if ($templateparams->get('html5') != 1) :
    require(JPATH_BASE . '/components/com_content/tmpl/category/default_articles.php');
    //evtl. ersetzen durch JPATH_COMPONENT.'/tmpl/...'

    return;
endif;
HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HTMLHelper::_('behavior.tooltip');
HTMLHelper::core();
$n = count($this->items);
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
?>
<?php if (empty($this->items)) : ?>
    <?php if ($this->params->get('show_no_articles', 1)) : ?>
        <p><?php echo Text::_('COM_CONTENT_NO_ARTICLES'); ?></p>
    <?php endif; ?>
<?php else : ?>
    <form action="<?php echo JFilterOutput::ampReplace(Factory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm">
    <?php if ($this->params->get('filter_field') != 'hide') : ?>
            <fieldset class="filters">
                <legend class="element-invisible">
        <?php echo Text::_('JGLOBAL_FILTER_LABEL'); ?>
                </legend>
                <div class="filter-search">
                    <label class="filter-search-lbl" for="filter-search"><?php echo Text::_('COM_CONTENT_' . $this->params->get('filter_field') . '_FILTER_LABEL') . '&#160;'; ?></label>
                    <input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo Text::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />
                </div>
    <?php endif; ?>
    <?php if ($this->params->get('show_pagination_limit')) : ?>
                <div class="display-limit">
            <?php echo Text::_('JGLOBAL_DISPLAY_NUM'); ?>&#160;
        <?php echo $this->pagination->getLimitBox(); ?>
                </div>
                <?php endif; ?>
    <?php if ($this->params->get('filter_field') != 'hide') : ?>
            </fieldset>
    <?php endif; ?>
        <table class="category">
            <?php if ($this->params->get('show_headings')) : ?>
                <thead>
                    <tr>
                        <th class="list-title" id="tableOrdering">
                    <?php echo HTMLHelper::_('grid.sort', 'COM_CONTENT_HEADING_TITLE', 'a.title', $listDirn, $listOrder); ?>
                        </th>
        <?php if ($date = $this->params->get('list_show_date')) : ?>
                            <th class="list-date" id="tableOrdering2">
            <?php echo HTMLHelper::_('grid.sort', 'COM_CONTENT_' . $date . '_DATE', 'a.created', $listDirn, $listOrder); ?>
                            </th>
        <?php endif; ?>
                <?php if ($this->params->get('list_show_author', 1)) : ?>
                            <th class="list-author" id="tableOrdering3">
            <?php echo HTMLHelper::_('grid.sort', 'JAUTHOR', 'author', $listDirn, $listOrder); ?>
                            </th>
        <?php endif; ?>
        <?php if ($this->params->get('list_show_hits', 1)) : ?>
                            <th class="list-hits" id="tableOrdering4">
                            <?php echo HTMLHelper::_('grid.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
                            </th>
                            <?php endif; ?>
                    </tr>
                </thead>
    <?php endif; ?>
            <tbody>
    <?php foreach ($this->items as $i => &$article) : ?>
                    <tr class="cat-list-row<?php echo $i % 2; ?>">
                        <?php if (in_array($article->access, $this->user->getAuthorisedViewLevels())) : ?>
                            <td class="list-title">
                                <a href="<?php echo Route::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid)); ?>">
                            <?php echo $this->escape($article->title); ?></a>
                            </td>
                    <?php if ($this->params->get('list_show_date')) : ?>
                                <td class="list-date">
                <?php echo HTMLHelper::_('date', $article->displayDate, $this->escape(
                                $this->params->get('date_format', Text::_('DATE_FORMAT_LC3'))));
                ?>
                                </td>
            <?php endif; ?>
            <?php if ($this->params->get('list_show_author', 1) && !empty($article->author)) : ?>	
                                <td class="createdby"> 
                <?php $author = $article->author ?>
                <?php $author = ($article->created_by_alias ? $article->created_by_alias : $author); ?>
                <?php if (!empty($article->contactid) && $this->params->get('link_author') == true): ?>
                    <?php echo
                    HTMLHelper::_('link', Route::_('index.php?option=com_contact&view=contact&id=' . $article->contactid), $author);
                    ?>
                <?php else : ?>
                                        <?php echo Text::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
                <?php endif; ?>
                                </td>
            <?php endif; ?>	
            <?php if ($this->params->get('list_show_hits', 1)) : ?>
                                <td class="list-hits">
                <?php echo $article->hits; ?>
                                </td>
            <?php endif; ?>
                            <?php else : ?>
                            <td>
            <?php
            echo $this->escape($article->title) . ' : ';
            $menu = JSite::getMenu();
            $active = $menu->getActive();
            $itemId = $active->id;
            $link = Route::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
            $returnURL = Route::_(ContentHelperRoute::getArticleRoute($article->slug));
            $fullURL = new JURI($link);
            $fullURL->setVar('return', base64_encode($returnURL));
            ?>
                                <a href="<?php echo $fullURL; ?>" class="register">
            <?php echo Text::_('COM_CONTENT_REGISTER_TO_READ_MORE'); ?></a>
                            </td>
        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
            </tbody>
        </table>
                        <?php if (($this->params->def('show_pagination', 2) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
            <div class="pagination">
                            <?php if ($this->params->def('show_pagination_results', 1)) : ?>
                    <p class="counter">
                                <?php echo $this->pagination->getPagesCounter(); ?>
                    </p>
                            <?php endif; ?>
                            <?php echo $this->pagination->getPagesLinks(); ?>
            </div>
                            <?php endif; ?>
        <div>
            <!-- @TODO add hidden inputs -->
            <input type="hidden" name="filter_order" value="" />
            <input type="hidden" name="filter_order_Dir" value="" />
            <input type="hidden" name="limitstart" value="" />
        </div>
    </form>
    <?php endif; ?>