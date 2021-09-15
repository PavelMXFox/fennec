<?php namespace vCenterCloud;

use \Exception;

class EntityType extends IdentifiableResourceType {
    public $name;
    public $description;
    public $tasks;
    
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->tasks = $this->fillArray($session, $data->tasks, __NAMESPACE__."\TasksInProgressType");
    }
}