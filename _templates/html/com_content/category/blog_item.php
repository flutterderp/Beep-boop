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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');

// Create a shortcut for params.
$app         = Factory::getApplication();
$params      = $this->item->params;
$canEdit     = $this->item->params->get('access-edit');
$info        = $params->get('info_block_position', 0);
$intro_image = LayoutHelper::render('joomla.content.intro_image', $this->item);

// Check if associations are implemented. If they are, define the parameter.
$assocParam           = (Associations::isEnabled() && $params->get('show_associations'));
$currentDate          = Factory::getDate()->format('Y-m-d H:i:s');
$nullDate             = Factory::getDbo()->getNullDate();
$conditionUnpublished = $this->item->state == ((Version::MAJOR_VERSION === 4) ? ContentComponent::CONDITION_UNPUBLISHED : 0);
$isNotPublishedYet    = $this->item->publish_up > $currentDate;
$isExpired            = !is_null($this->item->publish_down) && $this->item->publish_down !== $nullDate && $this->item->publish_down < $currentDate;
?>
<?php if ($conditionUnpublished || $isNotPublishedYet || $isExpired) : ?>
	<div class="system-unpublished">
<?php endif; ?>

<?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
	<?php echo LayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->item, 'print' => false)); ?>
<?php endif; ?>

<?php if ($params->get('show_tags') && !empty($this->item->tags->itemTags)) : ?>
	<?php echo LayoutHelper::render('joomla.content.tags', $this->item->tags->itemTags); ?>
<?php endif; ?>

<?php // Todo Not that elegant would be nice to group the params ?>
<?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
	|| $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author') || $assocParam); ?>

<?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
    <?php // Todo: for Joomla4 joomla.content.info_block.block can be changed to joomla.content.info_block ?>
	<?php echo LayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
<?php endif; ?>

<div class="row">
	<?php if($intro_image) : ?>
		<div class="small-12 large-3 columns">
			<?php echo $intro_image; ?>
		</div>
	<?php endif; ?>

	<div class="small-12 <?php echo $intro_image ? 'large-9' : ''; ?> columns end">
		<?php echo LayoutHelper::render('joomla.content.blog_style_default_item_title', $this->item); ?>

		<?php if (!$params->get('show_intro')) : ?>
			<?php // Content is generated by content plugin event "onContentAfterTitle" ?>
			<?php echo $this->item->event->afterDisplayTitle; ?>
		<?php endif; ?>
		<?php // Content is generated by content plugin event "onContentBeforeDisplay" ?>
		<?php echo $this->item->event->beforeDisplayContent; ?>

		<?php echo $this->item->introtext; ?>

		<?php if ($useDefList && ($info == 1 || $info == 2)) : ?>
			<?php // Todo: for Joomla4 joomla.content.info_block.block can be changed to joomla.content.info_block ?>
			<?php echo LayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'below')); ?>
		<?php  endif; ?>

		<?php if ($params->get('show_readmore') && $this->item->readmore) :
			if ($params->get('access-view')) :
				$link = Route::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language));
			else :
				$menu   = $app->getMenu();
				$active = $menu->getActive();
				$itemId = $active->id;
				$link   = new Uri(Route::_('index.php?option=com_users&view=login&Itemid=' . $itemId, false));
				$link->setVar('return', base64_encode(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language)));
			endif; ?>

			<?php echo LayoutHelper::render('joomla.content.readmore', array('item' => $this->item, 'params' => $params, 'link' => $link)); ?>

		<?php endif; ?>
	</div>
</div>

<?php if ($conditionUnpublished || $isNotPublishedYet || $isExpired) : ?>
</div>
<?php endif; ?>

<?php // Content is generated by content plugin event "onContentAfterDisplay" ?>
<?php echo $this->item->event->afterDisplayContent; ?>