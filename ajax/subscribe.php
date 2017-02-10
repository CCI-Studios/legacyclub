<?php
require("../vendor/autoload.php");
$config = require("../config.php");

use \DrewM\MailChimp\MailChimp;
$MailChimp = new MailChimp($config['MAILCHIMP_API_KEY']);
$result = $MailChimp->post('lists/'.$config['MAILCHIMP_LIST_ID'].'/members', array(
  'email_address' => $_POST['email'],
	'status' => 'subscribed',
  'merge_fields' => array(
		'FNAME'=>$_POST["fname"],
		'LNAME'=>$_POST["lname"]
	)
));

if (!$MailChimp->success()){
	echo "<h4>Please try again.</h4>";
} else {
	echo "<h4>Thank you, you have been added to our mailing list.</h4>";
}
?>