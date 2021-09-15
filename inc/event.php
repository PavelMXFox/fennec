<?php namespace agent; 

class event extends transportType {
    public $uid;
    public $entryStamp;
    public $refType=null;
    public $refId=null;
    public eventData $eventData;
    
    public ?db_iface $__db;
    public const rtEquipment="EQUIP";
    public const rtAgent="AGENT";
    public const rtService="SERVX";
    
    public function __construct($id=null, ?db_iface $db=null) {
        $this->eventData = new eventData();
        $this->entryStamp=time();
        parent::__construct($id, $db);
    }
    
    public function fill($data) {
        parent::fill($data);
        $this->setElement($data, "eventData", eventData::class);
    }
    
    public function save(?db_iface $db=null) {
        if (empty($this->uid)) {
            $this->uid = uniqid(null,1).".".$this->refType.".".(1000000+$this->refId);
        }
        
        if (empty($this->entryStamp)) { $this->entryStamp=time(); }
        
        if (!empty($db)) {
            $this->__db = $db;
        } else if (empty($this->__db)) {
            $this->__db = new db();
        }
        
        $this->__db->saveData("events", [[
            "uid"=>$this->uid,
            "entryStamp"=>$this->entryStamp,
            "refType"=>$this->refType,
            "refId"=>$this->refId,
            "eventData"=>json_encode($this->eventData)
        ]], "uid");
        
    }
    
    public function delete() {
        if (empty($this->__db)) {
            $this->__db = new db();
        }
        
        $this->__db->dropData("events","`uid`='".$this->uid."'");
        
    }
    
    public static function getEvents(?db_iface $db = null, $new=true) {
        
        if (empty($db)) {
            $db = new db();
        }
        
        $rv = [];
        
        foreach ($db->loadData("events",$new?"syncMark is NULL":"") as $row) {
            $rv[] = new self($row, $db);
        }
        return $rv;   
    }
    
}

?>