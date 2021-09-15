<?php namespace pollerPOP3;
require_once(__DIR__."/../../inc/api.php");
use \agent;
use \Exception;
use agent\request;
use agent\equipment;
use agent\serviceBaseType;
use agent\checkBaseType;
use agent\checkResultType;

class module extends \agent\moduleBaseClass {
    public static $targetType = "mail";
    public static $targetClass= "pop3";
    public static $version="1.0.0";
    public static $targetMethods =["check"];
 
    public static function check(request $request) {
         return static::ping($request->host, $request->port, $request->ssl, !$request->verifySSL,$request->timeout, $request->login, $request->password);
    }
    
    /*
     * ssl:
     * none = no SSL
     * ssl = SSL on dedicated port
     * startts = SSL with STARTTLS
     * 
     * sslIgnore: = ignore SSL Sertificate
     */
    public static function ping($host, $port=110, $ssl="none", $sslIgnore=false, $timeout=1, $login=null, $password=null) {
        $pop3 = new POP3();
        $pop3->do_debug=0;

        $rv = new checkResultType();
        
        if ($sslIgnore) {
            $options=[ 'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true]];
        } else {
            $options=[];
        }
        
        try {
            if (!$pop3->connect((($ssl=="ssl")?"ssl://":"").$host,$port,$timeout,$options)) {
                throw new Exception(($pop3->getErrors()[count($pop3->getErrors())-1]));
            }
            if ($ssl=='starttls') {
                if (!$pop3->startTLS()) {
                    throw new \Exception("Starttls failed");
                }
            }
            
            if (!empty($login) && !empty($password)) {
                if (!$pop3->login($login,$password)) {
                    throw new \Exception("Login failed");
                }
              /*  if (empty($pop3->list())) {
                    throw new \Exception("List command failed");
                }
                */
            }
            $pop3->disconnect();
            
            $rv->result=checkBaseType::resOK;
        } catch (\Exception $e) {
            $rv->result=checkBaseType::resFailed;
            $rv->message=$e->getMessage();
        }
        
        return $rv;
    }
}
?>