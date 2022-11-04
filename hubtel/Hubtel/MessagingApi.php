<?php

class MessagingApi {
    private $apiHost;
    private $httpRequest;

    public function __construct($apiHost) {
        $this->apiHost = $apiHost;
    }

    public function sendQuickMessage($from, $to, $message){
        $hostname = $this->apiHost->getHostname();
        $auth = $this->apiHost->getAuth();
        $urlEncodedMessage = urlencode($message);
        $request = "?clientsecret={$auth->getClientSecret()}&clientid={$auth->getClientId()}&from={$from}&to=$to&content=$urlEncodedMessage";

        $this->httpRequest = new HttpRequest($hostname);

        $response = $this->httpRequest->get($request);

        //echo $response;

        return new MessageResponse($response);
    }
}
