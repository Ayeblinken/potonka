<?php

class builditemmodel extends CI_Model
{

  public function getBuildItemsByManCatID($manCatID) {
    $query = $this->db->query("SELECT BuildItems.kp_BuildItemID,
                                ManCatSubCats.t_SubCategory,
                                BuildItems.t_Name
                              FROM BuildItems INNER JOIN ManCats ON BuildItems.kf_ManCatID = ManCats.kp_ManCatID
                              INNER JOIN ManCatSubCats ON BuildItems.kf_ManCatSubCatID = ManCatSubCats.kp_ManCatSubCatID
                              WHERE (ISNULL(BuildItems.nb_Inactive) or BuildItems.nb_Inactive = 0) and BuildItems.kf_ManCatID = \"$manCatID\"
                              ORDER BY ManCatSubCats.t_SubCategory ASC, BuildItems.t_Name ASC");

    return $query->result_array();
  }

    public function addDirectionData($buildItemID)
    {
         $this->db->select('ManCatSubCatDirections.t_Directions')
                 ->from('BuildItems')
                 ->join('ManCatSubCatDirections', 'BuildItems.kf_ManCatSubCatID = ManCatSubCatDirections.kf_ManCatSubCatID')
                 ->where('BuildItems.kp_BuildItemID',$buildItemID);

         $query  = $this->db->get();

         return $query->result_array();

    }
    public function getBuildItemNameFromManCatID($manCatID)
    {
        $this->db->select('BuildItems.kp_BuildItemID,
                           ManCats.t_Category,ManCatSubCats.t_SubCategory,
                           BuildItems.t_Name')
                ->from('BuildItems')
                ->join('ManCats', 'BuildItems.kf_ManCatID = ManCats.kp_ManCatID','inner')
                ->join('ManCatSubCats', 'BuildItems.kf_ManCatSubCatID = ManCatSubCats.kp_ManCatSubCatID','inner')
                ->where('BuildItems.kf_ManCatID',$manCatID)
                ->where('BuildItems.nb_Inactive',null)
                ->or_where('BuildItems.nb_Inactive',0);

        $query  = $this->db->get();

        return $query->result_array();

    }
    public function getBudilItems_Join_ManCats_InnerJoin_ManCatSubCats()
    {
        $this->db->select('BuildItems.kp_BuildItemID,
                           BuildItems.t_Name,
                           BuildItems.nb_CanAlsoPrintDoubleSided,
                           ManCats.t_Category,
                           ManCatSubCats.t_SubCategory,
                           BuildItems.t_ManufacturingCategory,
                           BuildItems.t_InitialsForOrderList,
                           BuildItems.n_Complexity,
                           BuildItems.t_Name,
                           BuildItems.nb_Inactive,
                           BuildItems.kf_ManCatID,
                           BuildItems.kf_ManCatSubCatID')
                ->from('BuildItems')
                ->join('ManCats', 'BuildItems.kf_ManCatID = ManCats.kp_ManCatID','inner')
                ->join('ManCatSubCats', 'BuildItems.kf_ManCatSubCatID = ManCatSubCats.kp_ManCatSubCatID','inner');

        $query  = $this->db->get();

        return $query->result_array();
    }
    public function getBudilItems_Join_ManCats_InnerJoin_ManCatSubCatsByBuildItemID($buildItemID)
    {
        $this->db->select('BuildItems.kp_BuildItemID,
                           BuildItems.t_Name,
                           BuildItems.nb_CanAlsoPrintDoubleSided,
                           ManCats.t_Category,
                           ManCatSubCats.t_SubCategory,
                           BuildItems.t_ManufacturingCategory,
                           BuildItems.t_InitialsForOrderList,
                           BuildItems.n_Complexity,
                           BuildItems.t_Name,
                           BuildItems.nb_Inactive,
                           BuildItems.kf_ManCatID,
                           BuildItems.kf_ManCatSubCatID')
                ->from('BuildItems')
                ->join('ManCats', 'BuildItems.kf_ManCatID = ManCats.kp_ManCatID','inner')
                ->join('ManCatSubCats', 'BuildItems.kf_ManCatSubCatID = ManCatSubCats.kp_ManCatSubCatID','inner')
                ->where('BuildItems.kp_BuildItemID',$buildItemID);

        $query  = $this->db->get();

        return $query->row_array();
    }

}

?>
