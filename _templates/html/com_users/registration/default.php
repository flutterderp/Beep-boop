<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');

?>
<div class="registration<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
		</div>
	<?php endif; ?>
	<form id="member-registration" action="<?php echo Route::_('index.php?option=com_users&task=registration.register'); ?>" method="post" class="form-validate form-horizontal well" enctype="multipart/form-data">
		<?php // Iterate through the form fieldsets and display each one. ?>
		<?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
			<?php $fields = $this->form->getFieldset($fieldset->name); ?>
			<?php if (count($fields)) : ?>
				<fieldset>
					<?php // If the fieldset has a label set, display it as the legend. ?>
					<?php if (isset($fieldset->label)) : ?>
						<legend><?php echo Text::_($fieldset->label); ?></legend>
					<?php endif; ?>

					<p><label id="jform_spacer-lbl" class=""><b class="red">*</b> Required field</label></p>

					<?php // Iterate through the fields in the set and display them. ?>
					<div class="row small-up-1 medium-up-2">
					<?php foreach ($fields as $field) :
						if($field->type == 'Spacer')
						{
							continue;
						}
						?>
						<?php // If the field is hidden, just display the input. ?>
						<?php if ($field->hidden) : ?>
							<?php echo $field->input; ?>
						<?php else : ?>
							<div class="control-group column">
								<div class="control-label">
									<?php echo $field->label; ?>
									<?php if (!$field->required && $field->type !== 'Spacer') : ?>
										<span class="optional"><?php echo Text::_('COM_USERS_OPTIONAL'); ?></span>
									<?php endif; ?>
								</div>
								<div class="controls">
									<?php echo $field->input; ?>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
					</div>
				</fieldset>
			<?php endif; ?>
		<?php endforeach; ?>

		<div class="clearfix">&nbsp;</div>

		<div class="control-group">
			<div class="controls button-group">
				<button type="submit" class="button primary validate">
					<?php echo Text::_('JREGISTER'); ?>
				</button>
				<a class="button secondary" href="<?php echo Route::_(''); ?>" title="<?php echo Text::_('JCANCEL'); ?>">
					<?php echo Text::_('JCANCEL'); ?>
				</a>
				<input type="hidden" name="option" value="com_users" />
				<input type="hidden" name="task" value="registration.register" />
			</div>
		</div>
		<?php echo HTMLHelper::_('form.token'); ?>
	</form>
</div>
