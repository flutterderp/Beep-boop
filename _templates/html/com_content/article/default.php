<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Component\Content\Administrator\Extension\ContentComponent;
// use Joomla\Component\Content\Site\Helper\RouteHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers');

// Create shortcuts to some parameters.
$app        = Factory::getApplication();
$doc        = Factory::getDocument();
$user       = Factory::getUser();
$tpl        = $app->getTemplate($tpl_params = true);
$tpl_params = $tpl->params;
$params     = $this->item->params;
$images     = json_decode($this->item->images);
$urls       = json_decode($this->item->urls);
$jcfields   = array();
$canEdit    = $params->get('access-edit');
$info       = $params->get('info_block_position', 0);
$root_url   = preg_replace("/\/$/", '', Uri::root());
// $full_image = LayoutHelper::render('joomla.content.full_image', $this->item);
// $doc->addHeadLink(Route::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)), 'canonical');
$doc->addCustomTag('<link href="' . $root_url . Route::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)) . '" rel="canonical">');

foreach($this->item->jcfields as $key => $field)
{
	$jcfields[$field->name] = $field;
}

// Check if associations are implemented. If they are, define the parameter.
$assocParam           = (Associations::isEnabled() && $params->get('show_associations'));
$currentDate          = Factory::getDate()->format('Y-m-d H:i:s');
$conditionUnpublished = (Version::MAJOR_VERSION === 4) ? ContentComponent::CONDITION_UNPUBLISHED : 0;
$isNotPublishedYet    = $this->item->publish_up > $currentDate;
$isExpired            = !is_null($this->item->publish_down) && $this->item->publish_down < $currentDate && $this->item->publish_down !== Factory::getDbo()->getNullDate();

