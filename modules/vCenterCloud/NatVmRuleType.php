<?php namespace vCenterCloud;

class NatVmRuleType extends VCloudExtensibleType {
    public $externalIpAddress;
    public $externalPort;
    public $internalPort;
    public $protocol;
    public $vAppScopedVmId;
    public $vmNicId;
}