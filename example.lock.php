<?php

error_reporting(-1);
ini_set('display_errors', 'On');
include ("vendor/autoload.php");

use \addwalk;

$app_id = "";
$app_secret = "";

$user_access_token = "";
$user_access_token_secret = "";

$category = new addwalk\Category(false);
$provider = new addwalk\Service\OAuth2Provider([
  'clientId'     => $app_id,
  'clientSecret' => $app_secret,
  'redirectUri'  => 'http://www.yoururl.com/'
]);

$provider->getAccessToken(null, array("username" => $user_access_token, "password" => $user_access_token_secret));
$category = new addwalk\Category($provider);

print_r($category->index());
