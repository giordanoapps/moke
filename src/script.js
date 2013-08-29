
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
  if(window.location.href.indexOf("mokesent") > -1){

    var title = new Array();
    var artist = new Array();
    $(".random").each(function(){
      title.push($(this).attr("data-title"));
      artist.push($(this).attr("data-artist"));
    });

    var target = $("#random");
    var selected = $(".random.selected").attr("data-i");
    var i = 0;
    var j = 0;
    var size = title.length;
    var content;

    target.fadeOut();

    var loop = setInterval(function(){
    
      j = Math.round(Math.random()*1000)
      j = j % (size-1);

      console.log(j);

      content = title[j] + " - " + artist[j];
      target.html(content);

      target.fadeIn(500).fadeOut(500);

      i++;

      if(i >= size || i >= 8){
        clearInterval(loop);

        content = title[selected] + " - " + artist[selected] + " !!!";
        target.html(content);

        target.fadeIn();
      }

    }, 1000);

  }
})