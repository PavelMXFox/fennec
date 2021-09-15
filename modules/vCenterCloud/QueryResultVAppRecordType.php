<?php

namespace vCenterCloud;

use \Exception;

class QueryResultVAppRecordType extends QueryResultRecordType {
    public $name;
    public $vdc;
    public $vdcName;
    public $isPublic;
    public $isEnabled;
    public $isBusy;
    public $creationDate;
    public $status;
    public $ownerName;
    public $isDeployed;
    public $isInMaintenanceMode;
    public $isAutoNature;
    public $isExpired;
    public $snapshot;
    public $snapshotCreated;
    public $description;

}