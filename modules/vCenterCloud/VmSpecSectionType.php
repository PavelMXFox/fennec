<?php

namespace vCenterCloud;

use \Exception;

class VmSpecSectionType extends baseType {
    public $modified;
    public $cpuResourceMhz;
    public $diskSection;
    public $hardwareVersion;
    public $mediaSection;
    public $memoryResourceMb;
    public $numCoresPerSocket;
    public $numCpus;
    public $osType;
    public $timeSyncWithHost;
    public $toolsGuestOsId;
    public $virtualCpuType;
    public $vmToolsVersion;
    
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->cpuResourceMhz = $this->fillElement($session, $data->cpuResourceMhz,__NAMESPACE__."\ComputeResourceType");
        $this->memoryResourceMb = $this->fillElement($session, $data->memoryResourceMb,__NAMESPACE__."\ComputeResourceType");
        $this->diskSection=$this->fillElement($session, $data->diskSection, __NAMESPACE__."\DiskSectionType");
        $this->mediaSection=$this->fillElement($session, $data->mediaSection, __NAMESPACE__."\MediaSectionType");
        $this->hardwareVersion=$this->fillElement($session, $data->hardwareVersion, __NAMESPACE__."\HardwareVersionType");
    }
    
    
}

?>