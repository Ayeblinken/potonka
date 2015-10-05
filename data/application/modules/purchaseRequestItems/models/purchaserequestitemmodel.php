<?php

class purchaserequestitemmodel extends CI_Model
{
    //put your code here
    public function __construct()
    {
        parent::__construct();
    }
    public function getPurchaseRequestItemPurchaseRequestFromInvtItemID($inventoryItemID)
    {
        $this->db
                ->select("PurchaseRequestItems.kf_PurchaseRequestID,
                          PurchaseRequests.t_QBPurchaseOrderNum,
                          DATE_FORMAT(PurchaseRequests.d_Created, '%c-%e-%Y') AS d_Created,
                          PurchaseRequestItems.n_Quantity,
                          PurchaseRequestItems.n_Rate", false)
                  ->from('PurchaseRequestItems')
                  ->join('PurchaseRequests', 'PurchaseRequestItems.kf_PurchaseRequestID = PurchaseRequests.kp_PurchaseRequestID','inner')
                 ->where('PurchaseRequestItems.kf_InventoryItemID',$inventoryItemID)
                 ->order_by("PurchaseRequests.d_Created", "desc")
                 ->limit(10);

         $query = $this->db->get();

         return $query->result_array();
    }
    public function getPurchaseOrderGraph($inventoryItemID)
    {
        $query = $this->db->query("select COUNT(PurchaseRequests.t_QBPurchaseOrderNum) AS \"numberOfOrders\",
                                   MONTHNAME(PurchaseRequests.d_Created) as \"monthName\",
                                   sum(PurchaseRequestItems.n_Quantity) as \"numberOfQty\"
                                   from PurchaseRequestItems
                                   inner join PurchaseRequests on PurchaseRequestItems.kf_PurchaseRequestID = PurchaseRequests.kp_PurchaseRequestID
                                   where PurchaseRequestItems.kf_InventoryItemID =".$inventoryItemID." and YEAR(PurchaseRequests.d_Created) = \"2014\"
                                   group by MONTH(PurchaseRequests.d_Created)
                                   order by MONTH(PurchaseRequests.d_Created)");
        return $query->result_array();
    }

}
