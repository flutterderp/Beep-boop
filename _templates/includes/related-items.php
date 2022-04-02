<?php
defined('_JEXEC') or die;

use \Joomla\CMS\Helper\TagsHelper;

$app          = JFactory::getApplication();
// $user         = JFactory::getUser();
// $language     = JFactory::getLanguage();
$jinput       = $app->input;
$option       = $jinput->get('option', '', 'string');
$view         = $jinput->get('view', '', 'word');
$id           = (int) $jinput->get('id', 0, 'int');
$notags       = array('com_tags', 'com_users');
$max_items    = 3;
$result_count = 0;
// $language->load('mod_tags_similar');

if(($view != 'category' && $view != 'categories') && !in_array($option, $notags)) :
	$tagHelper = new TagsHelper();
	$tagIds    = $tagHelper->getTagIds($id, $option . '.' . $view);
	// $tagIds    = str_ireplace(',', '|', $tagIds);
	$items     = fetchItems($id, $max_items, $tagIds, '');

	// Display the results
	?>
	<?php if(!empty($items)) : ?>
		<ul class="related-items">
			<?php echo $items; ?>
		</ul>
	<?php endif; ?>
<?php endif;


function fetchItems(int $id, int $max_items = 3, string $tagIds, string $content_type = '')
{
	$db        = JFactory::getDbo();
	$inflector = JStringInflector::getInstance();
	$query     = $db->getQuery(true);
	$items     = '';

	$query
		->select('*')
		->from($db->qn('vw_tagged_items'))
		->where('content_item_id <> '.(int) $id)
		// ->where('tag_ids REGEXP ('.$db->q($tagIds).')')
		->where('FIND_IN_SET('.$db->qn('tag_id'). ', '.$db->q($tagIds).')')
		->setLimit($max_items)
		->group('content_item_id')
		->order('core_publish_up DESC');

	if(!empty($content_type))
	{
		$query->where('type_alias = ' . $db->q($content_type));
	}

	try
	{
		$db->setQuery($query);

		$results = $db->loadObjectList();

		ob_start();

		foreach($results as $item) :
			$router_parts              = explode('::', $item->router);
			$component                 = substr($item->type_alias, 0, strpos($item->type_alias, '.'));
			require_once(JPATH_BASE . '/components/'.$component.'/helpers/route.php');
			$images                    = json_decode($item->core_images);
			$item->core_category_alias = JFilterOutput::stringUrlSafe($item->core_category);
			$item->link                = JRoute::_($router_parts[0]::{$router_parts[1]}($item->content_item_id . ':' . $item->core_alias, $item->core_catid), true);

			if($images === null || $images === false)
			{
				$images                  = new stdClass();
				$images->image_intro     = $item->core_images;
				$images->image_intro_alt = $item->core_title;
			}

			// Get the introtext/description
			$introtext = preg_replace("#<span[^>]*>(.*)</span>#isU", '', $item->core_body);
			$introtext = strip_tags($introtext);
			$introtext = trim(substr($introtext, 0, 142));

			if($inflector->isPlural($item->type_title))
			{
				$type_title = $inflector->toSingular($item->type_title);
			}
			else
			{
				$type_title = $item->type_title;
			}
			?>
			<li>
				<?php if($images->image_intro && file_exists(JPATH_BASE . '/' . $images->image_intro)) : ?>
					<a href="<?php echo $item->link; ?>"><img class="img-responsive" src="<?php echo $images->image_intro; ?>" alt="<?php echo $item->alias; ?>" width="100%"></a>
				<?php else : ?>
					<a href="<?php echo $item->link; ?>"><img class="img-responsive" src="https://via.placeholder.com/510x340/ccc/ccc" alt="<?php echo $item->alias; ?>" width="100%"></a>
				<?php endif; ?>

				<h3>
					<?php echo htmlspecialchars($type_title, ENT_QUOTES, 'utf-8'); ?>:
					<a href="<?php echo $item->link; ?>"><?php echo $item->core_title; ?></a>
				</h3>

				<?php /* <p><b class="mod-articles-category-category category"><a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($item->core_catid)); ?>"><?php echo $item->core_category; ?></a>:</b></p> */ ?>
			</li>
		<?php endforeach;

		$items = ob_get_clean();
	}
	catch(RuntimeException $e)
	{
		$items = '<li><b>No related items found.</b></li>';
	}

	if(empty($items))
	{
		$items = '<li><b>No related items found.</b></li>';
	}

	return $items;
}
