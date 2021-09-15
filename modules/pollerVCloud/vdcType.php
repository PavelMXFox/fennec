<?php namespace pollerVCloud;


use agent\resource;

class vdcType extends \agent\vdcType {
    
    public function __construct($ref) {
        if (gettype($ref)=='object') {
            if (get_class($ref) == 'vCenterCloud\QueryResultOrgVdcRecordType') {
                $this->name = $ref->name;
                $this->id = module::extractID($ref->href);
                $this->desc = $ref->description;
                
                $this->orgName=$ref->orgName;
                $this->resourcesAllocated=[];
                $this->resourcesAllocated[]=new resource("cpuMHz", $ref->cpuLimitMhz);
                $this->resourcesAllocated[]=new resource("ram", $ref->memoryLimitMB);
                $this->resourcesAllocated[]=new resource("storage", $ref->storageLimitMB);

                $this->resourcesUsed=[];
                $this->resourcesUsed[]=new resource("cpuMHz", $ref->cpuUsedMhz);
                $this->resourcesUsed[]=new resource("ram", $ref->memoryUsedMB);
                $this->resourcesUsed[]=new resource("storage", $ref->storageUsedMB);
                
            }
        }
    }
    
}
?>