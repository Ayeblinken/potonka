<?php

class purchaserequestmodel extends CI_Model
{
    //put your code here
    public function __construct()
    {
        parent::__construct();
    }

    public function getClosedPurchaseOrdersInProcess() {
        $query = $this->db->query("SELECT basetables2.Vendors.t_CompanyName, basetables2.PurchaseRequests.kp_PurchaseRequestID, DATE_FORMAT(basetables2.PurchaseRequests.d_Created, '%c-%e-%Y') AS d_Created,
                                          basetables2.PurchaseRequests.t_Notes, basetables2.PurchaseRequests.kf_VendorID, quickbooks_sql2.purchaseorder.Status, quickbooks_sql2.error_table.Error_Desc
                                   FROM basetables2.PurchaseRequests
                                   INNER JOIN basetables2.Vendors ON basetables2.PurchaseRequests.kf_VendorID = basetables2.Vendors.kp_VendorID
                                   LEFT OUTER JOIN quickbooks_sql2.purchaseorder ON quickbooks_sql2.purchaseorder.TxnID = basetables2.PurchaseRequests.t_QBTxnID
                                   LEFT JOIN quickbooks_sql2.error_table ON quickbooks_sql2.purchaseorder.TxnID = quickbooks_sql2.error_table.IDKEY
                                   WHERE quickbooks_sql2.purchaseorder.isManuallyClosed = 'true' AND (quickbooks_sql2.purchaseorder.Status = 'ADD' OR quickbooks_sql2.purchaseorder.Status = 'UPDATE' OR quickbooks_sql2.purchaseorder.Status = 'DELETE')");

        return $query->result_array();
    }

    public function updatePOStatus($formData) {
      $this->db->where('TxnID', $formData['TxnID']);
      $this->db->update('quickbooks_sql2.purchaseorder', $formData);
    }

    public function insertRequestItem($data) {
      $this->db->insert('basetables2.PurchaseRequestItems', $data);
      return $this->db->insert_id();
    }

    public function insertLineDetail($data, $kp_PurchaseRequestItemID) {
      $data['TxnLineID'] = $kp_PurchaseRequestItemID;
      $this->db->insert('quickbooks_sql2.purchaseorderlinedetail', $data);
    }

    public function updateRequestItem($data) {
      $this->db->where('kp_PurchaseRequestItemID', $data['kp_PurchaseRequestItemID']);
      $this->db->update('basetables2.PurchaseRequestItems', $data);
    }

    public function updateLineDetail($data) {
      $this->db->where('IDKEY', $data['IDKEY']);
      $this->db->where('ItemRef_ListID', $data['ItemRef_ListID']);
      $this->db->update('quickbooks_sql2.purchaseorderlinedetail', $data);
    }

    public function deleteRequestItem($data) {
      $this->db->where('kp_PurchaseRequestItemID', $data['kp_PurchaseRequestItemID']);
      $this->db->delete('basetables2.PurchaseRequestItems');
    }

    public function deleteLineDetail($data) {
      $this->db->where('IDKEY', $data['IDKEY']);
      $this->db->where('ItemRef_ListID', $data['ItemRef_ListID']);
      $this->db->delete('quickbooks_sql2.purchaseorderlinedetail');
    }


    public function getPurchaseRequestVendorTblData()
    {
        $query = $this->db->query("SELECT basetables2.Vendors.t_CompanyName, basetables2.PurchaseRequests.kp_PurchaseRequestID, DATE_FORMAT(basetables2.PurchaseRequests.d_Created, '%c-%e-%Y') AS d_Created,
                                          basetables2.PurchaseRequests.t_Notes, basetables2.PurchaseRequests.kf_VendorID, quickbooks_sql2.purchaseorder.Status, quickbooks_sql2.error_table.Error_Desc
                                   FROM basetables2.PurchaseRequests
                                   INNER JOIN basetables2.Vendors ON basetables2.PurchaseRequests.kf_VendorID = basetables2.Vendors.kp_VendorID
                                   LEFT OUTER JOIN quickbooks_sql2.purchaseorder ON quickbooks_sql2.purchaseorder.TxnID = basetables2.PurchaseRequests.t_QBTxnID
                                   LEFT JOIN quickbooks_sql2.error_table ON quickbooks_sql2.purchaseorder.TxnID = quickbooks_sql2.error_table.IDKEY
                                   WHERE quickbooks_sql2.purchaseorder.Status = 'ADD' OR quickbooks_sql2.purchaseorder.Status = 'UPDATE' OR quickbooks_sql2.purchaseorder.Status = 'DELETE'");

        return $query->result_array();

    }
    public function getPurchaseRequestVendorTblDataByVendor($vendorID)
    {
        $query = $this->db->query("SELECT basetables2.Vendors.t_CompanyName, basetables2.PurchaseRequests.kp_PurchaseRequestID,
                                          DATE_FORMAT(basetables2.PurchaseRequests.d_Created, '%c-%e-%Y') AS d_Created, basetables2.PurchaseRequests.t_Notes, basetables2.PurchaseRequests.kf_VendorID, basetables2.PurchaseRequests.t_QBPurchaseOrderNum
                                   FROM basetables2.PurchaseRequests
                                   INNER JOIN basetables2.Vendors ON basetables2.PurchaseRequests.kf_VendorID = basetables2.Vendors.kp_VendorID
                                   WHERE basetables2.PurchaseRequests.kf_VendorID = \"$vendorID\"");

        return $query->result_array();

    }
    public function getPurchaseRequestVendorTblDataByDate($dateCreated)
    {
        $query = $this->db->query("SELECT basetables2.Vendors.t_CompanyName, basetables2.PurchaseRequests.kp_PurchaseRequestID,
                                          DATE_FORMAT(basetables2.PurchaseRequests.d_Created, '%c-%e-%Y') AS d_Created, basetables2.PurchaseRequests.t_Notes, basetables2.PurchaseRequests.kf_VendorID, basetables2.PurchaseRequests.t_QBPurchaseOrderNum
                                   FROM basetables2.PurchaseRequests
                                   INNER JOIN basetables2.Vendors ON basetables2.PurchaseRequests.kf_VendorID = basetables2.Vendors.kp_VendorID
                                   WHERE basetables2.PurchaseRequests.d_Created = \"$dateCreated\"");

        return $query->result_array();

    }

}
