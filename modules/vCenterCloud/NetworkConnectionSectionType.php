<?php

namespace vCenterCloud;

use \Exception;

class NetworkConnectionSectionType extends sectionType {
   public $href;
   public $type;
   
   public $link;
   public $networkConnection;
   public $PrimaryNetworkConnectionIndex;
    
    
    protected function fill($session, $data) {
        parent::fill($session, $data);
        $this->link = $this->fillElement($session, $data->link, LinkType::class);
        $this->networkConnection = $this->fillElement($session, $data->networkConnection,NetworkConnectionType::class);
    }
    
    
}

?>