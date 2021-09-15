<?php namespace agent;
use Exception;

class equipment extends serviceBaseType {
    public $equipId;
    public $host;

    public ?snmpConfig $snmp;
    public $svcCount=0;
    public $checkSvcCount=0;
    protected $__services=[];
    
    protected ?db_iface $__db=null;
    

    
    public function __construct($id=null, ?db_iface $db=null) {
        $this->__db = $db;
        if (empty($this->__db)) { $this->__db = new db(); }
        $this->snmp=new snmpConfig();
        
        if (gettype($id) == 'array') {
            $this->fill((object)$id);
        } elseif (gettype($id) == 'object') {
            $this->fill($id);
        } elseif (is_numeric($id)) {
            $this->load($id);
        } elseif (is_null($id)) {
            
        } else {
            throw new Exception("Invalid _id_ type"); 
        }
        
    }

    public function __get($key) {
        switch($key) {
            case "services":
                return $this->getServices();
            case "db":
                return $this->db;
        }
    }
    
    public function getServices($force=false) {
        $this->checkDb();
        if (!$force || empty($this->__services)) {
                $this->__services=[];
                foreach ($this->__db->loadData("services","`equipId`='".$this->equipId."'") as $svcx) {
                    $this->__services[$svcx["svcId"]] = $svc = new serviceType($svcx);
                }
                $this->svcCount==count($this->__services);
            }
        return $this->__services;
    }
    
    public function updateSvcCount(){        
        // clear services
        $slx = $this->__db->loadData("services","`equipId`='".$this->equipId."'");
        foreach ($slx as $sx) {
            if (!array_key_exists($sx["svcId"],$this->__services)) {
                // drop missing services
                $this->__db->dropData("services","`id`='".$sx["id"]."' and `equipId`='".$this->equipId."' and `svcId` = '".$sx["svcId"]."'");
                trigger_error("Drop service ".$sx["equipId"].":".$sx["svcId"].":".$sx["id"]);
                
            }
        }
        
        $this->svcCount = count($this->getServices(true));
        $this->checkSvcCount=0;
        
        foreach ($this->__services as $s) {
            if ($s->command=='check') {$this->checkSvcCount++;}
        }
        
        $this->__db->saveData("equipment", [["equipId"=>$this->equipId, "svcCount"=>$this->svcCount, "checkSvcCount"=>$this->checkSvcCount]], "equipId");
        
        
    }
    
    protected function load($id) {
        $this->checkDb();
        $r = $this->__db->loadData('equipment',"`equipId`='".$id."'");
        if (empty($r)) {
            $this->equipId=$id;
            return;
            throw new \Exception("Object #".$id." not found.",404);
        }
        
        $this->fill($r[0]);
    }
    
    protected function fill($data) {
        if (gettype($data)!='object') {$data = (object)$data;}

        parent::fill($data);
        $this->setElement($data,"snmp",snmpConfig::class);
    }
    
    protected function checkDb() {
        if (empty($this->__db)) { $this->__db=new db(); }
    }
    
    public function save(?db_iface $db=null, $setSync=false) {
        if (empty($this->equipId)) {
            throw new \Exception("equipId can't be empty!");
        }
        
        if ($db !== null) { $this->db = $db; }
        $this->checkDb();

        if ($setSync === "true") {
            $this->syncStamp=time();
            print "XXX";
        } elseif ($setSync) {
            $this->syncStamp=$setSync;
        }
        // update set default
        $e = [
            "equipId"=>$this->equipId,
            "host"=>$this->host,
            "snmp"=>json_encode($this->snmp),
            "svcCount"=>$this->svcCount,
            "checkSvcCount"=>$this->checkSvcCount,
            "syncStamp"=>$this->syncStamp,
            "status"=>$this->status,
            "lastCheckStamp"=>$this->lastCheckStamp,
            "inState"=>$this->inState,
            "preFailMode"=> is_null($this->preFailMode)?null:($this->preFailMode?1:0),
            "preFailTimeoutOverride"=>is_null($this->preFailTimeoutOverride)?null:($this->preFailTimeoutOverride?1:0),
            
        ];
        
        
       
        if (empty($this->__db->loadData('equipment','equipId='.$this->equipId))) {
        //insert full set
            $e = array_merge($e,[
                "createStamp"=>time()
            ]);
            
        }
        
        $this->__db->saveData('equipment', [$e], 'equipId');
    }
    

