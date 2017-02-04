<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<!-- TOP HEADER -->
<?php if ($this->countModules('top-header')) : ?>
<!-- TOP-HEADER -->
<div id="top-header" class="top-fixed <?php $this->_c('navhelper') ?>">
  <jdoc:include type="modules" name="<?php $this->_p('top-header') ?>" />
</div>
<?php endif ?>
<!-- //TOP HEADER -->