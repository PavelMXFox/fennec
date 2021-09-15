<?php

namespace vCenterCloud;

use \Exception;

class ResourceEntityType extends EntityType {
    public $status;
    public $files;
        
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->files = (empty($data->files))?null:new FilesListType($session, $data->files);
    }
    
    
}

?>