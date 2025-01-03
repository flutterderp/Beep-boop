<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\HTML\HTMLHelper;

$attributes = array();

if ($item->anchor_title)
{
	$attributes['title'] = $item->anchor_title;
}

if ($item->anchor_css)
{
	$attributes['class'] = $item->anchor_css;
}

if ($item->anchor_rel)
{
	$attributes['rel'] = $item->anchor_rel;
}

if (empty($item->anchor_class))
{
	$item->anchor_class = '';
}

$linktype = $item->title;

if ($item->menu_image)
{
	$linktype = HTMLHelper::_('image', $item->menu_image, $item->title);

	if ($item->params->get('menu_text', 1))
	{
		$linktype .= '<span class="image-title">' . $item->title . '</span>';
	}
}

if ($item->browserNav == 1)
{
	$attributes['target'] = '_blank';
	$attributes['rel']    .= ' noopener noreferrer';
}
elseif ($item->browserNav == 2)
{
	$options = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes';

	$attributes['onclick'] = "window.open(this.href, 'targetWindow', '" . $options . "'); return false;";
}

$url     = OutputFilter::ampReplace(htmlspecialchars($item->flink, ENT_COMPAT, 'UTF-8', false));
$attribs = array();

if(isset($attributes['target']))
{
	$attribs[] = 'target="' . $attributes['target'] . '"';
}

if(isset($attributes['onclick']))
{
	$attribs[] = 'onclick="' . $attributes['onclick'] . '"';
}

if(isset($attributes['rel']))
{
	$attribs[] = 'rel="' . $attributes['rel'] . '"';
}
?>
<div class="menu-item">
	<a class="<?php echo $item->anchor_class; ?>" href="<?php echo $url; ?>" <?php echo implode(' ', $attribs); ?>><?php echo $linktype; ?></a>
</div>
