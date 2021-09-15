<?php namespace vCenterCloud;

use \Exception;

class CapabilitiesType extends VCloudExtensibleType {
    public $supportedHardwareVersions;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->supportedHardwareVersions = $this->fillElement($session, $data->supportedHardwareVersions, SupportedHardwareVersionsType::class);
    }
}