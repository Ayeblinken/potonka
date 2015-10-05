<?php
/**
 * Description of shippersmodel
 *
 * @author sraparla
 */
class shipperModel extends CI_Model
{
    //put your code here
    public function __construct()
    {
        parent::__construct();
    }

    public function getShipperServices() {
      $query = $this->db->query("SELECT ShipperService.t_InitialsForOrderList,
        ShipperService.t_PickUp
        FROM ShipperService");

        return $query->result_array();
    }

    public function getShipperCompanyInfo()
    {
        $query = $this->db->query("SELECT t_Company,kp_ShipperID FROM basetables2.Shippers
                                   WHERE nb_Inactive is null and kp_ShipperID is not null");

        return $query->result_array();

    }

    public function getShipperByName($shipperName)
    {
        $query = $this->db->query("SELECT t_Company FROM basetables2.Shippers
                                   WHERE t_Company =\"$shipperName\"");

        return $query->result_array();

    }

    public function getShippingTblData() {
      $query = $this->db->query("SELECT Shippers.kp_ShipperID, Shippers.kf_AddressID, Shippers.t_Company, Shippers.nb_Inactive, Addresses.t_ContactNameFull,  Addresses.t_ContactTitle,
      Addresses.t_Address1,  Addresses.t_Address2,  Addresses.t_City,  Addresses.t_StateOrProvince,
      Addresses.t_PostalCode,  Addresses.t_Country,  Addresses.t_WebAddress,  Addresses.t_Email,
      Addresses.t_Mobile,  Addresses.t_Phone,  Addresses.t_Fax,Shippers.t_Notes
                                FROM Shippers
                                LEFT OUTER JOIN Addresses
                                ON Shippers.kf_AddressID = Addresses.kp_AddressID");

      return $query->result_array();
    }
    public function getActiveShippingTblData() {
      $query = $this->db->query("SELECT Shippers.kp_ShipperID, Shippers.t_Company
                                FROM Shippers
                                WHERE Shippers.nb_Inactive = 0 or Shippers.nb_Inactive is null");

      return $query->result_array();
    }
    public function getShippingDataByID($shipperID) {
        $query = $this->db->query("SELECT Shippers.kp_ShipperID, Shippers.kf_AddressID, Shippers.t_Company, Shippers.nb_Inactive, Addresses.t_ContactNameFull,  Addresses.t_ContactTitle,
        Addresses.t_Address1,  Addresses.t_Address2,  Addresses.t_City,  Addresses.t_StateOrProvince,
        Addresses.t_PostalCode,  Addresses.t_Country,  Addresses.t_WebAddress,  Addresses.t_Email,
        Addresses.t_Mobile,  Addresses.t_Phone,  Addresses.t_Fax,Shippers.t_Notes
                                FROM Shippers
                                LEFT OUTER JOIN Addresses ON Shippers.kf_AddressID = Addresses.kp_AddressID
                                WHERE Shippers.kp_ShipperID = ".$shipperID."");

        return $query->row_array();
    }

}

?>
