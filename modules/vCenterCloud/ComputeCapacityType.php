<?php namespace vCenterCloud;

use \Exception;

class ComputeCapacityType extends VCloudExtensibleType {
    public $cpu;
    public $memory;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->cpu = $this->fillElement($session, $data->cpu, CapacityWithUsageType::class);
        $this->memory=$this->fillElement($session, $data->memory, CapacityWithUsageType::class);
    }
}

