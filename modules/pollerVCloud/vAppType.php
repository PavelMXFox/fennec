<?php namespace pollerVCloud;

use pollerVCloud;
use agent\entity;
use agent\resource;
use vCenterCloud;

class vAppType extends \agent\vAppType {
    protected ?vCenterCloud\QueryResultVAppRecordType $__ref=null; // reference object for getVMS method
    public function __construct($ref) {
        
        $this->__ref=$ref;
        
        if (gettype($ref)=='object') {
            if (get_class($ref) == 'vCenterCloud\QueryResultVAppRecordType') {
                $this->name = $ref->name;
                $this->vdcId=module::extractID($ref->vdc);
                $this->id = module::extractID($ref->href);
                $this->vdcName=$ref->vdcName;
                
                
                switch ($ref->status) {
                    case "POWERED_ON":
                        $this->status=entity::statusOn;
                        break;
                    default:
                        $this->status=entity::statusOff;
                        break;
                        
                }
                
                $this->desc=$ref->description;
                $this->resourcesAllocated=[];
                if (!empty($ref->otherAttributes->cpuAllocationInMhz)) { $this->resourcesAllocated[]=new resource("cpuMHz", $ref->otherAttributes->cpuAllocationInMhz); }
                $this->resourcesAllocated[]=new resource("storage", (int)ceil($ref->otherAttributes->storageKB/1024));
                
            }
        }
    }
    
    public function getVms() {
        $vapp = $this->__ref->getFullObject();
        $rv=[];

        foreach ($vapp->children->vm as $vmref) {
            $vm = new vmType($vmref);
            $vm->parentId=$this->id;
            $rv[]=$vm;
        };
        
        return $rv;
    }
}
?>