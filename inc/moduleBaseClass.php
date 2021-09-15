<?php namespace agent;
use \Exception;

class moduleBaseClass {
    public static $targetType = null;
        /* Описание типа цели модуля - что он может опрашивать, один модуль - один тип
         * "hypervisor"
         * "service"
         * "snmp-device"
         * "linux-host"
         * etc..
         * 
         */
    
     public static $targetClass = null;
         /* Описание класса цели модуля - что он может опрашивать, один модуль - один класс
          * "vSphereOld"
          * "vCloud"
          * "vCloudMTS"
          * "netIface"
          * "smtp"
          * "lmtp"
          * "sip"
          * etc...
          */
     
     public static $targetMethods =[];
     /* Module methods
      * "poll"
      * "check"
      */
     
     public static $version="0.0.0"; 
     // Module version
     
     public static function poll(request $request) {
         // return array of poller-data
         throw new Exception("Method poll not implemented");
     }
     
     public static function check(request $request) {
         // return service status string [OK, PREFAIL, FAIL, WARN, FLOAT,RECOVER]
         throw new Exception("Method poll not implemented");
     }
     
     public static $minPeriod=null;
}
?>