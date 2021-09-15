<?php namespace pollerVCloud;

use pollerVCloud;
use agent\resource;
use vCenterCloud;
use vCenterCloud\OperatingSystemSectionType;
use vCenterCloud\VmSpecSectionType;
use agent\operatingSystemType;
use agent\credentialType;
use agent\netIfaceType;
use vCenterCloud\RASDType;
use vCenterCloud\VirtualHardwareSectionType;
use agent\entity;

class vmType extends \agent\vmType {
    protected ?vCenterCloud\VmType $__ref=null; // reference object for getVMS method
    public $parentType=self::vAppType;
    public function __construct($ref) {
        
        if (gettype($ref)=='object') {
            
            if (get_class($ref) == vCenterCloud\QueryResultVMRecordType::class) {
                $ref = $ref->getFullObject();   
            }
            
            if (get_class($ref) == vCenterCloud\VmType::class) {
                
                $this->__ref=$ref;
                
                switch ($this->__ref->status) {
                    case 4:
                        $this->status=entity::statusOn;
                        break;
                    case -1:
                        $this->status=entity::statusFail;
                        break;
                    default:
                        $this->status=entity::statusOff;
                        break;
                        
                }
                
                $this->name = $ref->name;
                $this->id = module::extractID($ref->href);
                $this->desc=preg_replace("/[\n\r]/", " ", $ref->description);
                
                $this->operatingSystem=new operatingSystemType();
                if ($s=$ref->getSection(OperatingSystemSectionType::class) && !empty($s->description) && !empty($s->description->value)) {
                    $this->operatingSystem->description=preg_replace("/[\n\r]/", " ", $s->description->value);
                }
                
                if ($s=$ref->getSection(VmSpecSectionType::class)) {
                    $this->operatingSystem->type=$s->osType;
                    $this->resourcesAllocated[]=new resource("cpuCores", $s->numCpus);
                    $this->resourcesAllocated[]=new resource("coresPerSocket", $s->numCoresPerSocket);
                    if (!empty($s->numCoresPerSocket)) 
                    { $this->resourcesAllocated[]=new resource("cpuSockets", $s->numCpus/$s->numCoresPerSocket);}
                    else { $this->resourcesAllocated[]=new resource("cpuSockets",0);}
                }
                
                if ($s=$ref->getSection(vCenterCloud\GuestCustomizationSectionType::class)) {
                    $this->hostname = $s->computerName;
                    
                    if ($s->adminPasswordEnabled && $s->adminPassword!==null) {
                    $this->addCredential(new credentialType(
                        null,
                        $s->adminPassword,
                        "Default ".($s->adminPasswordEnabled?"auto":"default")." admin password"
                        ));
                    }
                }
                
                $this->resourcesAllocated=[];
                
                
                if ($s=$ref->getSection(vCenterCloud\VirtualHardwareSectionType::class)) {
                    $m = $ref->getSection(vCenterCloud\VirtualHardwareSectionType::class)->getItemsByResType(VirtualHardwareSectionType::resMemory)->item[0];
                    if (!empty($m->virtualQuantity)) {
                        $this->resourcesAllocated[]=new resource("ram", (int)ceil(RASDType::convertUnits($m->virtualQuantity->value, $m->allocationUnits->value,"M")));
                    }
                }
                

                if ($s=$ref->getSection(vCenterCloud\VirtualHardwareSectionType::class)) {
                    $d = $s->getItemsByResType(VirtualHardwareSectionType::resHDD);

                    $stt=new resource("storage", 0);
                    if (!empty($d->item)) {
                        foreach ($d->item as $disk) {
                            $res = new resource("storageUnit", (int)ceil(RASDType::convertUnits($disk->virtualQuantity->value, $disk->virtualQuantityUnits->value,"M")), $disk->elementName->value);
                            $stt->value +=$res->value;
                            $this->resourcesAllocated[]=$res;
                        }
                        $this->resourcesAllocated[]=$stt;
                    }
                }
                
                $n = $ref->getSection(vCenterCloud\NetworkConnectionSectionType::class);
                if (!empty($n) && !empty($n->networkConnection)) {
                    foreach ($n->networkConnection as $conn) {
                        $iface = new netIfaceType();
                        $iface->enabled=$iface->connected=$conn->isConnected;
                        $iface->ip[]=$conn->ipAddress;
                        $iface->mac=$conn->macAddress;
                        $iface->name=$conn->network;
                        $iface->snmpIdx=$conn->networkConnectionIndex;
                        $this->addNetIface($iface);
                    }
                }
                
                
                
            }
        }
    }
}
?>