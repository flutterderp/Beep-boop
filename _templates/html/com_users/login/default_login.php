<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');

$doc = Factory::getDocument();
?>
<div class="login<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page-header">
		<h1>
			<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
	</div>
	<?php endif; ?>

	<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
	<div class="login-description">
	<?php endif; ?>

		<?php if ($this->params->get('logindescription_show') == 1) : ?>
			<?php echo $this->params->get('login_description'); ?>
		<?php endif; ?>

		<?php if (($this->params->get('login_image') != '')) :?>
			<img src="<?php echo $this->escape($this->params->get('login_image')); ?>" class="login-image" alt="<?php echo Text::_('COM_USERS_LOGIN_IMAGE_ALT')?>"/>
		<?php endif; ?>

	<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
	</div>
	<?php endif; ?>

	<?php echo $doc->getBuffer('modules', 'loginpretext', array('style' => 'none')); ?>

	<form action="<?php echo Route::_('index.php?option=com_users&task=user.login'); ?>" method="post" class="form-validate form-horizontal well">
		<fieldset>
			<div class="row">
			<?php foreach ($this->form->getFieldset('credentials') as $field) : ?>
				<?php if (!$field->hidden) : ?>
					<div class="small-12 medium-6 columns control-group">
						<div class="control-label">
							<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
			</div>

			<?php if ($this->tfa): ?>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getField('secretkey')->label; ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getField('secretkey')->input; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if (PluginHelper::isEnabled('system', 'remember')) : ?>
			<div class="control-group">
				<div class="control-label"></div>
				<div class="controls">
					<label>
						<input id="remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
						<?php echo Text::_('COM_USERS_LOGIN_REMEMBER_ME') ?>
					</label>
				</div>
			</div>
			<?php endif; ?>

			<div class="control-group">
				<div class="controls button-group">
					<button type="submit" class="button"><span class="fa fa-sign-in-alt"></span> <?php echo Text::_('JLOGIN'); ?></button>
					<?php
					$usersConfig = ComponentHelper::getParams('com_users');
					if ($usersConfig->get('allowUserRegistration')) : ?>
						<a class="button" href="<?php echo Route::_('index.php?option=com_users&view=registration'); ?>"><?php echo Text::_('COM_USERS_LOGIN_REGISTER'); ?></a>
					<?php endif; ?>
					<a class="button secondary" href="<?php echo Route::_('index.php?option=com_users&view=reset'); ?>">
						<span class="fa fa-question"></span> <?php echo Text::_('COM_USERS_LOGIN_RESET'); ?>
					</a>
					<a class="button secondary" href="<?php echo Route::_('index.php?option=com_users&view=remind'); ?>">
						<span class="fa fa-question"></span> <?php echo Text::_('COM_USERS_LOGIN_REMIND'); ?>
					</a>
				</div>
				<ul>

				</ul>
			</div>

			<?php if ($this->params->get('login_redirect_url')) : ?>
				<input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('login_redirect_url', $this->form->getValue('return'))); ?>" />
			<?php else : ?>
				<input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('login_redirect_menuitem', $this->form->getValue('return'))); ?>" />
			<?php endif; ?>
			<?php echo HTMLHelper::_('form.token'); ?>
		</fieldset>
	</form>

	<?php echo $doc->getBuffer('modules', 'loginposttext', array('style' => 'none')); ?>
</div>
