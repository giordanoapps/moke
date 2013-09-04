<?php
session_start();

require("../classes/moke.php");
require("../classes/deezer.php");

if(isset($_GET["url"])) {
	$_SESSION["facebookReturnURL"] = $_GET["url"];
}

$moke = new moke();

$moke->initialize();

$ajaxReturn = new stdClass();

if($moke->user) {
	$ajaxReturn->auth = true;

	if(isset($_GET['sendmoke'])) {

		$deezer = new deezer();
		
		if(!isset($_SESSION["deezer_access_token"]) && isset($_GET["code"])){
			$deezer->initialize($_GET["code"]);
			$ajaxReturn->teste = true;
		}
		elseif(isset($_SESSION["deezer_access_token"])) {
			$deezer->accessToken = $_SESSION["deezer_access_token"];
			$deezer->getContent();
			$ajaxReturn->teste = true;
		}
		else {
			$ajaxReturn->teste = false;
		}

		$data = $moke->sendMoke($_GET,$deezer);

		$tracks = $data[0];

		$count = count($tracks);

		$selected = $data[1];

		$ajaxReturn->selected = $selected;
	}

	$received = $moke->firebase->GetReceivedMokes($moke->user);
    $sent = $moke->firebase->GetSentMokes($moke->user);

	$received = json_decode($received);
	$sent = json_decode($sent);

	$ajaxReturn->receivedMokes = $received;
	$ajaxReturn->sentMokes = $sent;

	$moke->requestFriends();

	$ajaxReturn->friends = $moke->friends;
}
else {
	$ajaxReturn->auth = false;
	$ajaxReturn->loginURL = $moke->loginURL;
}

if(isset($_GET["code"]) && isset($_GET["state"]))
	header("Location: ".$_SESSION["facebookReturnURL"]);

echo json_encode($ajaxReturn);

die();