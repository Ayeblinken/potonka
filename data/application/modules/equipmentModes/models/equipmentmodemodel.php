<?php

class EquipmentModeModel extends CI_Model
{

  public function getEquipmentModeSelectData($equipmentID) {
    $query = $this->db->query("SELECT EquipmentModes.kp_EquipmentModeID,
                                EquipmentModes.kf_EquipmentID,
                                EquipmentModes.t_Name
                              FROM EquipmentModes
                              WHERE (ISNULL(EquipmentModes.nb_Inactive) or EquipmentModes.nb_Inactive = 0) and EquipmentModes.kf_EquipmentID = \"$equipmentID\"
                              ORDER BY EquipmentModes.t_Name ASC");

    return $query->result_array();
  }

    public function getEquipmentModeByID($equipmentModeID)
    {
        $query = $this->db->get_where('EquipmentModes',array('kp_EquipmentModeID'=>$equipmentModeID));
        return $query->row_array();

    }
}

?>
