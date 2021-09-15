<?php namespace agent;

class hypervisorPollerType extends pollerBaseType {
    public $vdc;    // array of VDCs 
    public $vapp;   // array of vAPPs
    public $vm;     // array of VMs
    public $org;    // array of Orgs
    public $host;   // array of vHosts
    
    public function addVdc($obj) {
        $this->arrayAdd($this->vdc, $obj);
    }

    public function addvApp($obj) {
        $this->arrayAdd($this->vapp, $obj);
    }
    
    public function addVm($obj) {
        $this->arrayAdd($this->vm,$obj);
    }
    
    public function addOrg($obj) {
        $this->arrayAdd($this->org, $obj);
    }
    
    public function addHost($obj) {
        $this->arrayAdd($this->host, $obj);
    }
    
}
?>