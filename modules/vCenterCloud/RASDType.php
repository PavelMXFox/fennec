<?php

namespace vCenterCloud;

use \Exception;

class RASDType extends ResourceAllocationSettingDataType {
    
    public static function convertUnits($value, $allocationUnitString, $base="M") {
        $coeff=1;
        switch ($base) {
            case "K":
                $coeff=2**10;
                break;
            case "M":
                $coeff=2**20;
                break;
            case "G":
                $coeff=2**30;
                break;
            case "T":
                $coeff=2**40;
        }

        
        $allocationUnitString = preg_replace("/[ \t]/","",$allocationUnitString);
        $als=[];
        if ($allocationUnitString=='byte') {
            $alc=1;
        } else if (preg_match("/[a-zA-Z]\\*2\\^([0-9]*)/", $allocationUnitString, $als)) {
            $alc = 2**$als[1];
        } else {
            $alc=1;
        }
        return $value*$alc/$coeff;
        
    }
    
    public function getAnyByClass($class) {
        $rv=[];
        foreach ($this->any as $a) {
            if (get_class($a) == $class) {
                $rv[] = $a;
            }
        }
        return $rv;
    }
}

