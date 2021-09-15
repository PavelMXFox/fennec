<?php namespace agent;

class checkStatus extends transportType {
    public $status=null;
    public $stamp=null;
    protected $__config=null;
    
    public function __construct($config=null) {
        if (empty($config)) {
            $db = new db();
            $this->__config = $db->loadConfig();
        } else {
            $this->__config=$config;
        }
        
        if (empty($this->__config) || empty($this->__config["syncInterval"]) || empty($this->__config["holdTime1"]) || empty($this->__config["holdTime2"]) || empty($this->__config["holdTime2"]) || empty($this->__config["lastSync"])) {
            trigger_error("Empty requred config keys. Please resync",E_USER_NOTICE);
        }
    }
}
?>