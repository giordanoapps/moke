<?php
session_start();

require("../classes/deezer.php");
require_once("../config.php"); 

if(isset($_GET["url"])) {
	$_SESSION["deezerReturnURL"] = $_GET["url"];
}

$deezer = new deezer($CONFIG["APIS"]["deezer"]['appId'], $CONFIG["APIS"]["deezer"]['secret']);

$ajaxReturn = new stdClass();

if(!isset($_SESSION["deezer_access_token"]) && isset($_GET["code"])){
	$deezer->initialize($_GET["code"]);

	$ajaxReturn->auth = true;
	$ajaxReturn->tracks = $deezer->favoriteTracks;
}
elseif(isset($_SESSION["deezer_access_token"])) {
	$deezer->accessToken = $_SESSION["deezer_access_token"];
	$deezer->getContent();

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