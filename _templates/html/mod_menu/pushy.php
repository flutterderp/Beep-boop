<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

// Note. It is important to remove spaces between elements.
// $class_sfx
?>
<div class="pushy-content">
	<ul class="no-bullet"<?php
		$tag = '';

		if ($params->get('tag_id') != null)
		{
			$tag = $params->get('tag_id') . '';
			echo ' id="' . $tag . '"';
		}
	?>>
	<?php
	foreach ($list as $i => &$item)
	{
		$class = $item->deeper ? 'pushy-submenu' : 'pushy-link';
		$class .= ' item-' . $item->id;

		if (($item->id == $active_id) OR ($item->type == 'alias' AND $item->getParams()->get('aliasoptions') == $active_id))
		{
			$class .= ' current';
		}

		if (in_array($item->id, $path))
		{
			$class .= ' active';
		}
		elseif ($item->type == 'alias')
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

		if ($item->type == 'separator')
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

		if (!empty($class))
		{
			$class = ' class="' . trim($class) . '"';
		}

		echo '<li' . $class . '>';

		// Render the menu item.
		// renderItem($item->type, $item, $params);

		// The next item is deeper.
		if ($item->deeper)
		{
			echo '<button>' . htmlspecialchars($item->title, ENT_COMPAT|ENT_HTML5, 'utf-8') . '</button>';
			echo '<ul class="no-bullet">';
			// Render the parent menu item.
			echo '<li' . preg_replace('/pushy-submenu/', 'pushy-link', $class) . '>';

			renderItem($item->type, $item, $params);

			echo '</li>';
			// End parent menu item
		}
		elseif ($item->shallower)
		{
			// The next item is shallower.
			renderItem($item->type, $item, $params);
			echo '</li>';

			$end_html = '</ul></li>';
			echo str_repeat($end_html, $item->level_diff);
		}
		else
		{
			// The next item is on the same level.
			renderItem($item->type, $item, $params);
			echo '</li>';
		}
	}
	?>
	</ul>
</div>
<?php
/**
 * Method for rendering a menu item
 *
 * @param string	item_type	The menu item's type property
 * @param object	item			The item object itself
 */
function renderItem($item_type, $item, $params)
{
	switch ($item_type) :
		case 'separator':
		case 'url':
		case 'component':
		case 'heading':
			require ModuleHelper::getLayoutPath('mod_menu', 'pushy_' . $item_type);
			break;

		default:
			require ModuleHelper::getLayoutPath('mod_menu', 'pushy_url');
			break;
	endswitch;
}
