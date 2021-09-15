<?php 

namespace vCenterCloud;

use \Exception;

class SessionType extends ResourceType{    
    public $user = null;
    public $org=null;
    public $userId=null;
    public $roles = null;
    public $locationId=null;
    public $AuthorizedLocations=[];
    
    
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->AuthorizedLocations = new AuthorizedLocationsType($session, $data->authorizedLocations);
    }
}

?>