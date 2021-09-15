<?php namespace agent;


class netIfaceType extends transportType {
    public $name;
    public $desc;
    public $ip;
    public $mac;
    public $type;
    public $snmpIdx;
    public $snmpType;
    public bool $enabled=false;
    public bool $connected=false;
    public bool $internal=false;
}

?>