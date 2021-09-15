<?php 
namespace pollerVCloud;

require_once(__DIR__."/../../inc/api.php");
use \vCenterCloud;
use \agent;
use \Exception;

class module extends \agent\moduleBaseClass {
    public static $targetType = "hypervisor";
    public static $targetClass= "vCloud";
    public static $version="1.0.0";
    public static $targetMethods =["poll"];
    
    public static function poll(\agent\request $request) {
        if (get_class($request) != __NAMESPACE__."\\request" || !$request->validate()) { throw new Exception("Invalid request (".get_class($request)); }
        
        
        $db = new agent\db_sqlite();
        $token = $db->loadConfig("mod_".__NAMESPACE__."_token");
        $s = new vCenterCloud\session(preg_replace("/^__agent_vcloud/", "https", $request->host), $request->login, $request->password, $token,
            function($token, $opts) {
                $db = $opts["db"];
                $db->saveConfig(["mod_vCenterCloud_token"=>$token]);
            }, ["db"=>$db]
            );
       
        $rv = new \agent\hypervisorPollerType();
        foreach ($s->getVDCs() as $vdc) {
           $rv->addVdc(new vdcType($vdc));            
        }
        
        
        foreach ($s->getVAPPs() as $vappx) {
            $vapp = new vAppType($vappx);
            $rv->addvApp($vapp);
            $rv->addVm($vapp->getVms());
        }
        
        foreach ($s->getOrganizations() as $orgx) {
            $rv->addOrg(new orgType($orgx));
        }
        
        return $rv;
    }

    
    public static function extractID($href) {
        $r=[];
        if (preg_match("/([^\/]*)$/", $href,$r)) {
            return $r[1];
        } else {
            return null;
        }
    }
}
?>