<?php namespace vCenterCloud;

use \Exception;

class ErrorType extends VCloudExtensibleType {
    public $stackTrace;
    public $message;
    public $majorErrorCode;
    public $minorErrorCode;
    public $vendorSpecificErrorCode;
    
    public $TenantError; // TenantErrorType
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->TenantError = new TentantErrorType($session, $data);
    }
}