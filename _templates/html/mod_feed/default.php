<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_feed
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\HTML\HTMLHelper;

/* $testfeed   = new JFeedFactory;
print_r($testfeed->getFeed($rssurl)); */
?>

<?php
if (!empty($feed) && is_string($feed))
{
	echo $feed;
}
else
{
	$lang      = Factory::getLanguage();
	$myrtl     = $params->get('rssrtl', 0);
	$direction = ' ';

	$isRtl = $lang->isRtl();

	if ($isRtl && $myrtl == 0)
	{
		$direction = ' redirect-rtl';
	}

	// Feed description
	elseif ($isRtl && $myrtl == 1)
	{
		$direction = ' redirect-ltr';
	}

	elseif ($isRtl && $myrtl == 2)
	{
		$direction = ' redirect-rtl';
	}

	elseif ($myrtl == 0)
	{
		$direction = ' redirect-ltr';
	}
	elseif ($myrtl == 1)
	{
		$direction = ' redirect-ltr';
	}
	elseif ($myrtl == 2)
	{
		$direction = ' redirect-rtl';
	}

	if ($feed !== false) : ?>

		<div style="direction: <?php echo $rssrtl ? 'rtl' :'ltr'; ?>; text-align: <?php echo $rssrtl ? 'right' :'left'; ?> !important" class="feed">
			<?php if ($feed->title !== null && $params->get('rsstitle', 1)) : ?>
				<h2 class="rss__title <?php echo $direction; ?>">
					<a href="<?php echo htmlspecialchars($feed->image->link, ENT_COMPAT, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
						<?php echo $feed->title; ?>
					</a>

					<?php if ($feed->image && $params->get('rssimage', 1)) : ?>
						<img src="<?php echo $feed->image->uri; ?>" alt="<?php echo $feed->image->title; ?>"/>
					<?php endif; ?>
				</h2>
			<?php endif; ?>

			<h3 class="rss__subheading">Latest News</h3>

			<?php if ($params->get('rssdate', 1)) : ?>
				<h3 class="rss__date"><?php echo HTMLHelper::_('date', $feed->publishedDate, JText::_('DATE_FORMAT_LC3')); ?></h3>
			<?php endif; ?>

			<?php if ($params->get('rssdesc', 1)) : ?>
				<?php echo $feed->description; ?>
			<?php endif; ?>

			<?php if (!empty($feed)) : ?>
				<div class="news__feed">
					<?php for ($i = 0, $max = min(count($feed), $params->get('rssitems', 3)); $i < $max; $i++) : ?>
						<?php
						$uri  = $feed[$i]->uri || !$feed[$i]->isPermaLink ? trim($feed[$i]->uri) : trim($feed[$i]->guid);
						$uri  = !$uri || stripos($uri, 'http') !== 0 ? $rssurl : $uri;
						$text = $feed[$i]->content !== '' ? trim($feed[$i]->content) : '';
						?>
						<div class="feed__item">
							<?php if(isset($feed[$i]->links[0]) && (getimagesize($feed[$i]->links[0]->uri) !== false)) : ?>
								<img src="<?php echo $feed[$i]->links[0]->uri; ?>" alt="<?php echo trim($feed[$i]->title); ?>">
							<?php endif; ?>

							<div class="feed__content">
								<?php if (!empty($uri)) : ?>
									<span class="feed-link">
										<a href="<?php echo htmlspecialchars($uri, ENT_COMPAT, 'UTF-8'); ?>" target="_blank"><?php echo trim($feed[$i]->title); ?></a>
									</span>
								<?php else : ?>
									<span class="feed-link"><?php echo trim($feed[$i]->title); ?></span>
								<?php endif; ?>

								<?php if ($params->get('rssitemdate', 0)) : ?>
									<div class="feed-item-date">
										<?php echo HTMLHelper::_('date', $feed[$i]->publishedDate, JText::_('DATE_FORMAT_LC3')); ?>
									</div>
								<?php endif; ?>

								<?php if ($params->get('rssitemdesc', 1) && $text !== '') : ?>
									<div class="feed-item-description">
										<?php
										// Strip the images.
										$text = OutputFilter::stripImages($text);
										$text = HTMLHelper::_('string.truncate', $text, $params->get('word_count', 0));
										echo str_replace('&apos;', "'", $text);
										?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endfor; ?>

					<a class="feed__link" href="<?php echo $feed->image->link; ?>" target="_blank" rel="noopener noreferrer">More Headlines</a>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php
}
