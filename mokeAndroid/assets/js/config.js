var env = "live";

if(env == "dev")
{
	var config = new Object();

	config.returnURL = "http://localhost/mokeAndroid/";

	config.facebook_url = "http://localhost/moke/ajax/facebook.php";

	config.deezer_url = "http://localhost/moke/ajax/deezer.php";

	config.firebase_url = "http://localhost/moke/ajax/ajax_firebase.php";

}
else
{
	var config = new Object();

	config.returnURL = "http://moke.herokuapp.com/mokeAndroid/";

	config.facebook_url = "http://moke.herokuapp.com/ajax/facebook.php";

	config.deezer_url = "http://moke.herokuapp.com/ajax/deezer.php";

	config.firebase_url = "http://moke.herokuapp.com/ajax/ajax_firebase.php";
}