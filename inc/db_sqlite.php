<?php 
namespace agent;
use \SQLite3;

require_once 'db_iface.php';
class db_sqlite implements db_iface {
    var $type;
    const schemaVersion=23;
    
    public function __construct($dbfile="/tmp/agent.db") {
        try {
            $this->dbfile = $dbfile;
            $init = false;
            $init = (!file_exists($dbfile));
            
            $this->s=new SQLite3($dbfile);
            $this->s->busyTimeout(100000);
        } catch (\Exception $e) {
            $this->__destruct();
            throw $e;
        }
        if ($init) { $this->initialize();}
    }
    
    public function __destruct() {
        $this->s->close();
    }
    
    public function initialize() {
        
        $schemaVersion = $this->loadConfig("schemaVersion");
        try {
            if ($schemaVersion != self::schemaVersion) {
                $this->s->exec("drop table if exists `equipment`");
                $this->s->exec("drop table if exists `metadata`");
                $this->s->exec("drop table if exists `events`");
                $this->s->exec("drop table if exists `tasks`");
                $this->s->exec("drop table if exists `modules`");
                $this->s->exec("drop table if exists `services`");
                $this->s->exec("drop table if exists `pollerResult`");
            }
            
            $this->s->exec("create table if not exists `equipment` (
    `equipId` int PRIMARY KEY,
    `host` text ,
    `snmp` text default null,
    `status` text default 'UNKNOWN', 
    `lastCheckStamp` int default 0,   
    `inState` int default 0, 
    `syncStamp` int default 0,
    `createStamp` int default 0,
    `svcCount` int default 0,
    `checkSvcCount` int default 0,
    `preFailMode` int default null,
    `preFailTimeoutOverride` int default null
    )");

            $this->s->exec("create table if not exists `services` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    `equipId` int,
    `svcId` int default 0,
    `status` text default 'UNKNOWN',
    `lastPollStamp` int default 0,
    `lastCheckStamp` int default 0,
    `inState` int default 0,
    `syncStamp` int default 0,
    `createStamp` int default 0,
    `command` text,
    `weigth` int default 100,
    `request` text default null,
    `snmpPollerEnabled` int default 0,
    `lastPollStartStamp` int default 0,
    `lastCheckStartStamp` int default 0,
    `lastPollStatus`text default 'UNKNOWN',
    `preFailMode` int default null,
    `preFailTimeoutOverride` int default null,
    `minPeriod` int default null)");
            
            $this->s->exec("create table if not exists `pollerResult` (
    `id` int PRIMARY KEY,
    `equipId` int,
    `svcId` int,
    `status` text default 'UNKNOWN',
    `timestamp` int default 0,
    `result` test default null)");
            
            $this->s->exec("create table if not exists `metadata` (
    `key` text PRIMARY KEY, 
    `value` text)");        
            $this->s->exec("create table if not exists `events` (
    `uid` TEXT PRIMARY KEY NOT NULL,
    `entryStamp` int default 0,
    `refType` text,
    `refId` INTEGER,
    `syncMark` text default null,
    `eventData` TEXT default NULL
    )");
    
            $this->s->exec("create table if not exists `tasks` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    `equipId` INTEGER default null,
    `entryStamp` int default 0,
    `resultData` text default null,
    `taskData` TEXT default NULL,
    `completeStamp` INT default NULL,
    `syncMark` text default null
    )");

            $this->s->exec("create table if not exists `modules` (
    `target` text primary key,
    `targetClass` text default null,
    `targetType` text default null,
    `namespace` TEXT default NULL,
    `module` text default NULL,
    `version` text default null,
    `methods` text default null,
    `minPeriod` int default null
    )");
            
            
            $this->s->exec("create table if not exists `tasks` (`key` text PRIMARY KEY, `value` text)");
            $this->saveConfig(["schemaVersion"=>$this::schemaVersion]);
        } catch (\Exception $e) {
            $this->__destruct();
            throw $e;
        }
        $url = $this->loadConfig("callbackURL");
        if (empty($url) && !empty(getenv("FOXAGENT_HOOK"))) {
            $this->saveConfig(["callbackURL"=>getenv("FOXAGENT_HOOK")]);
        }
    }
   
