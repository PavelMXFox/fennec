<?php namespace agent;


class main
{
    public $url;
    public $uuid;
    public $salt;
    protected ?db_iface $db;
    
    const APIVersion=2;
    
    public function __construct() {
        $this->uuid = getenv("FOXAGENT_GUID");
        $this->salt = getenv("FOXAGENT_SALT");
        $this->db = new db();
        $this->url = $this->db->loadConfig("callbackURL");
    }
   
    public function encrypt($val) {
        return xcrypt::encrypt($val, $this->salt);
    }

    public function decrypt($val) {
        return xcrypt::decrypt($val, $this->salt);
    }

    public static function sEncrypt($val) {
        $a = new self();
        return $a->encrypt($val);
    }

    public static function sDecrypt($val) {
        $a = new self();
        return $a->decrypt($val);
    }

    public static function sRegister() {
        $agent = new self();
        $agent->register();
    }
    
    public function verifySign(&$message)
    {
        $re_sign = hash_hmac('sha256',json_encode($message->packet), $this->salt);
        if ($re_sign == $message->sign)
        {
            return true;
        }
        
        return false;
    }
    
    public function preparePacket($command, $data, $msgId=null)
    {
    if (empty($msgId)) {$msgId = getGUIDc();}
    	$packet = ["message_id"=>$msgId, "agent_guid"=>$this->uuid, "command"=>$command,"data"=>$data, "Version"=>static::APIVersion];
    	$sign = hash_hmac('sha256',json_encode($packet), $this->salt);
    	$msg = base64_encode(json_encode(["packet"=>$packet, "sign"=>$sign]));
    	return $msg;
    
    }
    
    public function sendPush($command, $data)
    {
       $msg = $this->preparePacket($command, $data);
       $headers = stream_context_create(array(
           'http' => array(
               'method' => 'POST',
               'header' => 'Content-Type: application/x-www-form-urlencoded' . PHP_EOL,
               'content' => 'req='.$msg,
               'timeout' => 600,
           ),
           "ssl"=>array(
               "verify_peer"=>false,
               "verify_peer_name"=>false,
           )
       ));
       $reply = file_get_contents($this->url, false, $headers);
       if (!$reply) {return false; }
       $res = json_decode(base64_decode($reply));
       if (!$res) 	{return false; }
       if (!$this->verifySign($res)) { return false; }
       return $res->packet;
       
    }

    public function register() {
        if (empty($this->url)) { return; }
        $packet = $this->sendPush("register", ["version"=>static::APIVersion]);
        
        if ($packet && $packet->data->status=="OK") {
            if (!empty($packet->data->config)) { $this->pushconfig($packet->data); };
            if (property_exists($packet->data, "resync") && $packet->data->resync) { $this->db->saveConfig(["lastSync"=>0]); }
            $this->db->saveConfig(["lastRegister"=>time()]);
        }
    }
    
    public function pushconfig($req_data) {
        $this->db->saveConfig($req_data->config);
        return true;
    }
    
    public function pushpolleritems($req_data) {
        $syncStamp=time();
        foreach ($req_data->items as $item) {
            $eq = new equipmentV2Sync($item->id);
            $eq->host=$item->host;
            $eq->ping=$item->monitorType;
            //$eq->snmpPollerEnabled=true;
            $eq->snmp->version=$item->snmpVersion;
            $eq->snmp->community=$item->snmpCommunity;
            //$eq->snmp->fdbType=snmpConfig::fdbDisabled;
            //$eq->snmp->lldpType=snmpConfig::lldpDisabled;
            //$eq->preFailMode = true;
            $eq->save($this->db,$syncStamp);
            
            // services not implemented yet
            if (property_exists($item, "services")) {
                foreach ($item->services as $srv) {
                    $eq->addService($srv->id, request::jsonDecode($srv));
                }
            }
            $eq->updateSvcCount();
        }        
        $this->db->saveConfig(["lastSync"=>$syncStamp]);
        equipment::dropExpired($syncStamp, $this->db);
        
        return true;
    }
        
    public function agentSync($full=false) {
        if (empty($this->url)) { return; }
        
        $lastSyncStamp=$this->db->loadConfig("lastSync");
        if (empty($lastSyncStamp)) {
            $lastSyncStamp=0;
        }
        
        $data = [
            "full"=>$full,
            //"events"=>$this->getEvents($lastSyncStamp),
            //"items"=>$this->getPollerItems($full),
            //"tasks"=>$this->getTasks($lastSyncStamp),
        ];
        
        $reply = $this->sendPush("sync", $data);
        if ($reply->data->status=="OK") {
            $this->pushpolleritems($reply->data);
        }
        return;        
        if ($reply->data->eventStatus == "OK") {
            foreach ($data["events"] as $event) {
                $this->db->dropData("events","`id`='".$event["id"]."'");
            }
        }
        
        if ($reply->data->taskStatus == "OK") {
            // not implemented yet
        }
    }
 
    public function pushEvents() {
        if (empty($this->url)) { return; }
        
        $data = [
            "events"=>event::getEvents($this->db,true),
        ];
        
        if (count($data["events"])==0) {
            return true;
        }
        
        $reply = $this->sendPush("event", $data);
        
        if ($reply->data->status=="OK") {
            //$sm=time();
            foreach ($data["events"] as $event) {
                $event->delete();
            }
        }       
    }
    
    public static function sPushEvents() {
        $a = new self();
        $a->pushEvents();
    }
}
?>