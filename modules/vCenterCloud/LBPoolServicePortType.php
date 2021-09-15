<?php namespace vCenterCloud;


class LBPoolServicePortType extends VCloudExtensibleType {
    public $algorithm;
    public $healthCheckPort;
    public $isEnabled;
    public $port;
    public $protocol;
    
    public $healthCheck;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "healthCheck",LBPoolHealthCheckType::class);
    }
}