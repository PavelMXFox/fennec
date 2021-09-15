<?php

namespace vCenterCloud;

use \Exception;

class PlatformSectionType extends sectionType {
    public $kind;
    public $version;
    public $vendor;
    public $locale;
    public $timezone;
    public $any;

    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "kind", cimString::class);
        $this->setElement($data, "version", cimString::class);
        $this->setElement($data, "vendor", cimString::class);
        $this->setElement($data, "locale", cimString::class);
        $this->setElement($data, "any", "string");
        
    }
}