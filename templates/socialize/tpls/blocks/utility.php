<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<?php if ($this->countModules('utility')) : ?>
<!-- UTILITY -->
<div class="wrap t3-utility<?php $this->_c('utility') ?>">
  <div class="container">
    <jdoc:include type="modules" name="<?php $this->_p('utility') ?>" />
  </div>
</div>
<!-- //UTILITY -->
<?php endif ?>