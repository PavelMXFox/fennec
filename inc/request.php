<?php namespace agent;

class request implements \JsonSerializable {
    public $command;
    public $weigth=100;
    public $type;
    public $host;
    public $module;
    public $filter;
    public $minPeriod;  // Минимальный интервал между опросами в секундах. Если не установлен - используется значение из модуля.
    public $preFailMode=null;
    public $preFailTimeoutOverride=null;
    
    public static function jsonDecode($json) {
        if (gettype($json) == 'array') {
            $rxd =(object)$json;   
        } elseif (gettype($json) == 'object') {
                $rxd =$json;
        } else {
            $rxd = json_decode($json,null,99,JSON_THROW_ON_ERROR);
        }
       
        $m=(new modules())->getTarget($rxd->type);
        
        if (!empty($m) && class_exists($m->namespace."\\request")) {
            $rqClass = $m->namespace."\\request";
            $req = new $rqClass;
        } else {
            $req = new self;
        }
        
        $req->module = $m;
        $agent = new main();
        
        foreach ($rxd as $key=>$val) {
            if (property_exists($req, $key)) {
                if (gettype($req->{$key}) != 'object') {
                    $req->{$key} = $val;
                } elseif ($req->{$key} instanceof jsonLoadable) {
                    $className = get_class($req->{$key});
                    $req->{$key} = new $className($val);
                }
            } elseif (property_exists($req, preg_replace("/^__enc__/","",$key))) {
                $keyx =  preg_replace("/^__enc__/","",$key);
                $req->{$keyx} = $agent->decrypt($val);
                $req->{$key} = $val;
            }
        }
        return $req;
        
    }
    
    public static function jsonExec($json) {
        $req = static::jsonDecode($json);
        if ($req) {
            return $req->execute();
        } else {
            throw new \Exception("Invalid request");
        }
    }
    
    public function jsonSerialize() {
        $rv = (array)$this;
        $rv["_type"]=get_class($this);
        return $rv;
    }
    
    public function execute() {
         
        if (!empty($this->module)) {
            $m = modules::getTargetModule($this->module);
            
            if (!empty($m) && method_exists($m, $this->command)) {
                return $m->{$this->command}($this);
            }
        } 
        return false;
    }
    
    
    public function validate() {
        return !empty($this->host);
    }
}


?>