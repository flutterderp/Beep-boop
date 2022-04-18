<?php
/**
 * @package Joomla.Site
 * @subpackage Templates.protostar
 * @copyright Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;

// Set HTTP header
switch($this->error->getCode())
{
	case '403' :
		header('HTTP/1.1 403 Forbidden');
		break;
	case '404' :
		header('HTTP/1.1 404 Not Found');
		break;
	default :
		break;
}

$utc_tz  = new DateTimeZone('UTC');
$today   = new DateTime(null, $utc_tz);
$logFile = JPATH_BASE . '/error_log';
$handle  = fopen($logFile, 'a+');
error_log('[' . $today->format('H:i:s') . '] ' . htmlspecialchars($this->error->getCode() . ': ' . $this->error->getMessage(), ENT_QUOTES, 'utf-8') . ' in ', 3, $logFile);
error_log(OutputFilter::ampReplace(Uri::getInstance()->getPath()) . PHP_EOL, 3, $logFile);
fclose($handle);

$app             = Factory::getApplication();
$doc             = Factory::getDocument();
$user            = Factory::getUser();
$error_type      = Version::MAJOR_VERSION === 4 ? 'error' : 'html';
$isHtml          = ($doc->_type === $error_type) ? true : false;
// $isHtml          = (method_exists($doc, 'getHeadData') !== true) ? true : false;
$this->baseurl   = Uri::root(false);
$this->language  = $doc->language;
$this->debug     = true;
$this->direction = $doc->direction;
$this->template  = 'candelaaluminium';
// Getting params from template
$params          = $app->getTemplate(true)->params;
$logo            = $params->get('logo');
$logo_footer     = $params->get('logo_footer');
// Detecting Active Variables
$option          = $app->input->getCmd('option', '');
$view            = $app->input->getCmd('view', '');
$layout          = $app->input->getCmd('layout', '');
$task            = $app->input->getCmd('task', '');
$itemid          = $app->input->getCmd('Itemid', '');
$sitename        = $app->get('sitename');

if($isHtml !== true)
{
	$uri      = Uri::getInstance();
	$pathname = pathinfo($uri->getPath(), PATHINFO_DIRNAME);
	$basename = pathinfo($uri->getPath(), PATHINFO_BASENAME);
	$new_base = '/' . OutputFilter::stringUrlSafe($basename) . '_' . time() . '.html';
	$app->redirect($uri->getScheme() . '://' . $uri->getHost() . str_replace('//', '/', $pathname . $new_base));
}

// Clear out Joomla/JCE scripts and stylesheets
$oldHeadData = $doc->getHeadData();
$doc->resetHeadData();
$doc->_custom                       = $oldHeadData['custom'];
$doc->title                         = $oldHeadData['title'];
$doc->description                   = $oldHeadData['description'];
$doc->_metaTags['name']['keywords'] = isset($oldHeadData['metaTags']['name']['keywords']) ? $oldHeadData['metaTags']['name']['keywords'] : '';

if($task == "edit" || $layout == "form" )
{
	$fullWidth = 1;
}
else
{
	$fullWidth = 0;
}

// Add JavaScript Frameworks
// JHtml::_('jquery.framework');

// JavaScript files
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/javascript/jquery-3.4.1.min.js', null, array('async' => false));
// $doc->addScript($this->baseurl . '/templates/' . $this->template . '/javascript/jquery-migrate-1.4.1.min.js', null, array('async' => true));
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/javascript/jquery-migrate-3.0.0.min.js', null, array('async' => true));
// Stylesheets
$doc->addHeadLink('https://fonts.googleapis.com', 'preconnect');
$doc->addHeadLink('https://fonts.gstatic.com', 'preconnect', 'rel', array('crossorigin' => 'crossorigin'));
$doc->addStyleSheet('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,700;1,700&family=Open+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap');
$doc->addStyleSheet($this->baseurl . '/templates/system/css/system.css');
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/fa59-all.min.css');
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/custom.css');

/* if(method_exists($doc, 'getHeadData'))
{
	$head_data = $doc->getHeadData();
}
else
{
	$uri = Uri::getInstance();
	$app->redirect('https://' . $uri->getHost() . $uri->getPath() . '.html', false);
} */
$head_data = $doc->getHeadData();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
	<head>
		<meta charset="utf-8">
		<title><?php echo $this->title; ?> <?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?></title>
		<meta name="msapplication-config" content="none">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<base href="<?php echo $this->baseurl; ?>">
		<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
		<?php
		foreach($head_data['styleSheets'] as $key => $style)
		{
			$style_html = '<link href="' . $key . '"';
			$style_html .= isset($style['media']) ? ' media="' . $style['media'] . '"' : '';
			$style_html .= ' rel="stylesheet">';
			echo $style_html;
		}

		foreach($head_data['scripts'] as $key => $script)
		{
			$script_html = '<script src="' . $key . '"';
			$script_html .= (isset($script['async']) && $script['async'] === true) ? ' async="async"' : '';
			$script_html .= (isset($script['defer']) && $script['defer'] === true) ? ' defer="defer"' : '';
			$script_html .= '></script>';
			echo $script_html;
		}
		?>
	</head>

	<body>
		<section name="pagetop" id="pagetop" aria-label="Anchor: top of page"></section>

		<nav class="pushy pushy-left" data-menu-btn-class=".pushy-menu-btn" role="navigation" arial-label="Mobile menu">
			<?php echo $doc->getBuffer('modules', 'mobilemenu', array('style' => 'none')); ?>
		</nav>

		<div class="wrapper pushy-container" id="wrapper">
			<header class="masthead inner" id="masthead">
				<div class="masthead-content row">
					<?php echo $doc->getBuffer('modules', 'logo', array('style' => 'xhtml5')); ?>

					<section class="topnavarea">
						<?php
						echo $doc->getBuffer('modules', 'top', array('style' => 'xhtml5'));
						echo $doc->getBuffer('modules', 'navigation', array('style' => 'xhtml5'));
						?>
						<a class="pushy-menu-btn"><i class="fa fa-bars"></i></a>
					</section>
				</div>
			</header><?php /* end of masthead */ ?>

			<section class="copyarea row" id="copyarea">
				<main class="small-12 columns copy" id="copy" role="main">
					<?php
					echo $doc->getBuffer('message');
					echo $doc->getBuffer('modules', 'copyhead', array('style' => 'xhtml5'));
					?>
					<!-- Begin Content -->
					<h1 class="page-header"><?php echo Text::_('JERROR_LAYOUT_PAGE_NOT_FOUND'); ?></h1>
					<div class="well">
						<div class="row collapse">
							<div class="small-12 columns">
								<p><strong><?php echo Text::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST'); ?></strong></p>
								<p><?php echo Text::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?></p>
								<ul>
									<li><?php echo Text::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE'); ?></li>
									<li><?php echo Text::_('JERROR_LAYOUT_MIS_TYPED_ADDRESS'); ?></li>
									<li><?php echo Text::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING'); ?></li>
									<li><?php echo Text::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE'); ?></li>
								</ul>
							</div>
							<div class="small-12 columns">
								<?php if(JModuleHelper::getModule('search')) : ?>
									<p><strong><?php echo Text::_('JERROR_LAYOUT_SEARCH'); ?></strong></p>
									<p><?php echo Text::_('JERROR_LAYOUT_SEARCH_PAGE'); ?></p>
									<?php echo $doc->getBuffer('module', 'search'); ?>
								<?php endif; ?>
								<p><a href="<?php echo $this->baseurl; ?>/" class="button"><span class="fa fa-home"></span> <?php echo Text::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?></a></p>
							</div>
						</div>
						<hr>
						<p><?php echo Text::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR'); ?></p>
						<p>
							<span class="label alert"><?php echo $this->error->getCode(); ?></span> <?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8');?>
						</p>
						<?php if($this->debug) : ?>
							<div>
								<?php echo $this->renderBacktrace(); ?>
								<?php // Check if there are more Exceptions and render their data as well ?>
								<?php if($this->error->getPrevious()) : ?>
									<?php $loop = true; ?>
									<?php // Reference $this->_error here and in the loop as setError() assigns errors to this property and we need this for the backtrace to work correctly ?>
									<?php // Make the first assignment to setError() outside the loop so the loop does not skip Exceptions ?>
									<?php $this->setError($this->_error->getPrevious()); ?>
									<?php while($loop === true) : ?>
										<p><strong><?php echo Text::_('JERROR_LAYOUT_PREVIOUS_ERROR'); ?></strong></p>
										<p><?php echo htmlspecialchars($this->_error->getMessage(), ENT_QUOTES, 'UTF-8'); ?></p>
										<?php echo $this->renderBacktrace(); ?>
										<?php $loop = $this->setError($this->_error->getPrevious()); ?>
									<?php endwhile; ?>
									<?php // Reset the main error object to the base error ?>
									<?php $this->setError($this->error); ?>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
					<!-- End Content -->
					<?php echo $doc->getBuffer('modules', 'copyfoot', array('style' => 'xhtml5')); ?>
				</main><?php /* end of copy */ ?>
			</section><?php /* end of copyarea */ ?>

			<footer role="contentinfo" arial-label="Footer content">
				<div class="row">
					<div class="small-12 columns">
						<?php echo $doc->getBuffer('modules', 'footer', array('style' => 'xhtml5')); ?>
						<jdoc:include type="modules" name="footer" style="xhtml5" />
					</div>
				</div>

				<?php echo $doc->getBuffer('modules', 'bottom', array('style' => 'xhtml5')); ?>

				<div class="row dev-copyright">
					<div class="small-12 columns text-center">
						<a href="http://www.southernanime.com" target="_blank">
							<?php echo Text::sprintf('TPL_CANDELAALUMINIUM_COPYRIGHT', JDate::getInstance('UTC')->format('Y')); ?>
						</a>
					</div>
				</div>
			</footer><?php /* end of footer */ ?>

			<?php echo $doc->getBuffer('modules', 'debug', array('style' => 'none')); ?>
			<jdoc:include type="modules" name="debug" />
			<div class="pushy-site-overlay"></div>

			<a class="up-button" href="#pagetop" aria-label="Action: scroll to top of page"></a>
		</div><?php /* end of wrapper */ ?>

		<?php
		$script_keys = array_keys($oldHeadData['scripts']);
		$script_keys = preg_grep('/(jquery\.min\.js|jquery-noconflict\.js|jquery-migrate\.min\.js)/', $script_keys, PREG_GREP_INVERT);

		foreach($script_keys as $script) : ?>
			<script src="<?php echo $script; ?>"></script>
		<?php endforeach; ?>

		<script src="<?php echo $this->baseurl . '/templates/' . $this->template; ?>/javascript/custom.js"></script>

		<?php if(!empty($oldHeadData['script']['text/javascript'])) : ?>
		 <script><?php echo $oldHeadData['script']['text/javascript']; ?></script>
		<?php endif; ?>
	</body>
</html>
