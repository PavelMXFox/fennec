<?php namespace vCenterCloud;

class VAppChildrenType extends VCloudExtensibleType {
    public $vApp;
    public $vm;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->vm = empty($data->vm)?null: $this->fillElement($session, $data->vm,VmType::class);
    }
}
?>