if(Version::MAJOR_VERSION < 4)
{
	HTMLHelper::_('behavior.caption');
}
?>
<article class="item-page<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Article">
	<?php
	$image_fulltext = $images->image_fulltext ? $images->image_fulltext : $images->image_intro;
	$image_fulltext = ($image_fulltext && file_exists(JPATH_BASE . '/' . $image_fulltext)) ? $image_fulltext : $tpl_params->get('logo', '');
	// echo '<meta name="twitter:card" content="summary_large_image">';
	// echo '<meta name="twitter:url" content="'.Route::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)).'">';
	echo '<meta name="twitter:description" content="'.$this->escape($this->item->introtext).'">';

	?>
	<link itemprop="mainEntityOfPage" href="<?php echo Route::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>">
	<meta itemprop="headline" content="<?php echo $this->escape($this->item->title); ?>">
	<meta itemprop="author" content="<?php echo $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author; ?>">
	<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
		<meta itemprop="name" content="<?php echo $tpl_params->get('publisher_name', '')? $tpl_params->get('publisher_name', ''): $this->item->author; ?>">
		<?php if(file_exists(JPATH_BASE . '/images/template/logo.png')) : ?>
			<?php list($tpl_width, $tpl_height) = getimagesize(JPATH_BASE . '/images/template/logo.png'); ?>
			<span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
				<link itemprop="url" content="<?php echo Uri::root() . 'images/template/logo.png'; ?>">
				<meta itemprop="width" content="<?php echo $tpl_width; ?>px">
				<meta itemprop="height" content="<?php echo $tpl_height; ?>px">
			</span>
		<?php endif; ?>
	</div>

	<?php if(!$params->get('show_modify_date')) : ?>
		<time datetime="<?php echo HTMLHelper::_('date', ($this->item->modified ? $this->item->modified : $this->item->publish_up), 'c'); ?>" itemprop="dateModified"></time>
	<?php endif; ?>

	<?php if(!$params->get('show_publish_date')) : ?>
		<time datetime="<?php echo HTMLHelper::_('date', $this->item->publish_up, 'c'); ?>" itemprop="datePublished"></time>
	<?php endif; ?>

	<meta itemprop="inLanguage" content="<?php echo ($this->item->language === '*') ? $app->get('language') : $this->item->language; ?>" />
	<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page-header">
		<h4> <?php echo $this->escape($this->params->get('page_heading')); ?> </h4>
	</div>
	<?php endif;
	if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && $this->item->paginationrelative)
	{
		echo $this->item->pagination;
	}
	?>

	<?php // Todo Not that elegant would be nice to group the params ?>
	<?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
	|| $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author') || $assocParam); ?>

	<?php if (!$useDefList && $this->print) : ?>
		<div id="pop-print" class="btn hidden-print">
			<?php echo HTMLHelper::_('icon.print_screen', $this->item, $params); ?>
		</div>
		<div class="clearfix"> </div>
	<?php endif; ?>
	<?php if ($params->get('show_title') || $params->get('show_author')) : ?>
	<div class="page-header">
		<?php if ($params->get('show_title')) : ?>
			<h1 itemprop="headline">
				<?php echo $this->escape($this->item->title); ?>
			</h1>
		<?php endif; ?>

		<?php /* if(isset($jcfields['subheading']) && !empty($jcfields['subheading'])) : ?>
			<h2><?php echo $this->escape($jcfields['subheading']->rawvalue); ?></h2>
		<?php endif; */ ?>

		<?php if ($this->item->state == $conditionUnpublished) : ?>
			<span class="label label-warning"><?php echo Text::_('JUNPUBLISHED'); ?></span>
		<?php endif; ?>
		<?php if ($isNotPublishedYet) : ?>
			<span class="label label-warning"><?php echo Text::_('JNOTPUBLISHEDYET'); ?></span>
		<?php endif; ?>
		<?php if ($isExpired) : ?>
			<span class="label label-warning"><?php echo Text::_('JEXPIRED'); ?></span>
		<?php endif; ?>
	</div>
	<?php endif; ?>
	<?php if (!$this->print) : ?>
		<?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
			<?php echo LayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->item, 'print' => false)); ?>
		<?php endif; ?>
	<?php else : ?>
		<?php if ($useDefList) : ?>
			<div id="pop-print" class="btn hidden-print">
				<?php echo HTMLHelper::_('icon.print_screen', $this->item, $params); ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php // Content is generated by content plugin event "onContentAfterTitle" ?>
	<?php echo $this->item->event->afterDisplayTitle; ?>

	<?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
		<?php echo LayoutHelper::render('joomla.content.info_block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
	<?php endif; ?>

	<?php echo LayoutHelper::render('joomla.content.full_image', $this->item); ?>

	<?php if ($info == 0 && $params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
		<?php $this->item->tagLayout = new FileLayout('joomla.content.tags'); ?>

		<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
	<?php endif; ?>

	<?php // Content is generated by content plugin event "onContentBeforeDisplay" ?>
	<?php echo $this->item->event->beforeDisplayContent; ?>

	<?php if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '0')) || ($params->get('urls_position') == '0' && empty($urls->urls_position)))
		|| (empty($urls->urls_position) && (!$params->get('urls_position')))) : ?>
	<?php echo $this->loadTemplate('links'); ?>
	<?php endif; ?>
	<?php if ($params->get('access-view')) : ?>

	<?php
	if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && !$this->item->paginationrelative) :
		echo $this->item->pagination;
	endif;
	?>
	<?php if (isset ($this->item->toc)) :
		echo $this->item->toc;
	endif; ?>
	<div itemprop="articleBody">
		<?php if(isset($jcfields['video-url']) && !empty($jcfields['video-url']->rawvalue)) : ?>
			<iframe src="<?php echo OutputFilter::ampReplace($jcfields['video-url']->rawvalue); ?>" height="480" width="640"></iframe>
		<?php endif; ?>

		<?php echo $this->item->text; ?>
	</div>

	<?php if ($info == 1 || $info == 2) : ?>
		<?php if ($useDefList) : ?>
			<?php echo LayoutHelper::render('joomla.content.info_block', array('item' => $this->item, 'params' => $params, 'position' => 'below')); ?>
		<?php endif; ?>
		<?php if ($params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
			<?php $this->item->tagLayout = new FileLayout('joomla.content.tags'); ?>
			<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
		<?php endif; ?>
	<?php endif; ?>

	<?php
	if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && !$this->item->paginationrelative) :
		echo $this->item->pagination;
	?>
	<?php endif; ?>
	<?php if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '1')) || ($params->get('urls_position') == '1'))) : ?>
	<?php echo $this->loadTemplate('links'); ?>
	<?php endif; ?>
	<?php // Optional teaser intro text for guests ?>
	<?php elseif ($params->get('show_noauth') == true && $user->get('guest')) : ?>
	<?php echo LayoutHelper::render('joomla.content.intro_image', $this->item); ?>
	<?php echo HTMLHelper::_('content.prepare', $this->item->introtext); ?>
	<?php // Optional link to let them register to see the whole article. ?>
	<?php if ($params->get('show_readmore') && $this->item->fulltext != null) : ?>
	<?php $menu = $app->getMenu(); ?>
	<?php $active = $menu->getActive(); ?>
	<?php $itemId = $active->id; ?>
	<?php $link = new Uri(Route::_('index.php?option=com_users&view=login&Itemid=' . $itemId, false)); ?>
	<?php $link->setVar('return', base64_encode(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language))); ?>
	<p class="readmore">
		<a href="<?php echo $link; ?>" class="register">
		<?php $attribs = json_decode($this->item->attribs); ?>
		<?php
		if ($attribs->alternative_readmore == null) :
			echo Text::_('COM_CONTENT_REGISTER_TO_READ_MORE');
		elseif ($readmore = $attribs->alternative_readmore) :
			echo $readmore;
			if ($params->get('show_readmore_title', 0) != 0) :
				echo HTMLHelper::_('string.truncate', $this->item->title, $params->get('readmore_limit'));
			endif;
		elseif ($params->get('show_readmore_title', 0) == 0) :
			echo Text::sprintf('COM_CONTENT_READ_MORE_TITLE');
		else :
			echo Text::_('COM_CONTENT_READ_MORE');
			echo HTMLHelper::_('string.truncate', $this->item->title, $params->get('readmore_limit'));
		endif; ?>
		</a>
	</p>
	<?php endif; ?>
	<?php endif; ?>
	<?php
	if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && $this->item->paginationrelative) :
		echo $this->item->pagination;
	?>
	<?php endif; ?>
	<?php // Content is generated by content plugin event "onContentAfterDisplay" ?>
	<?php echo $this->item->event->afterDisplayContent; ?>
</article>
