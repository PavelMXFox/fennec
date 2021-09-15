<?php 

namespace vCenterCloud;

use \Exception;

class session {    
    public $token = null;
    public $session = null;
    public $URL=null;
    
    public function __construct($URL, $authLogin, $authSecret, $authToken=null, callable $tokenUpdateCallback=null, array $tokenUpdateCallbackArgs=[]) {

        // check connection 
        $loginURL=null;
        $this->URL = $URL;
        foreach (request::quickExec(request::METHOD_GET, $URL, "versions")->versionInfo as $v) {
           if ($v->version == request::$version) {
               $loginURL=$v->loginUrl;
           }
        }
        
        if (empty($loginURL)) { throw new \Exception("Handshake failed. Version mismatch"); }

        // if we have token
        if ($authToken && ($rq = request::quickExec(request::METHOD_GET, $URL, "session", $authToken, false))) {
            $this->token=$authToken;
            $this-> session = new SessionType($this, $rq->getReply());
        } else {
            // neet to auth and retry
            $rq = new request(request::METHOD_POST);
            $rq->addAuthHeader($authLogin, "Basic", $authSecret);
            if ($rq->execCommand($loginURL)) {
                $this->token = $rq->getHeader("x-vcloud-authorization");
                if ($tokenUpdateCallback) {
                    call_user_func($tokenUpdateCallback,$this->token,$tokenUpdateCallbackArgs);
                }
                
                $this-> session = new SessionType($this, $rq->getReply());
            } else {
                throw new Exception("Auth failed. ".$rq->getHeader("HTTP_STATUS_LINE"));
            }
        }
    }
    
    public function getRequest() {
        
    }
    
    public function execCommand($mode, $command, $returnData=true, $href=false) {
        return request::quickExec($mode, ($href?$command:$this->URL), ($href?null:$command),$this->token, $returnData);
    }
    
    public function getOrganizations() {
        $orgs=[];
        
        $orgList = new OrgListType($this, request::quickExec(request::METHOD_GET, $this->URL, "org", $this->token, true));
        foreach ($orgList->org as $orgRef) {
           array_push($orgs, new OrgType($this, request::quickExec(request::METHOD_GET, $orgRef->href, null, $this->token, true)));
        }
        return $orgs;
    }

    public function query($filter=[]) {
        
        var_dump($url);
    }

    public function getVDCs() {
        $vms=[];
        $page=1;
        $pages=1;
        $pageSize=64;
        
        while ($page <= $pages) {
            $res = request::quickExec(request::METHOD_GET, $this->URL, "query?type=orgVdc&pageSize=$pageSize&page=$page", $this->token, true);
            $vml=new QueryResultRecordsType($this, $res);
            $page = $vml->page;
            $pages=ceil($vml->total/$vml->pageSize);
            $pageSize=$vml->pageSize;
            $vms = array_merge($vms, $vml->record);
            $page++;
        }
        
        return $vms;
    }
    
    public function getVMs() {
        $vms=[];
        $page=1;
        $pages=1;
        $pageSize=64;
        
        while ($page <= $pages) {
            $res = request::quickExec(request::METHOD_GET, $this->URL, "query?type=vm&pageSize=$pageSize&page=$page", $this->token, true);
            $vml=new QueryResultRecordsType($this, $res);
            $page = $vml->page;
            $pages=ceil($vml->total/$vml->pageSize);
            $pageSize=$vml->pageSize;
            $vms = array_merge($vms, $vml->record);
            $page++;
        }
        
        return $vms;
    }
    
    public function getVAPPs() {
        $vms=[];
        $page=1;
        $pages=1;
        $pageSize=64;
        
        while ($page <= $pages) {
            $res = request::quickExec(request::METHOD_GET, $this->URL, "query?type=vApp&pageSize=$pageSize&page=$page", $this->token, true);
            $vml=new QueryResultRecordsType($this, $res);
            $page = $vml->page;
            $pages=ceil($vml->total/$vml->pageSize);
            $pageSize=$vml->pageSize;
            $vms = array_merge($vms, $vml->record);
            $page++;
        }
        
        return $vms;
    }
    
    public function getVAPP($id) {
        $res = request::quickExec(request::METHOD_GET, $this->URL, "vApp/".$id, $this->token, true);
        return new VAppType($this, $res);        
    }
    
}

?>