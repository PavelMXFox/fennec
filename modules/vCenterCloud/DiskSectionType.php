<?php

namespace vCenterCloud;

use \Exception;

class DiskSectionType extends VCloudExtensibleType {
    public $diskSettings;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        
        $this->diskSettings = $this->fillElement($session, $data->diskSettings,__NAMESPACE__."\DiskSettingsType");
        
    }
}