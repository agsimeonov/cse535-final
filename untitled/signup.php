<?php
session_start();

require "autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;


define("CONSUMER_KEY",'MxxrVLC1F1ijE3jDSfuoODUqY');
define("CONSUMER_SECRET",'FKlNDelDS9Vo6Eu8DhkMVTsrhh4VSA3DntzJVoZHa3KvMhkZAa');
define('OAUTH_CALLBACK', 'http://istanbul.cse.buffalo.edu');


$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
    
// $content = $connection->get("account/verify_credentials");

#generating a token request
$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
    
#storing the outh_token and the secret in the users session, to make calls on behalf of the user
$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

$url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));

$_SESSION['status'] = 'verified';

header('location: ' . $url);

#$url = $connection->url("oauth/authorize", array("oauth_token" => ACCESS_TOKEN_SECRET));

#print_r($url);

?>
