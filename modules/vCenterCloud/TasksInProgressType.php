<?php 
namespace vCenterCloud;

use \Exception;

class TasksInProgressType extends VCloudExtensibleType {
   public $task=[];
   
   protected function fill($session, $data) {
       parent::fill($session, $data);
       $this->setElement($data, "task", TaskType::class);
   }
}
?>