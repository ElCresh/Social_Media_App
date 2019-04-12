<?php
require_once '../facebooklogin/autoload.php';
session_start();
$fb = new Facebook\Facebook([
  'app_id' => '2184553708460493', // Replace {app-id} with your app id
  'app_secret' => '5d379d25642c74400ca573ee37173477',
  'default_graph_version' => 'v3.2',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('https://example.com/fb-callback.php', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';