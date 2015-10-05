<?php

class ProductBuildItemModel extends CI_Model {

  public function getProductBuildItemTblDataByID($productBuildID) {
    $query = $this->db->query("SELECT ProductBuildItems.kf_ProductBuildID, ProductBuildItems.kp_ProductBuildItemID, ProductBuildItems.kf_BuildItemID,
                                      ProductBuildItems.kf_EquipmentID, ProductBuildItems.kf_EquipmentModeID,
                                    	IF(ISNULL(biManCats.n_Sort),ManCats.n_Sort, biManCats.n_Sort) AS Sort,
                                    	IF(ISNULL(biManCats.t_Category),ManCats.t_Category, biManCats.t_Category) AS Category,
                                    	IF(ISNULL(BuildItems.t_Name),CONCAT(Equipment.t_EquipmentName,' ',EquipmentModes.t_Name), BuildItems.t_Name) AS Description,
                                    	ProductBuildItems.nb_Required,
                                    	ProductBuildItems.nb_ShowOnInvoice
                              FROM ProductBuildItems
                                LEFT OUTER JOIN BuildItems ON ProductBuildItems.kf_BuildItemID = BuildItems.kp_BuildItemID
                              	LEFT OUTER JOIN ManCats biManCats ON BuildItems.kf_ManCatID = biManCats.kp_ManCatID
                              	LEFT OUTER JOIN Equipment ON ProductBuildItems.kf_EquipmentID = Equipment.kp_EquipmentID
                              	LEFT OUTER JOIN ManCats ON Equipment.kf_ManCatID = ManCats.kp_ManCatID
                              	LEFT OUTER JOIN EquipmentModes ON ProductBuildItems.kf_EquipmentModeID = EquipmentModes.kp_EquipmentModeID
                              	LEFT OUTER JOIN ProductBuilds ON ProductBuildItems.kf_ProductBuildID = ProductBuilds.kp_ProductBuildID
                              WHERE (ISNULL(BuildItems.nb_Inactive) or BuildItems.nb_Inactive = 0) and
                              (ISNULL(Equipment.nb_Inactive) or Equipment.nb_Inactive = 0) and
                              (ISNULL(EquipmentModes.nb_Inactive) or EquipmentModes.nb_Inactive = 0) and
                              (ISNULL(Equipment.nb_Inactive) or Equipment.nb_Inactive = 0) and
                              ProductBuildItems.kf_ProductBuildID = \"$productBuildID\"
                              ORDER BY Sort ASC, Description ASC");

    return $query->result_array();
  }

  public function getProductBuildItemDataByID($productBuildItemID) {
    $query = $this->db->query("SELECT ProductBuildItems.kf_ProductBuildID, ProductBuildItems.kp_ProductBuildItemID, ProductBuildItems.kf_BuildItemID,
                                      ProductBuildItems.kf_EquipmentID, ProductBuildItems.kf_EquipmentModeID,
                                IF(ISNULL(biManCats.n_Sort),ManCats.n_Sort, biManCats.n_Sort) AS Sort,
                                IF(ISNULL(biManCats.t_Category),ManCats.t_Category, biManCats.t_Category) AS Category,
                                IF(ISNULL(BuildItems.t_Name),CONCAT(Equipment.t_EquipmentName,' ',EquipmentModes.t_Name), BuildItems.t_Name) AS Description,
                                ProductBuildItems.nb_Required,
                                ProductBuildItems.nb_ShowOnInvoice
                              FROM ProductBuildItems
                                LEFT OUTER JOIN BuildItems ON ProductBuildItems.kf_BuildItemID = BuildItems.kp_BuildItemID
                                LEFT OUTER JOIN ManCats biManCats ON BuildItems.kf_ManCatID = biManCats.kp_ManCatID
                                LEFT OUTER JOIN Equipment ON ProductBuildItems.kf_EquipmentID = Equipment.kp_EquipmentID
                                LEFT OUTER JOIN ManCats ON Equipment.kf_ManCatID = ManCats.kp_ManCatID
                                LEFT OUTER JOIN EquipmentModes ON ProductBuildItems.kf_EquipmentModeID = EquipmentModes.kp_EquipmentModeID
                                LEFT OUTER JOIN ProductBuilds ON ProductBuildItems.kf_ProductBuildID = ProductBuilds.kp_ProductBuildID
                              WHERE (ISNULL(BuildItems.nb_Inactive) or BuildItems.nb_Inactive = 0) and
                              (ISNULL(Equipment.nb_Inactive) or Equipment.nb_Inactive = 0) and
                              (ISNULL(EquipmentModes.nb_Inactive) or EquipmentModes.nb_Inactive = 0) and
                              (ISNULL(Equipment.nb_Inactive) or Equipment.nb_Inactive = 0) and
                              ProductBuildItems.kp_ProductBuildItemID = \"$productBuildItemID\"
                              ORDER BY Sort ASC, Description ASC");

    return $query->row_array();
  }


}
