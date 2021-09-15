<?php namespace vCenterCloud;

class SubAllocationType extends VCloudExtensibleType {
    public $edgeGateway;
    public $ipRanges;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "edgeGateway", ReferenceType::class);
        $this->setElement($data, "ipRanges", IpRangesType::class);
    }
}