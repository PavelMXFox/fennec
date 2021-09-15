<?php namespace vCenterCloud;


class FirewallRuleType extends VCloudExtensibleType {
    public $description;
    public $destinationIp;
    public $destinationPortRange;
    public $enableLogging;
    public $icmpSubType;
    public $id;
    public $isEnabled;
    public $policy;
    public $sourceIp;
    public $sourcePortRange;
    
    public $destinationVm;
    public $protocols;
    public $sourceVm;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "destinationVm",VmSelectionType::class);
        $this->setElement($data, "sourceVm",VmSelectionType::class);
        $this->setElement($data, "protocols",DhcpServiceType::class);
    }
    
}