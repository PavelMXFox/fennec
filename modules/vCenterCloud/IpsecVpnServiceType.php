<?php namespace vCenterCloud;

class IpsecVpnServiceType extends NetworkServiceType {
    public $externalIpAddress;
    public $publicIpAddress;
    
    public $ipsecVpnTunnel;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "ipsecVpnTunnel",IpsecVpnTunnelType::class);
    }
}