<?php namespace vCenterCloud;

use \Exception;

class SupportedHardwareVersionsType extends VCloudExtensibleType {
    public $supportedHardwareVersion;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->supportedHardwareVersion = $this->fillElement($session, $data->supportedHardwareVersion, SupportedHardwareVersionType::class);
    }
}