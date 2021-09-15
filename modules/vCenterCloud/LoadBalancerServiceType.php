<?php namespace vCenterCloud;


class LoadBalancerServiceType extends NetworkServiceType {
    public $pool;
    public $virtualServer;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "pool",LoadBalancerPoolType::class);
        $this->setElement($data, "virtualServer",LoadBalancerVirtualServerType::class);
    }
}