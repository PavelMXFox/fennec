<?php namespace vCenterCloud;


class NetworkConfigSectionType extends sectionType {
    public $link;
    public $networkConfig;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "link", LinkType::class);
        $this->setElement($data, "networkConfig", VAppNetworkConfigurationType::class);
    }
}