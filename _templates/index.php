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

// HTMLHelper::_('jquery.framework', true);

$app             = Factory::getApplication();
$doc             = Factory::getDocument();
$utc_tz          = new DateTimeZone('UTC');
$today           = new DateTime('', $utc_tz);
$menu            = $app->getMenu();
$active          = $menu->getActive();
$default         = $menu->getDefault();
$is_home         = ($active === $default) ? true : false;
$sidebar_content = getModContent('right', 'xhtml5');
$this->setBase(Uri::root());
$this->setGenerator('Southern Anime');
// Clear out Joomla/JCE scripts and stylesheets
$oldHeadData = $this->getHeadData();
$this->resetHeadData();
$this->_custom                       = $oldHeadData['custom'];
$this->title                         = $oldHeadData['title'];
$this->description                   = $oldHeadData['description'];
$this->_styleSheets                  = $oldHeadData['styleSheets'];
$this->_metaTags['name']['keywords'] = isset($oldHeadData['metaTags']['name']['keywords']) ? $oldHeadData['metaTags']['name']['keywords'] : '';
$active_page_robots                  = $active ? $active->getParams()->get('robots', '') : '';

// unset _styleSheets because components using HTMLHelper to add stylesheets tend to render them before the other stylesheets below
unset($this->_styleSheets);

if(isset($oldHeadData['metaTags']['name']['robots']))
{
	$this->_metaTags['name']['robots'] = $oldHeadData['metaTags']['name']['robots'];
}
elseif($active_page_robots)
{
	$this->_metaTags['name']['robots'] = $active_page_robots;
}

if($app->get('seocfg_favicon', ''))
{
	$favicon          = HTMLHelper::cleanImageUrl($app->get('seocfg_favicon'));
	$favicon_filename = pathinfo(JPATH_ROOT . '/' . $favicon->url, PATHINFO_FILENAME);

	$this->addFavicon(Uri::root() . 'templates/' . $this->template . '/' . $favicon_filename . '.ico', 'image/vnd.microsoft.icon', 'shortcut icon');
	$this->addFavicon(Uri::root() . $favicon->url, mime_content_type($favicon->url), 'shortcut icon');
}

if($is_home)
{
	$this->addCustomTag('<link href="' . Uri::root() . '" rel="canonical">');
}

// JavaScript
$this->addScript($this->baseurl . '/templates/' . $this->template . '/javascript/jquery-3.5.1.min.js', null, array('async' => false));
// $this->addScript($this->baseurl . '/templates/' . $this->template . '/javascript/jquery-migrate-3.0.0.min.js', null, array('async' => true));
// Stylesheets
$this->addHeadLink('https://fonts.googleapis.com', 'preconnect');
$this->addHeadLink('https://fonts.gstatic.com', 'preconnect', 'rel', array('crossorigin' => 'crossorigin'));
$this->addStyleSheet('https://fonts.googleapis.com/css2?family=Caveat&family=Nunito:ital,wght@0,400;0,600;0,700;1,400;1,700&display=swap');

if(Version::MAJOR_VERSION < 4)
{
	$this->addStyleSheet($this->baseurl . '/templates/system/css/system.css');
}

$this->addStyleSheet('https://use.fontawesome.com/releases/v5.15.4/css/all.css', null, array('crossorigin' => 'anonymous'));
$this->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/glightbox.min.css');
$this->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/joomla-a11y-checker.min.css');
// $this->addStyleSheet('https://cdn.jsdelivr.net/gh/ryersondmp/sa11y@2.2.3/src/sa11y.css');

$css_keys = array_keys($oldHeadData['styleSheets']);

foreach($css_keys as $styleSheet)
{
	$this->addStyleSheet($styleSheet);
}

$this->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/custom.css');

// Custom Metatags
$logo            = $this->params->get('logo', '');
$logo_footer     = $this->params->get('logo_footer', '');
$base_image      = $this->params->get('base_image', '');
$gacode          = $app->get('gacode', $this->params->get('gacode', ''));
$gtmcode         = $app->get('gtmcode', $this->params->get('gtmcode', ''));
$ga_anonymizeIp  = (bool) $app->get('ga_anonymizeip', true);
$fbpixel         = $app->get('fbpixel', $this->params->get('fbpixel', ''));
$adsense_key     = $app->get('googleads', $this->params->get('googleads', ''));
$pageclass_sfx   = is_object($active) ? $active->getParams()->get('pageclass_sfx', ' inner-page') : ' inner-page';
$og_title        = htmlentities($this->getTitle(), ENT_QUOTES);
$og_desc         = $this->getDescription() ? htmlentities($this->getDescription(), ENT_QUOTES) : '';
$og_keywords     = htmlentities($this->getMetaData('keywords'), ENT_QUOTES);
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
else
{
	$og_image = $app->get('ogImage', $og_image);
}

if($og_image)
{
	$this->addCustomTag('<meta property="og:image" content="' . $app->get('ogImage', $og_image) . '">');
	$this->addCustomTag('<meta property="og:image:width" content="' . $app->get('ogImageWidth', $og_img_width) . '">');
	$this->addCustomTag('<meta property="og:image:height" content="' . $app->get('ogImageHeight', $og_img_height) . '">');
}

