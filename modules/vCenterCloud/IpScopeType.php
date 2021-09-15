<?php namespace vCenterCloud;

class IpScopeType extends VCloudExtensibleType {
    public $dns1;
    public $dns2;
    public $dnsSuffix;
    public $gateway;
    public $isEnabled;
    public $isInherited;
    public $netmask;
    public $subnetPrefixLength;
    
    public $allocatedIpAddress;
    public $subAllocations;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "allocatedIpAddress", IpAddressesType::class);
        $this->setElement($data, "subAllocations", SubAllocationsType::class);
    }
}