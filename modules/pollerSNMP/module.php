<?php namespace pollerSNMP;
require_once(__DIR__."/../../inc/api.php");
use \agent;
use \Exception;
use agent\request;
use agent\checkBaseType;
use agent\checkResultType;

class module extends \agent\moduleBaseClass {
    public static $targetType = "generic";
    public static $targetClass= "snmp";
    public static $version="1.0.0";
    public static $targetMethods =["check","poll"];
 
    public static function check(request $request) {
        return static::ping($request->host, $request->snmp->version, $request->snmp->community,1);
    }
    
    public static function ping($host, $snmpVer, $community, $maxCount=5, $timeout=1) {
        $rv = new checkResultType();
        for ($i=0; $i<$maxCount; $i++) {
            if ($snmpVer == 1) {
                @$uptime = snmpget($host, $community,"iso.3.6.1.2.1.1.3.0",500000,1);
            } elseif ($snmpVer ==2) {
                @$uptime = snmp2_get($host, $community,"iso.3.6.1.2.1.1.3.0",500000,1);
            } else {
                $uptime = null;
                break;
            }
            
            if ($uptime) {
                break;
            }
        }
        
        if ($i<($maxCount * 0.7)) {
            $rv->result = checkBaseType::resOK;
        } else {
            $rv->result=checkBaseType::resFailed;
        }
        return $rv;
    }
    
    public static function poll(request $request) {
        return [];
    }
}
?>