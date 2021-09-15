<?php namespace agent;
use Exception;

class equipmentV2Sync extends equipment {
    public $ping=self::pingDisabled;
    public $snmpPollerEnabled=false;
    
    public const pingDisabled=0;
    public const pingIcmp=1;
    public const pingSnmp=2;
    public const pingIcmpSnmp=3;
    
    
    public function save(?db_iface $db=null, $setSync=false) {
        parent::save($db, $setSync);
        
        if ($this->snmpPollerEnabled) {
            $this->addService(-1, request::jsonDecode([
                "command"=>"poll",
                "host"=>$this->host,
                "snmp"=>$this->snmp,
                "type"=>"generic/snmp",
            ]));
        }
        
        if ($this->ping & 1) {
            $this->addService(-2, request::jsonDecode([
                "command"=>"check",
                "host"=>$this->host,
                "type"=>"generic/icmp",
            ]));
        }
        
        if ($this->ping & 2) {
            $this->addService(-3, request::jsonDecode([
                "command"=>"check",
                "host"=>$this->host,
                "snmp"=>$this->snmp,
                "type"=>"generic/snmp",
            ]));
        }
        
    }
    
}