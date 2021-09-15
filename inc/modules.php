<?php 
namespace agent;

class modules {
    public $targets=[];
    
    public function __construct() {
        $db = new db_sqlite();
        $t = $db->loadData("modules");
        $db->__destruct();
        foreach ($t as $v) {
            $this->targets[preg_replace("/:.*$/","",$v["target"])] = (object)$v;
        }
    }

    public static function reload() {
        $prefix = preg_replace("/\/inc(\/)*$/","",__DIR__)."/modules";
        $targets=[];
        foreach (scandir($prefix) as $key => $value)
        {
            if (!in_array($value,array(".","..")))
            {
                $m_name = $prefix."/".$value."/module.php";
                if (file_exists($m_name)) {
                    include($m_name);
                    $class = $value."\module";
                    if (class_exists($class)) {
                        
                        $targets[$class::$targetType."/".$class::$targetClass]=(object)[
                            "target"=>$class::$targetType."/".$class::$targetClass.":".$class::$version,
                            "module" => $class,
                            "namespace"=>preg_replace("/\\\[^\\\]*$/", "", $class),
                            "targetType"=>$class::$targetType,
                            "targetClass"=>$class::$targetClass,
                            "version"=>$class::$version,
                            "methods"=>json_encode($class::$targetMethods),
                            "minPeriod"=>$class::$minPeriod
                            
                        ];
                    }
                }
            }
            
        }
        
        $db = new db_sqlite();
        $db->dropData("modules");
        $db->saveData("modules", $targets, "target");
        $db->__destruct();
    }
    
    public function poll($type, $host, $options=null) {
        if (array_key_exists($type, $this->targets)) {
            $m_name = $this->targets[$type];
            $m = new $m_name;
            return $m->poll($host, $options);
        } else {
            throw new \Exception("Module $type not installed!");
        }
    }

    public function getTarget($target) {
        $t=explode(":", $target);
        if (count($t) > 1) {
            $version=$t[1];
            $target=$t[0];
        }
        
        if (array_key_exists($target, $this->targets)) {
            if (!empty($version)) {
                if ($version==$this->targets[$target]->version) {
                    return $this->targets[$target];
                }
            } else {
                return $this->targets[$target];
            }
        } else {
            return null;
        }
        
    }
    
    public function getTargetModule($target)
    {
        if (gettype($target=='object')) {
            $t = $target;   
        } else {
             if (empty($this)) {
                $m = new self();
            } else {
                $m=&$this;
            }
            $t = $m->getTarget($target);
        }
        
        if (empty($t)) { return null; }
        $mClass = $t->module;
        
        if (class_exists($mClass)) {
            return new $mClass();
        } else {
            return null;
        }

    }
    
}

?>