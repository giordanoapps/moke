var facebook;
var deezer;

$(document).ready(function(){

  $.ajax({
    url: 'ajax/facebook.php',
    type: 'GET',
    dataType: 'json',
    data: 'url=http://localhost/moke/index.html',
    beforeSend: function() {

      $('#loading').addClass('current');

    },
    success: function(data) {

      facebook = data;

      console.log("facebook: "+facebook.auth);

      if(facebook.auth == true)
      {

        $.ajax({
          url: 'ajax/deezer.php',
          type: 'GET',
          dataType: 'json',
          data: 'url=http://localhost/moke/index.html',
          success: function(data) {

            deezer = data;

            console.log("deezer: "+deezer.auth);

            if(deezer.auth == true)
            {
              $('#home').addClass('current');
            }
            else
            {
              $('#homeDeezer').addClass('current');
              $("#deezerLink").attr("href", deezer.loginURL);
            }
          },
        });

        var content = "";

        for (var i in data.friends){

          content += '<li>';
          content += '<input type="checkbox" ';
          content += 'id="c'+i+'" ';
          content += 'name="'+data.friends[i]["id"]+'"/>';
          content += '<label for="c'+i+'">';
          content += '<span></span>';
          content += data.friends[i]["name"];
          content += '</label></li>';
        
        }

        $("#toMoke").html(content);
      }
      else
      {
        $('#homeFacebook').addClass('current');
        $("#facebookLink").attr("href", facebook.loginURL);
      }
    },
  });
});

function GetMokes(method){
  $.ajax({
    url: 'ajax/ajax_firebase.php',
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
    }


});
}
