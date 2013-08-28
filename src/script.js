
$(document).ready(function(){
  $("#toPoke li input").bind('click', function() {
    if($(this).prop('checked'))
      console.log($(this).attr("name"));
  })
})