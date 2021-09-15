<?php

namespace vCenterCloud;

use \Exception;

class OperatingSystemSectionType extends sectionType {
    public $id;
    public $version;
    public $description;
    public $any;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->info = $this->fillElement($session, $data->info, __NAMESPACE__."\MsgType");
        $this->description = $this->fillElement($session, $data->description, __NAMESPACE__."\MsgType");
        $this->any = $this->fillElement($session, $data->any, null);
    }
}

