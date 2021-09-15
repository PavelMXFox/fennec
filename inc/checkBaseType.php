<?php  namespace agent;

class checkBaseType extends transportType {
    public ?checkResultType $lastResult=null;
    public ?serviceBaseType $service=null;
    public $__prevStatus=null;
    public $newStatus=null;
    public $needUpdateIS=false;
    public $__prevInState=0;
    
    protected $__config=null;
    protected ?db_iface $__db=null;
    
    public const resOK="OK";
    public const resFailed="FAIL";
    public const resWarning="WARN";
    
    public function __construct(checkResultType $result, serviceBaseType &$service=null, ?db_iface $db=null) {
        $this->lastResult=$result;
        if (!empty($service)) {
            $this->service=$service;
            $this->__prevStatus=$service->status;
            $this->__prevInState=$service->inState;
            $this->updateStatus();
        }
    }
    
    public function updateStatus() {
        
        if (empty($this->__db)) { $this->__db = new db(); }
        $this->__config=$this->__db->loadConfig();
        
        $holdTime1=$this->__config["holdTime1"]*60;
        $holdTime2=$this->__config["holdTime2"]*60;
        $holdTime3=$this->__config["holdTime3"]*60;
        
        if ($this->service->preFailMode===false) {
            $holdTime3=0;
        } elseif (!empty($this->service->preFailTimeoutOverride)) {
            $holdTime3=$this->service->preFailTimeoutOverride*60;
        }
        
        $newStatus="UNKNOWN";
        $updateIS=false;
        $ISdT = time() - $this->__prevInState;
        $ht1=($ISdT>$holdTime1);
        $ht5=($ISdT>($holdTime2));
        $ht7=($ISdT>($holdTime3));
        
        switch ($this->lastResult->result) {
            case "OK":
                switch ($this->__prevStatus) {
                    case "OK":
                        $newStatus="OK";
                        break;
                    case "FAIL":
                        $newStatus="RECOVER";
                        $updateIS=true;
                        break;
                    case "RECOVER":
                        if ($ht1) {
                            $newStatus="OK";
                            $updateIS=true;
                        } else {
                            $newStatus="RECOVER";
                        }
                        break;
                    case "FLOAT":
                        if ($ht5) {
                            $newStatus="OK";
                            $updateIS=true;
                        } else {
                            $newStatus="FLOAT";
                        }
                        break;
                    default:
                        $newStatus="OK";
                        $updateIS=true;
                        break;
                }
                break;
            case "FAIL":
                switch ($this->__prevStatus) {
                    case "OK":
                        if ($holdTime3>0) {
                            $newStatus="PREFAIL";
                            $updateIS=true;
                        } else {
                            $newStatus="FAIL";
                            $updateIS=true;
                        }
                        break;
                    case "PREFAIL":
                        if ($ht7) {
                            $newStatus="FAIL";
                            $updateIS=true;
                        } else {
                            $newStatus="PREFAIL";
                        }
                        break;
                        
                    case "FAIL":
                        $newStatus="FAIL";
                        break;
                    case "RECOVER":
                        $newStatus="FLOAT";
                        $updateIS=true;
                        break;
                    case "FLOAT":
                        $newStatus="FLOAT";
                        $updateIS=true;
                        break;
                    default:
                        if ($holdTime3>0) {
                            $newStatus="PREFAIL";
                            $updateIS=true;
                        } else {
                            $newStatus="FAIL";
                            $updateIS=true;
                        }
                        break;
                }
                break;
            case "WARN":
                switch ($this->__prevStatus) {
                    case "OK":
                        $newStatus="OK";
                        
                        break;
                    case "FAIL":
                        $newStatus="FLOAT";
                        $updateIS=true;
                        break;
                    case "RECOVER":
                        $newStatus="FLOAT";
                        $updateIS=true;
                        break;
                    case "FLOAT":
                        $newStatus="FLOAT";
                        $updateIS=true;
                        break;
                    default:
                        $newStatus="FLOAT";
                        $updateIS=true;
                        break;
                }
                break;
        }
        $this->newStatus=$newStatus;
        $this->needUpdateIS=$updateIS;
        
        
    }
    
    public function updateService() {
        if ($this->service->status != $this->newStatus) {
            $this->service->status=$this->newStatus;
            if ($this->needUpdateIS) {
                $this->service->inState=time();
            }
            $this->service->save();
            return true;
        } else {
            if ($this->needUpdateIS) {
                $this->service->inState=time();
                $this->service->save();
            }
            return false;
        }
    }
    
    public static function sUpdateService(checkResultType $result, serviceBaseType &$service=null, ?db_iface $db=null) {
        $cbt = new self($result, $service, $db);
        return $cbt->updateService();
    }

    
}
?>