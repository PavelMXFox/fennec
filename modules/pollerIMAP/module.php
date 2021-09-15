<?php namespace pollerIMAP;
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
    public static $targetClass= "imap";
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
    public static function ping($host, $port=25, $ssl="none", $sslIgnore=false, $timeout=1, $login=null, $password=null) {
        $imap = new IMAP();
        $imap->do_debug=0;

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
            if (!$imap->connect((($ssl=="ssl")?"ssl://":"").$host,$port,$timeout,$options)) {
                throw new Exception(($imap->getErrors()[count($imap->getErrors())-1]));
            }
            if ($ssl=='starttls') {
                if (!$imap->startTLS()) {
                    throw new \Exception("Starttls failed");
                }
            }
            
            if (!empty($login) && !empty($password)) {
                if (!$imap->login($login,$password)) {
                    throw new \Exception("Login failed");
                }
                if (empty($imap->list())) {
                    throw new \Exception("List command failed");
                }
            }
            $imap->disconnect();
            
            $rv->result=checkBaseType::resOK;
        } catch (\Exception $e) {
            $rv->result=checkBaseType::resFailed;
            $rv->message=$e->getMessage();
        }
        
        return $rv;
    }
}
?>