<?php

namespace vCenterCloud;

use \Exception;

class NetworkConnectionType extends VCloudExtensibleType {
    public $network;
    public $needsCustomization;
    
    public $externalIpAddress;
    public $ipAddress;
    public $ipAddressAllocationMode;
    public $isConnected;
    public $macAddress;
    public $networkAdapterType;
    public $networkConnectionIndex;
    
}

