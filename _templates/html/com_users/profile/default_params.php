<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

JLoader::register('JHtmlUsers', JPATH_COMPONENT . '/helpers/html/users.php');
HTMLHelper::register('users.spacer', array('JHtmlUsers', 'spacer'));
HTMLHelper::register('users.helpsite', array('JHtmlUsers', 'helpsite'));
HTMLHelper::register('users.templatestyle', array('JHtmlUsers', 'templatestyle'));
HTMLHelper::register('users.admin_language', array('JHtmlUsers', 'admin_language'));
HTMLHelper::register('users.language', array('JHtmlUsers', 'language'));
HTMLHelper::register('users.editor', array('JHtmlUsers', 'editor'));

?>
<?php $fields = $this->form->getFieldset('params'); ?>
<?php if (count($fields)) : ?>
<fieldset id="users-profile-custom">
	<legend><?php echo JText::_('COM_USERS_SETTINGS_FIELDSET_LABEL'); ?></legend>
	<dl class="dl-horizontal">
	<?php foreach ($fields as $field):
		if (!$field->hidden) :?>
		<dt><?php echo $field->title; ?></dt>
		<dd>
			<?php if (HTMLHelper::isRegistered('users.' . $field->id)):?>
				<?php echo HTMLHelper::_('users.' . $field->id, $field->value);?>
			<?php elseif (HTMLHelper::isRegistered('users.' . $field->fieldname)):?>
				<?php echo HTMLHelper::_('users.' . $field->fieldname, $field->value);?>
			<?php elseif (HTMLHelper::isRegistered('users.' . $field->type)):?>
				<?php echo HTMLHelper::_('users.' . $field->type, $field->value);?>
			<?php else:?>
				<?php echo HTMLHelper::_('users.value', $field->value);?>
			<?php endif;?>
		</dd>
		<?php endif;?>
	<?php endforeach;?>
	</dl>
</fieldset>
<?php endif;?>
