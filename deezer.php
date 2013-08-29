<?php


class deezer{

	public function __construct(){

		$this->oAuth = "http://connect.deezer.com/oauth/auth.php?app_id=123703&redirect_uri=http://localhost/moke&perms=basic_access,email";

	}

	public function getAccesstoken($code){
		$urlAccessToken = "http://connect.deezer.com/oauth/access_token.php?app_id=123703&secret=91c511cfd4b7aa2b2067d7f8733dd7d0&code=" . $code;

	    $ch = curl_init();
	    curl_setopt($ch,CURLOPT_URL,$urlAccessToken);
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 4);

	    $response = curl_exec($ch);
	    if(!$response) {
	        echo curl_error($ch);
	    }
	    curl_close($ch);
	    $params    = null;
	    parse_str($response, $params);

	    return $params['access_token'];
	}

	public function getUserId($accessToken){
		$urlUser = "http://api.deezer.com/2.0/user/me?access_token=" . $accessToken;
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
	    return $user['id'];
	}

	public function getUserFavoriteTracks($deezerUserId, $accessToken){
		$urlPlaylist = "http://api.deezer.com/2.0/user/". $deezerUserId . "/tracks?access_token=" . $accessToken;
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
    	var_dump($dados->data);   ///[0]->link;
    	return $dados->data;
	}
}