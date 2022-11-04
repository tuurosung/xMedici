<?php

// Let us test the SDK
require './vendor/autoload.php';

$auth = new BasicAuth("sdwswewe", "sdsdwsssws");

// instance of ApiHost
$apiHost = new ApiHost($auth);

// instance of AccountApi
// $accountApi = new AccountApi($apiHost);

// set web console logging to false
$disableConsoleLogging = false;

// Let us try to send some message
$messagingApi = new MessagingApi($apiHost, $disableConsoleLogging);
try {
    // Send a quick message
    $messageResponse = $messagingApi->sendQuickMessage("DevUniverse", "233550592800", "Welcome to planet Hubtelsdsd!");

    if ($messageResponse instanceof MessageResponse) {
        echo $messageResponse->getStatus();
    } elseif ($messageResponse instanceof HttpResponse) {
        echo "\nServer Response Status : " . $messageResponse->getStatus();
    }
} catch (Exception $ex) {
    echo $ex->getTraceAsString();
}
