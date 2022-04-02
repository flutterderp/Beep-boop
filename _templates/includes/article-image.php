<?php
defined('_JEXEC') or die('Restricted access');

$app          = JFactory::getApplication();
$db           = JFactory::getDbo();
$doc          = JFactory::getDocument();
$jinput       = $app->input;
$joption      = $jinput->get('option', '', 'string');
$jview        = $jinput->get('view', '', 'word');
$jitem_id     = (int) $jinput->get('id', 0, 'int');
$style        = '';
$content_coms = array('com_content', 'com_blog');
$title        = str_ireplace(' - ' . $app->get('sitename'), null, $doc->getTitle());

if(in_array($joption, $content_coms))
{
	// Articles
	if($jview == 'category')
	{
		$images      = new stdClass();
		$column_name = 'params';
		$table_name  = 'categories';
		$result      = getImage($table_name, $column_name, $jitem_id, $jview);
		$images      = json_decode($result);

		$images->image_fulltext     = !empty($images->image) ? $images->image : '';
		$images->image_fulltext_alt = basename($images->image_fulltext);
	}
	else
	{
		$column_name = 'images';
		$table_name  = str_ireplace('com_', null, $joption);
		$result      = getImage($table_name, $column_name, $jitem_id, $jview);
		$images      = !empty($result) ? json_decode($result) : new stdClass();

		$images->image_fulltext     = !empty($images->image_fulltext) ? $images->image_fulltext : '';
		$images->image_fulltext_alt = !empty($images->image_fulltext_alt) ? $images->image_fulltext_alt : basename($images->image_fulltext);
	}

}

if(!empty($images->image_fulltext) && file_exists(JPATH_BASE . '/' . $images->image_fulltext))
{
	?>
	<div class="bannerimage" style="background-image: url('<?php echo $images->image_fulltext; ?>'); ?>"></div>
	<?php
}

function getImage($tbl, $column, $id, $view)
{
	$state_var = (preg_match('/(.*categories)/', $tbl)) ? 'published' : 'state';
	$db        = JFactory::getDbo();
	$sql       = $db->getQuery(true);
	$sql
		->select($db->qn($column))
		->from($db->qn('#__' . $tbl))
		->where($db->qn($state_var) . ' = 1')
		->where('id = ' . (int) $id)
		->setLimit(1);

	if($view == 'featured')
	{
		$sql->where('featured = 1');
	}
	elseif($view == 'article')
	{
		$sql->order('created DESC');
	}
	elseif($view == 'category')
	{
		$sql->order('created_time DESC');
	}

	$db->setQuery($sql);

	try
	{
		return $db->loadResult();
	}
	catch(RuntimeException $e)
	{
		JFactory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
		return false;
	}
}
