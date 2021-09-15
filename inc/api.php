<?php 
require_once 'common.php';

spl_autoload_register(function ($name) {
    
    $r=[];
    $rg = "/([^\\\]*)\\\([^\\\]*)/";
    if (preg_match($rg, $name, $r)) {
        if ($r[1] == 'agent') {
            include_once(__DIR__."/".$r[2].".php");
        } else {
            if (file_exists(__DIR__."/../modules/".$r[1]."/Autoloader.php")) {
                include_once(__DIR__."/../modules/".$r[1]."/Autoloader.php");
            } else {
                if (file_exists(__DIR__."/../modules/".$r[1]."/".$r[2].".php")) {
                    include_once(__DIR__."/../modules/".$r[1]."/".$r[2].".php");
                }
            }
        }
    } else {
        if (file_exists(__DIR__."/".$name.".php")) {
            include_once(__DIR__."/".$name.".php");
        }
    }
    
});
?>