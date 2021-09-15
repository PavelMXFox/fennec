<?php

namespace vCenterCloud;

use \Exception;

class ComputeResourceType extends sectionType {
    public $configured;
    public $limit;
    public $reservation;
    public $shares;
    public $sharesLevel;
    public $vCloudExtension;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        //$this->sharesLevel = $this->fillElement($session, $data->sharesLevel,__NAMESPACE__."\ResourceSharesLevelType");
        $this->vCloudExtension = $this->fillElement($session, $data->sharesLevel,__NAMESPACE__."\VCloudExtensionType");
        
    }
    
}