<?php namespace vCenterCloud;

class RasdItemsListType extends ResourceType {
    public $item;
    
    public function __construct($session, $id=null) {
        switch (strtolower(gettype($id))) {
            case "object":
                $this->fill($session, $id);
                break;
            case "array":
                $this->fill($session, (object)$id);
                break;
            case "null":
                break;
            default:
                throw new \Exception("Unknown type ".gettype($id));
        }
        
    }
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "item",RASDType::class);
    }}
?>