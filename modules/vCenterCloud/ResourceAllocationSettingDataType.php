<?php

namespace vCenterCloud;

use \Exception;

class ResourceAllocationSettingDataType extends baseType {
    public $address;
    public $addressOnParent;
    public $allocationUnits;
    public $automaticAllocation;
    public $automaticDeallocation;
    public $caption;
    public $changeableType;
    public $configurationName;
    public $connection;
    public $consumerVisibility;
    public $description;
    public $elementName;
    public $generation;
    public $hostResource;
    public $instanceID;
    public $limit;
    public $mappingBehavior;
    public $otherResourceType;
    public $parent;
    public $poolID;
    public $reservation;
    public $resourceSubType;
    public $resourceType;
    public $virtualQuantity;
    public $virtualQuantityUnits;
    public $weight;
    public $any;
    public $otherAttributes;
    public $bound;
    public $configuration;
    public $required;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        foreach ($this as $key=>&$val) {
            if (!preg_match("/^__.*/", $key)) {
                $val = empty($data->{$key})?null:$this->fillElement($session, $data->{$key}, __NAMESPACE__."\cimString" );
            }
        }
    }
}

