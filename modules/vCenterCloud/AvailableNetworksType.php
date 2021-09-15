<?php namespace vCenterCloud;

use \Exception;

class AvailableNetworksType extends VCloudExtensibleType {
    public $network;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->network = $this->fillElement($session, $data->network, ReferenceType::class);
    }
}