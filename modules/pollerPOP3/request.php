<?php namespace pollerPOP3;

class request extends \agent\request {
    public $verifySSL=true;
    public $port=110;
    public $timeout=5;
    public $ssl="none";
    public $login=null;
    public $password=null;
}
?>