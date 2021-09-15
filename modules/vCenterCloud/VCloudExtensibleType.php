<?php

namespace vCenterCloud;

class VCloudExtensibleType extends baseType {
    protected $__VCloudExtension=[];
    
    protected function fill($session, $data) {
        parent::fill($session, $data);        
        if (property_exists($data, "vCloudExtension")) {
            $this->__VCloudExtension = $this->fillArray($session, $data->vCloudExtension, __NAMESPACE__.'\VCloudExtensionType');
        }
        
    }
}?>