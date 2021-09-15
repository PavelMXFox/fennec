<?php namespace pollerICMP;
require_once(__DIR__."/../../inc/api.php");
use \agent;
use \Exception;
use agent\request;
use agent\equipment;
use agent\serviceBaseType;
use agent\checkBaseType;
use agent\checkResultType;

class module extends \agent\moduleBaseClass {
    public static $targetType = "generic";
    public static $targetClass= "icmp";
    public static $version="1.0.0";
    public static $targetMethods =["check"];
    
 
    public static function check(request $request) {
        return static::ping($request->host,2);
    }
    
    public static function ping($host, $maxCount=2, $timeout=1) {
        $rv = new checkResultType();
        for ($i=0; $i<$maxCount; $i++) {
            $res =  `ping -c1 -W$timeout $host 2>/dev/null | grep 'packets transmitted'`;
            
            $prm = preg_match('/([0-9]*) packets transmitted, ([0-9]*) received/', $res, $res);
            if ($prm && $res[1]==$res[2]) { break; }

        }
        if ($i==0) {
            $rv->result = checkBaseType::resOK;
        } elseif ($i==$maxCount) {
            $rv->result = checkBaseType::resFailed;    
        } else {
            $rv->result = checkBaseType::resWarning;
        }
        return $rv;
    }
}
?>