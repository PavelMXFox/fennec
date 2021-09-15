<?php namespace vCenterCloud;

class IpScopesType extends VCloudExtensibleType {
    public $ipScope;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "ipScope", IpScopeType::class);
    }
    
}