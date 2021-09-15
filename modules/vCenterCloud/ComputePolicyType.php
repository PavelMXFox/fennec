<?php

namespace vCenterCloud;

use \Exception;

class ComputePolicyType extends ResourceType {
    public $vmPlacementPolicyFinal;
    public $vmSizingPolicyFinal;
    public $vmPlacementPolicy;
    public $vmSizingPolicy;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "vmPlacementPolicy", ReferenceType::class);
        $this->setElement($data, "vmSizingPolicy", ReferenceType::class);
    }
}