<?php namespace pollerVSphereLegacy;

use agent;
use Vmwarephp\ManagedObject;
use agent\entity;
use agent\resource;
use \Exception;

class vHostType extends agent\vHostType {
    protected $__ref=null;
    
    public function __construct($obj) {
        if (gettype($obj) =='object' && get_class($obj)==ManagedObject::class && $obj->reference->type=="HostSystem") {
            $runtime = $obj->runtime;
            if ($runtime->inMaintenanceMode) {
                $this->status = entity::statusMaintenance;
            } elseif ($runtime->powerState == "poweredOff" || $runtime->powerState == "standBy") {
                $this->status=entity::statusOff;
            } else if ($runtime->connectionState=='connected') {
                $this->status=entity::statusOk;
            } else {
                $this->status=entity::statusFail;
            }
            
            $this->__ref=$obj;
            
            $this->id=$obj->reference->_;
            $this->name=$obj->name;
                        
            $hardware=$obj->hardware;
            
            
            if (!empty($hardware)) {
                $this->resourcesAllocated[] = new resource(resource::ramTotal, (int)floor($hardware->memorySize/(2**20)));
                $this->resourcesAllocated[] = new resource(resource::cpuMHzPerSocket,floor($hardware->cpuInfo->hz/(10**6)));
                $this->resourcesAllocated[] = new resource(resource::cpuCoresTotal, (int)$hardware->cpuInfo->numCpuCores);
                $this->resourcesAllocated[] = new resource(resource::cpuThreadsTotal, (int)$hardware->cpuInfo->numCpuThreads);
                $this->resourcesAllocated[] = new resource(resource::cpuSockets, $hardware->cpuInfo->numCpuPackages);
                $this->resourcesAllocated[] = new resource(resource::cpuCoresPerSocket,(int)floor($hardware->cpuInfo->numCpuCores/$hardware->cpuInfo->numCpuPackages));
                $this->resourcesAllocated[] = new resource(resource::cpuThreadsPerSocket,(int)floor($hardware->cpuInfo->numCpuThreads/$hardware->cpuInfo->numCpuPackages));
                $this->resourcesAllocated[] = new resource(resource::cpuMHzTotal,(int)floor($hardware->cpuInfo->hz/(10**6))*$hardware->cpuInfo->numCpuCores);
            }
            
            if (!empty($obj->summary && !empty($obj->summary->quickStats))) {
                $oas=$obj->summary->quickStats;
                $this->resourcesUsed[] = new resource(resource::cpuMHzTotal, $oas->overallCpuUsage);
                $this->resourcesUsed[] = new resource(resource::cpuMHzPerSocket, (int)floor($oas->overallCpuUsage/$hardware->cpuInfo->numCpuCores));
                $this->resourcesUsed[] = new resource(resource::ramTotal, $oas->overallMemoryUsage);
            }
          
            $dss_alc=0;
            $dss_usd=0;
            
            foreach ($obj->datastore as $ds) {
                $dss=$ds->summary;

                $dssa=$dss->capacity/(2**20); // allocated
                $dssu=($dss->capacity-$dss->freeSpace)/(2**20); //used
                
                $this->resourcesAllocated[] = new resource("storageUnit", $dssa, $dss->name);
                $this->resourcesUsed[] = new resource("storageUnit", $dssu, $dss->name);
                
                $dss_alc+=$dssa;
                $dss_usd+=$dssu;
            }
            
            $this->resourcesAllocated[] = new resource("storage", $dss_alc);
            $this->resourcesUsed[] = new resource("storage", $dss_usd);
        } else {
            return false;
        }
    }

    public function getPath() {
        if (empty($this->__ref)) {
            throw new Exception("Unable to call method on empty ref");
        } else {
            return module::getFolderPath($this->__ref);
        }
    }

    public function getVMs() {
        if (empty($this->__ref)) {
            throw new Exception("Unable to call method on empty ref");
        } else {
            return $this->__ref->vm;
        }
    }
    
}
?>