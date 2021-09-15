<?php namespace vCenterCloud;


class VAppNetworkConfigurationType extends ResourceType {
    public $networkName;
    public $configuration;
    public $description;
    public $isDeployed;
    
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "configuration", NetworkConfigurationType::class);

    }
}