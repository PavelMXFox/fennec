<?php namespace pollerSMTP;

class request extends \agent\request {
    public $verifySSL=true;
    public $port=25;
    public $timeout=5;
    public $ssl="none";
    public $login=null;
    public $password=null;
}
?>