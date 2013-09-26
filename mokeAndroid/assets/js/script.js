var facebook;
var deezer;

$(document).ready(function(){

  Lungo.dom('#moke-list-section').on('load', function(event) {
    GetMokes('received')
  });
  Lungo.dom('#received-mokes').on('load', function(event) {
    GetMokes('received')

  });
  Lungo.dom('#sent-mokes').on('load', function(event) {
    GetMokes('sent')
  });

  var pull_received = new Lungo.Element.Pull('#received-mokes', {
    onPull: "Pull down to refresh",      //Text on pulling
    onRelease: "Release to get new data",//Text on releasing
    onRefresh: "Refreshing...",          //Text on refreshing
    callback: function() {               //Action on refresh
      GetMokes('received')
      pull_received.hide();
    }
  });

  var pull_sent = new Lungo.Element.Pull('#sent-mokes', {
    onPull: "Pull down to refresh",      //Text on pulling
    onRelease: "Release to get new data",//Text on releasing
    onRefresh: "Refreshing...",          //Text on refreshing
    callback: function() {               //Action on refresh
      GetMokes('sent')
      pull_sent.hide();
    }
  });

  $("#error-article").click(function(){
    document.location.reload(true);
  })

  function select_people() {
    $("#fb-friends li").bind("click",function() {
      var checkbox = $(this).find("input");

      if(checkbox.prop('checked')){
        checkbox.prop('checked',false);
      }
      else {
        checkbox.prop('checked',true);
      }

      if($(this).hasClass("select-people"))
      {
        $(this).removeClass("select-people");
        $(this).addClass("deselect-people");
      }
      else
      {
        $(this).removeClass("deselect-people");
        $(this).addClass("select-people");
      }
    })
  }

  $("#login-fb-article a").click(function() {
      Lungo.Router.article("main", "loading-article");
  })

  $("#login-deezer-article a").click(function() {
      Lungo.Router.article("main", "loading-article");
  })

  var accent_map = {
      'á':'a',
      'à':'a',
      'â':'a',
      'å':'a',
      'ä':'a',
      'a':'a',
      'ã':'a',
      'ç':'c',
      'é':'e',
      'è':'e',
      'ê':'e',
      'ë':'e',
      'í':'i',
      'ì':'i',
      'î':'i',
      'ï':'i',
      'ñ':'n',
      'ó':'o',
      'ò':'o',
      'ô':'o',
      'ö':'o',
      'õ':'o',
      'ú':'u',
      'ù':'u',
      'û':'u',
      'ü':'u',};
 
 
      String.prototype.replaceEspecialChars = function() {
        var ret = '', s = this.toString();
        if (!s) { return ''; }
        for (var i=0; i<s.length; i++) {
          ret += accent_map[s.charAt(i)] || s.charAt(i);
        }
        return ret;
      };
 
      String.prototype.contains = function(otherString) {
        return this.toString().indexOf(otherString) !== -1;
      };
 
 
      $.extend($.expr[':'], {
 
        'contains-IgnoreAccents' : function(elemt, idx, math) {
          
          var expression1 = math[3].toLowerCase(),
            semAcent1 = expression1.replaceEspecialChars(),
            expression2 = elemt.innerHTML.toLowerCase(),
            semAcent2 = expression2.replaceEspecialChars();
 
          return semAcent2.contains(semAcent1);       
        }
    });
  $("#filter").bind('keyup', function(e){
    var filter = $(this).val();

    $("#fb-friends").find("li:contains-IgnoreAccents(" + filter + ")").show();
    $("#fb-friends").find("li:not(li:contains-IgnoreAccents(" + filter + "))").hide();
  })

	/*$("#fb-friends li").bind("click",function() {
     	var checkbox = $(this).children();
     	if(checkbox.prop('checked')){
     		checkbox.removeAttr('checked');
     	}
     	else {
     		checkbox.attr('checked','checked');
     	}
	});*/

  $.ajax({
    url: config.facebook_url,
    type: 'GET',
    dataType: 'json',
    data: 'url='+config.returnURL,
    beforeSend: function() {
      Lungo.Router.article("main", "loading-article");
    },
    error: function(XMLHttpRequest, textStatus, errorThrown){ 
      Lungo.Router.article("main", "error-article");

      Lungo.Notification.error(
          "Connection error",                      //Title
          "Please verify your internet connection",     //Description
          "cancel",                     //Icon
          4                            //Time on screen
      );
    },
    success: function(data) {
      facebook = data;

      console.log("facebook: "+facebook.auth);

      if(facebook.auth == true)
      {

        $.ajax({
          url: config.deezer_url,
          type: 'GET',
          dataType: 'json',
          data: 'url='+config.returnURL,
          beforeSend: function() {
            Lungo.Router.article("main", "loading-article");
          },
          error: function(XMLHttpRequest, textStatus, errorThrown){ 
            Lungo.Router.article("main", "error-article");
            Lungo.Notification.error(
                "Connection error",                      //Title
                "Please verify your internet connection",     //Description
                "cancel",                     //Icon
                4,                            //Time on screen
                afterNotification             //callback
            );
          },
          success: function(data) {

            deezer = data;

            console.log("deezer: "+deezer.auth);

            if(deezer.auth == true)
            {
              Lungo.Router.article("main", "main-article");
    		    	$("#loading-menu").removeClass("active");
    		    	$("#main-menu").addClass("active");
            }
            else
            {
              Lungo.Router.article("main", "login-deezer-article");
		          $("#login-deezer-article a").attr("href", deezer.loginURL);
            }
          },
        });

        var content = "";

        for (var i in data.friends){

          content += '<li class="warning">';
          content += '<input type="checkbox" ';
          content += 'id="c'+i+'" ';
          content += 'name="'+data.friends[i]["id"]+'"/>';
          content += '<label for="c'+i+'">';
          content += '<strong>'+data.friends[i]["name"]+'</strong>';
          content += '</label></li>';
        
        }

        $("#fb-friends").html(content);

        select_people();
      }
      else
      {
        Lungo.Router.article("main", "login-fb-article");
        $("#login-fb-article a").attr("href", facebook.loginURL);
      }
    },
  });

  $("#send-moke").bind('click', function() {
    var id, i = 0;
    var name;
    $("#fb-friends li input").each(function() {
      if($(this).prop('checked')) {

        if(i == 0) {
          id = $(this).attr("name");
          i++;
        }
        else
          id += ","+$(this).attr("name");

      }
    });

    $.ajax({
      url: config.facebook_url,
      type: 'GET',
      dataType: 'json',
      data: 'sendmoke=true&friend='+id,
      beforeSend: function() {
        Lungo.Router.article("send-moke-section", "loading-article");
      },
      error: function(XMLHttpRequest, textStatus, errorThrown){ 
          $.ajax({
            url: config.facebook_url,
            type: 'GET',
            dataType: 'text',
            data: 'destroy=true',
            beforeSend: function() {
              Lungo.Notification.error(
                  "Your session has been expired",                      //Title
                  "Please re-login to use the app",     //Description
                  "cancel",                     //Icon
                  4                            //Time on screen
              );
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){ 
              Lungo.Router.article("send-moke-section", "error-article");

              Lungo.Notification.error(
                  "Connection error",                      //Title
                  "Please verify your internet connection",     //Description
                  "cancel",                     //Icon
                  4                            //Time on screen
              );
            },
            success: function(data) {
              document.location.reload(true);
            },
          });
      },
      success: function(data) {

        console.log("success...");
        Lungo.Router.article("sent-moke-section", "loading-moke");

        var target = $("#loading-moke ul");
        var selected = data.selected;
        var i = 0;
        var j = 0;
        var size = deezer.tracks.length;
        var content;
        var html = "";

        target.fadeOut();

        var loop = setInterval(function(){
        
          j = Math.round(Math.random()*1000)
          j = j % (size-1);

          console.log(j);

          html += '<li class="thumb big moke-play">';
          html += '<img src="' + deezer.tracks[j].album.cover +'">';
          html += '<div>';
          html += '<strong>'+ deezer.tracks[j].title +'</strong>';
          html += '<small>'+ deezer.tracks[j].artist.name +'</small>';
          html += '<small>'+ deezer.tracks[j].album.title +'</small>';
          html += '</div>';
          html += '</li>';

          target.html(html);

          target.fadeIn(500).fadeOut(500);

          i++;

          html = "";

          if(i >= size || i >= 8){
            clearInterval(loop);

            html += '<li class="thumb big moke-play" data-track="'+deezer.tracks[selected].preview + '">';
            html += '<img src="' + deezer.tracks[selected].album.cover +'">';
            html += '<div>';
            html += '<strong>'+ deezer.tracks[selected].title +'</strong>';
            html += '<small>'+ deezer.tracks[selected].artist.name +'</small>';
            html += '<small>'+ deezer.tracks[selected].album.title +'</small>';
            html += '<a href="#" class="on-right">';
            html += '<span class="headphone icon play"></span>';
            html += '</a>';
            html += '</div>';
            html += '</li>';

            target.html(html);

            target.fadeIn();
          }

        }, 1000);


      },
    });
  });
});

  var Player = new Sound();

  Player.init();

  $('body').on('click', '.moke-play',function() {

    var preview = $(this).attr("data-track");

    if(!$(this).hasClass("current")) {
      $(".moke-play.playing .headphone").removeClass("pause");
      $(".moke-play.playing .headphone").addClass("play");
      $(".moke-play.playing").removeClass("playing");
    }

    if($(this).hasClass("playing")) {

      console.log("play")
      $(this).removeClass("playing");
      $(this).find(".headphone").removeClass("pause");
      $(this).find(".headphone").addClass("play");

      Player.pause();

    }
    else {
      
      console.log("pause")
      $(this).toggleClass("playing");

      if($(this).hasClass("current"))
        Player.play();
      else
        Player.start(preview);
      
      $(this).find(".headphone").removeClass("play");
      $(this).find(".headphone").addClass("pause");

    }

    if(!$(this).hasClass("current")) {
      $(".moke-play.current").removeClass("current");
      $(this).addClass("current");
    }

  });

