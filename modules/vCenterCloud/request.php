<?php

namespace vCenterCloud;

class request {    
    public $prefix=null;
    public $command=null;
    public $method="GET";
    public $requestHeaders=null;
    public $token=null;
    
    public $replyCode;
    public $replyBody;
    public $replyHeaders=[];
    
    public $curlInfo=null;
    public $debug=false;
    
    public const METHOD_GET="GET";
    public const METHOD_POST="POST";
    
    public static $version = "33.0";
    
    public function __construct($mode="GET", $URL=null, $command=null, $token=null, $headers=null) {
        $this->prefix=$URL;
        $this->command=$command;
        $this->token=$token;
        $this->method=$mode;
        $this->requestHeaders=$headers;
    }
    
    public static function quickExec($mode, $URL, $command, $token=null, $returnBody=true,$debug=false) {
        $rq = new self($mode, $URL, $command, $token);
        $rq->debug=$debug;
        $href=null;
        
        if (empty($command)) {
            $href=$URL;
        }
        
        if ($rq->execCommand($href)) {
            if ($returnBody) {
                return $rq->getReply();
            } else {
                return $rq;
            }
        } else {
            return false;
        }
    }

    public function execCommand($href=null) {
        
        
        $ch = curl_init();
        $this->replyBody=null;
        $this->replyHeaders=[];
        $this->replyCode=false;
        
        $options = ["Accept: application/*+json;version=".static::$version];
        if ($this->token) { array_push($options, "x-vcloud-authorization: ".$this->token); };
        
        if (gettype($this->requestHeaders) == 'array' || gettype($this->requestHeaders) == 'object') {
            foreach ($this->requestHeaders as $hKey=>$hVal) {
                array_push($options, $hKey.": ".$hVal);
            }
        }
       
        
        if ($href) {
            curl_setopt($ch, CURLOPT_URL, $href);
        } else {
            curl_setopt($ch, CURLOPT_URL, $this->prefix."/api/".$this->command);
        }
        
        switch (strtoupper($this->method)) {
            case "POST":
                curl_setopt($ch, CURLOPT_POST, 1);
                break;  
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this, "handleHeaderLine"));
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $options);

        $this->replyBody = curl_exec($ch);
        if ($this->debug) { $this->curlInfo=curl_getinfo($ch); } else { $this->curlInfo=null; }
        $this->replyCode= curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        
        
        curl_close($ch);
        return $this->replyCode==200;
    }
    
    public function addAuthHeader($login, $authType="Basic", $password=null) {
        if ($password) {
            $this->requestHeaders["Authorization"] = $authType." ".base64_encode($login.":".$password);
        } else {
            $this->requestHeaders["Authorization"] = $authType." ".$login;
        }
    }
    
    public function getReply() {
        return json_decode($this->replyBody);
    }
    
    public function getHeader($key) {
        if (array_key_exists($key, $this->replyHeaders)) {
            return $this->replyHeaders[$key];
        } else {
            return null;
        }
    }
     
    protected function handleHeaderLine($curl, $header_line) {
        $r=[];
        
        if (preg_match("/^([^\ \:]*):(.*)/", trim($header_line), $r)) {
            $this->replyHeaders[trim($r[1])] = trim($r[2]);
        } elseif (preg_match("/^(HTTP\/.*)/", trim($header_line), $r)) {
            $this->replyHeaders["HTTP_STATUS_LINE"] = trim($r[1]);
        } else if (strlen(trim($header_line)) > 0 ){
            array_push($this->replyHeaders, trim($header_line));
        }
        return strlen($header_line);    
        
    }
    
}
?>