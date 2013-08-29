
$(document).ready(function(){
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
    })

    window.location = "?sendmoke=true&friend="+id+"#mokesent";
  })
  jQuery.expr[":"].Contains = jQuery.expr.createPseudo(function(arg) {
      return function( elem ) {
          return jQuery(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
      };
  });
  $("#filter").bind('keyup', function(e){
    var filter = $(this).val();

    $("#toMoke").find("li:Contains(" + filter + ")").show();
    $("#toMoke").find("li:not(li:Contains(" + filter + "))").hide();
  })
  if(window.location.href.indexOf("mokesent") > -1){

    var title = new Array();
    var artist = new Array();
    var cover = new Array();
    $(".random").each(function(){
      title.push($(this).attr("data-title"));
      artist.push($(this).attr("data-artist"));
      cover.push($(this).attr("data-cover"));
    });

    var target = $("#random");
    var target_cover = $("#random_cover");
    var selected = $(".random.selected").attr("data-i");
    var i = 0;
    var j = 0;
    var size = title.length;
    var content;

    target.fadeOut();
    target_cover.fadeOut();

    var loop = setInterval(function(){
    
      j = Math.round(Math.random()*1000)
      j = j % (size-1);

      console.log(j);

      content = title[j] + " - " + artist[j];
      content_cover = "<img class='cover' src='"+cover[j]+"'/>";

      target.html(content);
      target_cover.html(content_cover);

      target.fadeIn(500).fadeOut(500);
      target_cover.fadeIn(500).fadeOut(500);

      i++;

      if(i >= size || i >= 8){
        clearInterval(loop);

        content = title[selected] + " - " + artist[selected] + " !!!";
        content_cover = "<img class='cover' src='"+cover[selected]+"'/>";
        target.html(content);
        target_cover.html(content_cover);

        target.fadeIn();
        target_cover.fadeIn();
      }

    }, 1000);

  }
})