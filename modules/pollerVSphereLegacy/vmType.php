<?php namespace pollerVSphereLegacy;

use agent;
use Vmwarephp;
use agent\entity;
use agent\resource;
use agent\netIfaceType;

class vmType extends agent\vmType {
    protected $__ref;
    public $uuid;
    
    public function __construct($obj) {
        if (gettype($obj) =='object' && (get_class($obj)==ManagedObject::class || get_class($obj)==Vmwarephp\Extensions\VirtualMachine::class ) && $obj->reference->type=="VirtualMachine") {

            $this->__ref=$obj;
            $this->id = $obj->reference->_;
            $this->name = $obj->name;

            if ($runtime=$obj->runtime) {
                $this->parentType="vmHostType";
                $this->parentId=$runtime->host->reference->_;
                switch ($runtime->powerState) {
                    case "poweredOn":
                        $this->status=entity::statusOn;
                        break;
                    case "poweredOff":
                        $this->status=entity::statusOff;
                        break;
                    case "suspended":
                        $this->status=entity::statusOff;
                        break;
                    default:
                        $this->status=entity::statusFail;
                        break;
                }
                
            }
            
            if ($config=$obj->config) {
                $this->isTemplate = $config->template;
                $this->uuid=$config->uuid;
            

                if ($hardware =$config->hardware) {
                    $this->resourcesAllocated[] = new resource(resource::cpuCoresTotal ,$hardware->numCPU);
                    $this->resourcesAllocated[] = new resource(resource::cpuCoresPerSocket ,$hardware->numCoresPerSocket);
                    $this->resourcesAllocated[] = new resource(resource::cpuSockets ,(int)floor($hardware->numCPU/$hardware->numCoresPerSocket));
                    $this->resourcesAllocated[] = new resource(resource::ramTotal ,$hardware->memoryMB);
                }
            
            }
            
            if (!empty($obj->summary && !empty($obj->summary->quickStats))) {
                $oas=$obj->summary->quickStats;
                $this->resourcesUsed[] = new resource(resource::cpuMHzTotal, $oas->overallCpuUsage);
                $this->resourcesUsed[] = new resource(resource::cpuMHzPerSocket, (int)floor($oas->overallCpuUsage/$hardware->numCPU));
                $this->resourcesUsed[] = new resource(resource::ramTotal, $oas->guestMemoryUsage);
            }
            
            if ($guest=$obj->guest) {
                $this->hostname=$guest->hostName;
            }
                
            if ($guest && $guest->guestId) {
                $this->operatingSystem=new agent\operatingSystemType();
                $this->operatingSystem->type = $guest->guestId;
                $this->operatingSystem->family = $guest->guestFamily;
                $this->operatingSystem->description = $guest->guestFullName;
            }
        

            if ($guest && $netx = $guest->net) {
                foreach ($netx as $netz) {
                    $net = new netIfaceType();
                    $net->connected=$net->enabled=$netz->connected;
                    $net->name=$netz->network;
                    $net->mac=$netz->macAddress;
                    $net->internal=empty($netz->network);
                    if (!empty($netz->ipAddress)) {
                        foreach ($netz->ipAddress as $ipz) {
                            $net->ip[] = $ipz;
                        }
                    }
                    $this->netIfaces[] = $net;
                }
            }
            
            
            $dss_alc=0;
            $dss_usd=0;
            
            
            if ($storage = $obj->storage) {
                foreach($storage->perDatastoreUsage as $stor) {
                    
                    $dss=$stor->datastore->summary;
                    $dssa=$stor->uncommitted/(2**20); // allocated
                    $dssu=$stor->committed/(2**20); //used
                    
                    $this->resourcesAllocated[] = new resource("storageUnit", (int)floor($dssa), $dss->name);
                    $this->resourcesUsed[] = new resource("storageUnit", (int)floor($dssu), $dss->name);
                    
                    $dss_alc+=$dssa;
                    $dss_usd+=$dssu;
                    
                }
            }
            $this->resourcesAllocated[] = new resource("storage", (int)floor($dss_alc));
            $this->resourcesUsed[] = new resource("storage", (int)floor($dss_usd));
            
            
        }
    }
}
?>