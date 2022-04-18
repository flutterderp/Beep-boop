<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;

JLoader::register('UsersHelperRoute', JPATH_SITE . '/components/com_users/helpers/route.php');

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('bootstrap.tooltip');

?>
<form action="<?php echo Route::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" class="form-inline">
	<?php if ($params->get('pretext')) : ?>
		<div class="pretext">
			<p><?php echo $params->get('pretext'); ?></p>
		</div>
	<?php endif; ?>
	<div class="userdata">
		<div class="row">
			<div class="small-12 medium-6 columns" id="form-login-username">
				<?php if (!$params->get('usetext')) : ?>
					<div class="input-prepend">
						<span class="add-on">
							<span class="icon-user hasTooltip" title="<?php echo Text::_('MOD_LOGIN_VALUE_USERNAME'); ?>"></span>
							<label for="modlgn-username" class="element-invisible"><?php echo Text::_('MOD_LOGIN_VALUE_USERNAME'); ?></label>
						</span>
						<input id="modlgn-username" type="text" name="username" class="input-small" tabindex="0" size="18" placeholder="<?php echo Text::_('MOD_LOGIN_VALUE_USERNAME'); ?>" />
					</div>
				<?php else : ?>
					<label for="modlgn-username"><?php echo Text::_('MOD_LOGIN_VALUE_USERNAME'); ?></label>
					<input id="modlgn-username" type="text" name="username" class="input-small" tabindex="0" size="18" placeholder="<?php echo Text::_('MOD_LOGIN_VALUE_USERNAME'); ?>" />
				<?php endif; ?>
			</div>

			<div class="small-12 medium-6 columns" id="form-login-password">
				<?php if (!$params->get('usetext')) : ?>
					<div class="input-prepend">
						<span class="add-on">
							<span class="icon-lock hasTooltip" title="<?php echo Text::_('JGLOBAL_PASSWORD'); ?>">
							</span>
								<label for="modlgn-passwd" class="element-invisible"><?php echo Text::_('JGLOBAL_PASSWORD'); ?>
							</label>
						</span>
						<input id="modlgn-passwd" type="password" name="password" class="input-small" tabindex="0" size="18" placeholder="<?php echo Text::_('JGLOBAL_PASSWORD'); ?>" />
					</div>
				<?php else : ?>
					<label for="modlgn-passwd"><?php echo Text::_('JGLOBAL_PASSWORD'); ?></label>
					<input id="modlgn-passwd" type="password" name="password" class="input-small" tabindex="0" size="18" placeholder="<?php echo Text::_('JGLOBAL_PASSWORD'); ?>" />
				<?php endif; ?>
			</div>

			<?php if (count($twofactormethods) > 1) : ?>
				<div class="small-12 medium-6 columns" id="form-login-secretkey">
					<?php if (!$params->get('usetext')) : ?>
						<div class="input-prepend input-append">
							<span class="add-on">
								<span class="icon-star hasTooltip" title="<?php echo Text::_('JGLOBAL_SECRETKEY'); ?>">
								</span>
									<label for="modlgn-secretkey" class="element-invisible"><?php echo Text::_('JGLOBAL_SECRETKEY'); ?>
								</label>
							</span>
							<input id="modlgn-secretkey" autocomplete="off" type="text" name="secretkey" class="input-small" tabindex="0" size="18" placeholder="<?php echo Text::_('JGLOBAL_SECRETKEY'); ?>" />
							<span class="btn width-auto hasTooltip" title="<?php echo Text::_('JGLOBAL_SECRETKEY_HELP'); ?>">
								<span class="icon-help"></span>
							</span>
					</div>
					<?php else : ?>
						<label for="modlgn-secretkey"><?php echo Text::_('JGLOBAL_SECRETKEY'); ?></label>
						<input id="modlgn-secretkey" autocomplete="off" type="text" name="secretkey" class="input-small" tabindex="0" size="18" placeholder="<?php echo Text::_('JGLOBAL_SECRETKEY'); ?>" />
						<span class="btn width-auto hasTooltip" title="<?php echo Text::_('JGLOBAL_SECRETKEY_HELP'); ?>">
							<span class="icon-help"></span>
						</span>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if (PluginHelper::isEnabled('system', 'remember')) : ?>
		<div id="form-login-remember" class="control-group checkbox">
			<label for="modlgn-remember" class="control-label"><?php echo Text::_('MOD_LOGIN_REMEMBER_ME'); ?></label> <input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
		</div>
		<?php endif; ?>
		<div id="form-login-submit" class="control-group">
			<div class="controls">
				<button type="submit" tabindex="0" name="Submit" class="button login-button"><?php echo Text::_('JLOGIN'); ?></button>
			</div>
		</div>
		<?php $usersConfig = ComponentHelper::getParams('com_users'); ?>
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.login" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo HTMLHelper::_('form.token'); ?>
	</div>
	<?php if ($params->get('posttext')) : ?>
		<div class="posttext">
			<p><?php echo $params->get('posttext'); ?></p>
		</div>
	<?php endif; ?>
</form>
