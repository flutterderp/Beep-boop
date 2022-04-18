<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;
use Joomla\Utilities\ArrayHelper;

$app    = Factory::getApplication();
$params = $displayData->params;
$images = json_decode($displayData->images);

if (empty($images->image_fulltext))
{
	return;
}

$extraAttr = '';

if (Version::MAJOR_VERSION === 4)
{
	$img          = HTMLHelper::cleanImageUrl($images->image_fulltext);
	$alt_text     = empty($images->image_fulltext_alt) && empty($images->image_fulltext_alt_empty) ? '' : 'alt="' . htmlspecialchars($images->image_fulltext_alt, ENT_COMPAT, 'utf-8') . '"';
	$img_relative = $img->url;
	$extraAttr    = ArrayHelper::toString($img->attributes) . ' loading="lazy"';
}
else
{
	$img          = $images->image_fulltext;
	$alt_text     = empty($images->image_fulltext_alt) ? '' : 'alt="' . htmlspecialchars($images->image_fulltext_alt, ENT_COMPAT, 'utf-8') . '"';
	$img_relative = $images->image_fulltext;
	$extraAttr    = ' loading="lazy"';
}

$imgfloat = empty($images->float_fulltext) ? $params->get('float_fulltext') : $images->float_fulltext;
$img_path = pathinfo($img_relative, PATHINFO_DIRNAME);
$img_base = pathinfo($img_relative, PATHINFO_BASENAME);
$img_url  = Uri::root() . $img_path . '/' . $img_base;
list($img_width, $img_height)	= getimagesize($img_relative);

$app->set('ogImage', $img_url);
$app->set('ogImageWidth', $img_width);
$app->set('ogImageHeight', $img_height);
?>
<figure class="<?php echo htmlspecialchars($imgfloat); ?> item-image">
	<meta name="twitter:image" content="<?php echo $img_url; ?>">

	<img src="<?php echo htmlspecialchars($img_relative, ENT_COMPAT, 'utf-8'); ?>"
		width="<?php echo $img_width; ?>" height="<?php echo $img_height; ?>"
		<?php echo $alt_text; ?>
		itemprop="image"
		<?php echo $extraAttr; ?>
	/>
	<?php if ($images->image_fulltext_caption !== '') : ?>
		<figcaption class="caption"><?php echo htmlspecialchars($images->image_fulltext_caption, ENT_COMPAT, 'utf-8'); ?></figcaption>
	<?php endif; ?>
</figure>
