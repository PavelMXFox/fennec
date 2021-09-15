<?php namespace agent;

use \Exception;


/**
 * 
 * @property request $request Request type 
 *
 **/

class serviceType extends serviceBaseType {
    public $id;
    public $svcId;
    public $equipId;
    public $command;
    public $weigth;
    public $minPeriod;
    
    public $lastPollStartStamp=0;
    public $lastPollStatus=self::statusUnknown;
    public $lastPollStamp=0;
    
    protected ?request $request;
    protected ?db_iface $__db=null;
    
    protected function checkDb() {
        if (empty($this->__db)) { $this->__db=new db(); }
    }
    
    public function __construct($id=null, ?db_iface $db=null) {
        $this->request = new request();
        if (gettype($id) == 'array') {
            $this->fill((object)$id);
        } elseif(gettype($id)=='object' and $id instanceof svcId) {
            $this->load($id);
        } elseif (gettype($id)=='object') {
            $this->fill($id);
        } elseif (gettype($id) == 'string' && $x = json_decode($id)) {
            $this->fill($x);
        } elseif ($id===null) {
            
        } else {
            throw new Exception("Invalid id-type");
        }
    }
    
    protected function load($svcId, $equipId=null) {
        $this->checkDb();
        if ($svcId instanceof svcId) {
            $equipId=$svcId->equipId;
            $svcId=$svcId->svcId;
        } elseif ($equipId === null) {
            throw new \Exception("Empty equipId not allowed here",701);
        }
        
        $r = $this->__db->loadData('services',"`svcId`='".$svcId."' and `equipId` = '".$equipId."'");
        if (empty($r)) {
            throw new \Exception("Service #".$equipId.":".$svcId." not found.",404);
        }
        
        $this->fill($r[0]);
    }
    
    protected function fill($id) {
        parent::fill($id);
        if (gettype($id)=='array') { $id = (object)$id; }
        $this->request=request::jsonDecode($id->request);
    }
    
    public function __get($key) {
        switch ($key) {
            case "request":
                return $this->request;
        }
    }
    
    public function __set($key, $val) {
        switch ($key) {
            case "request":
                if (gettype($val) == 'object' && $val instanceof request ) {
                    $this->request=$val;
                    $this->command=$val->command;
                    $this->weigth=$val->weigth;
                    if (empty($this->minPeriod)) {
                        if (empty($this->minPeriod=$val->minPeriod) && ($val->module)) {
                            $this->minPeriod = $val->module->minPeriod;
                        }
                    }
                } else {
                    throw new \Exception("Invalid class for request");
                }
        }
    }
    
    public function save(?db_iface $db=null,$setSync=false) {
        if (isset($db)) { $this->__db = $db;}
        $this->checkDb();
        if ($setSync) { $this->setSync(); }
        
        // update set default
        $e = [
            "svcId"=>$this->svcId,
            "equipId"=>$this->equipId,
            "id"=>$this->id,
            "request"=>json_encode($this->request),
            "command"=>$this->request->command,
            "weigth"=>$this->weigth,
            "syncStamp"=>$this->syncStamp,
            "status"=>$this->status,
            "lastCheckStamp"=>$this->lastCheckStamp,
            "lastPollStamp"=>$this->lastPollStamp,
            "lastPollStatus"=>$this->lastPollStatus,
            "inState"=>$this->inState,
            "minPeriod"=>$this->minPeriod,
            "preFailMode"=> is_null($this->preFailMode)?null:($this->preFailMode?1:0),
            "preFailTimeoutOverride"=>is_null($this->preFailTimeoutOverride)?null:($this->preFailTimeoutOverride?1:0),
        ];
        
        if (empty($this->id) || empty($this->__db->loadData('services','id='.$this->id))) {
            //insert full set
            $e = array_merge($e,[
                "createStamp"=>time(),
            ]);
        }
        $this->__db->saveData('services', [$e], 'id');
    }
    
    public static function getByCommand($command, $order=null, ?db_iface $db=null) {
        if (empty($db)) { $db = new db(); }
        $rv=[];
        
        foreach ($db->loadData("services","`command` = '".$command."'".(empty($order)?"":" order by ".$order)) as $svx) {
            $rv[$svx["id"]] = new self($svx); 
        }
        return $rv;
    }
}
?>