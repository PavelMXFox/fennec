<?php namespace agent;

/**
 * 
 * @author palkan
 * @copyright MXSTAR 2017-2021
 * @version 2.0
 * 
 **/

class serviceBaseType extends transportType {
    public $status=self::statusUnknown;
    public $lastCheckStamp=0;
    public $inState=0;
    public $syncStamp=0;
    public $createStamp=0;
    public ?bool $preFailMode=null;
    public $preFailTimeoutOverride=null;

    // Common statuses
    public const statusUnknown='UNKNOWN';
    public const statusOk='OK';
    public const statusPrefail='PREFAIL';
    public const statusFail='FAIL';
    public const statusRecovering='RECOVER';
    public const statusFloat='FLOAT';
    
    // Poller additional status
    public const statusReady='READY';
    public const statusComplete='COMPLETE';
    
    public function setSync() {
        $this->syncStamp=time();
    }
}
?>