<?php
require("../sdks/facebook-sdk/src/facebook.php");
require("sendgrid.php");
require("firebase.php");

class moke {

	private $facebook;

	public 	$user;
	public 	$loginURL;
	public 	$friends;
	public  $firebase;
	public function __construct($appId, $secret, $firebaseUrl, $firebaseToken){
		
		$this->facebook = new Facebook(array(
			'appId'  => $appId,
			'secret' => $secret,
		));

		$this->firebase = new firebaseData($firebaseUrl, $firebaseToken);
	
	}

	public function initialize(){

		$this->user = $this->facebook->getUser();

		if($this->user) {
			try {
				$this->facebook->api('/me');
			} catch (FacebookApiException $e) {
				error_log($e);
				$this->user = null;
			}
		}
		else {
			$this->loginURL = $this->facebook->getLoginUrl(array('scope' => 'email, publish_stream'));
		}
		$_SESSION['facebookUser'] = $this->user;

	}

	public function finalize(){

		$this->facebook->destroySession();
		session_destroy();

	}

	public function requestFriends(){

		if($this->friends == null) {

			$this->friends 	= array();
			$list 			= $this->facebook->api('/me/friends');

			foreach($list as $friends) {
				foreach($friends as $friend) {
					array_push($this->friends,$friend);
				}
			}

			$count = count($this->friends);

			for($i=0;$i<$count;$i++) {
				for($j=$i+1;$j<$count;$j++) {

					if(@strcmp($this->friends[$i]["name"], $this->friends[$j]["name"]) > 0) {
						$aux = $this->friends[$j];
						$this->friends[$j] = $this->friends[$i];
						$this->friends[$i] = $aux;
					}

				}
			}
			
		}

	}

	public function sendMoke($params, $deezer){

		$size = count($deezer->favoriteTracks);

		$random = mt_rand(0, $size-1);

		$track = $deezer->favoriteTracks[$random];

		$friend = $params["friend"];
		$url 	= $track->link;
		$fotoCover = $track->album->cover;
		$title 	= $track->title;
		$band 	= $track->artist->name;
		$trackId = $track->id;

		$data = array();

		$data['message'] = "Hey!\nCheck out this Moke!";
		$data['tags']= $friend;
		$data['place']='155021662189'; 

		$data['link'] = $url;
		$data['description'] = $title.' - '.$band;
		$data['caption'] = "Join ";

		$ret_obj = $this->facebook->api('/me/feed', 'POST', $data);

		if(!$ret_obj)
			return false;

		$me = $this->facebook->api('/me');
		$name = $me['name'];

		//Firebase SET DATA

		$now = new DateTime();

		$sentMoke = array(
		  'deezerUserId' => '' . $_SESSION['deezerUserId'] .'' ,
		  'artist' => $band,
		  'track' => $title,
		  'trackId' => $trackId,
		  'albumImage' => $fotoCover,
		  'date' => $now->format('y-m-d'),
		  'receiversFacebookIds' => $friend,
		  'senderName' => $name
		);

		$receivedMoke = array(
		  'deezerUserId' => '' . $_SESSION['deezerUserId'] .'' ,
		  'artist' => $band,
		  'track' => $title,
		  'trackId' => $trackId,
		  'albumImage' => $fotoCover,
		  'date' => $now->format('y-m-d'),
		  'senderFacebookId' => $this->user,
		  'senderName' => $name
		);

		$this->firebase->SetMoke($this->user, $sentMoke, $friend, $receivedMoke, $now);

		$params = array();
		array_push($params, $deezer->favoriteTracks);
		array_push($params, $random);

		return $params;
	}

}