<?php namespace pollerVSphereLegacy;

use agent;
use Vmwarephp\ManagedObject;
use agent\entity;

class vdcType extends agent\vdcType {
    public function __construct($obj) {
        if (gettype($obj) =='object' && get_class($obj)==ManagedObject::class && $obj->reference->type=="Datacenter") {
            
            $this->id=$obj->reference->_;
            $this->name=$obj->name;
            
        } else {
            return false;
        }
    }
}
?>