<?php namespace agent;

class snmpConfig extends transportType {
    public $version=self::snmpDisabled;
    public $community=null;
    public $fdbType=self::fdbDisabled;
    public $lldpType=self::lldpDisabled;
    
    public const snmpDisabled=0;
    public const snmpV1=1;
    public const snmpV2=2;
    
    public const fdbDisabled=0;
    public const fdbGeneric=1;
    public const fdbCisco=2;
    public const fdbLegacy=3;
    
    public const lldpDisabled=0;
    public const lldpCisco=1;
    public const lldpMikrotik=2;
    public const lldpGeneric=3;
    
}
?>