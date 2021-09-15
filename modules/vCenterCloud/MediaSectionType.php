<?php

namespace vCenterCloud;

use \Exception;

class MediaSectionType extends VCloudExtensibleType {
    public $mediaSettings;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        
        $this->mediaSettings = $this->fillElement($session, $data->mediaSettings,__NAMESPACE__."\MediaSettingsType");
        
    }
}