<?php

define('ENVIRONMENT', 'dev'); //we need to change this variable when we deploy to live

if( ENVIRONMENT == 'dev'){

	$CONFIG["APIS"] = array();
	$CONFIG["APIS"]["facebook"] = array();
	$CONFIG["APIS"]["facebook"]['appId'] = "144884582388415";
	$CONFIG["APIS"]["facebook"]['secret'] = "a5b72155e7c299e58b864717f3dc6bc2";
	$CONFIG["APIS"]["deezer"] = array();
	$CONFIG["APIS"]["deezer"]['appId'] = "124127";
	$CONFIG["APIS"]["deezer"]['secret'] = "35bbc77e35e79c81282ef5bfec4839c3";
}

if( ENVIRONMENT == 'live'){

	$CONFIG["APIS"] = array();
	$CONFIG["APIS"]["facebook"] = array();
	$CONFIG["APIS"]["facebook"]['appId'] = "374371822665745";
	$CONFIG["APIS"]["facebook"]['secret'] = "10940da5ca458cc591c7aabab3c6cf88";
	$CONFIG["APIS"]["deezer"] = array();
	$CONFIG["APIS"]["deezer"]['appId'] = "123703";
	$CONFIG["APIS"]["deezer"]['secret'] = "91c511cfd4b7aa2b2067d7f8733dd7d0";
}