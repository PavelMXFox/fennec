<?php namespace vCenterCloud;


class DhcpServiceType extends NetworkServiceType {
    public $defaultLeaseTime;
    public $domainName;
    public $maxLeaseTime;
    public $primaryNameServer;
    public $routerIp;
    public $secondaryNameServer;
    public $subMask;

    public $ipRange;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "ipRange",IpRangeType::class);
    }
}