function GetMokes(method){
  $.ajax({
    url: config.firebase_url,
    type: 'GET',
    dataType: 'json',
    data:'method=' + method,
    beforeSend: function() {

      Lungo.Notification.show();

    },
    error: function(XMLHttpRequest, textStatus, errorThrown){ 
      Lungo.Notification.hide();
      Lungo.Router.article("moke-list-section", "error-article");

      Lungo.Notification.error(
          "Connection error",                      //Title
          "Please verify your internet connection",     //Description
          "cancel",                     //Icon
          4                            //Time on screen
      );
    },
    success: function(data) {
      
      Lungo.Notification.hide();
      var html ='';
      var count = 0;
      $.each(data, function(key,value){

        if(value['trackId'] != null) {
          html += '<li class="thumb big moke-play" data-track="'+value['preview'] + '">';
          html += '<img src="' + value['albumImage'] +'">';
          html += '<div>';
          html += '<strong>'+ value['senderName'] +'</strong>';
          html += '<small>'+ value['track'] +'</small>';
          html += '<small>'+ value['artist'] +'</small>';
          html += '<a href="#" class="on-right">';
          html += '<span class="headphone icon play"></span>';
          html += '</a>';
          html += '</div>';
          html += '</li>';
          count++;
        }
      });

      var target;
      var bar;

      if(method == 'received') {
        target = $("#received-mokes ul");
        Lungo.Element.count("#received-mokes-bar", count);
      }
      else {
        target = $("#sent-mokes ul");
        Lungo.Element.count("#sent-mokes-bar", count);
      }

      target.html(html);

     // moke_play();
    }


});
}
