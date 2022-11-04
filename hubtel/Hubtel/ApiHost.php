<?php


    class ApiHost{
        private $auth;
        private $hostname;
        
        public function __construct($auth = null, $hostname = "https://smsc.hubtel.com/v1/messages/send"){
            $this->auth = $auth;
            $this->hostname = $hostname;
        }

        public function getAuth() {
            return $this->auth;
        }
    
        public function getHostname() {
            return $this->hostname;
        }
    }
?>