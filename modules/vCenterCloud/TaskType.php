<?php namespace vCenterCloud;

use \Exception;

class TaskType extends EntityType {
    public $status;
    public $operation;
    public $operationName;
    public $serviceNamespace;
    public $startTime;
    public $endTime;
    public $expiryTime;
    public $cancelRequested;
    
    public $details;
    public $error;  // ErrorType
    public $organization; // ReferenceType
    public $owner; // ReferenceType
    public $params=[]; // baseType;
    public $progress;
    public $result; // ResultType https://vdc-repo.vmware.com/vmwb-repository/dcr-public/7a028e78-bd37-4a6a-8298-9c26c7eeb9aa/09142237-dd46-4dee-8326-e07212fb63a8/doc/doc/types/ResultType.html
    public $tasks =[]; // TasksInProgressType
    public $user; // ReferenceType;
    
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->error=new ErrorType($this->session, $data->error);
        $this->organization = new ReferenceType($this->session, $data->organization);
        $this->owner = new ReferenceType($this->session, $data->owner);
        $this->result = new ResultType($this->session, $data->result);
        $this->tasks = $this->fillArray($this->session, $data->tasks, __NAMESPACE__."\TasksInProgressType");
    }
}