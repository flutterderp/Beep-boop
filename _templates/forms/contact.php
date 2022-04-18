<?php
defined('_JEXEC') or die;
define('LIB_PATH', realpath(__DIR__ . '/..'));

use Joomla\CMS\Factory;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Mail\Mail;
use Joomla\CMS\Uri\Uri;

require_once(LIB_PATH . '/libraries/recaptcha/vendor/autoload.php');

/**
 * @link https://www.google.com/recaptcha/admin
 *
 * Each site will need its own set of keys for the v2 API. Multiple domains can be set for each site
 * (e.g. non-www, www, host-based subdomain).
 *
 * When creating the recaptcha property, be sure to check the box to enable “invisible captcha”
 */

$app             = Factory::getApplication();
$doc             = Factory::getDocument();
$tpl_params      = $app->getTemplate(true)->params;
$resp            = null;
$jinput          = $app->input;
$jpost           = $jinput->post;
$jpost_recaptcha = $jpost->get('g-recaptcha-response', '', 'string');
$jpost_name      = $jpost->get('txt_name', '', 'string');
$jpost_email     = $jpost->get('txt_email', '', 'string');
$jpost_phone     = $jpost->get('txt_phone', '', 'string');
$jpost_comments  = $jpost->get('txt_comments', '', 'string');
$jpost_from_ip   = $jpost->get('from_ip', '', 'string');
$jpost_form_id   = $jpost->get('form_id', '', 'string');
$honeypot        = $jpost->get('a_password', '', 'string');
$doc->addScript('https://www.google.com/recaptcha/api.js', null, array('defer' => true));

if((count($_POST) > 0) && ($jpost_form_id == 'contact_form'))
{
	$recaptcha = new \ReCaptcha\ReCaptcha($tpl_params->get('grc_secretkey'));
	// Alternate instantiation if allow_url_fopen is off and cannot be overridden
	// $recaptcha = new \ReCaptcha\ReCaptcha($tpl_params->get('grc_secretkey'), new \ReCaptcha\RequestMethod\CurlPost());
	$resp      = $recaptcha->verify($jpost_recaptcha, $jpost_from_ip);
	$errors    = array();

	// $errors[] = 'Forced error';

	if((($resp != null) && !$resp->isSuccess()) || ($resp == null))
	{
		foreach($resp->getErrorCodes() as $recap_err)
		{
			$errors[] = 'reCAPTCHA error: ' . JText::_('RECAPTCHA_' . $recap_err);
		}
	}
	if(!empty($honeypot))
	{
		$errors[] = 'Unknown error';
	}
	if(empty($jpost_name))
	{
		$errors[] = '<b>[missing]</b> Please enter your name';
	}
	if(empty($jpost_email))
	{
		$errors[] = '<b>[missing]</b> Please enter your email address';
	}
	if(empty($jpost_comments))
	{
		$errors[] = '<b>[missing]</b> Please enter a brief message';
	}
	//elseif( !preg_match( "/^([A-Za-z0-9.]{1,})@([A-Za-z0-9\.\-]{3,})\.([a-z\.]{2,4})$/", $jpost_email) )
	if(!empty($jpost_email) && !preg_match("/^([\w\.\-\+]{1,})@([\w\.\-]{2,})\.([A-Za-z]{2,10})$/", $jpost_email))
	{
		$errors[] = '<b>[invalid]</b> &ldquo;' . htmlspecialchars($jpost_email) . '&rdquo; is not a valid email address';
	}
	if(!empty($jpost_phone) && !preg_match("/^([\d\s\(\)\-\+]{1,18})$/", $jpost_phone))
	{
		$errors[] = '<b>[invalid]</b> &ldquo;' . htmlspecialchars($jpost_phone) . '&rdquo; is not a valid phone number';
	}

	if(!empty($errors))
	{
		foreach($errors as $error)
		{
			$app->enqueueMessage($error, 'error');
		}
	}
	else
	{
		$sitename  = $app->get('sitename');
		$name      = stripslashes($jpost_name);
		$email     = stripslashes($jpost_email);
		$phone     = stripslashes($jpost_phone);
		$message   = stripcslashes($jpost_comments);
		$subject   = 'New contact form submission from ' . $name . ' via ' . $sitename;
		$from_ip   = $jpost_from_ip;

		$body = '<p>';
		$body .= '<b>Name:</b> ' . $name . '<br>';
		$body .= '<b>Email:</b> ' . $email . '<br>';

		if($phone)
		{
			$body .= '<b>Phone:</b> ' . $phone . '<br>';
		}

		$body .= '</p>';
		$body .= '<p><b>Message:</b><br>' . nl2br($message) . '</p>';
		$body .= '<hr style="border: 0; border-top: 1px dashed #ccc; clear: both; height: 1px; margin: 15px 0;">';
		$body .= '<p><small>As best as we could tell, this request came from ';
		$body .= '<a href="https://tools.keycdn.com/geo?host=' . urlencode($from_ip) . '" target="_blank" rel="noopener noreferrer">';
		$body .= $from_ip . '.</small></p>';

		// Jooma's JMail class is just an extension of PHPMailer, so:
		$recipient        = array('development@southernanime.com', 'Developer');
		$mailer           = Mail::getInstance();
		$mailer->From     = $app->get('mailfrom');
		$mailer->FromName = $app->get('fromname');
		$mailer->clearReplyTos();
		$mailer->addAddress($recipient[0], $recipient[1]);
		$mailer->addReplyTo($email, $name);
		// $mailer->addBCC('development@southernanime.com');
		// $mailer->addAttachment('');
		$mailer->isHTML(true);
		$mailer->Subject  = $subject;
		$mailer->Body     = $body;
		$alt_body         = preg_replace('/(<br\s*\/{0,1}>)/', "\n", $mailer->Body);
		$mailer->AltBody  = strip_tags($alt_body);
		$mailer->LE       = PHP_EOL;
		$mailer->Encoding = 'base64';
		$mailer->CharSet  = 'UTF-8';

		/* // This should be the same as the domain of your From address
		$mailer->DKIM_domain = 'example.com';
		// See the DKIM_gen_keys.phps script for making a key pair - here we assume you've already done that.
		// https://github.com/PHPMailer/PHPMailer/blob/master/examples/DKIM_gen_keys.phps
		// Path to your private key:
		$mailer->DKIM_private = dirname(JPATH_ROOT) . '/dkim_private.pem';
		// Set this to your own selector
		$mailer->DKIM_selector = 'phpmailer';
		// Put your private key's passphrase in here if it has one
		$mailer->DKIM_passphrase = '';
		// The identity you're signing as - usually your From address
		$mailer->DKIM_identity = $mailer->From;
		// Suppress listing signed header fields in signature, defaults to true for debugging purpose
		$mailer->DKIM_copyHeaderFields = false;
		// Optionally you can add extra headers for signing to meet special requirements
		$mailer->DKIM_extraHeaders = ['List-Unsubscribe', 'List-Help']; */

		try
		{
			$mailer->send();

			$app->enqueueMessage('Your message has been sent.', 'success');
			// $app->redirect(Uri::root() . 'thank-you', null, null, false);
		}
		catch(\Joomla\CMS\Mail\Exception\MailDisabledException $e)
		{
			$app->enqueueMessage('Mail Error: [' . $e->getCode() . '] ' . $e->getMessage(), 'error');
		}
		catch(Exception $e)
		{
			$app->enqueueMessage('Mail Error: [' . $e->getCode() . '] ' . $e->getMessage(), 'error');
		}
	}
}

