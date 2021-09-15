<?php namespace vCenterCloud;


class NetworkSectionType extends sectionType {
    public $network;
    public $any;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "any", "string");
        $this->setElement($data, "item", NetworkType::class);
    }
}