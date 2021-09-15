#!/usr/bin/php
<?php
use agent\db_sqlite;

require_once(__DIR__."/../inc/api.php");

$agent = new agent\main();
$agent->agentSync();
?>