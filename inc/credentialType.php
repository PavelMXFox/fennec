<?php namespace agent;
use agent;

/** 
 * @author palkan
 * 
 * @property string $password;
 *
 */

class credentialType extends transportType {
    public $url;
    public $login;
    public $secret;
    public $desc;
    
    public function __construct($login=null, $password=null, $url=null, $desc=null) {
        $this->login=$login;
        $this->url=$url;
        $this->desc=$desc;
        if (!empty($password)) {$this->__set("password",$password); }
        
    }
    
    public function __get($key) {
        switch ($key) {
            case "password":
                return main::sDecrypt($this->secret);
                break;
        }
    }
    
    public function __set($key, $val) {
        switch ($key) {
            case "password":
                $this->secret=main::sEncrypt($val);
                break;
        }
    }
    
}
?>