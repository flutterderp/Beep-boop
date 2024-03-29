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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$app       = Factory::getApplication();
$option    = $app->input->get('option', 'com_content', 'string');
$params    = $displayData['params'];
$item      = $displayData['item'];
$direction = Factory::getLanguage()->isRtl() ? 'left' : 'right';
?>

<p class="readmore">
	<?php if (!$params->get('access-view')) : ?>
		<a class="button" href="<?php echo $displayData['link']; ?>" aria-label="<?php echo Text::_('JGLOBAL_REGISTER_TO_READ_MORE') . ' ' . $this->escape($item->title); ?>">
			<?php /* echo '<span class="fa fa-chevron-' . $direction . '" aria-hidden="true"></span>'; */ ?>
			<?php echo Text::_('JGLOBAL_REGISTER_TO_READ_MORE'); ?>
		</a>
	<?php elseif ($readmore = $item->alternative_readmore) : ?>
		<a class="button" href="<?php echo $displayData['link']; ?>" aria-label="<?php echo $this->escape($readmore . ' ' . $item->title); ?>">
			<?php /* echo '<span class="fa fa-chevron-' . $direction . '" aria-hidden="true"></span>'; */ ?>
			<?php echo $readmore; ?>
			<?php if ($params->get('show_readmore_title', 0) != 0) : ?>
				<?php echo HTMLHelper::_('string.truncate', $item->title, $params->get('readmore_limit')); ?>
			<?php endif; ?>
		</a>
	<?php elseif ($params->get('show_readmore_title', 0) == 0) : ?>
		<a class="button" href="<?php echo $displayData['link']; ?>" aria-label="<?php echo Text::_($option . '_READ_MORE') . $this->escape($item->title); ?>">
			<?php /* echo '<span class="fa fa-chevron-' . $direction . '" aria-hidden="true"></span>'; */ ?>
			<?php echo Text::_($option . '_READ_MORE'); ?>
		</a>
	<?php else : ?>
		<a class="button" href="<?php echo $displayData['link']; ?>" aria-label="<?php echo Text::_($option . '_READ_MORE') . $this->escape($item->title); ?>">
			<?php /* echo '<span class="fa fa-chevron-' . $direction . '" aria-hidden="true"></span>'; */ ?>
			<?php echo Text::_($option . '_READ_MORE'); ?>
		</a>
	<?php endif; ?>
</p>
