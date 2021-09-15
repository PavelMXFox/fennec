<?php

namespace vCenterCloud;

use \Exception;

class VAppType extends AbstractVAppType {
    public $children;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
   //     $this->record = $this->fillArray($session, $data->record, __NAMESPACE__.'\QueryResultRecordType');
        $this->children = empty($data->children)?null: $this->fillElement($session, $data->children,VAppChildrenType::class);
    }
    
    
}

?>