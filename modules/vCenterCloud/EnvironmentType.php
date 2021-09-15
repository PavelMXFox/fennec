<?php

namespace vCenterCloud;

use \Exception;

class EnvironmentType extends baseType {
    public $id;
    public $section;
    public $entity;
    public $any;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "section", sectionType::class);
        $this->setElement($data, "entity", entity_Type::class);
        $this->setElement($data, "any", "string");
        
    }
}