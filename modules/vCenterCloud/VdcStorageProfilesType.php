<?php namespace vCenterCloud;

use \Exception;

class VdcStorageProfilesType extends VCloudExtensibleType {
    public $vdcStorageProfile;
    
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->vdcStorageProfile = $this->fillElement($session, $data->vdcStorageProfile, ReferenceType::class);
    }
}


