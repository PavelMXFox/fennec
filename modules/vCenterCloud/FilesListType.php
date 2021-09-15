<?php

namespace vCenterCloud;

use \Exception;

class FilesListType extends VCloudExtensibleType {
    public $file;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->file = $this->fillArray($session, $data->file, __NAMESPACE__.'\FileType');
    }
    
    
}

?>