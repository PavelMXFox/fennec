<?php

namespace vCenterCloud;

use \Exception;

class OrgListType extends ResourceType {
    public $name;
    public $rel;
    
    public $org=[]; // array of ReferenceType
    
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->org = $this->fillArray($this->__session, $data->org, __NAMESPACE__."\ReferenceType");
    }
}

?>