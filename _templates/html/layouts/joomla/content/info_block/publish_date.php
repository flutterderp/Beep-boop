<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
?>
<div class="created">
	<time datetime="<?php echo HTMLHelper::_('date', $displayData['item']->publish_up, 'c'); ?>" itemprop="datePublished">
		<?php echo Text::sprintf($displayData['context.option'].'_PUBLISHED_DATE_ON', HTMLHelper::_('date', $displayData['item']->publish_up, Text::_('DATE_FORMAT_LC3'))); ?>
	</time>
	<span class="createdby" itemprop="author" itemscope itemtype="https://schema.org/Person">
		<?php $author = ($displayData['item']->created_by_alias ?: $displayData['item']->author); ?>
		<?php $author = '<span itemprop="name">' . $author . '</span>'; ?>
		<?php if (!empty($displayData['item']->contact_link ) && $displayData['params']->get('link_author') == true) : ?>
			<?php echo Text::sprintf($displayData['context.option'].'_WRITTEN_BY', HTMLHelper::_('link', $displayData['item']->contact_link, $author, array('itemprop' => 'url'))); ?>
		<?php else : ?>
			<?php echo Text::sprintf($displayData['context.option'].'_WRITTEN_BY', $author); ?>
		<?php endif; ?>
	</span>
</div>
