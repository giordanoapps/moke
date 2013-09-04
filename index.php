<?php

session_start();
require("moke.php");
require("deezer.php");

var_dump($_GET);
var_dump($_POST);
// if($moke->user){

//   if(isset($_GET['sendmoke'])) {
//     $data = $moke->sendMoke($_GET,$deezer);

//     $tracks = $data[0];
//     $count = count($tracks);

//     $selected = $data[1];

//     $i = 0;
//     foreach($tracks as $track){
//       if($i == $selected)
//         echo '<div style="display:none" data-i="'.$selected.'" class="random selected" data-title="'.$track->title.'" data-artist="'.$track->artist->name.'" data-cover="'.$track->album->cover.'"></div>';
//       else
//         echo '<div style="display:none" class="random" data-cover="'.$track->album->cover.'" data-title="'.$track->title.'" data-artist="'.$track->artist->name.'"></div>';

//       $i++;
//     }
//   }

// }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
    <head>
        <title>MOKE</title>
        <meta charset="utf-8">
        <link href='http://fonts.googleapis.com/css?family=Roboto:300' rel='stylesheet' type='text/css'>
        <style type="text/css" media="screen">@import "themes/css/apple.css";</style>
        <style type="text/css" media="screen">@import "themes/css/new.css";</style>
        <style type="text/css" media="screen">
            .edgetoedge li a .preview, .edgetoedge li a .subject {
                display: block;
                color: #999;
                font-size: 12px;
                font-weight: normal;
            }
            .edgetoedge li a .subject {
                color: #000;
                font-size: 14px;
            }
        </style>
        <script src="src/lib/zepto.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="src/jqtouch.min.js" type="text/javascript" charset="utf-8"></script>

        <script type="text/javascript" charset="utf-8">
            var jqtouch = $.jQTouch({
                icon: 'mail.png',
                preloadImages: [
                    'themes/jqt/img/chevron.png',
                    'themes/jqt/img/back_button.png',
                    'themes/jqt/img/back_button_clicked.png',
                     'themes/jqt/img/button_clicked.png',
                    'themes/jqt/img/grayButton.png',
                    'themes/jqt/img/whiteButton.png'
                ]
            });
            // Add an onload function
            $(function(){
                // Dynamically set next page titles after clicking certain links
                $('#home ul a, #mailbox ul a').click(function(){
                    $( $(this).attr('href') + ' h1' ).html($(this).html());
                });
            });
        </script>
        <script src="http://code.jquery.com/jquery-1.10.2.min.js" type="text/javascript" charset="utf-8"></script>

        <script src="src/initmoke_2.js" type="text/javascript" charset="utf-8"></script>
        <script src="src/script.js" type="text/javascript" charset="utf-8"></script>
    </head>
    <body>
        <div id="jqt">
              <div id="loading" class="edgetoedge">
                  <div class="header">
                  </div>
                  <ul class="homemenu login">
                    <li><a id="" rel="external" href="">Loading...</a></li>
                  </ul>
              </div>
            <?php //if (!$moke->user): ?>
              <div id="homeFacebook" class="edgetoedge">
                  <div class="header">
                  </div>
                  <ul class="homemenu login">
                    <li><a id="facebookLink" rel="external" href="">Login with Facebook</a></li>
                  </ul>
              </div>
            <?php //elseif ($deezer->accessToken == null): ?>
              <div id="homeDeezer" class="edgetoedge">
                  <div class="header">
                  </div>
                  <ul class="homemenu login">

                    <li><a rel="external" id="deezerLink" href="<?php //echo $deezer->oAuth; ?>">Login with Deezer</a></li>
                  </ul>
              </div>
            <?php //else: ?>
              <div id="home" class="edgetoedge">
                    <!-- <a class="button slideup" id="infoButton" href="#about">About</a> -->
                <div class="header">
                    <!-- <a class="button slideup" id="infoButton" href="#about">About</a> -->
                </div>
                <ul class="homemenu def">
                    <li>
                      <span class="sound left"></span>
                      <a href="#sendmoke">Send a moke</a>
                      <span class="sound right"></span>
                    </li>
                    <li>
                      <span class="sound left"></span>
                      <a href="#mymokes">My mokes</a>
                      <span class="sound right"></span>
                    </li>
                    <li>
                      <span class="sound left"></span>
                      <a rel="external" href="?destroy=true">Logout</a>
                      <span class="sound right"></span>
                    </li>
                </ul>
                <div class="bottom">
                </div>
            </div>
            <div id="sendmoke" class="edgetoedge">
                <div class="header send">
                </div>
                <ul class="filter">
                  <a href="#home"><div class="bc bt"></div></a>
                  <li>
                    <input id="filter" type="text" name="search" placeholder="Choose your friends"/>
                  </li>
                  <a id="sendMoke" href="#"><div class="sd bt"></div></a>
                </ul>
                <ul id="toMoke" class="search">
                </ul>
            </div>
            <div id="mokesent">
                <div class="header send">
                </div>
                <ul class="search result">
                  <li><span id="random"></span><span id="random_cover"></span></li>
                </ul>
            </div>
            <div id="mymokes" class="edgetoedge">
                <div class="header send">
                </div>
                <ul class="filter">
                  <a href="#home"><div class="bc bt"></div></a>
                  <li><label>My mokes</label></li>
                </ul>
                <ul class="submenu">
                  <li id="to_my_pokes" class="act"><label>Received monkes</label></li>
                  <li id="to_sent_pokes"><label>Sent mokes</label></li>
                </ul>
                <ul class="my_pokes" id="received">
                </ul>
                <ul class="my_pokes" id="sent">
                </ul>
                <div id="dz-root"></div>
                <script src="http://br-cdn-files.deezer.com/js/min/dz.js"></script>
                <script>
                DZ.init({
                  appId  : '123703',
                  channelUrl : 'http://localhost/moke/channel.html',
                  player : {
                    onload : function(){
                    }
                  }
                });
                $(".headphone").bind('click', function() {

                  var trackId = $(this).attr("data-track");

                  if($(this).hasClass("active")) {

                    $(this).toggleClass("active");
                    $(this).css("background-image","url(../../img/pause.png");
                    DZ.player.pause();

                  }
                  else {
                    
                    DZ.player.playTracks([trackId])
                    $(this).css("background-image","url(../../img/headphone.png");
                    $(this).toggleClass("active");

                  }
                });
                </script>
            </div>
          <?php //endif ?>
        </div>
    </body>
</html>
