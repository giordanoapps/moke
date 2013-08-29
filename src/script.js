
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

    window.location = "?sendmoke=true&friend="+id+"#pokesent";
  })
})