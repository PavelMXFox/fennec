<?php namespace vCenterCloud;

class NatPortForwardingRuleType extends VCloudExtensibleType {
    public $externalIpAddress;
    public $externalPort;
    public $internalIpAddress;
    public $internalPort;
    public $protocol;
}