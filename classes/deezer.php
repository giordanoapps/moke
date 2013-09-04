<?php

class deezer{

	public $accessToken;
	public $userId;
	public $oAuth;
	public $favoriteTracks;

	public function __construct(){

		$this->oAuth = "http://connect.deezer.com/oauth/auth.php?app_id=123703&redirect_uri=".urlencode("http://localhost/moke/ajax/deezer.php")."&perms=basic_access,email";
		$this->access_token = null;

	}

	public function initialize($code){
		$urlAccessToken = "http://connect.deezer.com/oauth/access_token.php?app_id=123703&secret=91c511cfd4b7aa2b2067d7f8733dd7d0&code=" . $code;

	    $ch = curl_init();
	    curl_setopt($ch,CURLOPT_URL,$urlAccessToken);
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 4);

	    $response = curl_exec($ch);

	    if($response == "wrong code") {
	        echo curl_error($ch);
	    }
	    curl_close($ch);
	    $params    = null;
	    parse_str($response, $params);

	    $this->accessToken = $params['access_token'];
	    $_SESSION["deezer_access_token"] = $this->accessToken;

	    $this->getContent();
	}

	public function getContent(){
		setcookie("deezer_access_token", $this->accessToken);
     	setcookie("deezer_access_token", $this->accessToken, time() + (10 * 365 * 24 * 60 * 60));  /* expire in 1 hour */

		$this->getUserId();
		$this->getUserFavoriteTracks();
	}

	public function getUserId(){
		$urlUser = "http://api.deezer.com/2.0/user/me?access_token=" . $this->accessToken;
	    $chs = curl_init();
	    curl_setopt($chs,CURLOPT_URL,$urlUser);
	    curl_setopt($chs,CURLOPT_RETURNTRANSFER,1);
	    curl_setopt($chs,CURLOPT_CONNECTTIMEOUT, 4);

	    $responseUser = curl_exec($chs);
	    if(!$responseUser) {
	        echo curl_error($chs);
	    }
	    curl_close($chs);
	    

	    $user = json_decode($responseUser, true);
	    $this->userId = $user['id'];
	    $_SESSION['deezerUserId'] = $user['id'];
	}

	public function getUserFavoriteTracks(){

		$urlPlaylist = "http://api.deezer.com/2.0/user/". $this->userId . "/tracks?access_token=" . $this->accessToken;
    	$chs = curl_init();
    	curl_setopt($chs,CURLOPT_URL,$urlPlaylist);
    	curl_setopt($chs,CURLOPT_RETURNTRANSFER,1);
    	curl_setopt($chs,CURLOPT_CONNECTTIMEOUT, 4);

    	$responseFavorites = curl_exec($chs);
    	if(!$responseFavorites) {
        	echo curl_error($chs);
    	}
    	curl_close($chs);
    	$dados = json_decode($responseFavorites);
    	$this->favoriteTracks = $dados->data;
	}

}