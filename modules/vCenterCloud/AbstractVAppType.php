<?php

namespace vCenterCloud;

use \Exception;

class AbstractVAppType extends ResourceEntityType {
    public $deployed;
    public $dateCreated;
    public $section;
    public $vAppParent;
    
    protected function fill($session, $data) {
        
        parent::fill($session, $data);
        
        $this->vAppParent = empty($data->vAppParent)?null:new ReferenceType($session, $data->vAppParent);
        $this->section = $this->fillArray($session, $data->section, null);
    }
    
    public function getSection($type) {
        foreach ($this->section as $s) {
            if (get_class($s) == $type) {
                return $s;
            }
        }
        return false;
    }
    
    public function getSectionTypes() {
        $rv=[];
        foreach ($this->section as $s) {
            $rv[] = (get_class($s));
        }
        return $rv;
        
    }
    
    
}

?>