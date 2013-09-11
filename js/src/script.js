
$(document).ready(function(){

  $("#to_my_pokes").bind('click', function() {
    $("#received").show();
    $("#sent").hide();
    $(this).addClass("act");
    $("#to_sent_pokes").removeClass("act");

  });

  $("#to_sent_pokes").bind('click', function() {

    $("#sent").show();
    $("#received").hide();
    $(this).addClass("act");
    $("#to_my_pokes").removeClass("act");

  });

  $("#sendMoke").bind('click', function() {
    var id, i = 0;
    $("#toMoke li input").each(function() {
      if($(this).prop('checked')) {

        if(i == 0) {
          id = $(this).attr("name");
          i++;
        }
        else
          id += ","+$(this).attr("name");

      }
    });

    console.log("antes_ajax");
    $.ajax({
      url: 'ajax/facebook.php',
      type: 'GET',
      dataType: 'json',
      beforeSend: function() {

        $('#loading').addClass('current');

      },
      data: 'sendmoke=true&friend='+id,
      success: function(data) {

        $("#mokesent").addClass('current');

        var target = $("#random");
        var target_cover = $("#random_cover");
        var selected = data.selected;
        var i = 0;
        var j = 0;
        var size = deezer.tracks.length;
        var content;

        target.fadeOut();
        target_cover.fadeOut();

        var loop = setInterval(function(){
        
          j = Math.round(Math.random()*1000)
          j = j % (size-1);

          console.log(j);

          content = deezer.tracks[j].artist.name + "<br/>" + deezer.tracks[j].title;
          content_cover = "<img class='cover' src='"+deezer.tracks[j].album.cover+"'/>";

          target.html(content);
          target_cover.html(content_cover);

          target.fadeIn(500).fadeOut(500);
          target_cover.fadeIn(500).fadeOut(500);

          i++;

          if(i >= size || i >= 8){
            clearInterval(loop);

            content = '<div class="headphone" style="top:15px !important" data-track="'+deezer.tracks[selected].id + '"></div>';
            content += "<span style='color:#e67e22'>"+deezer.tracks[selected].artist.name + "<br/>" + deezer.tracks[selected].title+"</span>";
            content_cover = "<img class='cover' src='"+deezer.tracks[selected].album.cover+"'/>";
            target.html(content);
            target_cover.html(content_cover);

            target.fadeIn();
            target_cover.fadeIn();
            headphone();

            setTimeout(function(){
              //$("#home").addClass('current');

              var html = '<li><a class="u_voltar" href="#home">Voltar</a></li>';
              $('#random_final').append(html);
              
            },500);
          }

        }, 1000);


      },
    });
    console.log("depois_ajax");

    //window.location = "?sendmoke=true&friend="+id+"#mokesent";
  })
  /*
  jQuery.expr[":"].Contains = jQuery.expr.createPseudo(function(arg) {
      return function( elem ) {
          return jQuery(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
      };
  });*/
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
    console.log(e);
    var filter = $(this).val();

    $("#toMoke").find("li:contains-IgnoreAccents(" + filter + ")").show();
    $("#toMoke").find("li:not(li:contains-IgnoreAccents(" + filter + "))").hide();
  })
  if(window.location.href.indexOf("mokesent") > -1){


  }
});