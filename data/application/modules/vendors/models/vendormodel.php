<?php

class VendorModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

    }

    public function getVendorInProcessTblData() {
      $query = $this->db->query("SELECT quickbooks_sql2.vendor.Status,
                                  	quickbooks_sql2.error_table.Error_Desc,
                                  	Vendors.kp_VendorID,
                                  	Vendors.t_CompanyName
                                  FROM quickbooks_sql2.vendor
                                     LEFT JOIN quickbooks_sql2.error_table ON quickbooks_sql2.vendor.ListID = quickbooks_sql2.error_table.IDKEY
                                  	 LEFT JOIN Vendors ON quickbooks_sql2.vendor.ListID = Vendors.kp_VendorID
                                  WHERE quickbooks_sql2.vendor. STATUS = 'ADD'
                                  OR quickbooks_sql2.vendor. STATUS = 'UPDATE'
                                  OR quickbooks_sql2.vendor. STATUS = 'DELETE'");

      return $query->result_array();
    }

    public function getVendorTblData() {
      $query = $this->db->query("SELECT Vendors.kp_VendorID,  Vendors.kf_AddressID,  Vendors.t_CompanyName,
                                        Vendors.t_AccountNumber,  Vendors.ti_POCutOffTime,  Vendors.t_Notes,  Vendors.t_ListID,
                                        Vendors.t_QBServiceIDNonINV,  Vendors.nb_PostedToQuickBooks,   Vendors.nb_Inactive,
                                        Addresses.t_CompanyName AS address_CompanyName,  Addresses.t_ContactNameFull,  Addresses.t_ContactTitle,
                                        Addresses.t_Address1,  Addresses.t_Address2,  Addresses.t_City,  Addresses.t_StateOrProvince,
                                        Addresses.t_PostalCode,  Addresses.t_Country,  Addresses.t_WebAddress,  Addresses.t_Email,
                                        Addresses.t_Mobile,  Addresses.t_Phone,  Addresses.t_Fax,  Vendors.zCreated,
                                        Vendors.zModified
                                FROM Vendors
                                LEFT OUTER JOIN Addresses
                                ON Vendors.kf_AddressID = Addresses.kp_AddressID");

      return $query->result_array();
    }
    public function getVendorByName($vendorName)
    {
        $query = $this->db->query("SELECT Vendors.t_CompanyName FROM Vendors
                                   WHERE Vendors.t_CompanyName =\"$vendorName\"");

        return $query->result_array();

    }
    public function getVendorDataByID($vendorID) {
        $query = $this->db->query("SELECT Vendors.kp_VendorID,  Vendors.kf_AddressID,  Vendors.t_CompanyName,
                                        Vendors.t_AccountNumber,  Vendors.ti_POCutOffTime,  Vendors.t_Notes,  Vendors.t_ListID,
                                        Vendors.t_QBServiceIDNonINV,  Vendors.nb_PostedToQuickBooks,   Vendors.nb_Inactive,
                                        Addresses.t_CompanyName AS address_CompanyName,  Addresses.t_ContactNameFull,  Addresses.t_ContactTitle,
                                        Addresses.t_Address1,  Addresses.t_Address2,  Addresses.t_City,  Addresses.t_StateOrProvince,
                                        Addresses.t_PostalCode,  Addresses.t_Country,  Addresses.t_WebAddress,  Addresses.t_Email,
                                        Addresses.t_Mobile,  Addresses.t_Phone,  Addresses.t_Fax,  Vendors.zCreated,
                                        Vendors.zModified, Vendors.nb_VendorPaysGround, Vendors.nb_UseOurAcctInbound
                                FROM Vendors
                                LEFT OUTER JOIN Addresses ON Vendors.kf_AddressID = Addresses.kp_AddressID
                                WHERE Vendors.kp_VendorID = ".$vendorID."");

        return $query->row_array();
    }
    public function updateVendorDataByID($data, $vendorID) {
      $result = $this->db->update('Vendors', $data, array('kp_VendorID'=>  $vendorID));

      if(!$result) {
        return $this->db->_error_message();
      } else {
        return $this->db->affected_rows();
      }
    }
    public function getAllVendors()
    {
        $query = $this->db->get('Vendors');

        return $query->result_array();

    }
    public function getDistinctVendorCompanyNames()
    {
        $query = $this->db->query("SELECT DISTINCT(Vendors.t_CompanyName), t_ListID, kp_VendorID FROM Vendors
                  WHERE Vendors.nb_Inactive = 0 or ISNULL(Vendors.nb_Inactive)
                  ORDER BY Vendors.t_CompanyName ASC");

        return $query->result_array();
    }

}
?>
