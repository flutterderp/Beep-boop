<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

$msgList      = $displayData['msgList'];
$joomla_types = array('error', 'notice', 'info', 'message');
$zurb_types   = array('error', 'notice', 'info', 'message');
?>
<div id="system-message-container">
	<?php if (is_array($msgList) && !empty($msgList)) : ?>
		<div id="system-message">
			<?php foreach ($msgList as $type => $msgs) : ?>
				<div class="callout <?php echo str_ireplace($joomla_types, $zurb_types, $type) ?>" data-closable>
					<?php if (!empty($msgs)) : ?>
						<h4><?php echo JText::_(ucwords($type)); ?></h4>
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
