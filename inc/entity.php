<?php namespace agent;

class entity extends transportType {
    public $id;
    public $status;
    public $name;
    public $desc;
    public $resourcesAllocated;
    public $resourcesUsed;
    
    
    public const statusOk="OK";
    public const statusMaintenance="MAINTENANCE";
    public const statusOn="PWRON";
    public const statusOff="PWROFF";
    public const statusFail="FAILED";
}
?>