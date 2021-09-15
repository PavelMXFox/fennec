<?php namespace vCenterCloud;

class LeaseSettingsSectionType extends sectionType {
    public $deploymentLeaseExpiration;
    public $deploymentLeaseInSeconds;
    public $storageLeaseExpiration;
    public $storageLeaseInSeconds;
    public $link;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "link", LinkType::class);
    }
        
    
}