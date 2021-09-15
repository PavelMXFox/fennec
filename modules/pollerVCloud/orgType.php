<?php namespace pollerVCloud;

use agent;
use pollerVCloud;
use vCenterCloud;

class orgType extends agent\orgType {
    public function __construct($ref) {
        if (gettype($ref)=='object') {
            if (get_class($ref) == vCenterCloud\OrgType::class) {
                $this->name = $ref->name;
                $this->id = module::extractID($ref->href);
                $this->desc = $ref->description;
                
                $this->fullName=$ref->fullName;

            }
        }
    }
}
?>