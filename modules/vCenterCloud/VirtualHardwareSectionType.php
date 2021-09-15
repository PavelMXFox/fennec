<?php

namespace vCenterCloud;

use \Exception;

class VirtualHardwareSectionType extends sectionType {
    public $id;
    public $transport;
    
    public $system;
    public $item;
    public $any;
    
    
    public const resCPU=3;
    public const resMemory=4;
    public const resIDEBus=5;
    public const resSCSIBus=6;
    public const resLAN=10;
    public const resFDD=14;
    public const resODD=15;
    public const resHDD=17;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->system = $this->fillElement($session, $data->system, VirtualSystemSettingDataType::class);
        $this->any = $this->fillElement($session, $data->any, null);
        $this->item = $this->fillElement($session, $data->item, RASDType::class);
    }
    
    public function getItemsDesc() {
        $rv=[];
        foreach ($this->item as $item) {
            $rv[] = $item->description->value;
        }
        return $rv;
    }
    
    public function getItemsByDesc($ref):RasdItemsListType {
        $rv = new RasdItemsListType($this->__session);
        foreach ($this->item as $item) {
            if (count(explode("/", $ref))>1) {
                //regexp
                if (preg_match($ref, $item->description->value)) {
                    $rv->item[] =  $item;
                }
                
            } else {
                //general text
                if (strtolower($item->description->value) == strtolower($ref)) {
                    $rv->item[] =  $item;
                }
            }
            
            
        }
        return $rv;
    }
    
    
    public function getItemsByResType($type):RasdItemsListType {
        $rv = new RasdItemsListType($this->__session);
        foreach ($this->item as $item) {
            if (strtolower($item->resourceType->value) == $type) {
                $rv->item[] =  $item;
            }
        }
        return $rv;
    }
    
    public function getMemory():RASDType {
        $href = $this->otherAttributes->{"{http://www.vmware.com/vcloud/v1.5}href"}."memory";
        return new RASDType($this->__session, request::quickExec(request::METHOD_GET, $href,null,$this->__session->token));
    }
    
    public function getDisks():RasdItemsListType {
        $href = $this->otherAttributes->{"{http://www.vmware.com/vcloud/v1.5}href"}."disks";
        return new RasdItemsListType($this->__session, request::quickExec(request::METHOD_GET, $href,null,$this->__session->token));
    }
    
    public function getCpu() {
        
    }
}