/**
 * Alternative way to disable unnecessary scripts/stylesheets
 * @link https://github.com/joomla/joomla-cms/discussions/32350#discussioncomment-347638
 */
if(Version::MAJOR_VERSION === 4)
{
	$wa = $this->getWebAssetManager();
	$wr = $wa->getRegistry();

	/* $wa->addInlineScript(
		'{}',
		['name' => 'joomla.script.options', 'position' => 'before'],
		['type' => 'application/json', 'class' => 'joomla-script-options new'],
		['core']
	); */

	$wa->disableScript('bootstrap.collapse');
	$wa->disableScript('bootstrap.popover');
	$wa->disableScript('bootstrap.es5');
	$wa->disableScript('chosen');
	$wa->disableScript('core');
	$wa->disableScript('field.color-adv');
	// $wa->disableScript('joomla.treeselectmenu');
	$wa->disableScript('jquery-migrate');
	$wa->disableScript('jquery-noconflict');
	$wa->disableScript('jquery');
	// $wa->disableScript('minicolors');
	// $wa->disableScript('wcpolyfill');
	$wa->disableStyle('chosen');
	// $wa->disableStyle('minicolors');
	$wa->disableStyle('fontawesome');
}

/* $app->enqueueMessage('Message test', 'info');
$app->enqueueMessage('Message test', 'success');
$app->enqueueMessage('Message test', 'warning');
$app->enqueueMessage('Message test', 'error'); */
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>"
	prefix="og: http://ogp.me/ns#" typeof="og:article">
	<head>
		<meta name="msapplication-config" content="none">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<?php if(Version::MAJOR_VERSION < 4) : ?>
			<jdoc:include type="head" />
		<?php else : ?>
			<jdoc:include type="metas" />
			<jdoc:include type="styles" />
			<jdoc:include type="scripts" />
		<?php endif; ?>

		<?php if($gacode) : ?>
			<script src="https://www.googletagmanager.com/gtag/js?id=<?php echo $gacode; ?>" async></script>
			<script>
				window.dataLayer = window.dataLayer || [];
				function gtag() { dataLayer.push(arguments); }
				gtag('js', new Date());

				gtag('config', '<?php echo $gacode; ?>', { 'send_page_view': true });
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
		<nav class="skiplink__landmark" aria-label="Skip to main content"><a class="skiplink" href="#MainContent">Skip to Main Content</a></nav>

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

		<header id="masthead">
			<section class="masthead<?php echo $is_home ? ' home' : ' inner'; ?>">
				<h1><?php echo $app->get('sitename'); ?></h1>
				<a href="#" class="mobilenav__btn" id="mobilenav" aria-label="Click to expand mobile menu"><span class="fa fa-bars fa-2x"></span></a>

				<jdoc:include type="modules" name="navigation" style="none" />
				<?php /* <jdoc:include type="modules" name="top" style="xhtml5" /> */ ?>
			</section>
		</header><?php /* end of masthead */ ?>

		<section class="container" id="MainContent">
			<?php if($this->countModules('breadcrumb')) : ?>
				<div class="row">
					<nav class="column" role="navigation" aria-label="Breadcrumbs">
						<jdoc:include type="modules" name="breadcrumb" style="none" />
					</nav>
				</div>
			<?php endif; ?>

			<div class="row">
				<main class="contentarea column">
					<jdoc:include type="message" />

					<jdoc:include type="modules" name="copyhead" style="xhtml5" />
					<jdoc:include type="component" />
					<jdoc:include type="modules" name="copyfoot" style="xhtml5" />
				</main>

				<?php if(!empty($sidebar_content)) : ?>
					<aside class="sidebar column" role="complementary" aria-label="Sidebar content">
						<?php echo $sidebar_content; ?>
					</aside>
				<?php endif; ?>
			</div>
		</section><?php /* end of copyarea */ ?>

		<footer role="contentinfo" arial-label="Footer content">
			<div class="footer-item"><p><?php echo Text::sprintf('TPL_CANDELAALUMINIUM_COPYRIGHT', $today->format('Y')); ?></p></div>

			<?php /* <jdoc:include type="modules" name="footer" style="xhtml5" />
			<jdoc:include type="modules" name="bottom" style="xhtml5" /> */ ?>
		</footer>

		<jdoc:include type="modules" name="debug" />

		<nav class="up-button" role="navigation" aria-label="Up button">
			<a class="up-button__link" href="#pagetop" aria-label="Action: scroll to top of page"></a>
		</nav>

		<script src="<?php echo $this->baseurl . '/templates/' . $this->template; ?>/javascript/glightbox.min.js"></script>
		<script src="<?php echo $this->baseurl . '/templates/' . $this->template; ?>/javascript/joomla-a11y-checker.umd.min.js"></script>
		<script src="<?php echo $this->baseurl . '/templates/' . $this->template; ?>/javascript/en.umd.js"></script>
		<?php /* <script src="https://cdn.jsdelivr.net/combine/npm/@popperjs/core@2/dist/umd/popper.min.js,npm/tippy.js@6/dist/tippy.umd.min.js,gh/ryersondmp/sa11y@2.2.3/src/lang/en.min.js,gh/ryersondmp/sa11y@2.2.3/src/sa11y.min.js"></script> */ ?>

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
