<?php namespace vCenterCloud;

class NetworkConfigurationType extends VCloudExtensibleType {
    public $backwardCompatibilityMode;
    public $connected;
    public $distributedInterface;
    public $fenceMode;
    public $guestVlanAllowed;
    public $retainNetInfoAcrossDeployments;
    public $subInterface;
    
    
    public $features;
    public $ipScopes;
    public $parentNetwork;
    public $routerInfo;
    public $syslogServerSettings;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "features", NetworkFeaturesType::class);
        $this->setElement($data, "ipScopes", IpScopesType::class);
        $this->setElement($data, "parentNetwork", ReferenceType::class);
        $this->setElement($data, "routerInfo", RouterInfoType::class);
        $this->setElement($data, "syslogServerSettings", SyslogServerSettingsType::class);
        

    }
}