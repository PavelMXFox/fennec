<?php

namespace vCenterCloud;

use \Exception;

class MediaSettingsType extends VCloudExtensibleType {
    public $adapterType;
    public $busNumber;	
    public $deviceId;
    public $mediaImage;
    public $mediaState;
    public $mediaType;
    public $unitNumber;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->mediaImage=$this->fillElement($session, $data->mediaImage, __NAMESPACE__."\ReferenceType");
    }
}