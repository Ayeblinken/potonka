<?php
class EquipmentSubModeModel extends CI_Model 
{
    public function getEquipmentSubModeByEidEmid($equipmentID,$equipmentModeID)
    {
        $data  = array('kf_EquipmentID'=>$equipmentID,'kf_EquipmentModeID'=>$equipmentModeID);
        $query = $this->db->get_where('EquipmentSubModes',$data);
        
        return $query->result_array();
        
    }
    public function getEquipmentSubModeByID($equipmentSubModeID)
    {
         $query = $this->db->get_where('EquipmentSubModes',array('kp_EquipmentSubModeID'=>$equipmentSubModeID));
         
         return $query->row_array();
    }
 
}

?>
