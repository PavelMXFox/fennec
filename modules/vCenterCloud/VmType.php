<?php

namespace vCenterCloud;

use \Exception;

class VmType extends AbstractVAppType {
    public $needsCustomization;	  //boolean	No	none	0.9		True if this virtual machine needs customization.
    public $nestedHypervisorEnabled;       //boolean	No	none	5.1		True if hardware-assisted CPU virtualization capabilities in the host should be exposed to the guest operating system.
    public $isComputePolicyCompliant;	   //boolean	No	none	33.0		True if VM is compliant with the associated compute policies
    public $vAppScopedLocalId;      //string	No	none	1.0		A unique identifier for the virtual machine in the scope of the vApp.
    
    public $bootOptions;	      //BootOptionsType	No	always	21.0		Boot options for this virtual machine.
    public $computePolicy;	      //ComputePolicyType	No	always	33.0		A reference to a vdc compute policy. This contains VM's actual vdc compute policy reference and also optionally an add-on policy which always defines VM's sizing.
    public $computePolicyCompliance;       //ComputePolicyComplianceType	No	always	33.0		This element shows VM's non compliance details against a given compute policy.
    public $environment;           //Environment_Type  No		0.9		OVF environment section.
    public $media;	               //ReferenceType	No	none	30.0		Reference to the media object to insert in a new VM.
    public $storageProfile;	       //ReferenceType	No	always	5.1		A reference to a storage profile to be used for this object. The specified storage profile must exist in the organization vDC that contains the object. If not specified, the default storage profile for the vDC is used.
    public $vdcComputePolicy;	   //ReferenceType	No	always	31.0	33.0	A reference to a vdc compute policy. A VM will always belong to vdc compute policy. The specified vDC compute policy must exist in organization vDC. If not specified, default vDC compute policy will be used.
    public $vmCapabilities;	       //VmCapabilitiesType	No	always	5.1		Allows you to specify certain capabilities of this virtual machine.
    
    protected function fill($session, $data) {
        
        parent::fill($session, $data);
        
        $this->setElement($data, "media", ReferenceType::class);
        $this->setElement($data, "storageProfile", ReferenceType::class);
        $this->setElement($data, "vdcComputePolicy", ReferenceType::class);
        
        $this->setElement($data, "vmCapabilities", VmCapabilitiesType::class);
        $this->setElement($data, "bootOptions", BootOptionsType::class);
        $this->setElement($data, "computePolicy", ComputePolicyType::class);
        $this->setElement($data, "computePolicyCompliance", ComputePolicyComplianceType::class);
        $this->setElement($data, "environment", EnvironmentType::class);
        
    }
    
    
}

?>