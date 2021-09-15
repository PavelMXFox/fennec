<?php namespace agent;

class resource extends transportType {
    public $type;
    public $value;
    public $refDes;
    
    public const cpuCoresPerSocket="cpuCorePS";
    public const cpuCoresTotal="cpuCoresTTL";
    public const cpuSockets="cpuSockets";
    public const cpuThreadsPerSocket="cpuThPS";
    public const cpuThreadsTotal="cpuThTTL";
    public const cpuMHzPerSocket="cpuMHzPS";
    public const cpuMHzTotal="cpuMHzTTL";
    public const ramTotal="ram";
    public const storageTotal="storageTTL";
    public const storageUnit="storageUnit";
    
    public function __construct($type, $value, $refDes=null) {
        $this->type=$type;
        $this->value=$value;
        $this->refDes=$refDes;
    }
}
?>