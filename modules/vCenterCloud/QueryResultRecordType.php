<?php

namespace vCenterCloud;

use \Exception;
use agent\vmType;

class QueryResultRecordType extends baseType {
    public $href;
    public $id;
    public $_type;
    public $type;
    
    public $__link=[];
    public $metadata=[];
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->__link = $this->fillArray($session, $data->link, __NAMESPACE__."\LinkType");
        $this->metadata = $this->fillArray($session, $data->link, __NAMESPACE__."\MetadataType");
    }
    
    public function getFullObject() {
        switch(get_class($this)) {
            case "vCenterCloud\QueryResultOrgVdcRecordType":
                return new VdcType($this->__session, request::quickExec(request::METHOD_GET, $this->href,null,$this->__session->token));
            case "vCenterCloud\QueryResultVAppRecordType":
                return new VAppType($this->__session, request::quickExec(request::METHOD_GET, $this->href,null,$this->__session->token));
            case "vCenterCloud\QueryResultVMRecordType":
                return new vmType($this->__session, request::quickExec(request::METHOD_GET, $this->href,null,$this->__session->token));
            default:
                return false;
                
        }
    }
    
}

?>