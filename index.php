<?php
ob_start();

require_once __DIR__.'/inc/api.php';
use agent\main;
# ver 3.2, 20210713    
    
$msg=getVal("req",null,false);

// Agent listener test
$agent = new agent\main();
$message=json_decode(base64_decode($msg));
if (!$message) { exit; }

if ($agent->verifySign($message))
{
    try {
        switch ($message->packet->command)
        {
        // agent ping - check connectivity = QC OK
    	case "ping":
    	    $r_msg = $agent->preparePacket("reply",["command"=>$message->packet->command, "status"=>"OK"]);
    	    break;
    
    	case "request":
            $data = agent\request::jsonExec($message->packet->data);
    	    $r_msg = $agent->preparePacket("reply",["command"=>$message->packet->command, "status"=>"OK", "timestamp"=>time(),"data"=>$data]);
    	    break;
    	    
    	// receive initial config from fox-controller for static agents
    	case "pushconfig":
    	    if ($agent->pushconfig($message->packet->data)) {
    	        $r_msg = $agent->preparePacket("reply",["command"=>$message->packet->command, "status"=>"OK", "timestamp"=>time()]);
    	    } else {
    	        $r_msg = $agent->preparePacket("reply",["command"=>$message->packet->command, "status"=>"ERR",  "timestamp"=>time(), "message"=>"Command failed"]);
    	    }
    	    break;
    	    
    	default:
    	    $r_msg = $agent->preparePacket("reply",["command"=>$message->packet->command, "status"=>"ERR",  "timestamp"=>time(),"message"=>"Command not found"]);
    	    break;
        }
    } catch (Exception $e) {
        print "ERR 500"; exit;
    }
} else {print "ERR 500"; exit;}

if (!empty($obx = ob_get_clean())) {
    trigger_error($obx);
}
print $r_msg;

function getsnmpcounters($host, $snmpVersion, $snmpCommunity,$iflist)
{
	$ifaces=[];
	
   $ifdata = snmp_getifcounters($host,$snmpCommunity, $snmpVersion,$iflist);

	foreach ($iflist as $snmpIdx)
	{
           
	    $ifaces["if".$snmpIdx] = [
			"idx"=>$snmpIdx,
			"status_a"=>$ifdata["admin"][$snmpIdx],
			"status_o"=>$ifdata["oper"][$snmpIdx],
			"speed"=>$ifdata["speed"][$snmpIdx],
			"err_out"=>$ifdata["err_out"][$snmpIdx],
			"err_in"=>$ifdata["err_in"][$snmpIdx],
			"total_out"=>$ifdata["oct_out"][$snmpIdx],
			"total_in"=>$ifdata["oct_in"][$snmpIdx],
			"titles"=>$ifdata["title"][$snmpIdx],
	    ];
	
	}

		return $ifaces;
		
}

function getsnmpifaces($host, $snmpVersion, $snmpCommunity,$fdbType)
{
    $if_data = snmp_getifdata($host,$snmpCommunity,$snmpVersion,$fdbType);
    return $if_data;
}

function getsnmpfdb($host, $snmpVersion, $snmpCommunity,$fdbType)
{
    $if_data = snmp_getfdb($host,$snmpCommunity,$snmpVersion,$fdbType);
    return $if_data;

}

function getsnmparp($host, $snmpVersion, $snmpCommunity)
{
    $if_data = snmp_getarp($host,$snmpCommunity,$snmpVersion);
    return $if_data;
}

function snmpdiscover($host, $community)
{

    $h_data = host_detect($host, $community);

    return $h_data;
}
?>

