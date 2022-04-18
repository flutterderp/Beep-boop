<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.osmiumyoda
 *
 * @copyright   (C) 2020 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

$module  = $displayData['module'];
$params  = $displayData['params'];
$attribs = $displayData['attribs'];

if ($module->content === null || $module->content === '')
{
	return;
}

$modPositions = array('left','right');
$moduleTag    = $params->get('module_tag', 'div');
$moduleClass  = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
$moduleId     = !in_array($module->position, $modPositions) ? 'mod' . $module->id . '-' : '';
$headerTag    = htmlspecialchars($params->get('header_tag', 'h3'), ENT_COMPAT, 'UTF-8');
$headerClass  = htmlspecialchars($params->get('header_class', 'page-header'), ENT_COMPAT, 'UTF-8');

echo '<' . $moduleTag . ' class="moduletable' . $moduleClass . '" id="' . $moduleId . JFilterOutput::stringURLSafe($module->title) . '">';

if($module->showtitle)
{
	echo '<' . $headerTag . ' class="' . $headerClass . '"><span>' . $module->title . '</span></' . $headerTag . '>';
}

echo $module->content;
echo '</' . $moduleTag . '>';
