<?php
session_start();

require_once("../config.php"); 
require("../classes/moke.php");
require("../classes/deezer.php");


if(isset($_GET["url"])) {
	$_SESSION["facebookReturnURL"] = $_GET["url"];
}

if(isset($_SESSION["moke"])) {
	$moke = unserialize($_SESSION["moke"]);
}
else {
	$moke = new moke($CONFIG['APIS']['facebook']['appId'],  $CONFIG['APIS']['facebook']['secret'], $CONFIG["APIS"]["firebase"]["url"], $CONFIG["APIS"]["firebase"]["token"]);

	$moke->initialize();
}

if(isset($_GET["destroy"])) {
	$moke->finalize();

	echo "true";

	die();
}

$ajaxReturn = new stdClass();

if($moke->user) {
	$ajaxReturn->auth = true;

	if(isset($_GET['sendmoke'])) {

		$deezer = new deezer($CONFIG["APIS"]["deezer"]['appId'],$CONFIG["APIS"]["deezer"]['secret']);
		
		if(!isset($_SESSION["deezer_access_token"]) && isset($_GET["code"])){
			$deezer->initialize($_GET["code"]);
			//$ajaxReturn->teste = true;
		}
		elseif(isset($_SESSION["deezer_access_token"])) {
			$deezer->accessToken = $_SESSION["deezer_access_token"];
			$deezer->getContent();
			//$ajaxReturn->teste = true;
		}
		else {
			//$ajaxReturn->teste = false;
		}

		$data = $moke->sendMoke($_GET,$deezer);

		if(@($data == false)) {
			echo "Error";
			die();
		}

		$tracks = $data[0];

		$count = count($tracks);

		$selected = $data[1];

		$ajaxReturn->selected = $selected;

		echo json_encode($ajaxReturn);

		die();
	}

	$received = $moke->firebase->GetReceivedMokes($moke->user);
    $sent = $moke->firebase->GetSentMokes($moke->user);

	$received = json_decode($received);
	$sent = json_decode($sent);

	$ajaxReturn->receivedMokes = $received;
	$ajaxReturn->sentMokes = $sent;
	$ajaxReturn->facebookUser = $moke->user;

	$moke->requestFriends();

	$_SESSION["moke"] = serialize($moke);

	$ajaxReturn->friends = $moke->friends;
}
else {
	$ajaxReturn->auth = false;
	$ajaxReturn->loginURL = $moke->loginURL;
	$_SESSION['facebookUser'] = $moke->user;
}

if(isset($_GET["code"]) && isset($_GET["state"]))
	header("Location: ".$_SESSION["facebookReturnURL"]);

echo json_encode($ajaxReturn);

die();