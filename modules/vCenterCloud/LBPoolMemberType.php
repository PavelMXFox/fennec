<?php namespace vCenterCloud;


class LBPoolMemberType extends VCloudExtensibleType {
    public $condition;
    public $ipAddress;
    public $weight;
    
    public $servicePort;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "servicePort",LBPoolServicePortType::class);
    }
    
}