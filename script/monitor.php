#!/usr/bin/php
<?php
use agent\equipment;
use agent\checkResultType;
use agent\checkBaseType;
use agent\event;

require_once(__DIR__."/../inc/api.php");

$db = new agent\db();

$config = $db->loadConfig(["syncInterval","lastSync"]);
$syncTimeout=$config["syncInterval"]*60*3;
$syncStamp = $config["lastSync"];
if ((time() - $syncStamp) > $syncTimeout ) {
    trigger_error("Last sync over $syncTimeout seconts ago. Aborted.",E_USER_WARNING);
    exit;
}

foreach (equipment::getForCheck($db) as $eq) {
    $rate=0;
    print "Equipment ID: ".$eq->equipId."(".$eq->host.")\n";
    $eq->lastCheckStamp=time();
    $eq->save();
    
    foreach ($eq->services as $srv) {
        if ($srv->request->command != 'check') { continue; }
        print "Service # ".$srv->equipId.":".$srv->svcId.":".$srv->id.":".$srv->request->command.":".$srv->request->type." ( W: ".$srv->request->weigth.") ... ";
        $res = $srv->request->execute();
        
        $e=new event();
        $e->refType=event::rtService;
        $e->refId=$srv->svcId;
        $e->eventData->prevState=$srv->status;
        
       
        if (checkBaseType::sUpdateService($res, $srv)) {
            $e->eventData->newState=$srv->status;
            $e->eventData->source="monitor";
            $e->save($db);
        }
        

        if ($res->result != 'OK' && ($srv->status != 'PREFAIL' || $eq->preFailMode!==false)) {
            $rate += $srv->request->weigth;
        }
        
        print $res->result." / ".$srv->status.(empty($res->message)?"":" ".$res->message)."\n";
        
    }
    
    $res = new checkResultType();
    
    if ($rate >= 100) {
        $res->result="FAIL";
    } else {
        $res->result="OK";
    }
    $e=new event();
    $e->refType=event::rtEquipment;
    $e->refId=$eq->equipId;
    $e->eventData->prevState=$eq->status;
    
    if (checkBaseType::sUpdateService($res, $eq)) {
        $e->eventData->newState=$eq->status;
        $e->eventData->source="monitor";
        $e->save($db);
    }
    print "EqTTLRate: ".$rate."; ".$res->result." / ".$eq->status."\n";
    
}

$db->saveConfig(["monitorLastRun"=>time()]);
agent\main::sPushEvents();

?>
