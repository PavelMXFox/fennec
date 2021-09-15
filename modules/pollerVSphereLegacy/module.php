<?php namespace pollerVSphereLegacy;

require_once(__DIR__."/../../inc/api.php");
use \Vmwarephp;
use \agent;
use \agent\vAppType;
use agent\hypervisorPollerType;

class module extends \agent\moduleBaseClass {

    public static $targetType = "hypervisor";
    public static $targetClass= "vSphereSoap";
    public static $version="1.0.0";
    public static $targetMethods =["poll"];
    public static $minPeriod=30;
    
    public static function poll(\agent\request $request) {
        //var_dump($request);
        
        $rv = new hypervisorPollerType();
        
        $autoloader = new \Vmwarephp\Autoloader;
        $autoloader->register();
        
        
        $xhost = preg_replace("/^__agent_vsphere:\/\//", "", $request->host).":443";

        $vhost = new \Vmwarephp\Vhost($xhost, $request->login, $request->password);

        foreach ($vhost->findAllManagedObjects('Datacenter', array('name')) as $vdcx) {
            $rv->addVdc(new vdcType($vdcx));
        }

        foreach ($vhost->findAllManagedObjects('VirtualApp', array('name','runtime')) as $vappx) {
            // stub, not implemented here. If array will not emty it will be filled by empty vAppType objects
            $rv->addvApp(new vAppType($vappx));
        }
      
        $vms = $vhost->findAllManagedObjects('HostSystem', array('name'));
        foreach ($vms as $vx) {
            if (empty($request->filter)) {
                $rv->addHost(new vHostType($vx));
            } elseif (!property_exists($request->filter,"hostId") && !property_exists($request->filter,"hostName")) {
                $rv->addHost(new vHostType($vx));
            } elseif (property_exists($request->filter,"hostId") && array_search($vx->reference->_, $request->filter->hostId)!==false) {
                $rv->addHost(new vHostType($vx));
            } elseif (property_exists($request->filter,"hostName") && array_search($vx->name, $request->filter->hostName)!==false) {
                $rv->addHost(new vHostType($vx));
            }
        }

        foreach ($rv->host as $host) {
            foreach ($host->getVMs() as $vmx) {
                //var_dump($vmx->reference->_." ".$vmx->config->uuid." ".$vmx->name." ".$vmx->runtime->host->reference->_." ".$vmx->runtime->host->name);
                $rv->addVm(new vmType($vmx));
            }
        }
        
        foreach ($vhost->findAllManagedObjects('VirtualMachine', array('name')) as $vmx) {
            // stub, not implemented here. If array will not emty it will be filled by empty vAppType objects
            if ($vmx->reference->_ == 'vm-91729') {
                var_dump($vmx->reference->_." ".$vmx->config->uuid." ".$vmx->name." ".$vmx->runtime->host->reference->_." ".$vmx->runtime->host->name);
            }
            //var_dump($vmx->reference->_." ".$vmx->name);
            
        }
        
        return $rv;
        
    }
    
    public static function getFolderPath($obj, $filtered=true, $depth=0, $fStart=false) {
        $rv="";
        $depth++;
        $p=$obj->parent;
        if ($p->reference->type == 'ComputeResource' || $p->reference->type=='Folder') {
            $rv .= static::{__FUNCTION__}($p, $filtered, $depth,$fStart);
            if ($filtered && $p->reference->type != 'Folder') {
                //$rv .="/".$p->name;
            } else {
                $rv .="/".$p->name;
            }
            
        }
        if ($depth==1) {
            if ($filtered) {
                $rv = preg_replace("/^\\/((network)|(host)|(vm)|(datastore))/","", $rv);
            }
        }
        return $rv;
    }
}

?>