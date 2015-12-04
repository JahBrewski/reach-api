<?php
//require_once 'mandrill-api-php/src/Mandrill.php'; //Not required with Composer
require_once 'vendor/mandrill/mandrill/src/Mandrill.php';

define("MANDRILL_USERNAME","lappin@brewerdigital.com");
define("MANDRILL_API_KEY","EeAZX8VE5DnZtYVUPBPLEA");
define("EMAIL_FROM_ADDRESS","support@reachplatform.io");
define("EMAIL_FROM_NAME","Reach App Support");
define("EMAIL_REPLYTO_ADDRESS","support@reachplatform.io");

class Mailer {


	var $mandrill;

	function Mailer(){
		$this->mandrill = new Mandrill(MANDRILL_API_KEY);
	}

	
	function send_welcome_email($to_address,$to_name,$password) {

		try {
		    $message = array(
			'html' => '<p>Welcome to Reach</p><p>Your password is ' . $password,
			'text' => 'Welcome to Reach, your password is ' . $password,
			'subject' => 'Welcome to Reach',
			'from_email' => EMAIL_FROM_ADDRESS,
			'from_name' => EMAIL_FROM_NAME,
			'to' => array(
			    array(
				'email' => $to_address,
				'name' => $to_name,
				'type' => 'to'
			    )
			),
			'headers' => array('Reply-To' => EMAIL_REPLYTO_ADDRESS),
			'important' => false,
			'track_opens' => null,
			'track_clicks' => null,
			'auto_text' => null,
			'auto_html' => null,
			'inline_css' => null,
			'url_strip_qs' => null,
			'preserve_recipients' => null,
			'view_content_link' => null,
			//'bcc_address' => '',
			'tracking_domain' => null,
			'signing_domain' => null,
			'return_path_domain' => null,
			'merge' => true,
			'merge_language' => 'mailchimp',
		/*
			'global_merge_vars' => array(
			    array(
				'name' => 'merge1',
				'content' => 'merge1 content'
			    )
			),
			'merge_vars' => array(
			    array(
				'rcpt' => 'another.user@domain.com',
				'vars' => array(
				    array(
					'name' => 'merge2',
					'content' => 'merge2 content'
				    )
				)
			    )
			),
			'tags' => array('test-emails'),
			'subaccount' => 'customer-123',
			'google_analytics_domains' => array('example.com'),
			'google_analytics_campaign' => 'message.from_email@example.com',
			'metadata' => array('website' => 'www.example.com'),
			'recipient_metadata' => array(
			    array(
				'rcpt' => 'recipient.email@example.com',
				'values' => array('user_id' => 123456)
			    )
			),
			'attachments' => array(
			    array(
				'type' => 'text/plain',
				'name' => 'myfile.txt',
				'content' => 'ZXhhbXBsZSBmaWxl'
			    )
			),
			'images' => array(
			    array(
				'type' => 'image/png',
				'name' => 'IMAGECID',
				'content' => 'ZXhhbXBsZSBmaWxl'
			    )
			)
		*/
		    );
		    $async = false;
		    $ip_pool = 'Main Pool';
		    $hour_ago = date('Y-m-d H:i:s', strtotime('-1 hour'));
		    //$send_at = $hour_ago;
		    $send_at = null;
		    //$send_at = gmdate('Y-m-d h:i:s \G\M\T', time());
		    $result = $this->mandrill->messages->send($message, $async, $ip_pool, $send_at);
		    print_r($result);
		} catch(Mandrill_Error $e) {
		    // Mandrill errors are thrown as exceptions
		    //echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
		    // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
		    throw $e;
		}

	}
}
?>
