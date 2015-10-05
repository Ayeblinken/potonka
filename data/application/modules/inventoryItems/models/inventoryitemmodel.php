<?php
class InventoryItemModel extends CI_Model
{
    public function getInventoryItemByID($inventoryItemID)
    {
        $query = $this->db->get_where('InventoryItems',array('kp_InventoryItemID'=>$inventoryItemID));

        return $query->row_array();

    }

    public function getInvReceiptInProcessList() {
      $query = $this->db->query("SELECT quickbooks_sql2.itemreceipt.*, quickbooks_sql2.error_table.Error_Desc
                                     FROM quickbooks_sql2.itemreceipt
                                     LEFT JOIN quickbooks_sql2.error_table ON quickbooks_sql2.itemreceipt.TxnID = quickbooks_sql2.error_table.IDKEY
                                     WHERE (quickbooks_sql2.itemreceipt.Status = 'ADD' || quickbooks_sql2.itemreceipt.Status = 'UPDATE' || quickbooks_sql2.itemreceipt.Status = 'DELETE')");

    return $query->result_array();
    }

    public function getInvItemInProcessList() {
          $query = $this->db->query("SELECT quickbooks_sql2.itemnoninventory.ListID,
                                    	quickbooks_sql2.itemnoninventory.`Name`,
                                    	quickbooks_sql2.salesorpurchasedetail.Description,
                                    	quickbooks_sql2.itemnoninventory.`Status`,
                                    	quickbooks_sql2.error_table.Error_Desc
                                    FROM quickbooks_sql2.itemnoninventory
                                    	 LEFT OUTER JOIN quickbooks_sql2.error_table ON quickbooks_sql2.itemnoninventory.ListID = quickbooks_sql2.error_table.IDKEY
                                    	 LEFT OUTER JOIN quickbooks_sql2.salesorpurchasedetail ON quickbooks_sql2.itemnoninventory.ListID = quickbooks_sql2.salesorpurchasedetail.IDKEY
                                    WHERE quickbooks_sql2.itemnoninventory.Status = 'ADD' OR quickbooks_sql2.itemnoninventory.Status = 'UPDATE'
                                    OR quickbooks_sql2.itemnoninventory.Status = 'DELETE'");

        return $query->result_array();
    }

    public function getAllInventoryItems()
    {
        $query = $this->db->get('InventoryItems');

        return $query->result_array();
    }
    public function getInventoryOnHandMinMax($inventoryItemID)
    {
        $this->db->select('TRIM(TRAILING \'.\' FROM IFNULL(CAST(   TRIM(TRAILING \'0\' FROM Sum(InventoryLocationItems.n_QntyOnHand))AS CHAR),\'0\')) AS n_Qty',false)
                 ->select('TRIM(TRAILING \'.\' FROM ifnull(CAST(TRIM(TRAILING \'0\' FROM InventoryItems.n_ReorderPoint) AS CHAR),\'0\')) AS n_Min,
                     TRIM(TRAILING \'.\' FROM ifnull(CAST(TRIM(TRAILING \'0\' FROM InventoryItems.n_ReorderQty) AS CHAR),\'0\')) AS n_Max',false)
                 ->from('InventoryItems')
                 ->join('InventoryLocationItems', 'InventoryItems.kp_InventoryItemID = InventoryLocationItems.kf_InventoryItemID','left')
                 ->where('InventoryItems.kp_InventoryItemID',$inventoryItemID)
                 ->group_by('InventoryItems.kp_InventoryItemID');

        $query  = $this->db->get();

        return $query->row_array();

    }
    public function getInventoryItemLocationInfo($description)
    {
         $query = $this->db->query("SELECT InventoryItems.kp_InventoryItemID,InventoryItems.t_description,
                                     ifnull(SUM(InventoryLocationItems.n_QntyOnHand),0) as total,
                                    MATCH (t_description) AGAINST ('".$description."' IN BOOLEAN MODE) as Score
                                    FROM InventoryItems
                                    left join InventoryLocationItems on InventoryItems.kp_InventoryItemID = InventoryLocationItems.kf_InventoryItemID
                                    WHERE MATCH (t_description) AGAINST ('".$description."' IN BOOLEAN MODE)
                                    AND (isnull(InventoryItems.nb_Inactive) or (InventoryItems.nb_Inactive = 0))
                                    group by InventoryItems.kp_InventoryItemID
                                    ORDER BY total desc");

//        $this->db->select('InventoryItems.kp_InventoryItemID,InventoryItems.t_description,
//                           ifnull(SUM(InventoryLocationItems.n_QntyOnHand),0) as total',false)
//                 ->from('InventoryItems')
//                 ->join('InventoryLocationItems','InventoryItems.kp_InventoryItemID = InventoryLocationItems.kf_InventoryItemID','left')
//                 ->like('InventoryItems.t_description', $description,'both')
//                 ->group_by('InventoryItems.kp_InventoryItemID')
//                 ->order_by("total", "desc");
//
//        $query  = $this->db->get();

        return $query->result_array();

    }
    public function getInvItemDetialsTopList($inventoryItemID)
    {
        $query = $this->db->query("SELECT Vendors.t_CompanyName,
                            InventoryItems.kp_InventoryItemID,
                            InventoryItems.t_PartNumber,
                            InventoryItems.t_description,
                            InventoryItems.t_InvType,
                            ifnull(Sum(InventoryLocationItems.n_QntyOnHand),'') AS OH,
                            ifnull(InventoryItems.n_ReorderPoint,'') AS Min,
                            ifnull(InventoryItems.n_ReorderQty,'') AS Max,
                            InventoryItems.nb_OrderPlaced,
                            InventoryItems.t_ItemCategory
                            FROM InventoryItems
                                     LEFT JOIN InventoryLocationItems ON InventoryItems.kp_InventoryItemID = InventoryLocationItems.kf_InventoryItemID
                                     INNER JOIN Vendors ON InventoryItems.kf_VendorID = Vendors.kp_VendorID
                            WHERE InventoryItems.kp_InventoryItemID =".$inventoryItemID."
                            GROUP BY InventoryItems.kp_InventoryItemID");

        return $query->row_array();

    }
    public function  getInvItemDetialsBottomList($inventoryItemID)
    {
        $this->db->select('InventoryItems.kp_InventoryItemID,IFNULL(InventoryLocations.t_Location,\'\') as t_Location ,
                           ifnull(InventoryLocationItems.n_QntyOnHand,\'\') as n_QntyOnHand ',false)
                 ->from('InventoryItems')
                 ->join('InventoryLocationItems','InventoryItems.kp_InventoryItemID = InventoryLocationItems.kf_InventoryItemID','LEFT')
                 ->join('InventoryLocations','InventoryLocationItems.kf_InventoryLocationID = InventoryLocations.kp_InventoryLocationID','LEFT')
                 ->where('InventoryItems.kp_InventoryItemID',$inventoryItemID);

        $query  = $this->db->get();

        return $query->result_array();
    }
    public function getInvtInactiveVendorLocByInvtItemID($inventoryItemID)
    {
        $query = $this->db->query("SELECT InventoryItems.kp_InventoryItemID,
                                    Vendors.t_CompanyName,
                                    InventoryItems.t_ItemCategory,
                                    InventoryItems.t_description,
                                    trim(trailing \".\" from(ifnull(cast(trim(trailing \"0\" from Sum(InventoryLocationItems.n_QntyOnHand)) as CHAR),\"\"))) as sumInvtLocaQtyOnHand,
                                    trim(trailing \".\" from (ifnull(cast(trim(TRAILING \"0\" from InventoryItems.n_ReorderPoint) as char),\"\"))) as n_ReorderPoint,
                                    trim(trailing \".\" from (ifnull(cast(trim(TRAILING \"0\" from InventoryItems.n_ReorderQty) as char),\"\"))) as n_ReorderQty,
                                    InventoryItems.n_CasePrice,
                                    InventoryItems.t_InvType,
                                    InventoryItems.t_SupplierType,
                                    InventoryItems.nb_OrderPlaced,tb3.totalOnOrder,
                                    if (tb3.totalOnOrder>0,'showOnOrderTrue','hideOnOrderTrue') as showHideOnOrder,
                                    InventoryItems.nb_Inactive,
                                    InventoryItems.t_QBServiceID,
                                    InventoryItems.t_Notes,
                                    InventoryItems.kf_VendorID,
                                    InventoryItems.nb_ComesInCase,
                                    InventoryItems.t_PartNumber,
                                    trim(trailing\".\"from(ifnull(cast(trim(trailing \"0\" from InventoryItems.n_CasePrice) as char),\"\"))) as n_CasePrice,
                                    trim(trailing\".\"from(ifnull(cast(trim(trailing \"0\" from InventoryItems.n_EachPrice) as char),\"\"))) as n_EachPrice,
                                    ifnull(InventoryItems.t_PriceByFtSize,\"\") as t_PriceByFtSize,
                                    ifnull(InventoryItems.n_PriceByFt,\"\") as n_PriceByFt,
                                    tb2.WhenRecentOrderPlaced as WhenRecentOrderPlaced,
                                    if(t_InvType = \"Stocking\",
                                    if(ifnull(Sum(InventoryLocationItems.n_QntyOnHand),0) <= ifnull(InventoryItems.n_ReorderPoint,0),\"BelowMin\",
						if((ifnull(Sum(InventoryLocationItems.n_QntyOnHand),0)-ifnull(InventoryItems.n_ReorderPoint,0))/ifnull(InventoryItems.n_ReorderQty,0)<=0.20,\"CloseToMin\",\"stockingAboveMin\")),\"noStocking\")as warningDanger
                                   FROM InventoryItems
                                   LEFT JOIN Vendors ON InventoryItems.kf_VendorID = Vendors.kp_VendorID
                                   LEFT JOIN InventoryLocationItems ON InventoryItems.kp_InventoryItemID = InventoryLocationItems.kf_InventoryItemID
                                   left join (
                                   SELECT kp_PurchaseRequestItemID,kf_InventoryItemID,max(PurchaseRequests.d_Created) as WhenRecentOrderPlaced
                                   FROM basetables2.PurchaseRequestItems
                                   inner join PurchaseRequests on PurchaseRequests.kp_PurchaseRequestID = PurchaseRequestItems.kf_PurchaseRequestID
                                   group by kf_InventoryItemID
                                   ) as tb2
                                   on InventoryItems.kp_InventoryItemID = tb2.kf_InventoryItemID
                                   left join(
				   SELECT quickbooks_sql2.purchaseorderlinedetail.ItemRef_ListID,
                                   sum(quickbooks_sql2.purchaseorderlinedetail.Quantity) - sum(purchaseorderlinedetail.ReceivedQuantity)  AS totalOnOrder
                                   FROM quickbooks_sql2.purchaseorderlinedetail
				   INNER JOIN quickbooks_sql2.purchaseorder ON quickbooks_sql2.purchaseorderlinedetail.IDKEY = quickbooks_sql2.purchaseorder.TxnID
				   WHERE (quickbooks_sql2.purchaseorder.IsFullyReceived = \"false\" and quickbooks_sql2.purchaseorder.IsManuallyClosed = \"false\") and quickbooks_sql2.purchaseorderlinedetail.ItemRef_ListID IS NOT NULL
				   GROUP BY quickbooks_sql2.purchaseorderlinedetail.ItemRef_ListID
                                   ) as tb3
                                   on InventoryItems.t_QBServiceID = tb3.ItemRef_ListID
                                   WHERE (InventoryItems.nb_Inactive =1) and kp_InventoryItemID = ".$inventoryItemID."
                                   GROUP BY InventoryItems.kp_InventoryItemID");

        return $query->row_array();

    }

    public function getInvItemLocVenByInvtItemID($inventoryItemID)
    {
        $query = $this->db->query("SELECT InventoryItems.kp_InventoryItemID,
                                    Vendors.t_CompanyName,
                                    InventoryItems.t_ItemCategory,
                                    InventoryItems.t_description,
                                    trim(trailing \".\" from(ifnull(cast(trim(trailing \"0\" from Sum(InventoryLocationItems.n_QntyOnHand)) as CHAR),\"\"))) as sumInvtLocaQtyOnHand,
                                    trim(trailing \".\" from (ifnull(cast(trim(TRAILING \"0\" from InventoryItems.n_ReorderPoint) as char),\"\"))) as n_ReorderPoint,
                                    trim(trailing \".\" from (ifnull(cast(trim(TRAILING \"0\" from InventoryItems.n_ReorderQty) as char),\"\"))) as n_ReorderQty,
                                    InventoryItems.n_CasePrice,
                                    InventoryItems.t_InvType,
                                    InventoryItems.t_SupplierType,
                                    InventoryItems.nb_OrderPlaced,tb3.totalOnOrder,
                                    if (tb3.totalOnOrder>0,'showOnOrderTrue','hideOnOrderTrue') as showHideOnOrder,
                                    InventoryItems.nb_Inactive,
                                    InventoryItems.t_QBServiceID,
                                    InventoryItems.t_Notes,
                                    InventoryItems.kf_VendorID,
                                    InventoryItems.nb_ComesInCase,
                                    InventoryItems.t_PartNumber,
                                    InventoryItems.t_QBCOGSAccount,
                                    InventoryItems.t_CogNeedsFixed,
                                    InventoryItems.t_PsvAdhesiveType,
                                    InventoryItems.t_AttribName,
                                    InventoryItems.t_PODescription,
                                    InventoryItems.nb_CogNeedsFixed,
                                    InventoryItems.nb_ShipOnContainer,
                                    InventoryItems.n_ShipContainerTimeFrame,
                                    InventoryItems.nb_ShipFiller,
                                    InventoryItems.t_AttribCountByLotItem,
                                    InventoryItems.n_AttribLotQty,
                                    InventoryItems.nb_DoNotCountInValuation,
                                    InventoryItems.t_AttribSizeType,
                                    InventoryItems.n_AttribHeight,
                                    InventoryItems.n_AttribWidth,
                                    InventoryItems.n_AttribLength,
                                    InventoryItems.n_AttribThickness,
                                    InventoryItems.n_AttribVolume,
                                    InventoryItems.t_AttribHeightUOM,
                                    InventoryItems.t_AttribWidthUOM,
                                    InventoryItems.t_AttribLengthUOM,
                                    InventoryItems.t_AttribThicknessUOM,
                                    InventoryItems.t_AttribVolumeUOM,
                                    InventoryItems.t_AttribColor,
                                    InventoryItems.t_AttribSurfaceType,
                                    trim(trailing\".\"from(ifnull(cast(trim(trailing \"0\" from InventoryItems.n_CasePrice) as char),\"\"))) as n_CasePrice,
                                    trim(trailing\".\"from(ifnull(cast(trim(trailing \"0\" from InventoryItems.n_EachPrice) as char),\"\"))) as n_EachPrice,
                                    ifnull(InventoryItems.t_PriceByFtSize,\"\") as t_PriceByFtSize,
                                    ifnull(InventoryItems.n_PriceByFt,\"\") as n_PriceByFt,
                                    tb2.WhenRecentOrderPlaced as WhenRecentOrderPlaced,
                                    if(t_InvType = \"Stocking\",
                                    if(ifnull(Sum(InventoryLocationItems.n_QntyOnHand),0) <= ifnull(InventoryItems.n_ReorderPoint,0),\"BelowMin\",
						if((ifnull(Sum(InventoryLocationItems.n_QntyOnHand),0)-ifnull(InventoryItems.n_ReorderPoint,0))/ifnull(InventoryItems.n_ReorderQty,0)<=0.20,\"CloseToMin\",\"stockingAboveMin\")),\"noStocking\")as warningDanger
                                   FROM InventoryItems
                                   LEFT JOIN Vendors ON InventoryItems.kf_VendorID = Vendors.kp_VendorID
                                   LEFT JOIN InventoryLocationItems ON InventoryItems.kp_InventoryItemID = InventoryLocationItems.kf_InventoryItemID
                                   left join (
                                   SELECT kp_PurchaseRequestItemID,kf_InventoryItemID,max(PurchaseRequests.d_Created) as WhenRecentOrderPlaced
                                   FROM basetables2.PurchaseRequestItems
                                   inner join PurchaseRequests on PurchaseRequests.kp_PurchaseRequestID = PurchaseRequestItems.kf_PurchaseRequestID
                                   group by kf_InventoryItemID
                                   ) as tb2
                                   on InventoryItems.kp_InventoryItemID = tb2.kf_InventoryItemID
                                   left join(
				   SELECT quickbooks_sql2.purchaseorderlinedetail.ItemRef_ListID,
                                   sum(quickbooks_sql2.purchaseorderlinedetail.Quantity) - sum(purchaseorderlinedetail.ReceivedQuantity)  AS totalOnOrder
                                   FROM quickbooks_sql2.purchaseorderlinedetail
				   INNER JOIN quickbooks_sql2.purchaseorder ON quickbooks_sql2.purchaseorderlinedetail.IDKEY = quickbooks_sql2.purchaseorder.TxnID
				   WHERE (quickbooks_sql2.purchaseorder.IsFullyReceived = \"false\" and quickbooks_sql2.purchaseorder.IsManuallyClosed = \"false\") and quickbooks_sql2.purchaseorderlinedetail.ItemRef_ListID IS NOT NULL
				   GROUP BY quickbooks_sql2.purchaseorderlinedetail.ItemRef_ListID
                                   ) as tb3
                                   on InventoryItems.t_QBServiceID = tb3.ItemRef_ListID
                                   WHERE kp_InventoryItemID = ".$inventoryItemID."
                                   GROUP BY InventoryItems.kp_InventoryItemID");

        return $query->row_array();


    }
    public function getInvItemLocationVendor()
    {
          $query = $this->db->query("SELECT InventoryItems.kp_InventoryItemID,
                                      Vendors.t_CompanyName,
                                      InventoryItems.t_ItemCategory,
                                      InventoryItems.t_description,
                                      trim(trailing \".\" from(ifnull(cast(trim(trailing \"0\" from Sum(InventoryLocationItems.n_QntyOnHand)) as CHAR),\"\"))) as sumInvtLocaQtyOnHand,
                                      trim(trailing \".\" from (ifnull(cast(trim(TRAILING \"0\" from InventoryItems.n_ReorderPoint) as char),\"\"))) as n_ReorderPoint,
                                      trim(trailing \".\" from (ifnull(cast(trim(TRAILING \"0\" from InventoryItems.n_ReorderQty) as char),\"\"))) as n_ReorderQty,
                                      InventoryItems.n_CasePrice,
                                      InventoryItems.t_InvType,
                                      InventoryItems.t_SupplierType,
                                      InventoryItems.nb_OrderPlaced,tb3.totalOnOrder,
                                      if (tb3.totalOnOrder>0,'showOnOrderTrue','hideOnOrderTrue') as showHideOnOrder,
                                      InventoryItems.nb_Inactive,
                                      InventoryItems.t_QBServiceID,
                                      InventoryItems.t_Notes,
                                      InventoryItems.kf_VendorID,
                                      InventoryItems.nb_ComesInCase,
                                      InventoryItems.t_PartNumber,
                                      trim(trailing\".\"from(ifnull(cast(trim(trailing \"0\" from InventoryItems.n_CasePrice) as char),\"\"))) as n_CasePrice,
                                      trim(trailing\".\"from(ifnull(cast(trim(trailing \"0\" from InventoryItems.n_EachPrice) as char),\"\"))) as n_EachPrice,
                                      ifnull(InventoryItems.t_PriceByFtSize,\"\") as t_PriceByFtSize,
                                      ifnull(InventoryItems.n_PriceByFt,\"\") as n_PriceByFt,
                                      tb2.WhenRecentOrderPlaced as WhenRecentOrderPlaced,
                                      if(t_InvType = \"Stocking\",
                                      if(ifnull(Sum(InventoryLocationItems.n_QntyOnHand),0) <= ifnull(InventoryItems.n_ReorderPoint,0),\"BelowMin\",
  						if((ifnull(Sum(InventoryLocationItems.n_QntyOnHand),0)-ifnull(InventoryItems.n_ReorderPoint,0))/ifnull(InventoryItems.n_ReorderQty,0)<=0.20,\"CloseToMin\",\"stockingAboveMin\")),\"noStocking\")as warningDanger
                                     FROM InventoryItems
                                     LEFT JOIN Vendors ON InventoryItems.kf_VendorID = Vendors.kp_VendorID
                                     LEFT JOIN InventoryLocationItems ON InventoryItems.kp_InventoryItemID = InventoryLocationItems.kf_InventoryItemID
                                     left join (
                                     SELECT kp_PurchaseRequestItemID,kf_InventoryItemID,max(PurchaseRequests.d_Created) as WhenRecentOrderPlaced
                                     FROM basetables2.PurchaseRequestItems
                                     inner join PurchaseRequests on PurchaseRequests.kp_PurchaseRequestID = PurchaseRequestItems.kf_PurchaseRequestID
                                     group by kf_InventoryItemID
                                     ) as tb2
                                     on InventoryItems.kp_InventoryItemID = tb2.kf_InventoryItemID
                                     left join(
  				   SELECT quickbooks_sql2.purchaseorderlinedetail.ItemRef_ListID,
                                     sum(quickbooks_sql2.purchaseorderlinedetail.Quantity)- sum(purchaseorderlinedetail.ReceivedQuantity)  AS totalOnOrder
                                     FROM quickbooks_sql2.purchaseorderlinedetail
  				   INNER JOIN quickbooks_sql2.purchaseorder ON quickbooks_sql2.purchaseorderlinedetail.IDKEY = quickbooks_sql2.purchaseorder.TxnID
  				   WHERE (quickbooks_sql2.purchaseorder.IsFullyReceived = \"false\" and quickbooks_sql2.purchaseorder.IsManuallyClosed = \"false\") and quickbooks_sql2.purchaseorderlinedetail.ItemRef_ListID IS NOT NULL
  				   GROUP BY quickbooks_sql2.purchaseorderlinedetail.ItemRef_ListID
                                     ) as tb3
                                     on InventoryItems.t_QBServiceID = tb3.ItemRef_ListID
                                     WHERE (ISNULL(InventoryItems.nb_Inactive) ||  InventoryItems.nb_Inactive =0)
                                     GROUP BY InventoryItems.kp_InventoryItemID");

        return $query->result_array();


    }
    public function getInvItemLocationVendorInActive()
    {
        $query = $this->db->query("SELECT InventoryItems.kp_InventoryItemID,
                                    Vendors.t_CompanyName,
                                    InventoryItems.t_ItemCategory,
                                    InventoryItems.t_description,
                                    trim(trailing \".\" from(ifnull(cast(trim(trailing \"0\" from Sum(InventoryLocationItems.n_QntyOnHand)) as CHAR),\"\"))) as sumInvtLocaQtyOnHand,
                                    trim(trailing \".\" from (ifnull(cast(trim(TRAILING \"0\" from InventoryItems.n_ReorderPoint) as char),\"\"))) as n_ReorderPoint,
                                    trim(trailing \".\" from (ifnull(cast(trim(TRAILING \"0\" from InventoryItems.n_ReorderQty) as char),\"\"))) as n_ReorderQty,
                                    InventoryItems.n_CasePrice,
                                    InventoryItems.t_InvType,
                                    InventoryItems.t_SupplierType,
                                    InventoryItems.nb_OrderPlaced,tb3.totalOnOrder,
                                    if (tb3.totalOnOrder>0,'showOnOrderTrue','hideOnOrderTrue') as showHideOnOrder,
                                    InventoryItems.nb_Inactive,
                                    InventoryItems.kf_VendorID,
                                    InventoryItems.nb_ComesInCase,
                                    InventoryItems.t_PartNumber,
                                    trim(trailing\".\"from(ifnull(cast(trim(trailing \"0\" from InventoryItems.n_CasePrice) as char),\"\"))) as n_CasePrice,
                                    trim(trailing\".\"from(ifnull(cast(trim(trailing \"0\" from InventoryItems.n_EachPrice) as char),\"\"))) as n_EachPrice,
                                    ifnull(InventoryItems.t_PriceByFtSize,\"\") as t_PriceByFtSize,
                                    ifnull(InventoryItems.n_PriceByFt,\"\") as n_PriceByFt,
                                    tb2.WhenRecentOrderPlaced,
                                    if(t_InvType = \"Stocking\",
                                    if(ifnull(Sum(InventoryLocationItems.n_QntyOnHand),0) <= ifnull(InventoryItems.n_ReorderPoint,0),\"BelowMin\",
						if((ifnull(Sum(InventoryLocationItems.n_QntyOnHand),0)-ifnull(InventoryItems.n_ReorderPoint,0))/ifnull(InventoryItems.n_ReorderQty,0)<=0.20,\"CloseToMin\",\"stockingAboveMin\")),\"noStocking\")as warningDanger
                                   FROM InventoryItems
                                   LEFT JOIN Vendors ON InventoryItems.kf_VendorID = Vendors.kp_VendorID
                                   LEFT JOIN InventoryLocationItems ON InventoryItems.kp_InventoryItemID = InventoryLocationItems.kf_InventoryItemID
                                   left join (
                                   SELECT kp_PurchaseRequestItemID,kf_InventoryItemID,max(PurchaseRequests.d_Created) as WhenRecentOrderPlaced
                                   FROM basetables2.PurchaseRequestItems
                                   inner join PurchaseRequests on PurchaseRequests.kp_PurchaseRequestID = PurchaseRequestItems.kf_PurchaseRequestID
                                   group by kf_InventoryItemID
                                   ) as tb2
                                   on InventoryItems.kp_InventoryItemID = tb2.kf_InventoryItemID
                                   left join(
				   SELECT quickbooks_sql2.purchaseorderlinedetail.ItemRef_ListID,
                                   sum(quickbooks_sql2.purchaseorderlinedetail.Quantity) - sum(purchaseorderlinedetail.ReceivedQuantity)  AS totalOnOrder
                                   FROM quickbooks_sql2.purchaseorderlinedetail
				   INNER JOIN quickbooks_sql2.purchaseorder ON quickbooks_sql2.purchaseorderlinedetail.IDKEY = quickbooks_sql2.purchaseorder.TxnID
				   WHERE (quickbooks_sql2.purchaseorder.IsFullyReceived = \"false\" and quickbooks_sql2.purchaseorder.IsManuallyClosed = \"false\") and quickbooks_sql2.purchaseorderlinedetail.ItemRef_ListID IS NOT NULL
				   GROUP BY quickbooks_sql2.purchaseorderlinedetail.ItemRef_ListID
                                   ) as tb3
                                   on InventoryItems.t_QBServiceID = tb3.ItemRef_ListID
                                   WHERE (InventoryItems.nb_Inactive =1)
                                   GROUP BY InventoryItems.kp_InventoryItemID");

        return $query->result_array();


    }
    public function getInvItemLocationVendorByVendorID($vendorID) {
      $query = $this->db->query("SELECT InventoryItems.kp_InventoryItemID,
                                  Vendors.t_CompanyName,
                                  InventoryItems.t_ItemCategory,
                                  InventoryItems.t_description,
                                  trim(trailing \".\" from(ifnull(cast(trim(trailing \"0\" from Sum(InventoryLocationItems.n_QntyOnHand)) as CHAR),\"\"))) as sumInvtLocaQtyOnHand,
                                  trim(trailing \".\" from (ifnull(cast(trim(TRAILING \"0\" from InventoryItems.n_ReorderPoint) as char),\"\"))) as n_ReorderPoint,
                                  trim(trailing \".\" from (ifnull(cast(trim(TRAILING \"0\" from InventoryItems.n_ReorderQty) as char),\"\"))) as n_ReorderQty,
                                  InventoryItems.n_CasePrice,
                                  InventoryItems.t_InvType,
                                  InventoryItems.t_SupplierType,
                                  InventoryItems.nb_OrderPlaced,tb3.totalOnOrder,
                                  if (tb3.totalOnOrder>0,'showOnOrderTrue','hideOnOrderTrue') as showHideOnOrder,
                                  InventoryItems.nb_Inactive,
                                  InventoryItems.t_QBServiceID,
                                  InventoryItems.t_Notes,
                                  InventoryItems.kf_VendorID,
                                  InventoryItems.nb_ComesInCase,
                                  InventoryItems.t_PartNumber,
                                  trim(trailing\".\"from(ifnull(cast(trim(trailing \"0\" from InventoryItems.n_CasePrice) as char),\"\"))) as n_CasePrice,
                                  trim(trailing\".\"from(ifnull(cast(trim(trailing \"0\" from InventoryItems.n_EachPrice) as char),\"\"))) as n_EachPrice,
                                  ifnull(InventoryItems.t_PriceByFtSize,\"\") as t_PriceByFtSize,
                                  ifnull(InventoryItems.n_PriceByFt,\"\") as n_PriceByFt,
                                  tb2.WhenRecentOrderPlaced as WhenRecentOrderPlaced,
                                  if(t_InvType = \"Stocking\",
                                  if(ifnull(Sum(InventoryLocationItems.n_QntyOnHand),0) <= ifnull(InventoryItems.n_ReorderPoint,0),\"BelowMin\",
          if((ifnull(Sum(InventoryLocationItems.n_QntyOnHand),0)-ifnull(InventoryItems.n_ReorderPoint,0))/ifnull(InventoryItems.n_ReorderQty,0)<=0.20,\"CloseToMin\",\"stockingAboveMin\")),\"noStocking\")as warningDanger
                                 FROM InventoryItems
                                 LEFT JOIN Vendors ON InventoryItems.kf_VendorID = Vendors.kp_VendorID
                                 LEFT JOIN InventoryLocationItems ON InventoryItems.kp_InventoryItemID = InventoryLocationItems.kf_InventoryItemID
                                 left join (
                                 SELECT kp_PurchaseRequestItemID,kf_InventoryItemID,max(PurchaseRequests.d_Created) as WhenRecentOrderPlaced
                                 FROM basetables2.PurchaseRequestItems
                                 inner join PurchaseRequests on PurchaseRequests.kp_PurchaseRequestID = PurchaseRequestItems.kf_PurchaseRequestID
                                 group by kf_InventoryItemID
                                 ) as tb2
                                 on InventoryItems.kp_InventoryItemID = tb2.kf_InventoryItemID
                                 left join(
         SELECT quickbooks_sql2.purchaseorderlinedetail.ItemRef_ListID,
                                 sum(quickbooks_sql2.purchaseorderlinedetail.Quantity)- sum(purchaseorderlinedetail.ReceivedQuantity)  AS totalOnOrder
                                 FROM quickbooks_sql2.purchaseorderlinedetail
         INNER JOIN quickbooks_sql2.purchaseorder ON quickbooks_sql2.purchaseorderlinedetail.IDKEY = quickbooks_sql2.purchaseorder.TxnID
         WHERE (quickbooks_sql2.purchaseorder.IsFullyReceived = \"false\" and quickbooks_sql2.purchaseorder.IsManuallyClosed = \"false\") and quickbooks_sql2.purchaseorderlinedetail.ItemRef_ListID IS NOT NULL
         GROUP BY quickbooks_sql2.purchaseorderlinedetail.ItemRef_ListID
                                 ) as tb3
                                 on InventoryItems.t_QBServiceID = tb3.ItemRef_ListID
                                 WHERE (ISNULL(InventoryItems.nb_Inactive) ||  InventoryItems.nb_Inactive =0) && InventoryItems.kf_VendorID = ".$vendorID."
                                 GROUP BY InventoryItems.kp_InventoryItemID
                                 ");

      return $query->result_array();
    }
    public function insertInvItemData($data=null)
    {
         $this->db->insert('InventoryItems', $data);
         return $this->db->insert_id();

    }
}





?>
