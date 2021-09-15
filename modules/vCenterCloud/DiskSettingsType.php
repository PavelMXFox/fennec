<?php

namespace vCenterCloud;

use \Exception;

class DiskSettingsType extends VCloudExtensibleType {
    public $adapterType;
    public $busNumber;
    public $disk;
    public $diskId;
    public $sizeMb;
    public $storageProfile;
    public $thinProvisioned;
    public $unitNumber;
    public $virtualQuantity;
    public $virtualQuantityUnit;
    public $iops;
    public $overrideVmDefault;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->disk = $this->fillElement($session, $data->disk,__NAMESPACE__."\ReferenceType");
        $this->storageProfile = $this->fillElement($session, $data->storageProfile,__NAMESPACE__."\ReferenceType");
    }
}