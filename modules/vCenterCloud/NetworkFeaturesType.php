<?php namespace vCenterCloud;

class NetworkFeaturesType extends baseType {
    public $dhcpService;
    public $firewallService;
    public $ipsecVpnService;
    public $loadBalancerService;
    public $natService;
    public $staticRoutingService;

    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "dhcpService",DhcpServiceType::class);
        $this->setElement($data, "firewallService",FirewallServiceType::class);
        $this->setElement($data, "ipsecVpnService",IpsecVpnServiceType::class);
        $this->setElement($data, "loadBalancerService",LoadBalancerServiceType::class);
        $this->setElement($data, "natService",NatServiceType::class);
        $this->setElement($data, "staticRoutingService",StaticRoutingServiceType::class);
    }
}