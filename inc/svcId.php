<?php namespace agent;

class svcId implements \JsonSerializable {
    public $equipId;
    public $svcId;
    
    public function __construct($equipId, $svcId) {
        $this->svcId=$svcId;
        $this->equipId=$equipId;
    }
    
    public function jsonSerialize() {
        return [
            "equipId"=>$this->equipId,
            "svcId"=>$this->svcId
        ];
    }
}
?>