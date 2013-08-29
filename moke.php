<?php
require("facebook-sdk/src/facebook.php");
require("sendgrid.php");
require("firebase.php");

class moke {

	private $facebook;

	public 	$user;
	public 	$loginURL;
	public 	$friends;
	public  $firebase;

	public function __construct(){
		$this->facebook = new Facebook(array(
			'appId'  => '433999643379630',
			'secret' => '4406fdb6377380765834ab6f7387a229',
		));

		if(isset($_SESSION["friends"]))
			$this->friends = $_SESSION["friends"];

		$this->firebase = new firebaseData();
	
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

	}

	public function finalize(){

		$this->facebook->destroySession();
		session_destroy();
		header("Location: index.php");

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

			$_SESSION["friends"] = $this->friends;
		}

	}

	public function sendMoke($params, $deezer){

		$size = count($deezer->favoriteTracks);

		$random = mt_rand(0, $size-1);

		$track = $deezer->favoriteTracks[$random];

		$friend = $params["friend"];
		$url 	= $track->link;
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


		//Firebase SET DATA

		$now = new DateTime();

		$sentMoke = array(
		  'deezerUserId' => '' . $_SESSION['deezerUserId'] .'' ,
		  'artist' => $band,
		  'track' => $title,
		  'trackId' => $trackId,
		  'albumImage' => $url,
		  'date' => $now->format('Y-m-d H:i:s'),
		  'receiversFacebookIds' => $friend
		);

		$receivedMoke = array(
		  'deezerUserId' => '' . $_SESSION['deezerUserId'] .'' ,
		  'artist' => $band,
		  'track' => $title,
		  'trackId' => $trackId,
		  'albumImage' => $url,
		  'date' => $now->format('Y-m-d H:i:s'),
		  'senderFacebookId' => $this->user
		);

		$this->firebase->SetMoke($this->user, $sentMoke, $friend, $receivedMoke, $now);


		//Send Grid send email

		$sendEmail = new sendEmail();
		$sendEmail::sendEmailToFriend('ricardo@printi.com.br', 'ricardo.parro@gmail.com', "Your friend Mauricio sent you a moke", 
		'<span>' . "Your friend Mauricio sent you a moke</span><br /><br /><span>", $data['message'] . ' ====> ' . $band  . ' - ' . $title 
			. ' : ' . $url . '</span><br /><br /><img style="width:200px" src="'. $track->album->cover.'" />');	

		$ret_obj = $this->facebook->api('/me/feed', 'POST', $data);

		$params = array();
		array_push($params, $deezer->favoriteTracks);
		array_push($params, $random);


		return $params;
	}

}

?>