<?php
session_start();
require("../classes/firebase.php");
require("../config.php"); 

$firebase = new firebaseData($CONFIG["APIS"]["firebase"]["url"], $CONFIG["APIS"]["firebase"]["token"]);

$method = $_GET['method'];
$facebookId = $_SESSION['facebookUser'];
$response = null;

if($method == 'received'){
	$response = $firebase->GetReceivedMokes($facebookId); 
}

if($method == 'sent'){
	$response = $firebase->GetSentMokes($facebookId);	
}
echo($response);