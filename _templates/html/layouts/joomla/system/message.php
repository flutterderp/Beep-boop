<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

$msgList      = $displayData['msgList'];
$joomla_types = array('error', 'notice', 'info', 'message');
$zurb_types   = array('alert', 'primary', 'primary', 'primary');
$show_heading = array('error', 'notice');
?>
<div class="system-message-container" id="system-message-container">
	<?php if (is_array($msgList) && !empty($msgList)) : ?>
		<div id="system-message">
			<?php foreach ($msgList as $type => $msgs) : ?>
				<div class="callout <?php echo str_ireplace($joomla_types, $zurb_types, $type) ?>" data-closable>
					<?php if (!empty($msgs)) : ?>
						<?php if(in_array($type, $show_heading)) : ?>
							<div class="system-message-header"><?php echo Text::_(ucwords($type)); ?></div>
						<?php endif; ?>

						<?php foreach ($msgs as $msg) : ?>
							<p><?php echo $msg; ?></p>
						<?php endforeach; ?>
					<?php endif; ?>

					<?php // This requires JS so we should add it trough JS. Progressive enhancement and stuff. ?>
					<button class="close-button" aria-label="Dismiss alert" type="button" data-close><span aria-hidden="true">&times;</span></button>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
