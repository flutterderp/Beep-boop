<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

$app           = Factory::getApplication();
$option        = $app->input->get('option', 'com_content', 'string');
$component     = ucwords(str_ireplace('com_', '', $option));
$blockPosition = $displayData['params']->get('info_block_position', 0);

$displayData['context.option'] = $option;
$displayData['helperRoute'] = $component . 'HelperRoute';
?>
<div class="article-info">

	<?php if ($displayData['position'] === 'above' && ($blockPosition == 0 || $blockPosition == 2)
			|| $displayData['position'] === 'below' && ($blockPosition == 1)
			) : ?>

		<div class="article-info-term">
			<?php if ($displayData['params']->get('info_block_show_title', 1)) : ?>
				<?php echo JText::_($displayData['context.option'].'_ARTICLE_INFO'); ?>
			<?php endif; ?>
		</div>

		<?php if ($displayData['params']->get('show_author') && !empty($displayData['item']->author )) : ?>
			<?php echo $this->sublayout('author', $displayData); ?>
		<?php endif; ?>

		<?php if ($displayData['params']->get('show_parent_category') && !empty($displayData['item']->parent_slug)) : ?>
			<?php echo $this->sublayout('parent_category', $displayData); ?>
		<?php endif; ?>

		<?php if ($displayData['params']->get('show_category')) : ?>
			<?php echo $this->sublayout('category', $displayData); ?>
		<?php endif; ?>

		<?php if ($displayData['params']->get('show_associations')) : ?>
			<?php echo $this->sublayout('associations', $displayData); ?>
		<?php endif; ?>

		<?php if ($displayData['params']->get('show_publish_date')) : ?>
			<?php echo $this->sublayout('publish_date', $displayData); ?>
		<?php endif; ?>

	<?php endif; ?>

	<?php if ($displayData['position'] === 'above' && ($blockPosition == 0)
			|| $displayData['position'] === 'below' && ($blockPosition == 1 || $blockPosition == 2)
			) : ?>
		<?php if ($displayData['params']->get('show_create_date')) : ?>
			<?php echo $this->sublayout('create_date', $displayData); ?>
		<?php endif; ?>

		<?php if ($displayData['params']->get('show_modify_date')) : ?>
			<?php echo $this->sublayout('modify_date', $displayData); ?>
		<?php endif; ?>

		<?php if ($displayData['params']->get('show_hits')) : ?>
			<?php echo $this->sublayout('hits', $displayData); ?>
		<?php endif; ?>
	<?php endif; ?>
</div>
