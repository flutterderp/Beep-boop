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
$doc->addStyleSheet('https://fonts.googleapis.com/css2?family=Caveat&family=Nunito:ital,wght@0,400;0,600;0,700;1,400;1,700&display=swap');
$doc->addStyleSheet($this->baseurl . '/templates/system/css/system.css');
$doc->addStyleSheet('https://use.fontawesome.com/releases/v5.15.4/css/all.css', null, array('crossorigin' => 'anonymous'));
// $doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/fa59-all.min.css');
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/custom.css');

// Custom Metatags
$logo            = $this->params->get('logo');
$logo_footer     = $this->params->get('logo_footer');
$base_image      = $this->params->get('base_image');
$gacode          = $this->params->get('gacode', '');
$gtmcode         = $this->params->get('gtmcode', '');
$ga_anonymizeIp  = (bool) $this->params->get('ga_anonymizeip', true);
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

$app->enqueueMessage('Message test', 'info');
$app->enqueueMessage('Message test', 'success');
$app->enqueueMessage('Message test', 'warning');
$app->enqueueMessage('Message test', 'error');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>"
	prefix="og: http://ogp.me/ns#" typeof="og:article">
	<head>
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

		<nav class="pushy pushy-left" data-menu-btn-class=".pushy-menu-btn" role="navigation" arial-label="Mobile menu">
			<jdoc:include type="modules" name="mobilemenu" style="none" />
		</nav>

		<div class="wrapper pushy-container" id="container">
			<header id="masthead">
				<section class="masthead<?php echo $is_home ? ' home' : ' inner'; ?>">
					<h1><?php echo $app->get('sitename'); ?></h1>
					<a href="#" class="mobilenav__btn" id="mobilenav" aria-label="Click to expand mobile menu"><span class="fa fa-bars fa-2x"></span></a>

					<?php /* <jdoc:include type="modules" name="top" style="xhtml5" />
					<jdoc:include type="modules" name="navigation" style="xhtml5" /> */ ?>

					<nav class="menu mainmenu" role="navigation" aria-label="Main menu">
						<div class="menu-item"><a href="index.html">Home</a></div>
						<div class="menu-item has-submenu">
							<a href="#">Articles <span class="fa fa-angle-right"></span></a>
							<nav class="submenu" aria-label="Submenu – articles">
								<div class="menu-item"><a href="home.html">Blog</a></div>
								<div class="menu-item"><a href="home.html">Elegant Gothic Lolita</a></div>
								<div class="menu-item has-submenu">
									<a href="#">Music <span class="fa fa-angle-right"></span></a>
									<nav class="submenu" aria-label="Submenu – music">
										<div class="menu-item"><a href="home.html">Malice Mizer</a></div>
										<div class="menu-item"><a href="home.html">Moi dix Mois</a></div>
									</nav>
								</div>
								<div class="menu-item active"><a href="home.html">Stuff</a></div>
							</nav>
						</div>
						<div class="menu-item active"><a href="home.html">Stuff</a></div>
					</nav>
				</section>
			</header><?php /* end of masthead */ ?>

			<?php if($is_home) : ?>
				<section class="container" id="MainContent">
					<div class="row">
						<nav class="column" role="navigation" aria-label="Breadcrumbs">
							<ul class="breadcrumb">
								<li><a href="#">About us</a></li>
								<li>Beep boop</li>
								<li><a href="#">History of Lorem Ipsum</a></li>
							</ul>
						</nav>
						<?php /* if($this->countModules('breadcrumb')) : ?>
							<jdoc:include type="modules" name="breadcrumb" style="none" />
						<?php endif; */ ?>
					</div>

					<a id="MainContent"></a>

					<div class="row">
						<main class="contentarea column">
							<jdoc:include type="message" />

							<jdoc:include type="modules" name="copyhead" style="xhtml5" />
							<jdoc:include type="component" />
							<jdoc:include type="modules" name="copyfoot" style="xhtml5" />

							<!-- <nav class="pagination" aria-label="Pagination">
								<ul>
									<li><a href="" aria-label="First page"><i class="fa fa-angle-double-left"></i></a></li>
									<li><a href="" aria-label="Previous page"><i class="fa fa-angle-left"></i></a></li>
									<li><a href="">1</a></li>
									<li><a href="">2</a></li>
									<li><a href="">3</a></li>
									<li><a href="" aria-label="Next page"><i class="fa fa-angle-right"></i></a></li>
									<li><a href="" aria-label="Last page"><i class="fa fa-angle-double-right"></i></a></li>
								</ul>
							</nav> -->
						</main>

						<?php if(!empty($sidebar_content)) : ?>
							<aside class="sidebar column" role="complementary" aria-label="Sidebar content">
								<?php /* echo $sidebar_content; */ ?>

								<div>
									<h2>Beep boop</h2>
									<p>Aliquam erat volutpat. Etiam a finibus nisi, ultricies auctor elit. Curabitur et tristique mi. Curabitur volutpat urna eu urna commodo ultrices. Vivamus lacinia aliquam ipsum, et feugiat.</p>
									<ul class="menu vertical">
										<li><a href="https://www.example.com" target="_blank" rel="noopener">Example link</a></li>
										<li><a href="https://www.google.com" target="_blank" rel="noopener">Google</a></li>
										<li><a href="https://www.example.com" target="_blank" rel="noopener">Example link</a></li>
										<li><a href="https://www.google.com" target="_blank" rel="noopener">Google</a></li>
									</ul>
								</div>

								<div>
									<h2>Cool links</h2>
									<ul class="menu vertical">
										<li><a href="https://www.example.com" target="_blank" rel="noopener">Example link</a></li>
										<li><a href="https://www.google.com" target="_blank" rel="noopener">Google</a></li>
										<li><a href="https://www.example.com" target="_blank" rel="noopener">Example link</a></li>
										<li><a href="https://www.google.com" target="_blank" rel="noopener">Google</a></li>
									</ul>
								</div>

								<div>
									<h2>Last.fm</h2>
									<ul class="lastfm menu vertical">
										<li class="lastfm-track">
											<a class="lastfm-link" href="https://www.last.fm/music/%E5%88%86%E5%B3%B6%E8%8A%B1%E9%9F%B3/_/%E3%83%9E%E3%83%9C%E3%83%AD%E3%82%B7" target="_blank" rel="noopener">
												<span class="lastfm-title">分島花音 – マボロシ</span>
												<span class="lastfm-img">
													<picture>
														<source srcset="https://lastfm.freetls.fastly.net/i/u/300x300/493055bd674e45468af39f9bfaae1134.webp" type="image/webp">
														<source srcset="https://lastfm.freetls.fastly.net/i/u/300x300/493055bd674e45468af39f9bfaae1134.png" type="image/png">
														<img src="https://lastfm.freetls.fastly.net/i/u/300x300/493055bd674e45468af39f9bfaae1134.png" class="right" alt="album art 分島花音 – マボロシ" width="34" height="34" loading="lazy">
													</picture>
												</span>
											</a>
										</li>
										<li class="lastfm-track">
											<a class="lastfm-link" href="https://www.last.fm/music/%E5%88%86%E5%B3%B6%E8%8A%B1%E9%9F%B3/_/still+doll+(album+ver.)" target="_blank" rel="noopener">
												<span class="lastfm-title">分島花音 – still doll (album ver.)</span>
												<span class="lastfm-img">
													<picture>
														<source srcset="https://lastfm.freetls.fastly.net/i/u/300x300/493055bd674e45468af39f9bfaae1134.webp" type="image/webp">
														<source srcset="https://lastfm.freetls.fastly.net/i/u/300x300/493055bd674e45468af39f9bfaae1134.png" type="image/png">
														<img src="https://lastfm.freetls.fastly.net/i/u/300x300/493055bd674e45468af39f9bfaae1134.png" class="right" alt="album art 分島花音 – still doll (album ver.)" width="34" height="34" loading="lazy">
													</picture>
												</span>
											</a>
										</li>
										<li class="lastfm-track">
											<a class="lastfm-link" href="https://www.last.fm/music/%E5%88%86%E5%B3%B6%E8%8A%B1%E9%9F%B3/_/%E9%8F%A1" target="_blank" rel="noopener">
												<span class="lastfm-title">分島花音 – 鏡</span>
												<span class="lastfm-img">
													<picture>
														<source srcset="https://lastfm.freetls.fastly.net/i/u/300x300/493055bd674e45468af39f9bfaae1134.webp" type="image/webp">
														<source srcset="https://lastfm.freetls.fastly.net/i/u/300x300/493055bd674e45468af39f9bfaae1134.png" type="image/png">
														<img src="https://lastfm.freetls.fastly.net/i/u/300x300/493055bd674e45468af39f9bfaae1134.png" class="right" alt="album art 分島花音 – 鏡" width="34" height="34" loading="lazy">
													</picture>
												</span>
											</a>
										</li>
										<li class="lastfm-track">
											<a class="lastfm-link" href="https://www.last.fm/music/%E5%88%86%E5%B3%B6%E8%8A%B1%E9%9F%B3/_/%E7%9C%9F%E7%B4%85%E3%81%AE%E3%83%95%E3%82%A7%E3%83%BC%E3%82%BF%E3%83%AA%E3%82%BA%E3%83%A0" target="_blank" rel="noopener">
												<span class="lastfm-title">分島花音 – 真紅のフェータリズム</span>
												<span class="lastfm-img">
													<picture>
														<source srcset="https://lastfm.freetls.fastly.net/i/u/300x300/493055bd674e45468af39f9bfaae1134.webp" type="image/webp">
														<source srcset="https://lastfm.freetls.fastly.net/i/u/300x300/493055bd674e45468af39f9bfaae1134.png" type="image/png">
														<img src="https://lastfm.freetls.fastly.net/i/u/300x300/493055bd674e45468af39f9bfaae1134.png" class="right" alt="album art 分島花音 – 真紅のフェータリズム" width="34" height="34" loading="lazy">
													</picture>
												</span>
											</a>
										</li>
										<li class="lastfm-track">
											<a class="lastfm-link" href="https://www.last.fm/music/%E5%88%86%E5%B3%B6%E8%8A%B1%E9%9F%B3/_/sweet+ticket" target="_blank" rel="noopener">
												<span class="lastfm-title">分島花音 – sweet ticket</span>
												<span class="lastfm-img">
													<picture>
														<source srcset="https://lastfm.freetls.fastly.net/i/u/300x300/493055bd674e45468af39f9bfaae1134.webp" type="image/webp">
														<source srcset="https://lastfm.freetls.fastly.net/i/u/300x300/493055bd674e45468af39f9bfaae1134.png" type="image/png">
														<img src="https://lastfm.freetls.fastly.net/i/u/300x300/493055bd674e45468af39f9bfaae1134.png" class="right" alt="album art 分島花音 – sweet ticket" width="34" height="34" loading="lazy">
													</picture>
												</span>
											</a>
										</li>
										<li class="lastfm-track">
											<a class="lastfm-link" href="https://www.last.fm/music/Michael+Bolton/_/This+River" target="_blank" rel="noopener">
												<span class="lastfm-title">Michael Bolton – This River</span>
												<span class="lastfm-img">
													<picture>
														<source srcset="https://lastfm.freetls.fastly.net/i/u/300x300/f459b039a4e14bb0cb0998491611d340.webp" type="image/webp">
														<source srcset="https://lastfm.freetls.fastly.net/i/u/300x300/f459b039a4e14bb0cb0998491611d340.png" type="image/png">
														<img src="https://lastfm.freetls.fastly.net/i/u/300x300/f459b039a4e14bb0cb0998491611d340.png" class="right" alt="album art Michael Bolton – This River" width="34" height="34" loading="lazy">
													</picture>
												</span>
											</a>
										</li>
										<li class="lastfm-track">
											<a class="lastfm-link" href="https://www.last.fm/music/Michael+Bolton/_/A+Love+So+Beautiful" target="_blank" rel="noopener">
												<span class="lastfm-title">Michael Bolton – A Love So Beautiful</span>
												<span class="lastfm-img">
													<picture>
														<source srcset="https://lastfm.freetls.fastly.net/i/u/300x300/f459b039a4e14bb0cb0998491611d340.webp" type="image/webp">
														<source srcset="https://lastfm.freetls.fastly.net/i/u/300x300/f459b039a4e14bb0cb0998491611d340.png" type="image/png">
														<img src="https://lastfm.freetls.fastly.net/i/u/300x300/f459b039a4e14bb0cb0998491611d340.png" class="right" alt="album art Michael Bolton – A Love So Beautiful" width="34" height="34" loading="lazy">
													</picture>
												</span>
											</a>
										</li>
										<li class="lastfm-track">
											<a class="lastfm-link" href="https://www.last.fm/music/Michael+Bolton/_/I+Found+Someone" target="_blank" rel="noopener">
												<span class="lastfm-title">Michael Bolton – I Found Someone</span>
												<span class="lastfm-img">
													<picture>
														<source srcset="https://lastfm.freetls.fastly.net/i/u/300x300/f459b039a4e14bb0cb0998491611d340.webp" type="image/webp">
														<source srcset="https://lastfm.freetls.fastly.net/i/u/300x300/f459b039a4e14bb0cb0998491611d340.png" type="image/png">
														<img src="https://lastfm.freetls.fastly.net/i/u/300x300/f459b039a4e14bb0cb0998491611d340.png" class="right" alt="album art Michael Bolton – I Found Someone" width="34" height="34" loading="lazy">
													</picture>
												</span>
											</a>
										</li>
										<li class="lastfm-track">
											<a class="lastfm-link" href="https://www.last.fm/music/Michael+Bolton/_/I+Promise+You" target="_blank" rel="noopener">
												<span class="lastfm-title">Michael Bolton – I Promise You</span>
												<span class="lastfm-img">
													<picture>
														<source srcset="https://lastfm.freetls.fastly.net/i/u/300x300/f459b039a4e14bb0cb0998491611d340.webp" type="image/webp">
														<source srcset="https://lastfm.freetls.fastly.net/i/u/300x300/f459b039a4e14bb0cb0998491611d340.png" type="image/png">
														<img src="https://lastfm.freetls.fastly.net/i/u/300x300/f459b039a4e14bb0cb0998491611d340.png" class="right" alt="album art Michael Bolton – I Promise You" width="34" height="34" loading="lazy">
													</picture>
												</span>
											</a>
										</li>
										<li class="lastfm-track">
											<a class="lastfm-link" href="https://www.last.fm/music/Michael+Bolton/_/Can+I+Touch+You...There%3F" target="_blank" rel="noopener">
												<span class="lastfm-title">Michael Bolton – Can I Touch You...There?</span>
												<span class="lastfm-img">
													<picture>
														<source srcset="https://lastfm.freetls.fastly.net/i/u/300x300/f459b039a4e14bb0cb0998491611d340.webp" type="image/webp">
														<source srcset="https://lastfm.freetls.fastly.net/i/u/300x300/f459b039a4e14bb0cb0998491611d340.png" type="image/png">
														<img src="https://lastfm.freetls.fastly.net/i/u/300x300/f459b039a4e14bb0cb0998491611d340.png" class="right" alt="album art Michael Bolton – Can I Touch You...There?" width="34" height="34" loading="lazy">
													</picture>
												</span>
											</a>
										</li>
										<li class="lastfm-track">
											<a class="lastfm-link" href="https://www.last.fm/music/Michael+Bolton/_/Said+I+Loved+You...But+I+Lied" target="_blank" rel="noopener">
												<span class="lastfm-title">Michael Bolton – Said I Loved You...But I Lied</span>
												<span class="lastfm-img">
													<picture>
														<source srcset="https://lastfm.freetls.fastly.net/i/u/300x300/f459b039a4e14bb0cb0998491611d340.webp" type="image/webp">
														<source srcset="https://lastfm.freetls.fastly.net/i/u/300x300/f459b039a4e14bb0cb0998491611d340.png" type="image/png">
														<img src="https://lastfm.freetls.fastly.net/i/u/300x300/f459b039a4e14bb0cb0998491611d340.png" class="right" alt="album art Michael Bolton – Said I Loved You...But I Lied" width="34" height="34" loading="lazy">
													</picture>
												</span>
											</a>
										</li>
									</ul>
								</div>

								<div>
									<p>Curabitur sollicitudin iaculis ante, ac vehicula lacus pretium vel. Donec in rhoncus nunc, sit amet vulputate dui. Proin et nunc diam. Fusce ornare dui eget ante interdum, at ornare arcu mattis. Sed a euismod metus. Duis aliquam ultrices viverra. Pellentesque vestibulum erat metus, non iaculis tellus interdum in. Nunc quis aliquet leo. Ut efficitur risus eget ipsum pellentesque ultrices. Phasellus sed accumsan est. Nullam vestibulum nisi augue, a luctus augue condimentum id.</p>
									<p>Aenean eget sem nec turpis scelerisque posuere interdum sit amet lorem. Praesent iaculis magna a vehicula sagittis. Mauris sed laoreet mi, non vestibulum sem. In est massa, faucibus at erat a, molestie cursus est. Sed sit amet elit lectus. Sed lobortis blandit magna non dignissim. Duis in elit rutrum, maximus arcu et, convallis massa. Praesent tempor pulvinar pharetra. Sed porta nec justo eu finibus. Vestibulum quis feugiat nibh. Pellentesque feugiat lectus nec odio mattis rutrum. Morbi egestas ultricies nunc eu molestie. Sed sit amet lectus eu mi rhoncus finibus sit amet a est.</p>
								</div>

								<div>
									<p>Phasellus sollicitudin gravida orci, congue mattis justo interdum consectetur. Nam vehicula ut urna vitae tempus. Integer aliquam molestie sagittis. Nam vel posuere tellus. Donec eu dui interdum, luctus sem sed, fermentum sem. Donec varius sagittis metus vel ullamcorper. Etiam sagittis tellus urna. Pellentesque a imperdiet massa, in sollicitudin sem. Sed non ultrices lorem.</p>
								</div>
							</aside>
						<?php endif; ?>
					</div>
				</section><?php /* end of copyarea */ ?>
			<?php else : ?>
			<?php endif; ?>

			<footer role="contentinfo" arial-label="Footer content">
				<div class="footer-item"><p><?php echo Text::sprintf('TPL_CANDELAALUMINIUM_COPYRIGHT', $today->format('Y')); ?></p></div>
				<div class="footer-item"><p>&copy; 2018 Sitename</p></div>

				<?php /* <jdoc:include type="modules" name="footer" style="xhtml5" />
				<jdoc:include type="modules" name="bottom" style="xhtml5" /> */ ?>
				<div class="footer-item"><p>some extra footer text</p></div>
			</footer>

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
