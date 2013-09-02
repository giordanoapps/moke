window.onload = function(){

    //initialize
var $_GET = {};

document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
  function decode(s) {
    return decodeURIComponent(s.split("+").join(" "));
  }

  $_GET[decode(arguments[1])] = decode(arguments[2]);
});

jQuery.ajax({
url: 'ajax/initialize.php',
async: false,
type: 'POST',
dataType: 'json',
cache: false,
data: {"destroy" : $_GET['destroy'],"state": $_GET['state'], "codeDeezer": $_GET['code'], "sendmoke":$_GET['sendmoke'] },
success : function(data){
  //the url will be set after the page loads
  
    if(data.user){
      if(data.accessToken =='' || data.accessToken == null){
        $('#homeDeezer').addClass('current');
        $('#deezerLink').attr("href", data.oAuth);
      }
      else{
        $('#homeMenu').addClass('current');
      }
    }
    else{
       $('#homeFacebook').addClass('current');
       $("#facebookLink").attr("href", data.loginURL);
    }
    
    
  
}

});



      };            