#!/usr/bin/php
<?php
use agent\db_sqlite;
require_once(__DIR__."/../inc/api.php");


$db = new agent\db();

// register section
$lastReg = $db->loadConfig("lastRegister");
$regInterval = $db->loadConfig("registerInterval");
if (empty($lastReg)) { $lastReg=0;}
if (empty($regInterval)) { $regInterval=0;}
$a = new agent\main();

if ((time()-$lastReg)>($regInterval*60)) { $a->register(); }

$lastSync = $db->loadConfig("lastSync");
$syncInterval = $db->loadConfig("syncInterval");

if (empty($lastSync)) { $lastSync=0;}

if ((!empty($syncInterval)) && (time()-$lastSync)>($syncInterval*60)) { $a->agentSync(); }

?>