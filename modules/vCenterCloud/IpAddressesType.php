<?php namespace vCenterCloud;

class IpAddressesType extends VCloudExtensibleType {
    public $ipAddress;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "ipAddress", "string");
    }
}