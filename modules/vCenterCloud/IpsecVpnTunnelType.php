<?php namespace vCenterCloud;

class IpsecVpnTunnelType extends VCloudExtensibleType {
    public $description;
    public $encryptionProtocol;
    public $errorDetails;
    public $isEnabled;
    public $isOperational;
    public $mtu;
    public $name;
    public $peerIpAddress;
    public $peerNetworkAddress;
    public $peerNetworkMask;
    public $sharedSecret;
    
    public $ipsecVpnPeer;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "ipsecVpnPeer",IpsecVpnPeerType::class);
    }
}