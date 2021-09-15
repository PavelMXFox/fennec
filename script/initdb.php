<?php

use agent\db_sqlite;
use agent\modules;

require_once(__DIR__."/../inc/api.php");

$db = new db_sqlite();
$db->initialize();
$db->__destruct();

modules::reload();

