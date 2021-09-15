<?php namespace vCenterCloud;

class StaticRoutingServiceType extends NetworkServiceType {
    public $isEnabled;
    public $staticRoute;
        
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->setElement($data, "staticRoute",StaticRouteType::class);
    }
}