<?php namespace vCenterCloud;

use \Exception;

class AuthorizedLocationsType extends baseType {
    public $location=[];
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->location = $this->fillArray($session, $data->location, __NAMESPACE__."\AuthorizedLocationType");
    }
}