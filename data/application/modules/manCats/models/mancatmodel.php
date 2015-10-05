<?php
class ManCatModel extends CI_Model
{
    public function getManCatCategory()
    {
        $this->db->select('DISTINCT(ManCats.t_Category) as t_Category,ManCats.kp_ManCatID')
                 ->from('ManCats')
                 ->join('BuildItems','ManCats.kp_ManCatID = BuildItems.kf_ManCatID','inner');

        $query  = $this->db->get();

        return $query->result_array();

    }

    public function insertManCatTblData($data=null)
    {
         $this->db->insert('ManCats', $data);
         return $this->db->insert_id();

    }
    public function updateManCatTbl($data,$manCatID)
    {
        $result = $this->db->update('ManCats', $data, array('kp_ManCatID'=>  $manCatID));

        if(!$result)
        {
            return $this->db->_error_message();
        }
        else
        {
            return $this->db->affected_rows();

        }

    }
    public function updateManCatSort($manCatData) {
      $this->db->update('ManCats', $manCatData, array('kp_ManCatID' => $manCatData['kp_ManCatID']));
    }
    public function updateManCatSubCatSort($manCatSubCatData) {
      $this->db->update('ManCatSubCats', $manCatSubCatData, array('kp_ManCatSubCatID' => $manCatSubCatData['kp_ManCatSubCatID']));
    }
    public function updateManCatSubCatDirectionSort($manCatSubCatDirectionData) {
      $this->db->update('ManCatSubCatDirections', $manCatSubCatDirectionData, array('kp_ManCatSubCatDirectionID' => $manCatSubCatDirectionData['kp_ManCatSubCatDirectionID']));
    }
}
?>
