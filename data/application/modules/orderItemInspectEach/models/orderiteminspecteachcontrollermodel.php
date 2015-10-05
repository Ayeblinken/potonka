<?php
class orderiteminspecteachcontrollermodel extends CI_Model 
{
    public function insertOrderItemInspectEachData($data=null)
    {
         $this->db->insert('OrderItemInspectEach', $data);
         return $this->db->insert_id();
        
    } 
}

?>
