<?php

namespace vCenterCloud;

class baseType implements \JsonSerializable {
    
    protected static $excludeProps=['sql','changelog','__sqlSelectTemplate','fillPrefix'];
        
    protected $__session=null;
    public $otherAttributes=null;
    
    public function __construct($session, $id=null) {
        switch (gettype($id)) {
            case "object":
                $this->fill($session, $id);
                break;
            case "array":
                $this->fill($session, (object)$id);
                break;
            default:
                throw new \Exception("Unknown type ".gettype($id));
        }
        
    }
    
    protected function localSet($varName, $newVal) {
        $this->changelogAdd($varName, $this->{$varName}, $newVal);
        $this->{$varName} = $newVal;
    }
        
    protected function __getDef($key) {
        
        try {
            return self::__get($key);
        } catch (\Exception $e) {
            if (!preg_match("!^_!",$key) && property_exists($this, $key)) {// && isset($this->{$key})) {
                return $this->{$key};
            } else {
                throw $e;
            }
        }
    }
    
    protected function __setDef($key, $val) {
        if ( !preg_match("!^_!",$key) && property_exists($this, $key)) {
            $this->localSet($key, $val);
        } else {
            self::__set($key,$val);
        }
    }
    public function __get($key) {
        switch ($key) {
            case "id":
                if (property_exists($this, "id")) { return $this->id;} else {return  null;};
                break;
            case "sqlSelectTemplate":
                return $this->__sqlSelectTemplate;
                break;
            case "sql":
                
                $this->checkSql();
                return $this->sql;
            case "changelog":
                return $this->changelog;
                break;
            default: throw new \Exception("property $key not availiable for read in class ".get_class($this), 595); break;
        }
    }
    
    public function __set($key,$val) {
        switch ($key) {
            case "settings":
                $this->__settings=$val;
                break;
            default: throw new \Exception("property $key not availiable for write in class ".get_class($this), 596); break;
        }
    }
    
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
        $rv =[];
        foreach($this as $key => $value) {
            if (array_search($key, $this::$excludeProps) === false && !preg_match("!^_!",$key)) {
                $rv[$key]= $this->__get($key);
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
                $this->{$key} = $this->fillElement($this->__session, $data->{$key}, $baseType);
            }
        }
    }

    protected function fillElement($session, $data, $baseType) {
        
        
        switch (gettype($data)) {
            case "array":
                return $this->fillArray($session, $data, $baseType);
                break;
            default:
                return $this->fillObject($session, $data, $baseType);
                break;
        }
    }
    
    protected function fillObject($session, $data, $baseType) {
        if (gettype($data) != 'object') {
            return $data;
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
               return new $type($session, $data);
        } else {
            return null;
        }
        
    }
    
    protected function fillArray($session, $data, $baseType) {
        $rv = [];
        if (empty($data)) {
            return $rv;
        }
        
        foreach ($data as $val) {
            $obj = $this->fillObject($session, $val, $baseType);
            if ($obj) {
                array_push($rv, $obj);
            }
        }
        return $rv;
    }
    
    protected function fill($session, $data) {
        $this->__session = $session;
        foreach ($data as $key=>$val) {
            if (property_exists($this, $key) && (gettype($val) !='object' && gettype($val) !='array')) {
                $this->{$key} = $val;
            }
        }
        
        
        $this->setElement($data, "otherAttributes", "string");
    }
}?>