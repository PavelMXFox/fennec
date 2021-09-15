<?php namespace agent;


class vmType extends entity {
    
    public const vAppType='vAppType';
    public const hostType='hostType';
    public const vdcType='vdcType';
    
    public $parentType; // [[ hostType, vdcType, vAppType ]]
    public $parentId;
    public ?operatingSystemType $operatingSystem=null;
    public $hostname;
    public $credentials=null;
    public $netIfaces=null;
    public bool $isTemplate=false;
    
    public function addCredential(credentialType $cred) {
        $this->arrayAdd($this->credentials, $cred);
    }
    
    public function addNetIface(netIfaceType $iface) {
        $this->arrayAdd($this->netIfaces, $iface);
    }
    
}
?>