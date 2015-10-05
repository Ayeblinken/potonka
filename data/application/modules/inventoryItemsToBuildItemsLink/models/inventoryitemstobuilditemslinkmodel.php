<?php
class InventoryItemsToBuildItemsLinkModel extends CI_Model
{
    public function getInventoryItemList($buildItemID)
    {
        $where = "InventoryItemsToBuildItemsLink.kf_BuildItemID =".$buildItemID." and
                  (isnull(InventoryItems.nb_Inactive) or (InventoryItems.nb_Inactive=\"0\"))";
        $this->db->select(' InventoryItemsToBuildItemsLink.kf_InventoryItemID,
                            InventoryItems.t_description,
                            InventoryItems.t_SupplierType, ,')
                 ->select_sum('InventoryLocationItems.n_QntyOnHand','OH')
                 ->from('InventoryItemsToBuildItemsLink')
                 ->join('InventoryItems', 'InventoryItemsToBuildItemsLink.kf_InventoryItemID = InventoryItems.kp_InventoryItemID','LEFT')
                 ->join('InventoryLocationItems', 'InventoryItems.kp_InventoryItemID = InventoryLocationItems.kf_InventoryItemID','LEFT')
//                 ->where('InventoryItemsToBuildItemsLink.kf_BuildItemID',$buildItemID)
                 ->where($where)
                 ->group_by('InventoryItemsToBuildItemsLink.kf_InventoryItemID')
                 ->order_by("InventoryItems.t_SupplierType", "desc");

        $query  = $this->db->get();

        return $query->result_array();

    }
    public function categoryLinkedBuildItemsTblData($inventoryItemID)
    {
        $this->db->select('InventoryItemsToBuildItemsLink.kp_InventoryItemsToBuildItemsLinkID,
                           ManCats.t_Category,BuildItems.t_Name,BuildItems.kp_BuildItemID')
                 ->from('InventoryItemsToBuildItemsLink')
                 ->join('BuildItems', 'InventoryItemsToBuildItemsLink.kf_BuildItemID = BuildItems.kp_BuildItemID','inner')
                 ->join('ManCats', 'BuildItems.kf_ManCatID = ManCats.kp_ManCatID','inner')
                 ->where('InventoryItemsToBuildItemsLink.kf_InventoryItemID',$inventoryItemID);

        $query  = $this->db->get();

        return $query->result_array();

    }
    public function inventoryItemsToBuildItemsLink_Vendor_InventoryItems_InventoryLocationItems($buildItemID)
    {
        $where = "InventoryItemsToBuildItemsLink.kf_BuildItemID =".$buildItemID." and
                  (isnull(Vendors.nb_Inactive) or (Vendors.nb_Inactive=\"0\")) and
                  (isnull(InventoryItems.nb_Inactive) or (InventoryItems.nb_Inactive=\"0\"))";
        $this->db->select(' InventoryItemsToBuildItemsLink.kp_InventoryItemsToBuildItemsLinkID,
                            InventoryItemsToBuildItemsLink.kf_BuildItemID,
                            InventoryItemsToBuildItemsLink.kf_VendorID,
                            InventoryItemsToBuildItemsLink.kf_InventoryItemID,
                            InventoryItemsToBuildItemsLink.nb_UseAsDefaultItemForChosenMaterial,
                            Vendors.t_CompanyName,
                            InventoryItems.t_description,
                            InventoryItems.n_PriceByFt,
                            InventoryItems.t_PriceByFtSize,
                            InventoryItems.n_CasePrice,
                            InventoryItems.n_AttribHeight ')
                 ->select_sum('InventoryLocationItems.n_QntyOnHand','n_QntyOnHand')
                 ->from('InventoryItemsToBuildItemsLink')
                 ->join('Vendors', 'InventoryItemsToBuildItemsLink.kf_VendorID = Vendors.kp_VendorID','LEFT')
                 ->join('InventoryItems', 'InventoryItemsToBuildItemsLink.kf_InventoryItemID = InventoryItems.kp_InventoryItemID','LEFT')
                 ->join('InventoryLocationItems', 'InventoryItems.kp_InventoryItemID = InventoryLocationItems.kf_InventoryItemID','LEFT')
                 ->where($where)
                 ->group_by('InventoryItemsToBuildItemsLink.kp_InventoryItemsToBuildItemsLinkID')
                 ->order_by("Vendors.t_CompanyName", "ASC")
                 ->order_by("InventoryItems.n_AttribHeight", "ASC");

        $query  = $this->db->get();

        return $query->result_array();
    }
}

?>
