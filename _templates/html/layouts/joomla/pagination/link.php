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

/** @var JPaginationObject $item */
$item    = $displayData['data'];
$display = $item->text;
$class   = array();

switch ((string) $item->text)
{
	// Check for "Start" item
	case Text::_('JLIB_HTML_START') :
		$icon = 'fa fa-angle-double-left';
		$class[] = 'nav-start';
		break;

	// Check for "Prev" item
	case $item->text === Text::_('JPREV') :
		$item->text = Text::_('JPREVIOUS');
		$icon = 'fa fa-angle-left';
		$class[] = 'nav-prev';
		break;

	// Check for "Next" item
	case Text::_('JNEXT') :
		$icon = 'fa fa-angle-right';
		$class[] = 'nav-next';
		break;

	// Check for "End" item
	case Text::_('JLIB_HTML_END') :
		$icon = 'fa fa-angle-double-right';
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

	$onClick = 'document.forms.paginationForm.' . $item->prefix . 'limitstart.value=' . ($item->base > 0 ? $item->base : '0') . '; document.forms.paginationForm.submit(); return false;';
}
else
{
	$class[] = (property_exists($item, 'active') && $item->active) ? 'active' : 'disabled';
}
?>
<?php if ($displayData['active']) : ?>
	<li>
		<a <?php echo $cssClasses ? 'class="' . implode(' ', $cssClasses) . '"' : ''; ?> href="javascript:void();" <?php echo $title; ?> onclick="<?php echo $onClick; ?>">
			<?php echo $display; ?>
		</a>
	</li>
<?php else : ?>
	<li class="<?php echo implode(' ', $class); ?>">
		<a aria-hidden="true"><?php echo $display; ?></a>
	</li>
<?php endif;
