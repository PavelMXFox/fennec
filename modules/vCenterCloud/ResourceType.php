<?php namespace vCenterCloud;

use \Exception;

class ResourceType extends VCloudExtensibleType {
    public $href;
    public $type;
    public $__link;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->__link = $this->fillArray($session, $data->link, __NAMESPACE__.'\LinkType');
    }
    
    public function getLinkByType($type) {
        $rv=[];
        foreach ($this->__link as $l) {
            if ($l->type == $type) {
                array_push($rv, $l);
            }
        }
        return $rv;
    }
    

}