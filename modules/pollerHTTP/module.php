<?php namespace pollerHTTP;
require_once(__DIR__."/../../inc/api.php");
use \agent;
use \Exception;
use agent\request;
use agent\equipment;
use agent\serviceBaseType;
use agent\checkBaseType;
use agent\checkResultType;

class module extends \agent\moduleBaseClass {
    public static $targetType = "generic";
    public static $targetClass= "http";
    public static $version="1.0.0";
    public static $targetMethods =["check"];
    
 
    public static function check(request $request) {
        $url=$request->host.(empty($request->port)?"":":".$request->port);
        return static::ping($url, $request->successCodes, !$request->verifySSL,1,$request->timeout);
    }
    
    public static function ping($url, $successCodes=[], $ignoreSSL=false, $maxCount=1, $timeout=1) {
        

        $rv = new checkResultType();
        for ($i=0; $i<$maxCount; $i++) {
            
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);

            if ($ignoreSSL) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            }
            
            curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);

            curl_exec($ch);
            $replyCode= curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            $errCode=curl_errno($ch);
            $errDesc=curl_error($ch);
            curl_close($ch);
            
            if ($errCode != 0) {
                $rv->code=$errCode;
                $rv->message=$errDesc;
                continue; 
            }
            
            if (empty($successCodes) && $replyCode<400) { break;}
            if (!empty($successCodes) && array_search($replyCode, $successCodes) !==false) { break; }
            $rv->code=$replyCode;
            $rv->message="Invalid response code: ".$replyCode;

        }


        if ($i<($maxCount * 0.7)) {
            $rv->result = checkBaseType::resOK;
        } else {
            $rv->result=checkBaseType::resFailed;
        }
        return $rv;
    }
}
?>