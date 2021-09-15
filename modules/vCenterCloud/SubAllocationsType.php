<?php namespace vCenterCloud;

class SubAllocationsType extends ResourceType {
    public $subAllocation;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "subAllocation", SubAllocationType::class);
    }
}