<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;
$params = $displayData->params;
?>
<?php $images = json_decode($displayData->images); ?>
<?php if (isset($images->image_fulltext) && !empty($images->image_fulltext) && file_exists(JPATH_BASE . '/' . $images->image_fulltext) === true) : ?>
	<?php
	$imgfloat = empty($images->float_fulltext) ? $params->get('float_fulltext') : $images->float_fulltext;
	$img_path = pathinfo($images->image_fulltext, PATHINFO_DIRNAME);
	$img_base = pathinfo($images->image_fulltext, PATHINFO_BASENAME);
	$img_url = JUri::root() . $img_path . '/' . $img_base;
	list($img_width, $img_height)	= getimagesize($images->image_fulltext);
	$class = '';
	$title = '';

	if ($images->image_fulltext_caption)
	{
		$class = 'class="caption"';
		$title = 'title="' . htmlspecialchars($images->image_fulltext_caption, ENT_COMPAT, 'utf-8') . '"';
	}
	?>
	<div class="pull-<?php echo htmlspecialchars($imgfloat); ?> item-image">
		<meta name="twitter:image" content="<?php echo $img_url; ?>">
		<meta property="og:image" content="<?php echo $img_url; ?>">
		<meta property="og:image:width" content="<?php echo $img_width; ?>">
		<meta property="og:image:height" content="<?php echo $img_height; ?>">
		<img <?php echo $class; ?> src="<?php echo htmlspecialchars($images->image_fulltext, ENT_COMPAT, 'utf-8'); ?>"
			alt="<?php echo htmlspecialchars($images->image_fulltext_alt, ENT_COMPAT, 'utf-8'); ?>" <?php echo $title; ?>
			width="<?php echo $img_width; ?>" height="<?php echo $img_height; ?>" itemprop="image">
	</div>
<?php endif; ?>
