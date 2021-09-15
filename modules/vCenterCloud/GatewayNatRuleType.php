<?php namespace vCenterCloud;

class GatewayNatRuleType extends VCloudExtensibleType {
    public $icmpSubType;
    public $originalIp;
    public $originalPort;
    public $protocol;
    public $translatedIp;
    public $translatedPort;
    
    public $interface;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "interface",ReferenceType::class);
    }
}