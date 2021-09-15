<?php

namespace vCenterCloud;

use \Exception;

class sectionType extends baseType {
    public $required;
    public $info;

    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->info = empty($data->info)?null:$this->fillElement($session, $data->info, __NAMESPACE__."\MsgType");
    }
}