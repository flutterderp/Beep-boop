<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Component\Content\Site\Helper\RouteHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;
use Joomla\Utilities\ArrayHelper;

$app         = Factory::getApplication();
$option      = $app->input->get('option', 'com_content', 'string');
$component   = ucwords(str_ireplace('com_', '', $option));
$helperRoute = $component . 'HelperRoute';
$params      = $displayData->params;
$images      = json_decode($displayData->images);

if (empty($images->image_intro))
{
	return;
}

$extraAttr = '';

if (Version::MAJOR_VERSION === 4)
{
	// Route::_(RouteHelper::getArticleRoute($displayData->slug, $displayData->catid, $displayData->language))
	$img          = HTMLHelper::cleanImageUrl($images->image_intro);
	$alt_text     = empty($images->image_intro_alt) && empty($images->image_intro_alt_empty) ? '' : 'alt="' . htmlspecialchars($images->image_intro_alt, ENT_COMPAT, 'utf-8') . '"';
	$show_link    = ($params->get('link_intro_image') && ($params->get('access-view') || $params->get('show_noauth', '0') == '1')) ? true : false;
	$img_relative = $img->url;
	$extraAttr    = ArrayHelper::toString($img->attributes) . ' loading="lazy"';
}
else
{
	$img          = $images->image_intro;
	$alt_text     = empty($images->image_intro_alt) ? '' : 'alt="' . htmlspecialchars($images->image_intro_alt, ENT_COMPAT, 'utf-8') . '"';
	$show_link    = ($params->get('link_titles') && $params->get('access-view')) ? true : false;
	$img_relative = $images->image_intro;
	$extraAttr    = ' loading="lazy"';
}

$imgfloat  = empty($images->float_intro) ? $params->get('float_intro') : $images->float_intro;
$img_path  = pathinfo($img_relative, PATHINFO_DIRNAME);
$img_base  = pathinfo($img_relative, PATHINFO_BASENAME);
$img_url   = Uri::root() . $img_path . '/' . $img_base;
list($img_width, $img_height) = getimagesize($img_relative);
?>
<figure class="<?php echo htmlspecialchars($imgfloat, ENT_COMPAT, 'utf-8'); ?> item-image">
	<?php if ($show_link) : ?>
		<a href="<?php echo Route::_($helperRoute::getArticleRoute($displayData->slug, $displayData->catid, $displayData->language)); ?>"
			itemprop="url" title="<?php echo $this->escape($displayData->title); ?>">
			<img src="<?php echo htmlspecialchars($img_relative, ENT_COMPAT, 'utf-8'); ?>"
				height="<?php echo $img_height; ?>" width="<?php echo $img_width; ?>"
				<?php echo $alt_text; ?>
				itemprop="thumbnailUrl"
				<?php echo $extraAttr; ?>
			/>
		</a>
	<?php else : ?>
		<img src="<?php echo htmlspecialchars($img_relative, ENT_COMPAT, 'utf-8'); ?>"
			height="<?php echo $img_height; ?>" width="<?php echo $img_width; ?>"
			<?php echo $alt_text; ?>
			itemprop="thumbnailUrl"
			<?php echo $extraAttr; ?>
		/>
	<?php endif; ?>
	<?php if (isset($images->image_intro_caption) && $images->image_intro_caption !== '') : ?>
		<figcaption class="caption"><?php echo htmlspecialchars($images->image_intro_caption, ENT_COMPAT, 'utf-8'); ?></figcaption>
	<?php endif; ?>
</figure>
