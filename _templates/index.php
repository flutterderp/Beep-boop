<?php
/**
 * @package Joomla.Site
 * @subpackage Templates.candelaaluminium
 * @copyright Copyright (C) 2017 Southern Anime All rights reserved.
 * @license MIT License; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;

include_once(__DIR__ . '/includes/functions.php');

HTMLHelper::_('jquery.framework', true);

$app             = Factory::getApplication();
$doc             = Factory::getDocument();
$utc_tz          = new DateTimeZone('UTC');
$today           = new DateTime(null, $utc_tz);
$menu            = $app->getMenu();
$active          = $menu->getActive();
$default         = $menu->getDefault();
$is_home         = ($active === $default) ? true : false;
$sidebar_content = getModContent('right', 'xhtml5');
$doc->setGenerator('Southern Anime');
// Clear out Joomla/JCE scripts and stylesheets
$oldHeadData = $doc->getHeadData();
$doc->resetHeadData();
$doc->_custom                       = $oldHeadData['custom'];
$doc->title                         = $oldHeadData['title'];
$doc->description                   = $oldHeadData['description'];
$doc->_styleSheets                  = $oldHeadData['styleSheets'];
$doc->_metaTags['name']['keywords'] = isset($oldHeadData['metaTags']['name']['keywords']) ? $oldHeadData['metaTags']['name']['keywords'] : '';
$active_page_robots                 = $active ? $active->getParams()->get('robots', '') : '';

// unset _styleSheets because components using HTMLHelper to add stylesheets tend to render them before the other stylesheets below
unset($doc->_styleSheets);

if(isset($oldHeadData['metaTags']['name']['robots']))
{
	$doc->_metaTags['name']['robots'] = $oldHeadData['metaTags']['name']['robots'];
}
elseif($active_page_robots)
{
	$doc->_metaTags['name']['robots'] = $active_page_robots;
}

// JavaScript
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/javascript/jquery-3.5.1.min.js', null, array('async' => false));
// $doc->addScript($this->baseurl . '/templates/' . $this->template . '/javascript/jquery-migrate-3.0.0.min.js', null, array('async' => true));
// Stylesheets
$doc->addHeadLink('https://fonts.googleapis.com', 'preconnect');
$doc->addHeadLink('https://fonts.gstatic.com', 'preconnect', 'rel', array('crossorigin' => 'crossorigin'));
$doc->addStyleSheet('https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;600;700&Montserrat:ital,wght@0,400;0,700;1,700&family=Open+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap');
$doc->addStyleSheet($this->baseurl . '/templates/system/css/system.css');
// $doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/fa59-all.min.css');
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/custom.css');

// Custom Metatags
$logo            = $this->params->get('logo');
$logo_footer     = $this->params->get('logo_footer');
$base_image      = $this->params->get('base_image');
$gacode          = $this->params->get('gacode', '');
$gtmcode         = $this->params->get('gtmcode', '');
$ga_anonymizeIp  = (bool) $this->params->get('ga_anonymizeip', false);
$fbpixel         = $this->params->get('fbpixel', '');
$adsense_key     = $this->params->get('googleads', '');
$pageclass_sfx   = is_object($active) ? $active->getParams()->get('pageclass_sfx', ' inner-page') : ' inner-page';
$og_title        = htmlentities($doc->getTitle(), ENT_QUOTES);
$og_desc         = htmlentities($doc->getDescription(), ENT_QUOTES);
$og_keywords     = htmlentities($doc->getMetaData('keywords'), ENT_QUOTES);
$og_image        = '';
$og_img_width    = 300;
$og_img_height   = 300;
$base_img_width  = 300;
$base_img_height = 300;

if($base_image && file_exists(JPATH_BASE . '/' . $base_image))
{
	$og_image = Uri::root() . $base_image;

	list($og_img_width, $og_img_height)     = getimagesize(JPATH_BASE . '/' . $base_image);
	list($base_img_width, $base_img_height) = getimagesize(JPATH_BASE . '/' . $base_image);
}

$doc->addCustomTag('<meta property="og:image" content="' . $app->get('ogImage', $og_image) . '">');
$doc->addCustomTag('<meta property="og:image:width" content="' . $app->get('ogImageWidth', $og_img_width) . '">');
$doc->addCustomTag('<meta property="og:image:height" content="' . $app->get('ogImageHeight', $og_img_height) . '">');

/**
 * Alternative way to disable unnecessary scripts/stylesheets
 * @link https://github.com/joomla/joomla-cms/discussions/32350#discussioncomment-347638
 */
