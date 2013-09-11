<?php
session_start();

require("../classes/deezer.php");
require_once("../config.php"); 

if(isset($_GET["url"])) {
	$_SESSION["deezerReturnURL"] = $_GET["url"];
}

if(isset($_SESSION["deezer"])) {
	$deezer = unserialize($_SESSION["deezer"]);
}
else {
	$deezer = new deezer($CONFIG["APIS"]["deezer"]['appId'], $CONFIG["APIS"]["deezer"]['secret']);
}

$ajaxReturn = new stdClass();

if(!isset($_SESSION["deezer_access_token"]) && isset($_GET["code"])){

	if(!isset($_SESSION["deezer"])){
		$deezer->initialize($_GET["code"]);

		$_SESSION["deezer"] = serialize($deezer);
	}

	$ajaxReturn->auth = true;
	$ajaxReturn->tracks = $deezer->favoriteTracks;
}
elseif(isset($_SESSION["deezer_access_token"])) {

	if(!isset($_SESSION["deezer"])){
		$deezer->accessToken = $_SESSION["deezer_access_token"];
		$deezer->getContent();

		$_SESSION["deezer"] = serialize($deezer);
	}

	$ajaxReturn->auth = true;
	$ajaxReturn->tracks = $deezer->favoriteTracks;
}
else {
	$ajaxReturn->auth = false;
	$ajaxReturn->loginURL = $deezer->oAuth;
}

if(isset($_GET["code"]))
	header("Location: ".$_SESSION["facebookReturnURL"]);

echo json_encode($ajaxReturn);

die();