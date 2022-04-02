<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

$app = JFactory::getApplication();
$option = $app->input->get('option', 'com_content', 'string');

$article = $displayData['article'];
$overlib = $displayData['overlib'];
$legacy  = $displayData['legacy'];


if ($legacy)
{
	$icon = $article->state ? 'edit.png' : 'edit_unpublished.png';

	if (strtotime($article->publish_up) > strtotime(JFactory::getDate())
		|| ((strtotime($article->publish_down) < strtotime(JFactory::getDate())) && $article->publish_down != JFactory::getDbo()->getNullDate()))
	{
		$icon = 'edit_unpublished.png';
	}
}
else
{
	$icon = $article->state ? 'edit' : 'eye-close';

	if (strtotime($article->publish_up) > strtotime(JFactory::getDate())
		|| ((strtotime($article->publish_down) < strtotime(JFactory::getDate())) && $article->publish_down != JFactory::getDbo()->getNullDate()))
	{
		$icon = 'eye-close';
	}
}

?>
<?php if ($legacy) : ?>
	<?php echo JHtml::_('image', 'system/' . $icon, JText::_('JGLOBAL_EDIT'), null, true); ?>
<?php else : ?>
	<span class="hasTooltip icon-<?php echo $icon; ?> tip" title="<?php echo JHtml::tooltipText(JText::_($option.'_EDIT_ITEM'), $overlib, 0, 0); ?>"></span>
	<?php echo JText::_('JGLOBAL_EDIT'); ?>
<?php endif; ?>
