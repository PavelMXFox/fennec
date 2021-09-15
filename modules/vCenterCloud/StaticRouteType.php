<?php namespace vCenterCloud;

class StaticRouteType extends VCloudExtensibleType {
    public $interface;
    public $name;
    public $network;
    public $nexthopIp;
    
    public $gatewayInterface;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "gatewayInterface",ReferenceType::class);
    }
}