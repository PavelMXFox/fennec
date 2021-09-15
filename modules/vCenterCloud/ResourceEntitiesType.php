<?php namespace vCenterCloud;

use \Exception;

class ResourceEntitiesType extends VCloudExtensibleType {

    public $resourceEntity;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->resourceEntity = $this->fillElement($session, $data->resourceEntity, ResourceReferenceType::class);
        
    }
}



