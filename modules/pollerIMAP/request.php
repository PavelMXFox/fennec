<?php namespace pollerIMAP;

class request extends \agent\request {
    public $verifySSL=true;
    public $port=143;
    public $timeout=5;
    public $ssl="none";
    public $login=null;
    public $password=null;
}
?>