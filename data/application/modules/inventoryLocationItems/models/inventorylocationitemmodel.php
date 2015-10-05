<?php

class inventorylocationitemmodel extends CI_Model
{
      public function getLocationData($id)
      {
          $selectArray = array('kp_InventoryLocationItemID', 'kf_InventoryItemID', 'n_QntyOnHand', 'InventoryItems.t_description');
          $this->db->select($selectArray)->from('InventoryLocationItems')
                   ->join('InventoryItems', 'InventoryLocationItems.kf_InventoryItemID = InventoryItems.kp_InventoryItemID')
                   ->where('InventoryLocationItems.kf_InventoryLocationID', $id)
                   ->order_by('InventoryLocationItems.kf_InventoryItemID', 'Asc');

           $query = $this->db->get();
           $result=$query->result();
           return $result;
      }
      public function getInvtLocationItemsFromInvtItemID($inventoryItemID)
      {
          $this->db
                ->select(' InventoryLocationItems.kp_InventoryLocationItemID,
                           InventoryLocationItems.kf_InventoryItemID,
                           InventoryLocationItems.kf_InventoryLocationID,
                           InventoryLocations.t_Location,
                           TRIM(TRAILING "." FROM(ifnull(CAST(TRIM(TRAILING "0" FROM InventoryLocationItems.n_QntyOnHand)as CHAR),""))) as n_QntyOnHand',false)
                  ->from('InventoryLocationItems')
                  ->join('InventoryLocations', 'InventoryLocationItems.kf_InventoryLocationID = InventoryLocations.kp_InventoryLocationID','inner')
                 ->where('InventoryLocationItems.kf_InventoryItemID',$inventoryItemID);

         $query = $this->db->get();

         return $query->result_array();

      }
      public function getInvLocationItemByID($inventoryLocationItemID)
      {
         $query = $this->db->get_where('InventoryLocationItems',array('kp_InventoryLocationItemID'=>$inventoryLocationItemID));

         return $query->row_array();

      }

      public function insertInvLocationItemTable($insertInvLocationItemArr)
      {
          $lenArr                  = sizeof($insertInvLocationItemArr);
          for($i = 0; $i<$lenArr; $i++)
          {
              $this->db->insert('InventoryLocationItems', $insertInvLocationItemArr[$i]);

          }
          return $this->db->insert_id();
      }

}

?>
