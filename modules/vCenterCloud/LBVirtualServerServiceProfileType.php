<?php namespace vCenterCloud;


class LBVirtualServerServiceProfileType extends VCloudExtensibleType {
    public $isEnabled;
    public $port;
    public $protocol;
    
    public $persistence;
    
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "persistence",LBPersistenceType::class);
    }
}