<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_category
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$default_img = 'https://via.placeholder.com/176x96.png?text=No%20image';
?>

<?php foreach ($list as $item) : ?>
	<?php $images = json_decode($item->images); ?>
	<article class="lead-item">
		<h1 itemprop="name">
			<?php if ($params->get('link_titles') == 1) : ?>
				<a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a>
			<?php else : ?>
				<?php echo $item->title; ?>
			<?php endif; ?>
		</h1>

		<div class="article-info">
			<div class="created">
				<?php if ($item->displayDate) : ?>
					<?php $displayDate = DateTime::createFromFormat('Y-m-d H:i:s', $item->displayDate, new DateTimeZone('UTC')); ?>
					<time dateime="<?php echo $displayDate->format('r'); ?>" itemprop="dateCreate">
						Posted on <?php echo HTMLHelper::_('date', $item->displayDate, 'DATE_FORMAT_LC3'); ?>
					</time>
				<?php endif; ?>

				<?php if ($params->get('show_author')) : ?>
					<span class="createdby">by <?php echo $item->displayAuthorName; ?></span>
				<?php endif; ?>
			</div>

			<?php if ($item->displayCategoryTitle) : ?>
				<b class="mod-articles-category-category category">
					<?php echo $item->displayCategoryTitle; ?>
				</b>
			<?php endif; ?>
		</div>

		<?php if ($params->get('show_introtext')) : ?>
			<p><?php echo $item->introtext; ?></p>
		<?php endif; ?>

		<?php if ($params->get('show_readmore')) : ?>
			<p class="readmore">
				<a class="button <?php echo $item->active; ?>" href="<?php echo $item->link; ?>" aria-label="<?php echo Text::_('MOD_ARTICLES_CATEGORY_READ_MORE') . $item->title; ?>">
					<?php if ($item->params->get('access-view') == false) : ?>
						<?php echo Text::_('MOD_ARTICLES_CATEGORY_REGISTER_TO_READ_MORE'); ?>
					<?php elseif ($readmore = $item->alternative_readmore) : ?>
						<?php echo $readmore; ?>
						<?php echo HTMLHelper::_('string.truncate', $item->title, $params->get('readmore_limit')); ?>
					<?php elseif ($params->get('show_readmore_title', 0) == 0) : ?>
						<?php echo Text::_('MOD_ARTICLES_CATEGORY_READ_MORE_TITLE'); ?>
					<?php else : ?>
						<?php echo Text::_('MOD_ARTICLES_CATEGORY_READ_MORE'); ?>
						<?php echo HTMLHelper::_('string.truncate', $item->title, $params->get('readmore_limit')); ?>
					<?php endif; ?>
				</a>
			</p>
		<?php endif; ?>

		<?php /* <div>
			<a class="taglink">Nullam</a><a class="taglink">non</a><a class="taglink">nisl</a>
			<a class="taglink">vel</a><a class="taglink">arcu</a><a class="taglink">lobortis</a>
			<a class="taglink">commodo</a>
		</div> */ ?>
	</article>
<?php endforeach; ?>
