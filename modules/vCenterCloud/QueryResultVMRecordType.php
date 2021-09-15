<?php

namespace vCenterCloud;

use \Exception;

class QueryResultVMRecordType extends QueryResultRecordType {
    public $name;
    public $containerName;
    public $container;
    public $ownerName;
    public $owner;
    public $vdc;
    public $vappScopedLocalId;
    public $isVAppTemplate;
    public $isDeleted;
    public $guestOs;
    public $numberOfCpus;
    public $memoryMB;
    public $status;
    public $networkName;
    public $network;
    public $ipAddress;
    public $isBusy;
    public $isDeployed;
    public $isPublished;
    public $catalogName;
    public $hardwareVersion;
    public $vmToolsStatus;
    public $isInMaintenanceMode;
    public $isAutoNature;
    public $storageProfileName;
    public $snapshot;
    public $snapshotCreated;
    public $gcStatus;
    public $autoUndeployDate;
    public $autoDeleteDate;
    public $isAutoUndeployNotified;
    public $isAutoDeleteNotified;
    public $isComputePolicyCompliant;
    public $vmSizingPolicyId;
    public $vmPlacementPolicyId;
}