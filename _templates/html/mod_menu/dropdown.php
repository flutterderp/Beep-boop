<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

$id = '';

if ($tagId = $params->get('tag_id', ''))
{
	$id = ' id="' . $tagId . '"';
}
?>
<nav class="menu mainmenu<?php echo $class_sfx; ?>" role="navigation" aria-label="Main menu">
	<?php /* <div class="menu-item"><a href="/">Home</a></div> */ ?>

	<?php foreach ($list as $i => &$item)
	{
		$class = 'item-' . $item->id;

		if ($item->id == $default_id)
		{
			$class .= ' default';
		}

		if ($item->id == $active_id || ($item->type === 'alias' && $item->getParams()->get('aliasoptions') == $active_id))
		{
			$class .= ' current';
		}

		if (in_array($item->id, $path))
		{
			$class .= ' active';
		}
		elseif ($item->type === 'alias')
		{
			$aliasToId = $item->getParams()->get('aliasoptions');

			if (count($path) > 0 && $aliasToId == $path[count($path) - 1])
			{
				$class .= ' active';
			}
			elseif (in_array($aliasToId, $path))
			{
				$class .= ' alias-parent-active';
			}
		}

		if ($item->type === 'separator')
		{
			$class .= ' divider';
		}

		if ($item->deeper)
		{
			$class .= ' deeper';
		}

		if ($item->parent)
		{
			$class .= ' parent';
		}

		if ($item->deeper)
		{
			// The next item is deeper.
			echo '<div class="menu-item has-submenu">';

			if($item->type == 'separator' || $item->type == 'heading')
			{
				echo '<a href="#">' . $item->title . ' <span class="fa fa-angle-right"></span></a>';
			}
			else
			{
				// render the parent menu item
				echo renderItem($item->type, $item, $params);
			}

			echo '<nav class="submenu" aria-label="Submenu – ' . mb_strtolower($item->title) . '">';
		}
		elseif ($item->shallower)
		{
			// The next item is shallower.
			echo renderItem($item->type, $item, $params);
			echo str_repeat('</nav></div>', $item->level_diff);
		}
		else
		{
			// The next item is on the same level.
			echo renderItem($item->type, $item, $params);
		}
	}
	?>
</nav>

<?php
/**
 * Method for rendering a menu item
 *
 * @param string item_type The menu item's type property
 * @param object item      The item object itself
 */
function renderItem($item_type, $item, $params)
{
	switch ($item_type) :
		case 'separator':
		case 'component':
		case 'heading':
		case 'url':
			require ModuleHelper::getLayoutPath('mod_menu', 'dropdown_' . $item_type);
			break;

		default:
			require ModuleHelper::getLayoutPath('mod_menu', 'dropdown_url');
			break;
	endswitch;
}
