<?php namespace vCenterCloud;


class FirewallServiceType extends NetworkServiceType {
    public $defaultAction;
    public $logDefaultAction;
    
    public $firewallRule;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "firewallRule",FirewallRuleType::class);
    }
    
}