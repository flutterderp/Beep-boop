<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template.system
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app              = JFactory::getApplication();
$doc              = JFactory::getDocument();
$menu             = $app->getMenu();
$show_login       = (int) $this->params->get('show_offline_login', 0);
$twofactormethods = JAuthenticationHelper::getTwoFactorMethods();
$doc->setGenerator('Southern Anime');
// Clear out Joomla/JCE scripts and stylesheets
$oldHeadData = $doc->getHeadData();
$doc->resetHeadData();
$doc->_custom                       = $oldHeadData['custom'];
$doc->title                         = $oldHeadData['title'];
$doc->description                   = $oldHeadData['description'];
$doc->_metaTags['name']['keywords'] = $oldHeadData['metaTags']['name']['keywords'];

// JavaScript
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/javascript/jquery-3.3.1.min.js', null, array('async' => false));
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/javascript/jquery-migrate-3.0.0.min.js', null, array('async' => true));
// Stylesheets
$doc->addStyleSheet('https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700|Open+Sans:400,400italic,500,600,700');
$doc->addStyleSheet($this->baseurl . '/templates/system/css/system.css');
// $doc->addStyleSheet($this->baseurl . '/templates/system/css/offline.css');
// $doc->addStyleSheet($this->baseurl . '/templates/system/css/general.css');
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/app.css');
$doc->addStyleSheet($this->baseurl . '/plugins/system/jcemediabox/css/jcemediabox.css');
$doc->addStyleSheet($this->baseurl . '/plugins/system/jcemediabox/themes/light/css/style.css');
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/fa511-all.css');
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/custom.css');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="msapplication-config" content="none">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8">
		<jdoc:include type="head" />
	</head>
	<body>
		<main class="row">
			<div class="small-12 medium-8 medium-offset-2 columns">
				<jdoc:include type="message" />

				<section id="frame" class="outline text-center">
					<?php if ($app->get('offline_image') && file_exists($app->get('offline_image'))) : ?>
						<img src="<?php echo $app->get('offline_image'); ?>" alt="<?php echo htmlspecialchars($app->get('sitename'), ENT_COMPAT, 'UTF-8'); ?>" />
					<?php endif; ?>
					<h1><?php echo htmlspecialchars($app->get('sitename'), ENT_COMPAT, 'UTF-8'); ?></h1>
					<?php if ($app->get('display_offline_message', 1) == 1 && str_replace(' ', '', $app->get('offline_message')) != '') : ?>
						<p><?php echo $app->get('offline_message'); ?></p>
					<?php elseif ($app->get('display_offline_message', 1) == 2 && str_replace(' ', '', JText::_('JOFFLINE_MESSAGE')) != '') : ?>
						<p><?php echo JText::_('JOFFLINE_MESSAGE'); ?></p>
					<?php endif; ?>
				</section>

				<?php if($show_login !== 0) : ?>
					<section>
						<form action="<?php echo JRoute::_('index.php', true); ?>" method="post" id="form-login">
							<fieldset class="input">
								<div class="input-group" id="form-login-username">
									<label class="input-group-label" for="username"><?php echo JText::_('JGLOBAL_USERNAME'); ?></label>
									<input class="inputbox input-group-field" name="username" id="username" type="text" alt="<?php echo JText::_('JGLOBAL_USERNAME'); ?>" autocomplete="off" autocapitalize="none" />
								</div>

								<div class="input-group" id="form-login-password">
									<label class="input-group-label" for="passwd"><?php echo JText::_('JGLOBAL_PASSWORD'); ?></label>
									<input class="inputbox input-group-field" type="password" name="password" alt="<?php echo JText::_('JGLOBAL_PASSWORD'); ?>" id="passwd" />
								</div>

								<?php if (count($twofactormethods) > 1) : ?>
									<div class="input-group" id="form-login-secretkey">
										<label class="input-group-label" for="secretkey"><?php echo JText::_('JGLOBAL_SECRETKEY'); ?></label>
										<input class="inputbox input-group-field" type="text" name="secretkey" alt="<?php echo JText::_('JGLOBAL_SECRETKEY'); ?>" id="secretkey" />
									</div>
								<?php endif; ?>

								<div id="submit-button">
									<input type="submit" name="Submit" class="button login" value="<?php echo JText::_('JLOGIN'); ?>" />
								</div>

								<input type="hidden" name="option" value="com_users" />
								<input type="hidden" name="task" value="user.login" />
								<input type="hidden" name="return" value="<?php echo base64_encode(JUri::base()); ?>" />
								<?php echo JHtml::_('form.token'); ?>
							</fieldset>
						</form>
					</section>
				<?php endif; ?>
			</div>
		</main>

		<script src="<?php echo $this->baseurl . '/templates/' . $this->template; ?>/javascript/foundation.min.js"></script>
		<script src="<?php echo $this->baseurl; ?>/media/system/js/caption.js"></script>
		<script src="<?php echo $this->baseurl; ?>/plugins/system/jcemediabox/js/jcemediabox.js"></script>
		<?php /* <script src="<?php echo $this->baseurl . '/templates/' . $this->template; ?>/javascript/custom.js"></script> */ ?>

		<?php if(!empty($oldHeadData['script']['text/javascript'])) : ?>
		 <script><?php echo $oldHeadData['script']['text/javascript']; ?></script>
		<?php endif; ?>
	</body>
</html>
