<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Version;

$catslug = (Version::MAJOR_VERSION === 4) ? $catslug = $displayData['item']->catid : $displayData['item']->catslug;
?>
<dd class="category-name">
	<?php $title = $this->escape($displayData['item']->category_title); ?>
	<?php if ($displayData['params']->get('link_category') && $catslug) : ?>
		<?php $url = '<a href="' . Route::_($displayData['helperRoute']::getCategoryRoute($catslug)) . '" itemprop="genre">' . $title . '</a>'; ?>
		<?php echo Text::sprintf($displayData['context.option'].'_CATEGORY', $url); ?>
	<?php else : ?>
		<?php echo Text::sprintf($displayData['context.option'].'_CATEGORY', '<span itemprop="genre">' . $title . '</span>'); ?>
	<?php endif; ?>
</dd>
