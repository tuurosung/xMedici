Hubtel SMS API PHP SDK 
=======================

## **Overview**

This is an open source PHP SDK that allows you to access the Hubtel [REST SMS API](https://developers.hubtel.com/documentations/sendmessage) from your PHP application. You need to create a Hubtel account in order to use this API.

## **Installation**

The SDK can smoothly run on **PHP 5.3 and above with CURL extension enabled**.
The Hubtel PHP SDK can be installed with [Composer](https://getcomposer.org). Run this command:

`composer require hubtel/hubtel-sms-sdk`
 
You can also download the **Hubtel** folder from the repository and add it to your project. 
You may then <code>include</code> the Hubtel/Api.php file by referring to the
appropriate path like such: <pre><code>include '/path/to/location/Hubtel/Api.php';</code></pre>

## **Usage**

The SDK currently is organized around the following classes:

* *MessagingApi.php* : 
    It handles sending messages.(For more information about these terms refer to [Our developer site](http://developers.hubtel.com/documentations/sendmessage).)

## **Examples**

* **How to Send a Message**

To send a message just copy this code snippet and do the necessary modifications:
```php
require './vendor/autoload.php';

$auth = new BasicAuth("user123", "pass123");
// instance of ApiHost
$apiHost = new ApiHost($auth);
// Let us try to send some message
$messagingApi = new MessagingApi($apiHost);
try {
    // Send a quick message
    $messageResponse = $messagingApi->sendQuickMessage("DevUniverse", "+233207110652", "Welcome to planet Hubtel!");

    if ($messageResponse instanceof MessageResponse) {
        echo $messageResponse->getStatus();
    } elseif ($messageResponse instanceof HttpResponse) {
        echo "\nServer Response Status : " . $messageResponse->getStatus();
    }
} catch (Exception $ex) {
    echo $ex->getTraceAsString();
}
```
