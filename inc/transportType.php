<?php namespace agent;
class transportType implements \JsonSerializable, jsonLoadable {
    
    public static $excludeProps=[];
    
    public function __debugInfo() {
        
        $rv =[];
        foreach($this as $key => $value) {
            if (array_search($key, $this::$excludeProps) === false && !preg_match("!^_!",$key)) {
                $rv[$key]= $value;
            }
        }
        return $rv;
    }
    
    public function export() {
        $csa = explode("\\", get_class($this));
        $rv =[];
        if (count($csa)>1) {
            $rv["_type"] = $csa[count($csa) - 1];
        } else {
            $rv["_type"] = $csa[0];
        }
        
        foreach($this as $key => $value) {
            if (array_search($key, $this::$excludeProps) === false && !preg_match("!^_!",$key)) {
                //$rv[$key]= $this->__get($key);
                $rv[$key]= $value;
            }
        }
        return $rv;
    }
    
    public function jsonSerialize()
    {
        return $this->export();
    }
    
    protected function setElement($data, $key,$baseType) {
        if (property_exists($this, $key)) {
            if (empty($data->{$key})) {
                $this->{$key}= null;
            } else {
                $this->{$key} = $this->fillElement($data->{$key}, $baseType);
            }
        }
    }
    
    protected function fillElement($data, $baseType) {

        if (gettype($data=='string') && $x=json_decode($data)) {
            $data=$x;
        }
        
        switch (gettype($data)) {
            case "array":
                return $this->fillArray($data, $baseType);
                break;
            default:
                return $this->fillObject($data, $baseType);
                break;
        }
    }
    
    protected function fillObject($data, $baseType) {

        if (gettype($data) != 'object') {
            if (gettype($data=='string') && $x=json_decode($data)) {
                $data=$x;
            } else {
                return $data;
            }
        }
        
        if (!empty($data->_type)) {
            if (class_exists(__NAMESPACE__."\\".$data->_type)) {
                $type = __NAMESPACE__."\\".$data->_type;
            } else {
                $type=$baseType;
                trigger_error("Class ".$data->_type." not found in namespace ".__NAMESPACE__);
            }
        } else {
            $type = $baseType;
        }
        
        if ($type=="string") {
            if(empty((array)$data)) { return null; }
            return $data;
        } elseif (!empty($type)) {
            return new $type($data);
        } else {
            return null;
        }
        
    }
    
    protected function fillArray($data, $baseType) {
        $rv = [];
        if (empty($data)) {
            return $rv;
        }
        
        foreach ($data as $val) {
            $obj = $this->fillObject($val, $baseType);
            if ($obj) {
                array_push($rv, $obj);
            }
        }
        return $rv;
    }
    
    protected function fill($data) {
        if (gettype($data)=='array') { $data = (object)$data; } 
        elseif (gettype($data)=='string' && $x=json_decode($data)) {
            $data=$x;
        }
        
        foreach ($data as $key=>$val) {
            if (property_exists($this, $key) && (gettype($this->{$key}) !='object' && gettype($this->{$key}) !='array') && (gettype($val) !='object' && gettype($val) !='array')) {
                $this->{$key} = $val;
            }
        }
    }
    
    public function arrayAdd(&$arr, $obj) {
        $arr = array_merge(empty($arr)?[]:$arr,gettype($obj)=='array'?$obj:[$obj]);
    }
    
    public function __construct($id=null, ?db_iface $db=null) {
        $this->__db = $db;
        if (gettype($id) == 'array') {
            $this->fill((object)$id);
        } elseif (gettype($id) == 'object') {
            $this->fill($id);
        } elseif (is_numeric($id)) { 
            $this->load($id);
        } elseif (is_null($id)) {
                    
        } else {
            throw new \Exception("Invalid _id_ type");
        }
    }

    protected function load($id) {
        throw new \ErrorException("Method load not implemented yet");
    }
}

?>