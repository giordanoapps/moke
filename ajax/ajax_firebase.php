<?php
session_start();
require("../classes/firebase.php");

$firebase = new firebaseData();

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