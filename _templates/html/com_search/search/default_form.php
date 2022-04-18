<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$lang        = Factory::getLanguage();
$upper_limit = $lang->getUpperLimitSearchWord();

// HTMLHelper::_('bootstrap.tooltip');
?>
<form id="searchForm" action="<?php echo Route::_('index.php?option=com_search');?>" method="post">

	<?php /*<div class="searchintro<?php echo $this->params->get('pageclass_sfx'); ?>">
		<?php if (!empty($this->searchword)):?>
		<p><?php echo Text::plural('COM_SEARCH_SEARCH_KEYWORD_N_RESULTS', '<span class="badge badge-info">'. $this->total. '</span>');?></p>
		<?php endif;?>
	</div>*/ ?>

	<fieldset>
		<?php /*<legend><?php echo Text::_('COM_SEARCH_SEARCH_KEYWORD'); ?></legend>*/ ?>
		<div class="row collapse">
			<div class="small-12 medium-6 columns">
				<div class="input-group">
					<input type="hidden" name="task" value="search" />
					<input class="inputbox input-group-field" type="search" name="searchword" placeholder="<?php echo Text::_('COM_SEARCH_SEARCH_KEYWORD'); ?>" id="search-searchword" size="30" maxlength="<?php echo $upper_limit; ?>" value="<?php echo $this->escape($this->origkeyword); ?>">
					<div class="input-group-button">
					<button class="button postfix hasTooltip" name="Search" onclick="this.form.submit()" title="<?php echo Text::_('COM_SEARCH_SEARCH');?>"><span class="fa fa-search"></span></button>
					</div>
				</div>
			</div>
		</div>
	</fieldset>

	<div class="row">
		<?php if ($this->params->get('search_areas', 1)) : ?>
			<div class="small-12 medium-6 columns">
				<fieldset class="only">
				<legend><?php echo Text::_('COM_SEARCH_SEARCH_ONLY');?></legend>
				<?php foreach ($this->searchareas['search'] as $val => $txt) :
					$checked = is_array($this->searchareas['active']) && in_array($val, $this->searchareas['active']) ? 'checked="checked"' : '';
				?>
				<label for="area-<?php echo $val;?>" class="checkbox">
					<input type="checkbox" name="areas[]" value="<?php echo $val;?>" id="area-<?php echo $val;?>" <?php echo $checked;?> >
					<?php echo Text::_($txt); ?>
				</label>
				<?php endforeach; ?>
				</fieldset>
			</div>
		<?php endif; ?>

		<?php if ($this->params->get('search_phrases', 1)) : ?>
			<div class="small-12 medium-6 columns">
				<fieldset class="phrases">
					<legend><?php echo Text::_('COM_SEARCH_FOR');?></legend>
					<div class="phrases-box"><?php echo $this->lists['searchphrase']; ?></div>
				</fieldset>
			</div>
		<?php endif; ?>
	</div>

	<div class="row" style="display: none;">
		<div class="small-12 medium-6 columns ordering-box">
			<label for="ordering" class="ordering"><?php echo Text::_('COM_SEARCH_ORDERING');?></label>
			<?php echo $this->lists['ordering']; ?>
		</div>

		<?php if ($this->total > 0) : ?>
			<div class="small-12 medium-6 columns">
				<fieldset>
					<label><?php echo Text::_('JGLOBAL_DISPLAY_NUM'); ?></label>
					<div class="form-limit">
						<?php /*<label for="limit"><?php echo Text::_('JGLOBAL_DISPLAY_NUM'); ?></label>*/ ?>
						<?php echo $this->pagination->getLimitBox(); ?>
					</div>
				</fieldset>
			</div>
		<?php endif; ?>
	</div>


</form>
