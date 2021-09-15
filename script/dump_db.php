<?php 
$dbfile="/tmp/agent.db";

if (!file_exists($dbfile)) {
    include 'initdb.php';
}


if (count($argv)==1) {
    $eq=$svc=$evs=$mtd=$mod=true;
} else {
    $eq=$svc=$evs=$mtd=$mod=false;
    foreach ($argv as $arg) {
        switch ($arg) {
            case "-eq":
                $eq=true;
                break;
            case "-svc":
                $svc=true;
                break;
            case "-evts":
                $evs=true;
                break;
            case "-mtd":
                $mtd=true;
                break;
            case "-mod":
                $mod=true;
                break;
        }
    }
}

$s=new SQLite3($dbfile);
$s->busyTimeout(100000);
try {
    if ($eq) {
        print "Equipment:\n";
        
        $res=$s->query("select * from `equipment`");
        while ($row=$res->fetchArray(SQLITE3_ASSOC)) {
            print_r($row);
        }
    }

    if ($svc) {
        print "------------\nServices:\n";
        
        $res=$s->query("select * from `services`");
        while ($row=$res->fetchArray(SQLITE3_ASSOC)) {
            print_r($row);
        }
    }
    
    if ($evs) {
        print "------------\nEvents:\n";
        
        $res=$s->query("select * from `events`");
        while ($row=$res->fetchArray(SQLITE3_ASSOC)) {
            print_r($row);
        }
    }
    
    if ($mtd) {
        print "------------\nMetadata:\n";
        
        $res=$s->query("select * from `metadata`");
        while ($row=$res->fetchArray(SQLITE3_ASSOC)) {
            print_r($row);
        }
    }
    
    if ($mod) {
        print "------------\nModules:\n";
        
        $res=$s->query("select * from `modules`");
        while ($row=$res->fetchArray(SQLITE3_ASSOC)) {
            print_r($row);
        }
    }
    print "------------\n";
    $s->close();
} catch (Exception $e) {
        $s->close();
        throw $e;
    }

?>