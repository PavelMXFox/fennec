<?php namespace pollerSNMP;

use agent\snmpConfig;

class request extends \agent\request {
    public ?snmpConfig $snmp;
    
    public function __construct() {
        $this->snmp=new snmpConfig();
    }
}
?>