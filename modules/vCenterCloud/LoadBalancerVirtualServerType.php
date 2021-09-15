<?php namespace vCenterCloud;


class LoadBalancerVirtualServerType extends VCloudExtensibleType {
    public $description;
    public $ipAddress;
    public $logging;
    public $name;
    public $pool;
    
    public $interface;
    public $loadBalancerTemplates;
    public $serviceProfile;
    
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "interface",ReferenceType::class);
        $this->setElement($data, "loadBalancerTemplates",VendorTemplateType::class);
        $this->setElement($data, "serviceProfile",LBVirtualServerServiceProfileType::class);
    }
}