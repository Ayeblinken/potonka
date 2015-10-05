<?php
class OrderRedoItemModel extends CI_Model
{
    public function __construct() 
    {
        parent::__construct();
    }
    public function insertOrderRedoItemTable($orderRedoItemArray)
    {
         $lenArr                  = sizeof($orderRedoItemArray);
         for($i = 0; $i<$lenArr; $i++)
         {
             $this->db->insert('OrderRedoItems', $orderRedoItemArray[$i]);

         }
         return $this->db->insert_id();
    }
    public function getOrderRedoItemsByOrderRedoID($orderRedoID)
    {
         $query = $this->db->get_where('OrderRedoItems',array('kf_OrderRedoID'=>$orderRedoID));
         
         return $query->result_array();
        
    }
    public function updateOrderRedoItemTable($orderRedoItemID,$data)
    {
        $this->db->where('kp_OrderRedoItemID', $orderRedoItemID);
        
        $this->db->update('OrderRedoItems',$data);
    }
    public function deleteOrderRedoItemTable($orderRedoID,$data)
    {
        $this->db->where('kf_OrderRedoID', $orderRedoID);
        
        $this->db->delete('OrderRedoItems');
    }
}

?>
