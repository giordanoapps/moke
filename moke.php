<?php
require("facebook-php-sdk-master/src/facebook.php");

class moke {

	private $facebook;

	public 	$user;
	public 	$loginURL;
	public 	$friends;

	public function __construct(){
		$this->facebook = new Facebook(array(
			'appId'  => '433999643379630',
			'secret' => '4406fdb6377380765834ab6f7387a229',
		));

		if(isset($_SESSION["friends"]))
			$this->friends = $_SESSION["friends"];
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
			$this->loginURL = $this->facebook->getLoginUrl();
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

					if($this->friends[$i]["name"] > $this->friends[$j]["name"]) {
						$aux = $this->friends[$j];
						$this->friends[$j] = $this->friends[$i];
						$this->friends[$i] = $aux;
					}

				}
			}

			$_SESSION["friends"] = $this->friends;
		}

	}

	public function sendMoke($params){

		$friend = $params["friend"];
		$url 	= "http://www.deezer.com/track/2423901";
		$title 	= "fasga";
		$band 	= "fasga";

		$data = array();

		$data['message'] = "Hey!\nCheck out this Moke!";
		$data['tags']= $friend;
		$data['place']='155021662189'; 

		$data['link'] = $url;
		$data['description'] = $title.' - '.$band;
		$data['caption'] = "Join ";

		$ret_obj = $this->facebook->api('/me/feed', 'POST', $data);

		if($ret_obj)
			header("Location: index.php#pokesent");

	}

}

?>