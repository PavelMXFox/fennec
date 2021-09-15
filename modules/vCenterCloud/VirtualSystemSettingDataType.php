<?php

namespace vCenterCloud;

use \Exception;

class VirtualSystemSettingDataType extends baseType {
    public $automaticRecoveryAction;
    public $automaticShutdownAction;
    public $automaticStartupAction;
    public $automaticStartupActionDelay;
    public $automaticStartupActionSequenceNumber;
    public $caption;
    public $changeableType;
    public $configurationDataRoot;
    public $configurationFile;
    public $configurationID;
    public $configurationName;
    public $creationTime;
    public $description;
    public $elementName;
    public $generation;
    public $instanceID;
    public $logDataRoot;
    public $notes;
    public $recoveryFile;
    public $snapshotDataRoot;
    public $suspendDataRoot;
    public $swapFileDataRoot;
    public $virtualSystemIdentifier;
    public $virtualSystemType;
    public $any;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        foreach ($this as $key=>&$val) {
            if (!preg_match("/^__.*/", $key)) {
                $val = empty($data->{$key})?null:$this->fillElement($session, $data->{$key}, cimString::class );
            }
        }
    }
}