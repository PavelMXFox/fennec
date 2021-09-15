<?php namespace vCenterCloud;

use \Exception;

class VdcType extends EntityType {
    public $status;
    public $allocationModel;
    public $availableNetworks;
    public $capabilities;
    public $computeCapacity;
    public $computeProviderScope;
    public $defaultComputePolicy;
    public $description;
    public $isEnabled;
    public $maxComputePolicy;
    public $networkProviderScope;
    public $networkQuota;
    public $nicQuota;
    public $resourceEntities;
    public $uUsedNetworkCount;
    public $vCpuInMhz2;
    public $vdcStorageProfiles;
    public $vmQuota;
    
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->availableNetworks = $this->fillElement($session, $data->availableNetworks, AvailableNetworksType::class);
        $this->capabilities=$this->fillElement($session, $data->capabilities, CapabilitiesType::class);
        $this->computeCapacity=$this->fillElement($session, $data->computeCapacity, ComputeCapacityType::class);
        $this->defaultComputePolicy=$this->fillElement($session, $data->defaultComputePolicy, ReferenceType::class);
        $this->maxComputePolicy=$this->fillElement($session, $data->maxComputePolicy, ReferenceType::class);
        $this->resourceEntities=$this->fillElement($session, $data->resourceEntities, ResourceEntitiesType::class);
        $this->vdcStorageProfiles=$this->fillElement($session, $data->vdcStorageProfiles, VdcStorageProfilesType::class);
    }
}

