<?php
if(isset($_POST['code'])){
  header('Location: http://localhost/moke');
}
session_start();
require("../moke.php");
require("../deezer.php");


$moke = new moke();

$moke->initialize();

if(isset($_POST['destroy']))
    $moke->finalize();

if($moke->user) {

 $moke->requestFriends();

}
//Deezer setup 
$deezer = new deezer();

if(!isset($_POST["state"])) {

  if(!isset($_SESSION["deezer_access_token"]) && isset($_POST["codeDeezer"])){

    $deezer->initialize($_POST["codeDeezer"]);

  }
  elseif(isset($_SESSION["deezer_access_token"])) {

    $deezer->accessToken = $_SESSION["deezer_access_token"];
    $deezer->getContent();

  }

}

$tracks = null;

if($moke->user){

  if(isset($_POST['sendmoke'])) {
    $data = $moke->sendMoke($_POST,$deezer);

    $tracks = $data[0];
    $count = count($tracks);

    $selected = $data[1];

    // $i = 0;
    // foreach($tracks as $track){
    //   if($i == $selected)
    //     echo '<div style="display:none" data-i="'.$selected.'" class="random selected" data-title="'.$track->title.'" data-artist="'.$track->artist->name.'" data-cover="'.$track->album->cover.'"></div>';
    //   else
    //     echo '<div style="display:none" class="random" data-cover="'.$track->album->cover.'" data-title="'.$track->title.'" data-artist="'.$track->artist->name.'"></div>';

    //   $i++;
    // }
  }

}

$mokeArray = (array)$moke;
//$tracksArray = (array)$tracks;
$deezerArray = (array)$deezer;
//$resAux = array_merge_recursive($mokeArray['user'] , $tracksArray);
$res = array_merge_recursive($mokeArray, $deezerArray);
$jsonResponse = json_encode($res);

echo($jsonResponse);
die();