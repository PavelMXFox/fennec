<?php namespace pollerHTTP;

class request extends \agent\request {
    public $verifySSL=true;
    public $successCodes=null;
    public $port=null;
    public $timeout=5;
}
?>