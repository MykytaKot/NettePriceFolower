<?php
namespace Cron;
require __DIR__ . '/../vendor/autoload.php';
use Nette;

$mail = new Nette\Mail\Message;
$mail->setFrom('John <john@example.com>')
	->addTo('peter@example.com')
	->addTo('jack@example.com')
	->setSubject('Order Confirmation')
	->setBody("Hello, Your order has been accepted.");

$mailer = new Nette\Mail\SmtpMailer(
    host: 'sandbox.smtp.mailtrap.io',
    username: 'b026708b335372',
    password: 'a24eea3e2da932',
    port: '2525'
);
$mailer->send($mail);
