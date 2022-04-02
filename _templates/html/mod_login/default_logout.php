<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure', 0)); ?>" method="post" id="login-form" class="form-vertical">
<?php if ($params->get('greeting', 1)) : ?>
	<div class="login-greeting">
	<?php if (!$params->get('name', 0)) : ?>
		<?php echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('name'), ENT_COMPAT, 'UTF-8')); ?>
	<?php else : ?>
		<?php echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('username'), ENT_COMPAT, 'UTF-8')); ?>
	<?php endif; ?>
	</div>
<?php endif; ?>

	<div class="logout-button button-group">
		<?php if ($params->get('profilelink', 0)) : ?>
			<a class="button" href="<?php echo JRoute::_('index.php?option=com_users&view=profile'); ?>"><span class="fa fa-user"></span> <?php echo JText::_('MOD_LOGIN_PROFILE'); ?></a>
		<?php endif; ?>
		<button class="button primary" type="submit" name="Submit"><span class="fa fa-power-off"></span> <?php echo JText::_('JLOGOUT'); ?></button>
		<input type="hidden" name="option" value="com_users">
		<input type="hidden" name="task" value="user.logout">
		<input type="hidden" name="return" value="<?php echo $return; ?>">
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
