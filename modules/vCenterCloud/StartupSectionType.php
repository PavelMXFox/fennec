<?php namespace vCenterCloud;


class StartupSectionType extends sectionType {
    public $item;
    public $any;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "any", "string");
        $this->setElement($data, "item", StartupSectionItemType::class);
    }
}