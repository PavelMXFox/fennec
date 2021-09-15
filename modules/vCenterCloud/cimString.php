<?php

namespace vCenterCloud;

use \Exception;

class cimString extends baseType {
    public $value;
    public $otherAttributes;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        
        if (property_exists($data, "otherOttributes") && gettype($data->otherAttributes) == 'array') {
            foreach ($data->otherAttributes as $key=>$val) {
                $this->otherAttributes[$key]=$val;
            }
        }
    }
}