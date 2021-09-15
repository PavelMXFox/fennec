<?php

namespace vCenterCloud;

class PropertySectionType extends  baseType {
    public $otherAttributes;
    public $property;
    public $any;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "any", "string");
        $this->setElement($data, "otherAttributes", "string");
        $this->setElement($data, "property", PropertyType::class);
    }
}