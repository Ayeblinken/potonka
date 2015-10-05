<?php

class EquipmentModel  extends CI_Model
{

  public function getEquipmentSelectData() {
    $query = $this->db->query("SELECT Equipment.kp_EquipmentID,
                                Equipment.t_EquipmentName
                              FROM Equipment
                              WHERE ISNULL(Equipment.nb_Inactive) or Equipment.nb_Inactive = 0
                              ORDER BY Equipment.nb_NotAPress ASC, Equipment.t_EquipmentName ASC");

    return $query->result_array();
  }

    public function getEquipmentByID($equipmentID)
    {


        $query = $this->db->get_where('Equipment',array('kp_EquipmentID'=>$equipmentID));

        return $query->row_array();

    }
}

?>
