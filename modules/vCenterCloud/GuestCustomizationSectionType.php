<?php

namespace vCenterCloud;

use \Exception;

class GuestCustomizationSectionType extends sectionType {
    public $href;
    public $type;
    public $adminAutoLogonCount;
    public $adminAutoLogonEnabled;
    public $adminPassword;
    public $adminPasswordAuto;
    public $adminPasswordEnabled;
    public $changeSid;
    public $computerName;
    public $customizationScript;
    public $domainName;
    public $domainUserName;
    public $domainUserPassword;
    public $enabled;
    public $joinDomainEnabled;
    public $link;
    public $machineObjectOU;
    public $resetPasswordRequired;
    public $useOrgSettings;
    public $virtualMachineId;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->link = $this->fillElement($session, $data->link, __NAMESPACE__."\LinkType");
    }
}