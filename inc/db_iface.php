<?php

namespace agent;

interface db_iface {
    
    public function initialize();
    
    public function loadConfig($keys=[]);
    public function saveConfig($keys=[]);
    public function dropConfig($keys=[]);
    
    public function loadData($table, $filter=[]);
    public function saveData($table, $items,$key);
    public function dropData($table, $filter=[]);
    
}