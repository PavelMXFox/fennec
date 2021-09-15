<?php namespace vCenterCloud;

class IpRangesType extends VCloudExtensibleType {
    public $ipRange;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "ipRange", IpRangeType::class);
    }
    
}