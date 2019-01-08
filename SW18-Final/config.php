<?php
//session_start();
if(!session_id()) {
    session_start();
}

require_once "Facebook/autoload.php";

$FB = new \Facebook\Facebook([
    'app_id' => '2184404411813747',
    'app_secret' => '',
    'default_graph_version' => 'v2.10'
]);

$helper = $FB->getRedirectLoginHelper();
?>