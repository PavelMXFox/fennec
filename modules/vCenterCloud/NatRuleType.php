<?php namespace vCenterCloud;

class NatRuleType extends VCloudExtensibleType {
    public $description;
    public $id;
    public $isEnabled;
    public $ruleType;
    
    public $gatewayNatRule;
    public $oneToOneBasicRule;
    public $oneToOneVmRule;
    public $portForwardingRule;
    public $vmRule;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "gatewayNatRule",GatewayNatRuleType::class);
        $this->setElement($data, "oneToOneBasicRule",NatOneToOneBasicRuleType::class);
        $this->setElement($data, "oneToOneVmRule",NatOneToOneVmRuleType::class);
        $this->setElement($data, "portForwardingRule",NatPortForwardingRuleType::class);
        $this->setElement($data, "vmRule",NatVmRuleType::class);
    }
}