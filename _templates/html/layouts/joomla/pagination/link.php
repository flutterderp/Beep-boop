<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/** @var JPaginationObject $item */
$item = $displayData['data'];
$display = $item->text;
$class = array();

switch ((string) $item->text)
{
	// Check for "Start" item
	case JText::_('JLIB_HTML_START') :
		$icon = 'fa fa-fast-backward icon-first';
		$class[] = 'nav-start';
		break;

	// Check for "Prev" item
	case $item->text === JText::_('JPREV') :
		$item->text = JText::_('JPREVIOUS');
		$icon = 'fa fa-backward icon-previous';
		$class[] = 'nav-prev';
		break;

	// Check for "Next" item
	case JText::_('JNEXT') :
		$icon = 'fa fa-forward icon-next';
		$class[] = 'nav-next';
		break;

	// Check for "End" item
	case JText::_('JLIB_HTML_END') :
		$icon = 'fa fa-fast-forward icon-last';
		$class[] = 'nav-end';
		break;

	default:
		$icon = null;
		break;
}

if ($icon !== null)
{
	$display = '<span class="' . $icon . '"></span>';
}

if ($displayData['active'])
{
	if ($item->base > 0)
	{
		$limit = 'limitstart.value=' . $item->base;
	}
	else
	{
		$limit = 'limitstart.value=0';
	}

	$cssClasses = array();

	$title = '';

	if (!is_numeric($item->text))
	{
		// JHtml::_('bootstrap.tooltip');
		$cssClasses[] = 'has-tip';
		$title = ' title="' . $item->text . '" ';
	}

	$onClick = 'document.paginationForm.' . $item->prefix . 'limitstart.value=' . ($item->base > 0 ? $item->base : '0') . '; document.paginationForm.submit(); return false;';
}
else
{
	$class[] = (property_exists($item, 'active') && $item->active) ? 'active' : 'disabled';
}
?>
<?php if ($displayData['active']) : ?>
	<li class="<?php echo implode(' ', $class); ?>">
		<a <?php echo $cssClasses ? 'class="' . implode(' ', $cssClasses) . '"' : ''; ?> <?php echo $title; ?> onclick="<?php echo $onClick; ?>">
			<?php echo $display; ?>
		</a>
	</li>
<?php else : ?>
	<li class="<?php echo implode(' ', $class); ?>">
		<span><?php echo $display; ?></span>
	</li>
<?php endif;
