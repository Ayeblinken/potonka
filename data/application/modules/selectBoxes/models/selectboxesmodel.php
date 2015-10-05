<?php

class SelectBoxesModel extends CI_Model {
  public function __construct() {
    parent::__construct();
  }

  public function getEmployeeDirectory() {
    $query = $this->db->query("SELECT kp_EmployeeID, t_UserName, t_EmployeeEmail, n_Extension
                                FROM Employees
                                WHERE (nb_Inactive = 0 OR ISNULL(nb_Inactive)) AND ((t_EmployeeEmail IS NOT NULL AND t_EmployeeEmail LIKE '%@indyimaging.com%') OR n_Extension IS NOT NULL)
                                ORDER BY t_UserName");

    return $query->result_array();
  }

  public function getVendorsByListID($listID) {
    $query = $this->db->query("SELECT Vendors.kp_VendorID
              FROM Vendors
              WHERE Vendors.t_ListID = '$listID'");

    return $query->result_array();
  }

  public function getPurchaseRequestItemPurchaseRequestFromInvtItemID($inventoryItemID) {
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

  public function getDistinctVendorCompanyNames() {
      $query = $this->db->query("SELECT DISTINCT(Vendors.t_CompanyName), t_ListID, kp_VendorID FROM Vendors
                WHERE Vendors.nb_Inactive = 0 or ISNULL(Vendors.nb_Inactive)
                ORDER BY Vendors.t_CompanyName ASC");

      return $query->result_array();
  }

  public function getCustomerNotes($customerID) {
    $query = $this->db->query("SELECT Customers.t_Notes
                                FROM basetables2.Customers
                                WHERE kp_CustomerID = " . $customerID);

    return $query->row_array();
  }

  public function getCustomerCreditHoldInfo($customerID) {
    $query = $this->db->query("SELECT Customers.t_CustCompany,Customers.t_CreditHoldReason
                                FROM Customers
                                WHERE Customers.kp_CustomerID = " . $customerID);

    return $query->row_array();
  }

  public function setCustomerNotes($customerID,$data)
  {
      $result = $this->db->update('Customers', $data, array('kp_CustomerID'=>  $customerID));
      if(!$result)
      {
          return $this->db->error_message();
      }
      else
      {
          return $this->db->affected_rows();

      }

  }

  public function getActiveMasterJobsByCustomer($customerID) {
    $query = $this->db->query("SELECT MasterJobs.kp_MasterJobID,MasterJobs.t_Name
                                FROM MasterJobs
                                WHERE MasterJobs.kf_CustomerID = " . $customerID . " and (isnull(MasterJobs.nb_Inactive) or (MasterJobs.nb_Inactive='0')) and (isnull(MasterJobs.nb_Completed) or (MasterJobs.nb_Completed='0'))
                                ORDER BY MasterJobs.t_Name ASC");

    return $query->result_array();
  }

  public function getBuildItemDirections($buildItemID) {
    $query = $this->db->query("SELECT ManCatSubCatDirections.t_Directions,ManCatSubCatDirections.n_Sort
                                FROM BuildItems INNER JOIN ManCatSubCatDirections ON BuildItems.kf_ManCatSubCatID = ManCatSubCatDirections.kf_ManCatSubCatID
                                WHERE BuildItems.kp_BuildItemID = " . $buildItemID . "
                                ORDER BY ManCatSubCatDirections.n_Sort ASC");

    return $query->result_array();
  }

  public function getEquipmentSubModes($equipmentModeID) {
    $query = $this->db->query("SELECT kp_EquipmentSubModeID, t_Name
                                FROM EquipmentSubModes
                                WHERE kf_EquipmentModeID = " . $equipmentModeID . "  and (nb_Inactive is null OR nb_Inactive = '0')
                                ORDER BY t_Name");

    return $query->result_array();
  }

  public function getInventoryItemList($buildItemID) {
      $where = "InventoryItemsToBuildItemsLink.kf_BuildItemID =".$buildItemID." and
                (isnull(InventoryItems.nb_Inactive) or (InventoryItems.nb_Inactive=\"0\"))";
      $this->db->select(' InventoryItemsToBuildItemsLink.kf_InventoryItemID,
                          InventoryItems.t_description,
                          InventoryItemsToBuildItemsLink.nb_UseAsDefaultItemForChosenMaterial,
                          InventoryItems.t_SupplierType, ,')
               ->select_sum('InventoryLocationItems.n_QntyOnHand','OH')
               ->from('InventoryItemsToBuildItemsLink')
               ->join('InventoryItems', 'InventoryItemsToBuildItemsLink.kf_InventoryItemID = InventoryItems.kp_InventoryItemID','LEFT')
               ->join('InventoryLocationItems', 'InventoryItems.kp_InventoryItemID = InventoryLocationItems.kf_InventoryItemID','LEFT')
               ->where($where)
               ->group_by('InventoryItemsToBuildItemsLink.kf_InventoryItemID')
               ->order_by("InventoryItems.t_SupplierType", "desc");

      $query  = $this->db->get();

      return $query->result_array();

  }

  public function getRelatedBuilditems($productBuildID) {
    $query = $this->db->query("SELECT IF(ISNULL(biManCats.t_Category), ManCats.t_Category, biManCats.t_Category) AS t_DisplayCategory,
                              	IF(ISNULL(biManCats.t_Category), CONCAT(Equipment.t_EquipmentName,' - ', EquipmentModes.t_Name), BuildItems.t_Name) AS t_DisplayName,
                              	ProductBuildItems.kp_ProductBuildItemID as kf_ProductBuildItemID,ProductBuildItems.kf_BuildItemID as kf_BuildItemID,ProductBuildItems.kf_EquipmentID as kf_EquipmentID,
                              	ProductBuildItems.kf_EquipmentModeID as kf_EquipmentModeID,IF(ISNULL(biManCats.t_Category) ,ManCats.n_Sort,biManCats.n_Sort) AS n_DisplaySort,
                              	IF(ISNULL(biManCats.t_FormView) ,ManCats.t_FormView,biManCats.t_FormView) AS t_FormView,BuildItems.nb_NotConnectedToInventory,ProductBuildItems.nb_ShowOnInvoice
                              FROM ProductBuildItems
                              	LEFT JOIN BuildItems ON ProductBuildItems.kf_BuildItemID = BuildItems.kp_BuildItemID
                              	LEFT JOIN ManCats biManCats ON BuildItems.kf_ManCatID = biManCats.kp_ManCatID
                              	LEFT JOIN Equipment ON ProductBuildItems.kf_EquipmentID = Equipment.kp_EquipmentID
                              	LEFT JOIN ManCats ON Equipment.kf_ManCatID = ManCats.kp_ManCatID
                              	LEFT JOIN EquipmentModes ON ProductBuildItems.kf_EquipmentModeID = EquipmentModes.kp_EquipmentModeID
                              WHERE ProductBuildItems.kf_ProductBuildID = ". $productBuildID . "
                              	and (isnull(BuildItems.nb_Inactive) or (BuildItems.nb_Inactive='0'))
                              	and (isnull(Equipment.nb_Inactive) or (Equipment.nb_Inactive='0'))
                              	and (isnull(EquipmentModes.nb_Inactive) or (EquipmentModes.nb_Inactive='0'))
                              ORDER BY n_DisplaySort, t_DisplayName ASC");

    return $query->result_array();
  }

  public function getPrintMaterialBuilditemsByProduct($productID) {
    $query = $this->db->query("SELECT BuildItems.t_Name,
                                BuildItems.kp_BuildItemID,
                                ManCats.n_Sort,
                                ProductBuilds.t_Category
                              FROM BuildItems
                              	LEFT OUTER JOIN ManCats ON BuildItems.kf_ManCatID = ManCats.kp_ManCatID
                                LEFT OUTER JOIN ProductBuildItems ON ProductBuildItems.kf_BuildItemID = BuildItems.kp_BuildItemID
                                LEFT OUTER JOIN ProductBuilds ON ProductBuilds.kp_ProductBuildID = ProductBuildItems.kf_ProductBuildID
                              WHERE ManCats.t_Category = 'Print Material' and (isnull(BuildItems.nb_Inactive) or (BuildItems.nb_Inactive='0')) and ProductBuilds.kp_ProductBuildID = $productID
                              GROUP BY t_Name, t_Category
                              ORDER BY t_Category, n_Sort, t_Name ASC");

    return $query->result_array();
  }

  public function getPrintMaterialInvItems($buildItemID) {
    $query = $this->db->query("SELECT InventoryItems.t_description, InventoryItems.n_AttribHeight, InventoryItems.n_AttribWidth, InventoryItems.t_AttribSizeType,
                                      InventoryItems.t_AttribWidthUOM, InventoryItems.t_AttribHeightUOM, InventoryItems.t_SupplierType, SUM(InventoryLocationItems.n_QntyOnHand) AS OH
                              FROM InventoryItemsToBuildItemsLink
                              LEFT JOIN InventoryItems ON InventoryItemsToBuildItemsLink.kf_InventoryItemID = InventoryItems.kp_InventoryItemID
                              LEFT JOIN InventoryLocationItems ON InventoryItems.kp_InventoryItemID = InventoryLocationItems.kf_InventoryItemID
                              WHERE InventoryItemsToBuildItemsLink.kf_BuildItemID = $buildItemID
                              GROUP BY InventoryItemsToBuildItemsLink.kf_InventoryItemID");

    return $query->result_array();
  }

  public function getProductBuildsListByCategory($category) {
    $query = $this->db->query("SELECT ProductBuilds.kp_ProductBuildID, ProductBuilds.t_Category, ProductBuilds.t_Name
                                FROM ProductBuilds
                                WHERE ProductBuilds.t_Category = '" . $category . "'
                                ORDER BY ProductBuilds.t_Name ASC");

    return $query->result_array();
  }

  public function getProductBuildsCategoryList() {
    $query = $this->db->query("SELECT ProductBuilds.t_Category
                                FROM ProductBuilds
                                WHERE ISNULL(ProductBuilds.nb_Inactive) or ProductBuilds.nb_Inactive = 0
                                GROUP BY ProductBuilds.t_Category");

    return $query->result_array();
  }

  public function getBuildItemsList() {
    $query = $this->db->query("SELECT BuildItems.kp_BuildItemID, BuildItems.t_Name, CONCAT(ManCats.t_Category,' - ',ManCatSubCats.t_SubCategory) as t_Category
                                FROM BuildItems LEFT OUTER JOIN ManCats ON BuildItems.kf_ManCatID = ManCats.kp_ManCatID
                            	  LEFT OUTER JOIN ManCatSubCats ON ManCats.kp_ManCatID = ManCatSubCats.kf_ManCatID
                                WHERE ISNULL(BuildItems.nb_Inactive) or BuildItems.nb_Inactive = 0
                                GROUP BY BuildItems.kp_BuildItemID
                                ORDER BY CONCAT(ManCats.t_Category,'-',ManCatSubCats.t_SubCategory) ASC, BuildItems.t_Name ASC");

    return $query->result_array();
  }

  public function getProductBuildsList() {
    $query = $this->db->query("SELECT ProductBuilds.kp_ProductBuildID, ProductBuilds.t_Category, ProductBuilds.t_Name, CONCAT(ProductBuilds.t_Category,' - ',ProductBuilds.t_Name) as t_DisplayName
                                FROM ProductBuilds
                                WHERE ISNULL(ProductBuilds.nb_Inactive) or ProductBuilds.nb_Inactive = 0
                                ORDER BY ProductBuilds.t_Category ASC, ProductBuilds.t_Name ASC");

    return $query->result_array();
  }

  public function getEmployeeAdminManagerAccounting() {
    $query = $this->db->query("SELECT Employees.kp_EmployeeID, Employees.t_UserName, Employees.nb_OverrideCreditHold, Employees.nb_Inactive
                                FROM basetables2.Employees
                                WHERE (Employees.nb_Inactive IS NULL OR Employees.nb_Inactive = 0) AND (Employees.t_Department = 'Accounting' || Employees.t_PrivilegeSet = 'Admin' || Employees.t_PrivilegeSet = 'Manager')
                                ORDER BY t_UserName ASC");

    return $query->result_array();
  }

  public function getEmployeeSupervisorManagerAccounting() {
    $query = $this->db->query("SELECT Employees.kp_EmployeeID, Employees.t_UserName, Employees.nb_OverrideCreditHold, Employees.nb_Inactive, Employees.t_Department, Employees.t_PrivilegeSet
                                FROM basetables2.Employees
                                WHERE (Employees.nb_Inactive IS NULL OR Employees.nb_Inactive = 0) AND (Employees.t_PrivilegeSet = 'Supervisor' || Employees.t_PrivilegeSet = 'Manager' || Employees.t_Department = 'Accounting')
                                ORDER BY t_UserName ASC");

    return $query->result_array();
  }

  public function getCreditHoldEmployeeList() {
    $query = $this->db->query("SELECT Employees.kp_EmployeeID, Employees.t_UserName, Employees.t_Department, Employees.nb_OverrideCreditHold, Employees.nb_Inactive
                                FROM basetables2.Employees
                                WHERE (Employees.nb_Inactive IS NULL OR Employees.nb_Inactive = '0') AND Employees.nb_OverrideCreditHold = '1'
                                ORDER BY t_UserName ASC");

    return $query->result_array();
  }

  public function getEmployeeList() {
    $query = $this->db->query("SELECT Employees.kp_EmployeeID, Employees.t_UserName, Employees.t_Department, Employees.nb_Inactive, Employees.t_EmployeeEmail
                                FROM basetables2.Employees
                                ORDER BY t_UserName ASC");

    return $query->result_array();
  }

  public function getEmployeeListCustomerApproval() {
    $query = $this->db->query("SELECT Employees.kp_EmployeeID, Employees.t_UserName, Employees.t_Department, Employees.nb_Inactive, Employees.nb_NewCustomerSalesApproval,
                                      Employees.nb_NewCustomerAccountingApproval, Employees.nb_CustomerApproveTerms, Employees.t_QBSalesRepListID
                                FROM basetables2.Employees
                                ORDER BY t_UserName ASC");

    return $query->result_array();
  }

  public function getEmployeeSalesList() {
    $query = $this->db->query("SELECT Employees.kp_EmployeeID, Employees.t_UserName, Employees.t_Department, Employees.t_QBSalesRepListID
                                FROM basetables2.Employees
                                WHERE Employees.t_Department = 'Sales'
                                ORDER BY t_UserName ASC");

    return $query->result_array();
  }

  public function getEmployeePrepressList() {
    $query = $this->db->query("SELECT Employees.kp_EmployeeID, Employees.t_UserName, Employees.t_Department
                                FROM basetables2.Employees
                                WHERE Employees.t_Department = 'Prepress'
                                ORDER BY t_UserName ASC");

    return $query->result_array();
  }

  public function getLimitedEmployeeDataByName($name) {
    $query = $this->db->query("SELECT Employees.kp_EmployeeID, Employees.t_EmployeeEmail, Employees.t_QBEmployeeListID
                                FROM basetables2.Employees
                                WHERE t_UserName = '" . $name."'");

    return $query->row_array();
  }

  public function getLimitedEmployeeDataByID($id) {
    $query = $this->db->query("SELECT Employees.kp_EmployeeID, Employees.t_EmployeeEmail, Employees.t_QBEmployeeListID, Employees.t_UserName
                                FROM basetables2.Employees
                                WHERE kp_EmployeeID = " . $id);

    return $query->row_array();
  }

  public function getOrderContactsFromCustomerID($customerID) {
    $query = $this->db->query('SELECT Addresses.kp_AddressID,Addresses.t_ContactNameFull,Addresses.t_Phone,Addresses.t_Mobile,Addresses.t_Email
      FROM Addresses
      WHERE (ISNULL(nb_Inactive) or nb_Inactive = 0) and kf_TypeMain = "Customer" and kf_TypeSub = "Contact" and kf_OtherID = ' . $customerID .'
      ORDER BY Addresses.t_ContactNameFull ASC');

      return $query->result_array();
  }

  public function getActiveCustomerNames() {
    $query = $this->db->query("SELECT Customers.kp_CustomerID, Customers.t_CustCompany FROM Customers
      WHERE Customers.nb_Inactive is null or Customers.nb_Inactive = 0
      ORDER BY Customers.t_CustCompany");

    return $query->result_array();

  }

  public function getActiveCustomerNamesWithSales() {
    $query = $this->db->query("SELECT Customers.kp_CustomerID, Customers.t_CustCompany, Customers.kf_EmployeeID_Sales, Customers.t_CustJobFolderName, Customers.t_NewCustomerStatus, Customers.t_NewCustomerStatus FROM Customers
      WHERE Customers.nb_Inactive is null or Customers.nb_Inactive = 0
      ORDER BY Customers.t_CustCompany");

    return $query->result_array();

  }

  public function getActiveCustomerNamesWithOrderCount() {
    $query = $this->db->query("SELECT Customers.kp_CustomerID, Customers.t_CustCompany, Count(Orders.kp_OrderID) AS OrderCount
                                FROM Customers
                                INNER JOIN Orders ON Orders.kf_CustomerID = Customers.kp_CustomerID
                                WHERE Customers.nb_Inactive is null or Customers.nb_Inactive = 0
                                GROUP BY Customers.t_CustCompany
                                ORDER BY Customers.t_CustCompany");

    return $query->result_array();

  }

  public function getShipperServices() {
    $query = $this->db->query("SELECT ShipperService.t_InitialsForOrderList,
      ShipperService.t_PickUp
      FROM ShipperService");

    return $query->result_array();
  }

  public function getMachineList() {
    $query = $this->db->query("SELECT Equipment.t_EquipAbr,
      Equipment.t_EquipmentName
      FROM Equipment");

    return $query->result_array();
  }

  public function getStatusList() {
    $query = $this->db->query("SELECT Statuses.t_StatusName
                              FROM Statuses");

    return $query->result_array();
  }

}

?>
