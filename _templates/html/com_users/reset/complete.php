<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');
?>
<div class="reset-complete<?php echo $this->pageclass_sfx?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>

	<form action="<?php echo Route::_('index.php?option=com_users&task=reset.complete'); ?>" method="post" class="form-validate form-horizontal well">
		<?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
			<fieldset>
				<p><?php echo Text::_($fieldset->label); ?></p>
				<?php foreach ($this->form->getFieldset($fieldset->name) as $name => $field) : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</fieldset>
		<?php endforeach; ?>

		<div class="control-group">
			<div class="controls">
				<button type="submit" class="button validate"><?php echo Text::_('JSUBMIT'); ?></button>
			</div>
		</div>
		<?php
		$db		= Factory::getDbo();
		$sql	= $db->getQuery(true);
		$sql
			->select('id')
			->from($db->quoteName('#__menu'))
			->where('alias = ' . $db->quote('log-in'))
			->setLimit(1);
		$db->setQuery($sql);
		$itemId = (int) $db->loadResult();

		if($itemId > 0)
		{
			$return_uri	= Route::_('index.php?Itemid=' . $Itemid);
			$return_uri	= urlencode(base64_encode($return_uri));
			?>
			<input type="hidden" name="return" value="<?php echo $return_uri; ?>">
			<?php
		}

		echo HTMLHelper::_('form.token');
		?>
	</form>
</div>
