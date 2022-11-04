<?php 


class HttpRequest{
    private $url;
    private $connection;
    private $headers;

    public function __construct($url = "", $headers = ["Content-Type: application/json"]){
        $this->url = $url;
        $this->headers = $headers;
        $this->connection = curl_init();
    }

    public function post($request = null){
        curl_setopt($this->connection, CURLOPT_URL, $this->url);
        curl_setopt($this->connection, CURLOPT_POST, 1);
        curl_setopt($this->connection, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($this->connection, CURLOPT_POSTFIELDS, $request);
        curl_setopt($this->connection, CURLOPT_RETURNTRANSFER, true);

        $apiResponse = curl_exec($this->connection);
        curl_close($this->connection);

        return $apiResponse;
    }

    public function get($request = ""){
        curl_setopt($this->connection, CURLOPT_URL, "{$this->url}{$request}");
        curl_setopt($this->connection, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($this->connection, CURLOPT_CONNECTTIMEOUT, 5000);
        curl_setopt($this->connection, CURLOPT_TIMEOUT, 5000);
        curl_setopt($this->connection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->connection, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->connection, CURLOPT_SSL_VERIFYPEER, 0);

        $apiResponse = curl_exec($this->connection);

        if (curl_errno($this->connection)) {
            echo 'Error: ' . curl_error($this->connection);
            return "";
        }

        return $apiResponse;
    }
}