<?php

namespace vCenterCloud;

use \Exception;

class SnapshotSectionType extends sectionType {
    public $href;
    public $type;
    public $link;
    public $snapshot;
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->link = empty($data->link)?null:$this->fillElement($session, $data->link, __NAMESPACE__."\LinkType");
        $this->snapshot = empty($data->snapshot)?null:$this->fillElement($session, $data->snapshot, __NAMESPACE__."\SnapshotType");
    }
}