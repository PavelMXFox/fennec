<?php namespace vCenterCloud;


class NetworkType extends sectionType {
    public $name;
    public $description;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "description", MsgType::class);
    }
}