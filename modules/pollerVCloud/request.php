<?php namespace pollerVCloud;

require_once(__DIR__."/../../inc/api.php");

class request extends \agent\request {
    public $login;
    public $password;
    
    public function validate() {
        return (parent::validate() && (preg_match("/^__agent_vcloud:/", $this->host)) && !empty($this->login) && !empty($this->password));
    }
}

?>