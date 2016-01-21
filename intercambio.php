<?php
require 'email/PHPMailerAutoload.php';
require 'email.php';

$mapEmails = array (
	array ( 
		'name' => 'Joe Doe', 
		'email' => 'joedoe@email.com'
	), 
	array ( 
		'name' => 'Cool Many', 
		'email' => 'coolmany@email.com'
	),
	array ( 
		'name' => 'Juan Perez', 
		'email' => 'juanperez@email.com'
	),
	array ( 
		'name' => 'Max Power', 
		'email' => 'maxpower@email.com'
	), 
	array ( 
		'name' => 'Laura Doe', 
		'email' => 'lauradoe@email.com'
	), 
);

shuffle($mapEmails);
shuffle($mapEmails);
shuffle($mapEmails);

$sSubject = '! Santa Secreto de:...';

$total = count($mapEmails);

echo $total . '<br>' . PHP_EOL;

for($i=0; $i<$total; $i++) {
	$dude = $mapEmails[$i]['name'];
	$dudeMail = $mapEmails[$i]['email'];
	if ($i+1 !== $total) {
		$selectedFriend = $mapEmails[$i+1]['name'];
	}
	else {
		$selectedFriend = $mapEmails[0]['name'];
	}
	$mail = new Email();
	// $emailSent = $mail->sendMail('luar007@gmail.com', $sSubject, 'default', $params = array('from'=>$dude,'to'=>$selectedFriend));
	$emailSent = $mail->sendMail($dudeMail, $sSubject, 'default', $params = array('from'=>$dude,'to'=>$selectedFriend));
	if ($emailSent !== true)
		die(var_dump($emailSent));
	else
		echo 'email success: ' . $i . ' <br> ' . PHP_EOL;
}
