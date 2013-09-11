DZ.init({
  appId  : '123703',
  channelUrl : 'http://localhost/moke/channel.html',
  player : {
    onload : function(){
    }
  }
});

function headphone() {
  $(".headphone").on('click', function() {
    
    var trackId = $(this).attr("data-track");

    if(!$(this).hasClass("current")) {
      $(".headphone.active").css("background-image","url(img/headphone.png)");
      $(".headphone.active").removeClass("active");
    }

    if($(this).hasClass("active")) {

      console.log("play")
      $(this).toggleClass("active");
      $(this).css("background-image","url(img/headphone.png)");
      DZ.player.pause();

    }
    else {
      
      console.log("pause")

      if($(this).hasClass("current"))
        DZ.player.play();
      else
        DZ.player.playTracks([trackId])
      
      $(this).css("background-image","url(img/pause.png)");
      $(this).toggleClass("active");

    }

    if(!$(this).hasClass("current")) {
      $(".headphone.current").removeClass("current");
      $(this).toggleClass("current");
    }
  });
}