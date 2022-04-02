<?php
function addHttp( $url )
{
	if ( !preg_match( '~^(?:f|ht)tps?://~i', $url ) )
	{
			$url = 'http://' . $url;
	}
	return $url;
}

/**
 * Count number of modules whose content is not empty.
 * Some modules (e.g. mod_tags_similar) don't have any content until they are actually rendered,
 * so we render the whole set and store it in a string for later.
 *
 * @param pos string
 * @param style string
 * @return result string
 */
function getModContent(string $pos, string $style = 'xhtml5')
{
	$result  = '';
	$modules = JModuleHelper::getModules($pos);

	ob_start();

	foreach($modules as $module)
	{
		// Some modules (e.g. mod_tags_similar) don't have any content until they are actually rendered
		echo JModuleHelper::renderModule($module, array('style' => $style));
	}

	$result = ob_get_clean();

	return trim($result);
}

/**
 * Count number of modules whose content is not empty
 *
 * @deprecated
 */
function countActiveModules($pos)
{
	$result  = 0;
	$modules = JModuleHelper::getModules($pos);

	/* foreach($modules as $module)
	{
		// Some modules (e.g. mod_tags_similar) don't have any content until they are actually rendered
		JModuleHelper::renderModule($module);

		if(empty($module->content))
		{
			continue;
		}
		else
		{
			$result++;
		}
	} */

	return (int) $result;
}

/**
 * Usage
 * @var search_type The content type alias
 * $results = findTags('com_products.product', $option, $view, $id);
 */
function findTags($search_type, $option, $view, array $id)
{
	$app        = JFactory::getApplication();
	$today      = JDate::getInstance('UTC');
	$db         = JFactory::getDbo();
	$user       = JFactory::getUser();
	$groups     = implode(',', $user->getAuthorisedViewLevels());
	$matchtype  = 'any';
	$maximum    = 3;
	$counter    = 0;
	$tagsHelper = new JHelperTags;
	$prefix     = $option . '.' . $view;
	$notags     = array('com_tags', 'com_users');
	$found_tags = array();

	// Strip off any slug data from $id.
	foreach ($id as $id)
	{
		if (substr_count($id, ':') > 0)
		{
			$idexplode = explode(':', $id);
			$id        = (int) $idexplode[0];
		}
	}

	$tagsToMatch = $tagsHelper->getTagIds($id, $prefix);
	if(!$tagsToMatch || is_null($tagsToMatch))
	{
		$return = false;
		return $return;
		//break;
	}

	$tagCount = substr_count($tagsToMatch, ',') + 1;

	$query = $db->getQuery(true);
	$query
		->select('m.tag_id, m.core_content_id, m.content_item_id, m.type_alias, COUNT(m.tag_id) AS count')
		->select('t.access, t.id, ct.router, cc.core_title, cc.core_alias, cc.core_catid, cc.core_language')
		->select('cc.core_body, c.title AS core_category');

	$query->from($db->qn('#__contentitem_tag_map', 'm'));

	$query
		->join('INNER', $db->qn('#__tags', 't') . ' ON m.tag_id = t.id')
		->join('INNER', $db->qn('#__ucm_content', 'cc') . ' ON m.core_content_id = cc.core_content_id')
		->join('INNER', $db->qn('#__content_types', 'ct') . ' ON m.type_alias = ct.type_alias')
		->join('INNER', $db->qn('#__categories', 'c') . ' ON c.id = cc.core_catid');

	$query->where('m.tag_id IN (' . $tagsToMatch . ')');
	$query->where('t.access IN (' . $groups . ')');

	// Don't show current item
	$query->where('m.content_item_id <> ' . (int) $id);

	// Search specific component
	$query->where('m.type_alias = ' . $db->q($search_type));

	// Only return published tags
	$query->where('cc.core_state = 1 ');

	// Optionally filter on language
	$language = JComponentHelper::getParams('com_tags')->get('tag_list_language_filter', 'all');

	if ($language != 'all')
	{
		if ($language == 'current_language')
		{
			$language = JHelperContent::getCurrentLanguage();
		}
		$query->where('cc.core_language IN (' . $db->q($language) . ', ' . $db->q('*') . ')');
	}

	$query->group('m.core_content_id');
	if ($matchtype == 'all' && $tagCount > 0)
	{
		$query->having('COUNT(m.tag_id) = ' . (int) $tagCount);
	}
	elseif ($matchtype == 'half' && $tagCount > 0)
	{
		$tagCountHalf = ceil($tagCount / 2);
		$query->having('COUNT(m.tag_id) >= ' . (int) $tagCountHalf);
	}
	elseif ($matchtype == 'any' && $tagCount > 0)
	{
		$query->having('COUNT(m.tag_id) >= 1');
	}

	// Order by content type ( i.e. Articles, then Videos )
	$query
		->order('ct.type_title ASC')
		->setLimit($maximum, 0);
	$db->setQuery($query);

	try
	{
		$return = $db->loadObjectList();
		/*foreach ($return as $result)
		{
			$explodedAlias = explode('.', $result->type_alias);
			$result->link  = 'index.php?option=' . $explodedAlias[0] . '&view=' . $explodedAlias[1] . '&id=' . $result->content_item_id . '-' . $result->core_alias;
			$found_tags[]  = $result->core_alias;
			$counter++;
		}*/
	}
	catch(Exception $e)
	{
		$return = false;
	}

	return $return;
}
