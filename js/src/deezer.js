/* DZ.init({
  appId  : '123703',
  channelUrl : 'http://localhost/moke/channel.html',
  player : {
    onload : function(){
    }
  }
}); */

function Sound(source,volume,loop)
{
    this.source=source;
    this.volume=volume;
    this.loop=loop;
    var son;
    this.son=son;
    this.finish=false;
    this.stop=function()
    {
        document.body.removeChild(this.son);
    }
    this.start=function()
    {
        if(this.finish)return false;
        this.son=document.createElement("embed");
        this.son.setAttribute("src",this.source);
        this.son.setAttribute("hidden","true");
        this.son.setAttribute("volume",this.volume);
        this.son.setAttribute("autostart","true");
        this.son.setAttribute("loop",this.loop);
        document.body.appendChild(this.son);
    }
    this.remove=function()
    {
        document.body.removeChild(this.son);
        this.finish=true;
    }
    this.init=function(volume,loop)
    {
        this.finish=false;
        this.volume=volume;
        this.loop=loop;
    }
}

/*

var foo=new Sound("url",100,true);
foo.start();
foo.stop();
foo.start();
foo.init(100,false);
foo.remove();

 */

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