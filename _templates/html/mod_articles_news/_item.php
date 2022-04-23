<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   (C) 2010 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<article class="lead-item">
	<?php if ($params->get('item_title')) : ?>
		<h1 class="newsflash-title<?php echo $params->get('moduleclass_sfx'); ?>">
		<?php if ($item->link !== '' && $params->get('link_titles')) : ?>
			<a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a>
		<?php else : ?>
			<?php echo $item->title; ?>
		<?php endif; ?>
		</h1>
	<?php endif; ?>

	<!-- <div class="article-info">
		<div class="created">
			<time datetime="2014-10-05T14:24:27-04:00" itemprop="dateCreate">
				Posted on 5 Oct 2014
			</time>
			<span class="createdby">by Gracelynn</span>
		</div>
	</div> -->

	<?php if ($params->get('img_intro_full') !== 'none' && !empty($item->imageSrc)) : ?>
		<figure class="newsflash-image">
			<img src="<?php echo $item->imageSrc; ?>" alt="<?php echo $item->imageAlt; ?>">
			<?php if (!empty($item->imageCaption)) : ?>
				<figcaption>
					<?php echo $item->imageCaption; ?>
				</figcaption>
			<?php endif; ?>
		</figure>
	<?php endif; ?>

	<?php if (!$params->get('intro_only')) : ?>
		<?php echo $item->afterDisplayTitle; ?>
	<?php endif; ?>

	<?php echo $item->beforeDisplayContent; ?>

	<?php if ($params->get('show_introtext', 1)) : ?>
		<?php echo $item->introtext; ?>
	<?php endif; ?>

	<?php echo $item->afterDisplayContent; ?>

	<?php if (isset($item->link) && $item->readmore != 0 && $params->get('readmore')) : ?>
		<p><a class="button readmore" href="<?php echo $item->link ; ?>"><?php echo $item->linkText; ?></a></p>
	<?php endif; ?>

	<!-- <div>
		<a class="taglink">Nullam</a><a class="taglink">non</a><a class="taglink">nisl</a>
		<a class="taglink">vel</a><a class="taglink">arcu</a><a class="taglink">lobortis</a>
		<a class="taglink">commodo</a>
	</div> -->
</article>
