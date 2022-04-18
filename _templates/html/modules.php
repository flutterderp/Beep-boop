<?php
/**
 * @package Joomla.Site

 * @copyright Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\Filter\OutputFilter;

/**
 * Spanned title chrome
 */
function modChrome_spanTitle($module, &$params, &$attribs)
{
	$modPositions = array('left','right');
	$moduleTag    = $params->get('module_tag', 'div');
	$moduleClass  = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
	$moduleId     = !in_array($module->position, $modPositions) ? 'mod' . $module->id . '-' : '';
	$headerTag    = htmlspecialchars($params->get('header_tag', 'h3'), ENT_COMPAT, 'UTF-8');
	$headerClass  = htmlspecialchars($params->get('header_class', 'page-header'), ENT_COMPAT, 'UTF-8');

	if($module->content)
	{
		echo '<' . $moduleTag . ' class="moduletable' . $moduleClass . '" id="' . $moduleId . OutputFilter::stringUrlSafe($module->title) . '" aria-label="' . $moduleId . $module->title . '">';

		if($module->showtitle)
		{
			echo '<' . $headerTag . ' class="' . $headerClass . '"><span>' . $module->title . '</span></' . $headerTag . '>';
		}

		echo $module->content;
		echo '</' . $moduleTag . '>';
	}
}

/**
 * Padded mod_custom chrome
 */
function modChrome_padCustom($module, &$params, &$attribs)
{
	$modPositions = array('left','right');
	$moduleImg    = $params->get('backgroundimage', '');
	$moduleTag    = $params->get('module_tag', 'div');
	$moduleClass  = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
	$moduleId     = !in_array($module->position, $modPositions) ? 'mod' . $module->id . '-' : '';
	$headerTag    = htmlspecialchars($params->get('header_tag', 'h3'), ENT_COMPAT, 'UTF-8');
	$headerClass  = htmlspecialchars($params->get('header_class', 'page-header'), ENT_COMPAT, 'UTF-8');

	if($module->content)
	{
		echo '<' . $moduleTag . ' class="moduletable' . $moduleClass . '" id="' . $moduleId . OutputFilter::stringUrlSafe($module->title) . '" aria-label="' . $moduleId . $module->title . '">';

		if($moduleImg && file_exists(JPATH_BASE . '/' . $moduleImg))
		{
			echo '<img src="' . $moduleImg . '" alt="' . OutputFilter::stringUrlSafe($module->title) . '">';
		}

		echo '<div class="padwrapper">';

		if($module->showtitle)
		{
			echo '<' . $headerTag . ' class="' . $headerClass . '"><span>' . $module->title . '</span></' . $headerTag . '>';
		}

		echo $module->content;
		echo '</div>';
		echo '</' . $moduleTag . '>';
	}
}

/*
 * html5 (chosen html5 tag and font header tags)
 */
function modChrome_xhtml5($module, &$params, &$attribs)
{
	$modPositions  = array('left','right');
	$moduleTag     = $params->get('module_tag', 'div');
	$headerTag     = htmlspecialchars($params->get('header_tag', 'h3'), ENT_COMPAT, 'UTF-8');
	$bootstrapSize = (int) $params->get('bootstrap_size', 0);
	$moduleClass   = $bootstrapSize != 0 ? ' span' . $bootstrapSize : '';
	$moduleId      = !in_array($module->position, $modPositions) ? 'mod' . $module->id . '-' : '';
	// Temporarily store header class in variable
	$headerClass   = $params->get('header_class');
	$headerClass   = !empty($headerClass) ? ' class="' . htmlspecialchars($headerClass, ENT_COMPAT, 'UTF-8') . '"' : '';

	if(!empty ($module->content))
	{
		echo '<' . $moduleTag . ' class="moduletable' . htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8') . $moduleClass .
			'" id="' . $moduleId . OutputFilter::stringUrlSafe($module->title) . '" aria-label="' . $moduleId . $module->title . '">';

		if((bool) $module->showtitle)
			echo '<' . $headerTag . $headerClass . '>' . $module->title . '</' . $headerTag . '>';

		echo $module->content;
		echo '</' . $moduleTag . '>';
	}
}
