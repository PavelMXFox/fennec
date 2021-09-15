<?php namespace vCenterCloud;

use \Exception;

class MetadataType extends ResourceType {
    public $metadataEntry;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->metadataEntry = new MetadataEntryType($session, $data->metadataEntry);
    }
}