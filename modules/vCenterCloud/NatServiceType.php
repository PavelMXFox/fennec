<?php namespace vCenterCloud;

class NatServiceType extends NetworkServiceType {
    public $externalIp;
    public $isEnabled;
    public $natType;
    public $policy;
    
    public $natRule; 
    
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "natRule",NatRuleType::class);
    }
}