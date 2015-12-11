<?php
	require "autoload.php";
	use Abraham\TwitterOAuth\TwitterOAuth;

	define("CONSUMER_KEY",'MxxrVLC1F1ijE3jDSfuoODUqY');
	define("CONSUMER_SECRET",'FKlNDelDS9Vo6Eu8DhkMVTsrhh4VSA3DntzJVoZHa3KvMhkZAa');
	define("ACCESS_TOKEN_KEY",'165390377-stwO6RMGMkCsIegBH3mdADZpTt2Qs0GbEAM0V9gN');
	define("ACCESS_TOKEN_SECRET",'cipEHo8hjlS7RGax9vjjjRrwFKWP1HXgZf3hp2BSqMVfL');
	define('OAUTH_CALLBACK', 'http://localhost/twitteroauth/example.php');


	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
	
	// $content = $connection->get("account/verify_credentials");

	#generating a token request
	$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
	
	#storing the outh_token and the secret in the users session, to make calls on behalf of the user
	$_SESSION['oauth_token'] = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

	$url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));

	header('location: ' . $url);

	#$url = $connection->url("oauth/authorize", array("oauth_token" => ACCESS_TOKEN_SECRET));

	#print_r($url);
?>