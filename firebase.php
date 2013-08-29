<?php

require_once 'firebase-sdk/firebaseLib.php';

class firebaseData{
	private $fb;

	public function __construct(){

			$urlFire = 'https://moke.firebaseio.com/';
			$token = 'AFpIBjmV19PRcnAZkoXLywv8iDLLDsoAp6TEmXlP';

			$this->fb = new fireBase($urlFire, $token);
			
		
	}

	public function SetMoke($senderUserFacebookId, $sentMoke, $receiverFacebookIds, $receivedMoke, $now){

		$todoPath = '/Sent/'. $senderUserFacebookId . '/' . $now->format('YmdHis');
		//set sender data
		$responseSenders = $this->fb->set($todoPath, $sentMoke);

		$arrayFriendsReceivers = explode(',', $receiverFacebookIds);

		$max = sizeof($arrayFriendsReceivers);

		for ($i=0; $i < $max; $i++) { 
		
		$receiversPath = '/Received/'. $arrayFriendsReceivers[$i] . '/' . $now->format('YmdHis');
		//set receivers data
		$responseReceivers = $this->fb->set($receiversPath, $receivedMoke);
		}
	}

	public function GetReceivedMokes($facebookId){

		$responseGet = $this->fb->get('/Received/'. $facebookId . '');

		return $responseGet;
	}

	public function GetSentMokes($facebookId){

		$responseGet = $this->fb->get('/Sent/'. $facebookId . '');

		return $responseGet;
	}
}