    public function addService($svcId, request $request) {
        if (empty($this->equipId)) {
            throw new \Exception("Unable to create or update service on empty EquipId",504);
        }
        
        try {
            $svc = new serviceType(new svcId($this->equipId, $svcId), $this->__db);
        } catch (\Exception $e) {
            if ($e->getCode()==404) {
                $svc = new serviceType();   
            } else {
                throw $e;
            }
        }
        
        if (strlen($request->host) == 0) {
            $request->host=$this->host;
        }
        $svc->svcId=$svcId;
        $svc->request=$request;
        $svc->equipId=$this->equipId;
        if (empty($request->preFailMode)) {
            $svc->preFailMode=$this->preFailMode;
        } else {
            $svc->preFailMode=$request->preFailMode;
        }
        
        if (!empty($request->preFailTimeoutOverride)) {
            $svc->preFailTimeoutOverride=$request->preFailTimeoutOverride;
        } elseif (!empty($this->preFailTimeoutOverride)) {
            $svc->preFailTimeoutOverride=$this->preFailTimeoutOverride;
        } else {
            $svc->preFailTimeoutOverride=null;
        }
        
        $this->__services[$svcId] = $svc;
        $svc->save($this->__db, true);
        
    }
    
    public function check() {
        if ($this->ping & static::pingIcmp) {
            $resIcmp = request::jsonExec([
                "command"=>"check",
                "host"=>$this->host,
                "type"=>"generic/icmp"
            ]);
        } else {
            $resIcmp=new checkResultType();
            $resIcmp->result = "OK";
        }

        if ($this->ping & static::pingSnmp) {
            $resSnmp = request::jsonExec([
                "command"=>"check",
                "host"=>$this->host,
                "community"=>$this->snmp->community,
                "version"=>$this->snmp->version,
                "type"=>"generic/snmp"
            ]);
        } else {
            $resSnmp=new checkResultType();
            $resSnmp->result = "OK";
        }
        
        $res = new checkResultType();
        if ($resIcmp->result==checkBaseType::resOK && $resSnmp->result==checkBaseType::resOK) { $res->result="OK"; }
        elseif ($resIcmp->result==checkBaseType::resFailed || $resSnmp->result==checkBaseType::resFailed) { $res->result=checkBaseType::resFailed; $res->message="ICMP: ".$resIcmp->result."; SNMP: ".$resSnmp->result; }
        elseif ($resIcmp->result==checkBaseType::resWarning || $resSnmp->result==checkBaseType::resWarning) { $res->result=checkBaseType::resWarning;$res->message="ICMP: ".$resIcmp->result."; SNMP: ".$resSnmp->result;  }
        else {$res->result=checkBaseType::resWarning; $res->message="Unknown error. ICMP: ".$resIcmp->result."; SNMP: ".$resSnmp->result; }

        $rv = new checkBaseType($res, $this->status, $this->inState, $this->__db);
      
        return $rv;
    }
    
    public static function getForCheck(?db_iface $db=null) {
        if (empty($db)) {
            $db = new db();
        }
        $rv=[];
        foreach ($db->loadData('equipment','`checkSvcCount` > 0') as $row) {
            $rv[] = new equipment($row);
        }
        return $rv;
    }
    
    public function delete() {
        $this->getServices(true);
        foreach ($this->__services as $sx) {
            $this->__db->dropData("services","`id`='".$sx->id."' and `equipId`='".$this->equipId."' and `svcId` = '".$sx->svcId."'");
        }
        $this->__db->dropData("equipment","`equipId`='".$this->equipId."'");
    }
    
    public static function dropExpired($syncStamp, ?db_iface $db=null) {
        if (empty($db)) {
            $db = new db();
        }

        foreach ($db->loadData('equipment','`syncStamp` < "'.$syncStamp.'"') as $row) {
            $eq =  new equipment($row);
            $eq->delete();
        }
    }
}


?>