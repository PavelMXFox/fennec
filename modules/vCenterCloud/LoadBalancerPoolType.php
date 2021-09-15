<?php namespace vCenterCloud;


class LoadBalancerPoolType extends VCloudExtensibleType {
    public $description;
    public $errorDetails;
    public $id;
    public $name;
    public $operational;
    
    
    public $member;
    public $servicePort;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "member",LBPoolMemberType::class);
        $this->setElement($data, "servicePort",LBPoolServicePortType::class);
    }
}