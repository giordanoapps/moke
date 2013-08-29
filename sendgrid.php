<?php 

include 'sendgrid-php/SendGrid_loader.php';


class sendEmail{


	public static function sendEmailToFriend($emailFrom, $emailTo, $subject, $text, $html){

		$sendgrid = new SendGrid('ricardo.parro@gmail.com', 'moke2013');

		$mail = new SendGrid();
		$mail->
  		addTo($emailTo)->
  		setFrom($emailFrom)->
  		setSubject($subject)->
  		setText($text)->
  		setHtml($html);

		$sendgrid->web->send($mail);
	}
}

