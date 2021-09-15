<?php

namespace vCenterCloud;

use \Exception;

class entity_Type extends baseType {
    public $id;
    public $section;
    public $any;

    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "section", sectionType::class);
        $this->setElement($data, "any", "string");
        
    }
}