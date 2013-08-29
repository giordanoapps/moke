<?php 

include 'sendgrid-php/SendGrid_loader.php';




class sendEmail{
	public function __construct(){
		
	}

	public function sendEmailToFriend($emailFrom, $emailTo, $subject, $text, $html){

		$urlSendGrid = "http://sendgrid.com/api/mail.send.json?api_user=ricardo.parro&api_key=moke2013&to[]=ricardo.parro@gmail.com&toname[]=Ricardo&subject=". 
		urlencode($subject) ."&text=". urlencode($text) ."&html=". urlencode($html) ."&from=info@domain.co";
	   
	    $chs = curl_init();
	    curl_setopt($chs,CURLOPT_URL,$urlSendGrid);
	    curl_setopt($chs,CURLOPT_RETURNTRANSFER,1);
	    curl_setopt($chs,CURLOPT_CONNECTTIMEOUT, 4);

	    $responseEmail = curl_exec($chs);
	    if(!$responseEmail) {
	        echo curl_error($chs);
	    }
	    curl_close($chs);
		
	}
}