if(!isset($_POST['from_ip']) || !empty($errors)) : ?>
	<form action="<?php echo OutputFilter::ampReplace(Uri::current()); ?>#system-message-container" method="post"
		id="contact_form" enctype="multipart/form-data">
		<input tabindex="0" type="text" name="a_password" autocomplete="new-password" aria-hidden="true">

		<div class="grid-container">
			<div class="grid-x grid-padding-x small-up-1 medium-up-2">
				<div class="cell">
					<label class="required">
						Name
						<input tabindex="0" type="text" id="txt_name" name="txt_name" value="<?php echo $jpost_name; ?>" required>
					</label>
				</div>

				<div class="cell">
					<label class="required">
						Email
						<input tabindex="0" type="email" id="txt_email" name="txt_email" value="<?php echo $jpost_email; ?>" required>
					</label>
				</div>

				<div class="cell">
					<label>
						Phone
						<input tabindex="0" type="tel" id="txt_phone" name="txt_phone" value="<?php echo $jpost_phone; ?>">
					</label>
				</div>

				<div class="cell">
					<label class="required">
						Comments / Brief message
						<textarea tabindex="0" type="textarea" rows="4" id="txt_comments" name="txt_comments" required><?php echo $jpost_comments; ?></textarea>
					</label>
				</div>
			</div>

			<div class="grid-x grid-padding-x">
				<div class="cell small-12">
					<input type="hidden" name="form_id" value="contact_form">
					<input type="hidden" name="from_ip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>">

					<div id="recaptcha" class="g-recaptcha" data-sitekey="<?php echo $tpl_params->get('grc_sitekey'); ?>"
						data-callback="onSubmit" data-badge="inline" data-size="invisible"></div>
					<button class="button" id="submit_btn" tabindex="0" type="submit">Submit <span class="far fa-envelope"></span></button>
					<span class="fa fa-spinner fa-spin hide" id="spinner"></span>
				</div>
			</div>
		</div>
	</form>

	<script>
		let contact_form = document.getElementById('contact_form'),
		    submit_btn   = document.getElementById('submit_btn'),
		    spinner      = document.getElementById('spinner');

		contact_form.addEventListener('submit', function(e) {
			e.preventDefault();

			submit_btn.classList.add('hide');
			spinner.classList.remove('hide');

			grecaptcha.execute();
		})

		var onSubmit = function(token) { contact_form.submit(); };
	</script>
<?php else : ?>
	<p>&nbsp;</p>
<?php endif; ?>
