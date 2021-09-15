<?php

namespace vCenterCloud;

use \Exception;

class QueryResultRecordsType extends ContainerType {
    public $record=[];
    

    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->record = $this->fillArray($session, $data->record, __NAMESPACE__.'\QueryResultRecordType');
    }
    
    
}

?>