<?php

namespace vCenterCloud;

use \Exception;

class RuntimeInfoSectionType extends sectionType {
    public $VMWareTools;
    public $otherAttributes;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        
        if (property_exists($data, "otherOttributes") && gettype($data->otherAttributes) == 'array') {
            foreach ($data->otherAttributes as $key=>$val) {
                $this->otherAttributes[$key]=$val;
            }
        }
        $this->VMWareTools = $this->fillElement($session, $data->vmWareTools, __NAMESPACE__."\VMWareTools");
    }
}