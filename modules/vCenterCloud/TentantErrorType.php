<?php namespace vCenterCloud;

use \Exception;

class TentantErrorType extends VCloudExtensibleType {
    public $message;
    public $majorErrorCode;
    public $minorErrorCode;
    public $vendorSpecificErrorCode;
}