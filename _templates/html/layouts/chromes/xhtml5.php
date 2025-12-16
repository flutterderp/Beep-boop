<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.osmiumyoda
 *
 * @copyright   (C) 2020 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Filter\OutputFilter;
use Joomla\Utilities\ArrayHelper;

$module  = $displayData['module'];
$params  = $displayData['params'];
$attribs = $displayData['attribs'];

if ($module->content === null || $module->content === '')
{
	return;
}

$modPositions  = array('left','right');
$moduleTag     = $params->get('module_tag', 'div');
$headerTag     = htmlspecialchars($params->get('header_tag', 'h3'), ENT_COMPAT, 'UTF-8');
$bootstrapSize = (int) $params->get('bootstrap_size', 0);
$moduleClass   = $bootstrapSize != 0 ? ' span' . $bootstrapSize : '';
$moduleId      = !in_array($module->position, $modPositions) ? 'mod' . $module->id . '-' : '';
// Temporarily store header class in variable
$headerClass   = $params->get('header_class');
$headerClass   = !empty($headerClass) ? ' class="' . htmlspecialchars($headerClass, ENT_COMPAT, 'UTF-8') . '"' : '';

echo '<' . $moduleTag . ' class="moduletable' . htmlspecialchars($params->get('moduleclass_sfx', ''), ENT_COMPAT, 'UTF-8') . $moduleClass .
	'" id="' . $moduleId . OutputFilter::stringURLSafe($module->title) . '">';

if((bool) $module->showtitle)
	echo '<' . $headerTag . $headerClass . '>' . $module->title . '</' . $headerTag . '>';

echo $module->content;
echo '</' . $moduleTag . '>';