/* if(Version::MAJOR_VERSION === 4)
{
	$wa = $this->getWebAssetManager();
	$wa->disableScript('bootstrap.collapse');
	$wa->disableStyle('fontawesome');
} */
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>"
	prefix="og: http://ogp.me/ns#" typeof="og:article">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="msapplication-config" content="none">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<jdoc:include type="head" />

		<?php if($gacode) : ?>
			<script src="https://www.googletagmanager.com/gtag/js?id=<?php echo $gacode; ?>" async></script>
			<script>
				window.dataLayer = window.dataLayer || [];
				function gtag() { dataLayer.push(arguments); }
				gtag('js', new Date());

				gtag('config', '<?php echo $gacode; ?>', {
					'anonymize_ip':   <?php echo $ga_anonymizeIp === true ? 'true' : 'false'; ?>,
					'send_page_view': true
				});
			</script>
		<?php endif; ?>

		<?php if($gtmcode) : ?>
			<!-- Google Tag Manager -->
			<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','<?php echo $gtmcode; ?>');</script>
			<!-- End Google Tag Manager -->
		<?php endif; ?>

		<?php if($fbpixel) : ?>
			<!-- Facebook Pixel Code -->
			<script>
			!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
			n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
			n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
			t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
			document,'script','https://connect.facebook.net/en_US/fbevents.js');
			// Insert Your Facebook Pixel ID below.
			fbq('init', '<?php echo $fbpixel; ?>');
			fbq('track', 'PageView');
			</script>
			<!-- Insert Your Facebook Pixel ID below. -->
			<noscript><img height="1" width="1" style="display:none"
			src="https://www.facebook.com/tr?id=<?php echo $fbpixel; ?>&amp;ev=PageView&amp;noscript=1"></noscript>
			<!-- End Facebook Pixel Code -->
		<?php endif; ?>

		<?php if($adsense_key) : ?>
			<script src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" async></script>
		<?php endif; ?>

		<!-- Microdata metatags -->
		<meta itemprop="title" content="<?php echo $og_title; ?>">
		<meta itemprop="description" content="<?php echo $og_desc; ?>">
		<meta itemprop="keywords" content="<?php echo $og_keywords; ?>">
		<!-- Open Graph metatags -->
		<meta property="og:description" content="<?php echo $og_desc; ?>">
		<meta property="og:title" content="<?php echo $og_title; ?>">
		<meta property="og:type" content="website">
		<meta property="og:url" content="<?php echo Uri::current(); ?>">
		<meta property="twitter:card" content="summary_large_image">
		<meta property="twitter:url" content="<?php echo Uri::current(); ?>">
		<meta property="twitter:description" content="<?php echo $og_desc; ?>">
		<!-- End microdata metatags -->
	</head>
	<body itemscope itemtype="https://schema.org/WebPage">
		<section name="pagetop" id="pagetop" aria-label="Anchor: top of page"></section>
		<nav class="skiplink__landmark" aria-label="Allows users to skip to the main content"><a class="skiplink" href="#MainContent">Skip to Main Content</a></nav>

		<?php if($base_image) : ?>
			<div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
				<meta itemprop="url" content="<?php echo Uri::root() . $base_image; ?>">
				<meta itemprop="width" content="<?php echo $base_img_width; ?>">
				<meta itemprop="height" content="<?php echo $base_img_height; ?>">
			</div>
		<?php endif; ?>

		<?php if($gtmcode) : ?>
			<!-- Google Tag Manager (noscript) -->
			<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $gtmcode; ?>"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
			<!-- End Google Tag Manager (noscript) -->
		<?php endif; ?>

		<nav class="pushy pushy-left" data-menu-btn-class=".pushy-menu-btn" role="navigation" arial-label="Mobile menu">
			<jdoc:include type="modules" name="mobilemenu" style="none" />
		</nav>

		<div class="wrapper pushy-container" id="container">
			<header class="masthead<?php echo $is_home ? ' home' : ' inner'; ?>" id="masthead">
				<div class="masthead-content row">
					<jdoc:include type="modules" name="logo" style="xhtml5" />

					<section class="topnavarea">
						<jdoc:include type="modules" name="top" style="xhtml5" />
						<jdoc:include type="modules" name="navigation" style="xhtml5" />

						<nav class="pushy-menu-btn__container">
							<a class="pushy-menu-btn" id="pushy-menu-btn" href="javascript:void(0);" tabindex="0" title="Open mobile menu"><i class="fa fa-bars"></i></a>
						</nav>
					</section>
				</div>
			</header><?php /* end of masthead */ ?>

			<?php if($is_home) : ?>
				<section class="slideshow">
					<jdoc:include type="modules" name="slideshow" style="none" />
				</section>
			<?php endif; ?>

			<a id="MainContent"></a>

			<section class="copyarea<?php echo $is_home ? ' home' : ' inner'; ?> row" id="copyarea">
				<?php if($this->countModules('breadcrumb')) : ?>
					<div class="small-12 columns" role="complementary" aria-label="Breadcrumbs">
						<jdoc:include type="modules" name="breadcrumb" style="none" />
					</div>
				<?php endif; ?>

				<main class="small-12 <?php echo !empty($sidebar_content) ? 'medium-8 large-9': ''; ?> columns copy" id="copy" role="main">
					<jdoc:include type="message" />
					<jdoc:include type="modules" name="copyhead" style="xhtml5" />
					<jdoc:include type="component" />
					<jdoc:include type="modules" name="copyfoot" style="xhtml5" />
				</main><?php /* end of copy */ ?>

				<?php if(!empty($sidebar_content)) : ?>
					<aside class="sidebar small-12 medium-4 large-3 columns" id="sidebar" role="complementary" aria-label="Sidebar content">
						<?php echo $sidebar_content; ?>
					</aside><?php /* end of sidebar */ ?>
				<?php endif; ?>
			</section><?php /* end of copyarea */ ?>

			<?php if($this->countModules('homeblocks')) : ?>
				<section class="homeblockarea">
					<jdoc:include type="modules" name="homeblocks" style="none" />
				</section>
			<?php endif; ?>

			<footer role="contentinfo" arial-label="Footer content">
				<div class="row">
					<div class="small-12 columns">
						<jdoc:include type="modules" name="footer" style="xhtml5" />
					</div>
				</div>

				<jdoc:include type="modules" name="bottom" style="xhtml5" />

				<div class="row dev-copyright">
					<div class="small-12 columns text-center">
						<a href="http://www.southernanime.com" target="_blank">
							<?php echo Text::sprintf('TPL_CANDELAALUMINIUM_COPYRIGHT', $today->format('Y')); ?>
						</a>
					</div>
				</div>
			</footer><?php /* end of footer */ ?>

			<jdoc:include type="modules" name="debug" />

			<nav class="up-button" role="navigation" aria-label="Up button">
				<a class="up-button__link" href="#pagetop" aria-label="Action: scroll to top of page"></a>
			</nav>
		</div><?php /* end of wrapper */ ?>

		<div class="site-overlay"></div>

		<?php
		$script_keys = array_keys($oldHeadData['scripts']);
		$script_keys = preg_grep('/(jquery\.min\.js|jquery-noconflict\.js|jquery-migrate\.min\.js)/', $script_keys, PREG_GREP_INVERT);

		foreach($script_keys as $script) : ?>
			<script src="<?php echo $script; ?>"></script>
		<?php endforeach; ?>

		<script src="<?php echo $this->baseurl . '/templates/' . $this->template; ?>/javascript/custom.js"></script>

		<?php if(isset($oldHeadData['script']['text/javascript']) && !empty($oldHeadData['script']['text/javascript'])) : ?>
			<?php if(Version::MAJOR_VERSION === 4) : ?>
				<?php $script_text = implode(PHP_EOL, $oldHeadData['script']['text/javascript']); ?>
				<script><?php echo $script_text; ?></script>
			<?php else : ?>
				<script><?php echo $oldHeadData['script']['text/javascript']; ?></script>
			<?php endif; ?>
		<?php endif; ?>

		<!-- Social media sharing widget scripts -->
		<?php /* <script async>window.twttr = (function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0], t = window.twttr || {}; if (d.getElementById(id)) return t; js = d.createElement(s); js.id = id; js.src = "https://platform.twitter.com/widgets.js"; fjs.parentNode.insertBefore(js, fjs); t._e = []; t.ready = function(f) { t._e.push(f); }; return t; }(document, "script", "twitter-wjs"));</script> */ ?>
		<?php /* <script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script> */ ?>
		<?php /* <script src="https://platform.linkedin.com/in.js" type="text/javascript" async>lang: en_US</script> */ ?>
		<?php /* <script src="https://assets.pinterest.com/js/pinit.js" async></script> */ ?>
		<!-- End social media sharing widget scripts -->
	</body>
</html>
