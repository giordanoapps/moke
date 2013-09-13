var facebook;
var deezer;

$(document).ready(function(){

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
    url: 'http://moke.herokuapp.com/ajax/facebook.php',
    type: 'GET',
    dataType: 'json',
    data: 'url=http://moke.herokuapp.com/mokeAndroid/',
    beforeSend: function() {
    	$("#main article.indenteds.active").removeClass("active");
    	$("#main #loading-article").addClass("active");
    },
    success: function(data) {
      facebook = data;

      console.log("facebook: "+facebook.auth);

      if(facebook.auth == true)
      {

        $.ajax({
          url: 'http://moke.herokuapp.com/ajax/deezer.php',
          type: 'GET',
          dataType: 'json',
          data: 'url=http://moke.herokuapp.com/mokeAndroid/index.html',
          success: function(data) {

            deezer = data;

            console.log("deezer: "+deezer.auth);

            if(deezer.auth == true)
            {
		    	$("#main article.indenteds.active").removeClass("active");
		    	$("#main #main-article").addClass("active");
		    	$("#loading-menu").removeClass("active");
		    	$("#main-menu").addClass("active");
            }
            else
            {
		    	$("#main article.indenteds.active").removeClass("active");
		    	$("#main #login-deezer-article").addClass("active");
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
    	$("article.indenteds.active").removeClass("active");
    	$("#login-fb-article").addClass("active");
        $("#login-fb-article a").attr("href", facebook.loginURL);
      }
    },
  });

  $("#send-moke").bind('click', function() {
    var id, i = 0;
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
      url: 'http://moke.herokuapp.com/ajax/facebook.php',
      type: 'GET',
      dataType: 'json',
      data: 'sendmoke=true&friend='+id,
      beforeSend: function() {

        $("#send-moke-section article.indenteds.active").removeClass("active");
        $("#send-moke-section #loading-article").addClass("active");
      console.log("before...");
      },
      error: function(XMLHttpRequest, textStatus, errorThrown){ 
        console.log("erro");

        console.log(XMLHttpRequest);
        console.log(textStatus);
        console.log(errorThrown);
      },
      success: function(data) {

        console.log("success...");
        $("#sent-moke-section article.indenteds.active").removeClass("active");
        $("#sent-moke-section #loading-moke").addClass("active");

        var target = $("#random");
        var selected = data.selected;
        var i = 0;
        var j = 0;
        var size = deezer.tracks.length;
        var content;

        target.fadeOut();

        var loop = setInterval(function(){
        
          j = Math.round(Math.random()*1000)
          j = j % (size-1);

          console.log(j);

          content = deezer.tracks[j].artist.name + "<br/>" + deezer.tracks[j].title;
          content_cover = "<img class='cover' src='"+deezer.tracks[j].album.cover+"'/>";

          target.html(content + content_cover);

          target.fadeIn(500).fadeOut(500);

          i++;

          if(i >= size || i >= 8){
            clearInterval(loop);

            content = '<span class="icon play" data-track="'+deezer.tracks[selected].id + '"></span>'
            content += "<span style='color:#e67e22'>"+deezer.tracks[selected].artist.name + "<br/>" + deezer.tracks[selected].title+"</span>";
            content_cover = "<img class='cover' src='"+deezer.tracks[selected].album.cover+"'/>";
            target.html(content + content_cover);

            target.fadeIn();
            headphone();
          }

        }, 1000);


      },
    });
  });
});

function GetMokes(method){
  $.ajax({
    url: 'http://moke.herokuapp.com/ajax/ajax_firebase.php',
    type: 'GET',
    dataType: 'json',
    data:'method=' + method,
    beforeSend: function() {

      $('#loading').addClass('current');

    },
    success: function(data) {
      
      var html ='';
      $.each(data, function(key,value){

        console.log('key : ' + key + ' value : ' + value['artist']);

        html += '<li><label><span></span>' + value['senderName'] + '</label>' +
                 '<img class="cover_2" src="' + value['albumImage'] +'">'+
                 '<span class="music">' + value['track'] +'</span>' +
                  '<span class="artist">' + value['artist'] + '</span>'+
                  '<div class="headphone" data-track="'+value['trackId'] + '"></div>'+
                   '<div class="calendar"></div>' +
                    '<label class="calendar">' + value['date'] + '</label>'+
                  '</li>';

      });
      $('#' + method).html(html);
      headphone();
    }


});
}
