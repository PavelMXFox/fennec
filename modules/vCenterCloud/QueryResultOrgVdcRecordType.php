<?php

namespace vCenterCloud;

use \Exception;

class QueryResultOrgVdcRecordType extends QueryResultRecordType {
    public $name;
    public $description;
    public $computeProviderScope;
    public $networkProviderScope;
    public $isEnabled;
    public $cpuAllocationMhz;
    public $cpuLimitMhz;
    public $cpuUsedMhz;
    public $cpuReservedMhz;
    public $memoryAllocationMB;
    public $memoryLimitMB;
    public $memoryUsedMB;
    public $memoryReservedMB;
    public $storageLimitMB;
    public $storageUsedMB;
    public $providerVdcName;
    public $providerVdc;
    public $orgName;
    public $numberOfVApps;
    public $numberOfUnmanagedVApps;
    public $numberOfMedia;
    public $numberOfDisks;
    public $numberOfVAppTemplates;
    public $isBusy;
    public $status;
    public $numberOfDatastores;
    public $numberOfStorageProfiles;
    public $numberOfVMs;
    public $numberOfRunningVMs;
    public $networkPoolUniversalId;
    public $numberOfDeployedVApps;
    public $numberOfDeployedUnmanagedVApps;
    public $isThinProvisioned;
    public $isFastProvisioned;
}

?>