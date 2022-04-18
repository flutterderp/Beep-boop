<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Component\Content\Administrator\Extension\ContentComponent;
// use Joomla\Component\Content\Site\Helper\RouteHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Version;

$app                  = Factory::getApplication();
$option               = $app->input->get('option', 'com_content', 'string');
$component            = ucwords(str_ireplace('com_', '', $option));
$helperRoute          = $component . 'HelperRoute';
// Create a shortcut for params.
$params               = $displayData->params;
$canEdit              = $displayData->params->get('access-edit');
$currentDate          = Factory::getDate()->format('Y-m-d H:i:s');
$conditionUnpublished = (Version::MAJOR_VERSION === 4) ? ContentComponent::CONDITION_UNPUBLISHED : 0;
$isNotPublishedYet    = $displayData->publish_up > $currentDate;
$isExpired            = !is_null($displayData->publish_down) && $displayData->publish_down !== Factory::getDbo()->getNullDate() && $displayData->publish_down < $currentDate;

HTMLHelper::addIncludePath(JPATH_COMPONENT.'/helpers/html');
?>

<?php if ($displayData->state == 0 || $params->get('show_title') || ($params->get('show_author') && !empty($displayData->author ))) : ?>
	<div class="item-header">
		<?php if ($params->get('show_title')) : ?>
			<h2 itemprop="name">
				<?php if ($params->get('link_titles') && ($params->get('access-view') || $params->get('show_noauth', '0') == '1')) : ?>
					<a href="<?php echo Route::_(
						$helperRoute::getArticleRoute($displayData->slug, $displayData->catid, $displayData->language)
					); ?>" itemprop="url">
						<?php echo $this->escape($displayData->title); ?>
					</a>
				<?php else : ?>
					<?php echo $this->escape($displayData->title); ?>
				<?php endif; ?>
			</h2>
		<?php endif; ?>

		<?php if ($conditionUnpublished) : ?>
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