    public function loadConfig($keys=[]) {
        try {
            if(empty($keys)) {
                $where="";
            } elseif (gettype($keys)=='array') {
                $where = " where `key` in (".$this->serialize($keys).")";
            } else {
                $where = " where `key` = '".$keys."'";
            }
            
            @$r=$this->s->query("select * from `metadata`".$where);
            
            $rv=[];
            if (empty($r)) {
                return null;
            }
            while ($row = $r->fetchArray(SQLITE3_ASSOC)) {
                $rv[$row["key"]] = $row["value"];
            }
    
            if (gettype($keys)=='array') {
                if (count($keys)==1 && empty($rv)) {
                    $rv=[$keys[0]=>null];
                }
                return $rv;
            } else {
                if (empty($rv)) {
                    return null;
                } else {
                    return $rv[$keys];
                }
            }
        } catch (\Exception $e) {
            $this->__destruct();
            throw $e;
        }
        
    }    
    public function saveConfig($keys=[]) {
        try {
            if (empty($keys)) {
                return;
            }
            
            foreach ($keys as $key=>$val) {
                $st=$this->s->prepare('insert into `metadata` (key, value) values (:key, :value) on conflict(key) do update  set key=:key, value=:value');
                $st->bindValue(':key', $key,SQLITE3_TEXT);
                $st->bindValue(':value', $val,SQLITE3_TEXT);
                $st->execute();
            }
        } catch (\Exception $e) {
            $this->__destruct();
            throw $e;
        }
    }
    public function dropConfig($keys=[]) {
        try {
            if(empty($keys)) {
                $where="";
            } else {
                $where = " where `key` in (".$this->serialize($keys).")";
            }
            
            $this->s->exec("delete from `metadata`".$where);
        } catch (\Exception $e) {
            $this->__destruct();
            throw $e;
        }
        
    }
    
    public function loadData($table, $filter=null) {
        try {
            if(empty($filter)) {
                $where="";
            } else {
                $where = " where ".$filter;
            }
            
            $r=$this->s->query("select * from `".$table."`".$where);
            if (empty($r)) {
                //trigger_error("Query failed "."select * from `".$table."`".$where);
                throw new \Exception("Query failed "."select * from `".$table."`".$where,7709);
                return null;
            }
            $rv=[];
            while ($row = $r->fetchArray(SQLITE3_ASSOC)) {
                array_push($rv, $row);;
            }
            
            return $rv;
        } catch (\Exception $e) {
            $this->__destruct();
            throw $e;
        }
        
    }
    
    public function saveData($table, $items, $pkey) {
        $rv=[];
        try {
            if (empty($table) || empty($items) || empty ($pkey)) {
                return;
            }
            foreach ($items as $item) {
                $keys="";
                $params="";
                $update="";
                
                foreach ($item as $key=>$val) {
                    $keys .= (empty($keys)?"":",")."`".$key."`";
                    $params .= (empty($params)?"":",").":".$key;
                    $update .= (empty($update)?"":",")."`".$key."` = :".$key;
                }
                
                
                $qs = 'insert into `'.$table.'` ('.$keys.') values ('.$params.') on conflict(`'.$pkey.'`) do update  set '.$update;
        
                $st=$this->s->prepare($qs);
                if (empty($st)) {
                    trigger_error("ERROR at: ".$qs);
                    return false;
                }
                
                foreach ($item as $key=>$val) {
                    $st->bindValue(':'.$key, $val,(gettype($val)=='integer')?SQLITE3_INTEGER:SQLITE3_TEXT);
                    
                }
                
                $st->execute();
                array_push($rv, $this->s->lastInsertRowID());
            }
            return $rv;
        } catch (\Exception $e) {
            $this->__destruct();
            throw $e;
        }
        
    }
    public function dropData($table, $filter=null) {
        try {
            if(empty($filter)) {
                $where="";
            } else {
                $where = " where ".$filter;
            }
            
            $this->s->exec("delete from `".$table."`".$where);
            
        } catch (\Exception $e) {
            $this->__destruct();
            throw $e;
        }
        
    }
    
    protected function serialize($arr) {
        try {
            $rv="";
            foreach ($arr as $val) {
                if (!empty($rv)) {
                    $rv .=",";
                }
                $rv.="'".$val."'";
            }
            return $rv;
        } catch (\Exception $e) {
            $this->__destruct();
            throw $e;
        }
        
    }
}

?>