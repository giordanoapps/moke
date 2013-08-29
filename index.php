<?php

session_start();
require("moke.php");
require("deezer.php");

$moke = new moke();

$moke->initialize();

if(isset($_GET['destroy']))
    $moke->finalize();

if($moke->user) {

  $moke->requestFriends();

  if(isset($_GET['sendmoke']))
    $moke->sendMoke($_GET);

}
//Deezer setup 
$deezer = new deezer();
$urlDeezer =$deezer->oAuth;
if(isset($_REQUEST["code"])){
    $accessToken = $deezer::getAccesstoken($_REQUEST["code"]);
    setcookie("deezer_access_token", $accessToken);
    setcookie("deezer_access_token", $accessToken, time() + (10 * 365 * 24 * 60 * 60));  /* expire in 1 hour */
    $deezerUserId = $deezer::getUserId($accessToken);
    $favoriteTracks = $deezer::getUserFavoriteTracks($deezerUserId, $accessToken); 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
    <head>
        <title>MOKE</title>
        <meta charset="utf-8">
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
    </head>
    <body>
        <div id="jqt">
            <?php if (!$moke->user): ?>
              <div id="home" class="edgetoedge">
                  <div class="toolbar">
                      <h1>MOKE</h1>
                  </div>
                  <ul class="edgetoedge">

                    <li><a rel="external" href="<?php echo $moke->loginURL; ?>">Login with Facebook</a></li>
                    <li><a rel="external" href="<?php echo $urlDeezer; ?>">Login with Deezer</a></li>
                  </ul>
              </div>
            <?php else: ?>
              <div id="home" class="edgetoedge">
                    <!-- <a class="button slideup" id="infoButton" href="#about">About</a> -->
                <div class="toolbar">
                    <h1>MOKE</h1>
                    <!-- <a class="button slideup" id="infoButton" href="#about">About</a> -->
                </div>
                <ul class="edgetoedge">
                    <li><a href="#sendmoke">Send a moke</a></li>
                    <li><a href="#mailbox">moke history</a></li>
                    <li><a rel="external" href="?destroy=true">Logout</a></li>
                </ul>
            </div>
            <div id="sendmoke" class="edgetoedge">
                <div class="toolbar">
                    <a href="#" class="back button"></a>
                    <h1>MOKE</h1>
                    <a class="button" id="sendMoke" href="#">Send</a>
                </div>
                <ul id="toMoke" class="edgetoedge">
                  <?php
                  foreach($moke->friends as $friend) {
                    echo '<li><input type="checkbox" name="'.$friend["id"].'"/>&nbsp;'.$friend["name"].'</li>';
                  }
                  ?>
                </ul>
            </div>
            <div id="mokesent">
                <div class="toolbar">
                    <a href="#home" class="back button"></a>
                    <h1>MOKE</h1>
                </div>
                <ul class="edgetoedge">
                  <li>Moke sent!</li>
                </ul>
            </div>
            <div id="new" class="edgetoedge">
                <form>
                    <div class="toolbar">
                        <h1>New Message</h1>
                        <a class="cancel" href="#home">Cancel</a> <a class="button disabled blueButton" href="#">Send</a>
                    </div>
                    <fieldset>
                        <ul class="rounded">
                            <li><label>To: <input type="text" name="name" value=""></label></li>
                            <li><label>Cc/Bcc, From:</label> <input type="text" name="bcc" placeholder="you@email.com"></li>
                            <li><label>Subject:</label> <input type="text" name="subject" placeholder="you@email.com"></li>
                            <li><textarea>My email</textarea></li>
                        </ul>
                    </fieldset>
                    <input type="submit" />
                </form>
            </div>
            <div id="message"></div>
            <div id="features">
                <div class="toolbar">
                    <h1>Features</h1>
                    <a class="back button" href="#home">jQTouch</a>
                </div>
                <form action="#" method="get" accept-charset="utf-8">
                    <p><input type="submit" value="Continue →"></p>
                </form>
                <div class="pad">
                    <ul>
                        <li>One-line setup, with options for selectors, viewport settings, icon path and glossiness, and status bar style</li>
                        <li>Pages can be built in a single HTML file, or loaded dynamically via GET or POST</li>
                        <li>Native, hardware-accelerated, page animations, including slide in/out, slide up/down, and 3D flip. All with history support.</li>
                        <li>Image preloading functions</li>
                        <li>Easy to theme</li>
                    </ul>
                </div>
            </div>
            <div id="flipdemo">
                <div class="pad">
                    <div style="font-size: 1.5em; text-align: center; margin: 160px 0 160px; font-family: Marker felt;">
                        Pretty smooth, eh?
                    </div><a href="#" class="back whiteButton">Go back</a>
                </div>
            </div>
            <form id="formdemo" title="Movie Search" action="search.php" method="post" name="formdemo">
                <div class="toolbar">
                    <h1>
                        Demos
                    </h1><a class="back button" href="#">Home</a>
                </div>
                <div class="pad">
                    <fieldset>
                        <div class="row">
                            <label>Movie</label> <input type="text" name="movie" value="">
                        </div>
                        <div class="row">
                            <label>Zip</label> <input type="text" name="zip" value="">
                        </div>
                    </fieldset><input type="submit">
                </div>
            </form>
            <form id="searchForm" class="dialog" action="search.php" name="searchForm">
                <fieldset>
                    <h1>Music Search</h1>
                    <a class="button leftButton" type="cancel">Cancel</a> <a class="button blueButton" type="submit">Search</a> <label>Artist:</label> <input id="artist" type="text" name="artist"> <label>Song:</label> <input type="text" name="song">
                    <p>This form retrieves the next page with Ajax via a POST request.</p>
                </fieldset>
            </form>
            <div id="license">
                <div class="pad">
                    <p><strong>The MIT License</strong></p>
                    <p>Copyright © 2009 David Kaneda</p>
                    <p>Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:</p>
                    <p>The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.</p>
                    <p>THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.</p>
                    <a href="#" class="grayButton back">Return</a>
                </div>
            </div>
          <?php endif ?>
        </div>
    </body>
    <script src="src/script.js" type="text/javascript" charset="utf-8"></script>
</html>
