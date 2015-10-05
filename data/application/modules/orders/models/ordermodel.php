<?php

class OrderModel extends CI_Model
{
    var $QRCodeImagePath;

    public function __construct()
    {
        parent::__construct();

        //Server Shipping path
        $this->QRCodeImagePath   = realpath(APPPATH . '../../../images/Orders');

        $this->webRootPath       = realpath(APPPATH . '../..');

        $this->loadWebRootPath   = 'https://'.$_SERVER['SERVER_NAME'];

        $this->load->library('ciqrcode');
        $this->load->library('image_lib');

    }

    public function uploadInspectionImage($orderID, $shortID) {
      if(isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        // checks and creates short Order ID Folder
        if(!is_dir(APPPATH . '../../../inspection_pics/'.$shortID)) {

          if(!mkdir(APPPATH . '../../../inspection_pics/'.$shortID,0777,TRUE)) {
            die("Failed to create Employee Folder");
          } else {
            chmod(APPPATH . '../../../inspection_pics/'.$shortID, 0777);
          }

        }
        $path = APPPATH . '../../../inspection_pics/'.$shortID;

        //CHECK and create Order ID Folder
        if(!is_dir($path.'/'.$orderID)) {

          if(!mkdir($path.'/'.$orderID,0777,TRUE)) {
            die("Failed to create Employee ID Folder");
          } else {
            chmod($path.'/'.$orderID, 0777);
          }

        }

        $fullPath = APPPATH . '../../../inspection_pics/'.$shortID.'/'.$orderID;



        $tempPath   = $_FILES[ 'file' ][ 'tmp_name' ];

        $fileName   = str_replace(" ", "_", $_FILES['file']['name']);


        $path_parts = pathinfo($fileName);
        $fileNameNoExt = $path_parts['filename'];
        $extension = ".".$path_parts['extension'];

        if(file_exists($fullPath.'/'.$fileName)) {
          $looking = true;
          $n = 1;
          $s = "_01".$extension;
          while($looking && $n < 100) {
            if(!file_exists($fullPath.'/'.$fileNameNoExt.$s)) {
              $looking = false;
              $fileName = $fileNameNoExt.$s;
            } else {
              $n++;
              if($n < 10) {
                $s = "_0".$n.$extension;
              } else {
                $s = "_".$n.$extension;
              }
            }
          }

        }




        $uploadPath = $fullPath.DIRECTORY_SEPARATOR.$fileName;


        if(move_uploaded_file($tempPath, $uploadPath)) {

          chmod($uploadPath, 0777);


          //[ THUMB IMAGE ]
          $img_config_0['image_library']   = 'gd2';
          $img_config_0['source_image']    = $uploadPath;
          $img_config_0['maintain_ratio']  = TRUE;
          $img_config_0['width']           = 150;
          $img_config_0['height']          = 150;
          $img_config_0['create_thumb']    = TRUE;
          $this->image_lib->initialize($img_config_0);

          $this->image_lib->resize();



          $returnData = array(
            'path' => $uploadPath
          );

          return $returnData;

        } else {
          $msg = "upload failed";
          return $msg;
        }
      }
    }

    public function getOrderInspectionImageContent($data) {
        $allowed                    = array('jpeg','jpg');

        $fullPath                   = APPPATH . '../../../inspection_pics/'.$data['shortOrderNum'].'/'.$data['orderID'];

        $image = array();
        if(file_exists($fullPath)) {
            $files = scandir( $fullPath);

            $filesArry = array_diff($files,array('.','..','.DS_Store','.AppleDouble'));

            foreach($filesArry as $file) {
                $extension           = pathinfo($file, PATHINFO_EXTENSION);

                if(in_array(strtolower($extension), $allowed) && strrpos($file, '_thumb')) {
                    $imagePath           = '../inspection_pics/'.$data['shortOrderNum'].'/'.$data['orderID'].'/'.$file;
                    $imageHref           = '../inspection_pics/'.$data['shortOrderNum'].'/'.$data['orderID'].'/'.$file;

                    $actualImageName     = $file;

                    $image[] = array(
                       'imageUrl' => $imagePath,
                       'imagehref' => $imageHref,
                       'imageName' => $actualImageName,
                       'shortOrderNum'=> $data['shortOrderNum']);
                }

            }

        }
        if(!is_null($image)) {
            return $image;
        } else {
            return array(array('error' => 'No Pictures Found'));
        }

    }

    public function getOrderShipToProductionGroupConversion() {
      $query = $this->db->query("SELECT ShipperService.t_InitialsForOrderList,
                                    ShipperService.t_ProductionGroup,
                                    ShipperService.n_ProductionGroupSort
                                FROM ShipperService
                                WHERE ShipperService.nb_Inactive IS NULL
                                GROUP BY ShipperService.t_InitialsForOrderList
                                ORDER BY ShipperService.n_ProductionGroupSort ASC");

      return $query->result_array();
    }

    public function getReadyToFinishSign($date) {
      $query = $this->db->query("SELECT Orders.kp_OrderID,
        GROUP_CONCAT(DISTINCT OrderItems.t_OIStatus SEPARATOR ',') as OIDistinctStatuses,
        Orders.t_ServiceLevel,
        Customers.t_CustCompany,
        Addresses.t_ContactNameFull,
        Orders.t_JobName,
        Orders.t_JobStatus,
        Orders.d_JobDue,
        TIME_FORMAT(Orders.ti_JobDue, '%l:%i %p') AS ti_JobDue,
        Orders.d_PrintJobDue,
        Orders.ti_PrintJobDue,
        Orders.d_ProofDue,
        Orders.ti_ProofDue,
        SUBSTRING_INDEX(Orders.n_OrderItemCount, '.', 1) AS n_OrderItemCount,
        SUBSTRING_INDEX(Orders.n_OICSqFtSum, '.', 1) AS n_OICSqFtSum,
        Orders.nb_JobFinished,
        Orders.n_DurationTime,
        Orders.t_OrderItemAb,
        Orders.t_MachineAb,
        Orders.t_OrdShip,
        Orders.n_Complexity,
        Orders.nb_SureDate,
        Statuses.n_SortOrder,
        Orders.n_TotalGrandTotal,
        EmployeesSales.t_UserName AS t_Sales,
        Orders.t_PersonWritingOrder,
        Orders.t_TypeOfOrder,
        Orders.kf_EmployeeIDSales,
        Orders.nb_PressProof,
        Orders.nb_PressProofApproved
        FROM Orders LEFT OUTER JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
        LEFT OUTER JOIN Statuses ON Orders.t_JobStatus = Statuses.t_StatusName
        LEFT OUTER JOIN Addresses ON Orders.kf_ContactID = Addresses.kp_AddressID
        LEFT OUTER JOIN Employees EmployeesSales ON Orders.kf_EmployeeIDSales = EmployeesSales.kp_EmployeeID
        JOIN OrderItems ON Orders.kp_OrderID = OrderItems.kf_OrderID
        WHERE Orders.t_TypeOfJobTicket = 'JobWithProductBuilds' and ISNULL(Orders.nb_JobFinished) and (Orders.t_JobStatus = 'Ready to Finish' or Orders.t_JobStatus = 'Multi Line Status')
        and Orders.d_JobDue = '$date'
        GROUP BY Orders.kp_OrderID
        ORDER BY Orders.kp_OrderID DESC");


      return $query->result_array();
    }

    public function getReadyToCutSign($date) {
      $query = $this->db->query("SELECT Orders.kp_OrderID,
        GROUP_CONCAT(DISTINCT OrderItems.t_OIStatus SEPARATOR ',') as OIDistinctStatuses,
        Orders.t_ServiceLevel,
        Customers.t_CustCompany,
        Addresses.t_ContactNameFull,
        Orders.t_JobName,
        Orders.t_JobStatus,
        Orders.d_JobDue,
        TIME_FORMAT(Orders.ti_JobDue, '%l:%i %p') AS ti_JobDue,
        Orders.d_PrintJobDue,
        Orders.ti_PrintJobDue,
        Orders.d_ProofDue,
        Orders.ti_ProofDue,
        SUBSTRING_INDEX(Orders.n_OrderItemCount, '.', 1) AS n_OrderItemCount,
        SUBSTRING_INDEX(Orders.n_OICSqFtSum, '.', 1) AS n_OICSqFtSum,
        Orders.nb_JobFinished,
        Orders.n_DurationTime,
        Orders.t_OrderItemAb,
        Orders.t_MachineAb,
        Orders.t_OrdShip,
        Orders.n_Complexity,
        Orders.nb_SureDate,
        Statuses.n_SortOrder,
        Orders.n_TotalGrandTotal,
        EmployeesSales.t_UserName AS t_Sales,
        Orders.t_PersonWritingOrder,
        Orders.t_TypeOfOrder,
        Orders.kf_EmployeeIDSales,
        Orders.nb_PressProof,
        Orders.nb_PressProofApproved
        FROM Orders LEFT OUTER JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
        LEFT OUTER JOIN Statuses ON Orders.t_JobStatus = Statuses.t_StatusName
        LEFT OUTER JOIN Addresses ON Orders.kf_ContactID = Addresses.kp_AddressID
        LEFT OUTER JOIN Employees EmployeesSales ON Orders.kf_EmployeeIDSales = EmployeesSales.kp_EmployeeID
        JOIN OrderItems ON Orders.kp_OrderID = OrderItems.kf_OrderID
        WHERE Orders.t_TypeOfJobTicket = 'JobWithProductBuilds' and ISNULL(Orders.nb_JobFinished) and (Orders.t_JobStatus = 'Ready to Cut' or Orders.t_JobStatus = 'Multi Line Status')
        and Orders.d_JobDue = '$date'
        GROUP BY Orders.kp_OrderID
        ORDER BY Orders.kp_OrderID DESC");


      return $query->result_array();
    }

    public function getReadyToLamSign($date) {
      $query = $this->db->query("SELECT Orders.kp_OrderID,
        GROUP_CONCAT(DISTINCT OrderItems.t_OIStatus SEPARATOR ',') as OIDistinctStatuses,
        Orders.t_ServiceLevel,
        Customers.t_CustCompany,
        Addresses.t_ContactNameFull,
        Orders.t_JobName,
        Orders.t_JobStatus,
        Orders.d_JobDue,
        TIME_FORMAT(Orders.ti_JobDue, '%l:%i %p') AS ti_JobDue,
        Orders.d_PrintJobDue,
        Orders.ti_PrintJobDue,
        Orders.d_ProofDue,
        Orders.ti_ProofDue,
        SUBSTRING_INDEX(Orders.n_OrderItemCount, '.', 1) AS n_OrderItemCount,
        SUBSTRING_INDEX(Orders.n_OICSqFtSum, '.', 1) AS n_OICSqFtSum,
        Orders.nb_JobFinished,
        Orders.n_DurationTime,
        Orders.t_OrderItemAb,
        Orders.t_MachineAb,
        Orders.t_OrdShip,
        Orders.n_Complexity,
        Orders.nb_SureDate,
        Statuses.n_SortOrder,
        Orders.n_TotalGrandTotal,
        EmployeesSales.t_UserName AS t_Sales,
        Orders.t_PersonWritingOrder,
        Orders.t_TypeOfOrder,
        Orders.kf_EmployeeIDSales,
        Orders.nb_PressProof,
        Orders.nb_PressProofApproved
        FROM Orders LEFT OUTER JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
        LEFT OUTER JOIN Statuses ON Orders.t_JobStatus = Statuses.t_StatusName
        LEFT OUTER JOIN Addresses ON Orders.kf_ContactID = Addresses.kp_AddressID
        LEFT OUTER JOIN Employees EmployeesSales ON Orders.kf_EmployeeIDSales = EmployeesSales.kp_EmployeeID
        JOIN OrderItems ON Orders.kp_OrderID = OrderItems.kf_OrderID
        WHERE Orders.t_TypeOfJobTicket = 'JobWithProductBuilds' and ISNULL(Orders.nb_JobFinished) and (Orders.t_JobStatus = 'Ready to Lam' or Orders.t_JobStatus = 'Multi Line Status')
        and Orders.d_JobDue = '$date'
        GROUP BY Orders.kp_OrderID
        ORDER BY Orders.kp_OrderID DESC");


      return $query->result_array();
    }

    public function getOrderTotals($id) {
      $query = $this->db->query("SELECT
                                  IFNULL(oc.n_TotalOtherCharges, 0) AS n_TotalOtherCharges,
                                  IFNULL(oi.n_TotalOrderItemPrice, 0) AS n_TotalOrderItemPrice,
                                  IFNULL(os.n_TotalShippingCharges, 0) AS n_TotalShippingCharges,
                                  Addresses.t_StateOrProvince,
                                  Customers.t_CustReseller,
                                  CompanyLocations.n_SalesTaxRate,
                                  Orders.n_TotalOrderPrice,
                                  Orders.nb_UseTotalOrderPricing
                                  FROM Orders
                                  LEFT JOIN (SELECT kf_OrderID, sum(OtherCharges.n_Quantity * OtherCharges.n_Price) as n_TotalOtherCharges FROM OtherCharges WHERE OtherCharges.kf_OrderID = '$id' GROUP BY kf_OrderID) oc ON oc.kf_OrderID = Orders.kp_OrderID
                                  LEFT JOIN (
                                  SELECT
                                  	kf_OrderID,
                                  	sum(if(Orders.t_TypeOfOrder ='Redo' || Orders.t_TypeOfOrder='Sample' || Orders.nb_UseTotalOrderPricing = 1 ,0,
                                  	CASE
                                  	WHEN OrderItems.t_Pricing = 'Line Item Pricing'  THEN  OrderItems.n_Price * OrderItems.n_Quantity
                                  	WHEN OrderItems.t_Pricing = 'SQ.FT. Pricing' THEN Round((IFNULL(OrderItems.n_HeightInInches,0) * IFNULL(OrderItems.n_WidthInInches,0) / 144)
                                  	  * IFNULL(OrderItems.n_Price,0) * IFNULL(OrderItems.n_Quantity,0),2)
                                  	ELSE NULL
                                  	END)) AS n_TotalOrderItemPrice,
                                  	Orders.t_TypeOfOrder,
                                  	Orders.nb_UseTotalOrderPricing
                                  FROM
                                  	OrderItems
                                  	LEFT JOIN Orders ON Orders.kp_OrderID = OrderItems.kf_OrderID
                                  WHERE
                                  	OrderItems.kf_OrderID = '$id'
                                  GROUP BY
                                  	kf_OrderID
                                  ) oi ON oi.kf_OrderID = Orders.kp_OrderID
                                  LEFT JOIN (SELECT kf_OrderID, sum(OrderShipTracking.n_ShippingCharge) as n_TotalShippingCharges FROM OrderShipTracking WHERE OrderShipTracking.kf_OrderID = '$id' GROUP BY kf_OrderID) os ON os.kf_OrderID = Orders.kp_OrderID
                                  LEFT JOIN Customers ON Customers.kp_CustomerID = Orders.kf_CustomerID
                                  LEFT JOIN Addresses ON Addresses.kf_OtherID = Orders.kf_CustomerID
                                  LEFT JOIN OrderShipTracking ON OrderShipTracking.kf_OrderID = Orders.kp_OrderID
                                  LEFT JOIN CompanyLocations ON CompanyLocations.kp_CompanyLocations = 100000
                                  WHERE Orders.kp_OrderID = '$id' AND Addresses.kf_TypeMain = 'Customer' AND Addresses.kf_TypeSub = 'BillTo'");

      return $query->row_array();
    }

    public function getProductData($id) {
      $query = $this->db->query("SELECT * FROM ProductBuildItems WHERE kp_ProductBuildItemID = " . $id);

      return $query->row_array();
    }

    public function getOrderEmailTrackingDataByOrderID($orderID) {
        $query = $this->db->query("SELECT
                                    Orders.kp_OrderID as TxnID,basetables2.Employees.t_UserName,
                                    basetables2.Employees.t_EmployeeEmail,
                                    Customers.t_CustCompany,
                                    Customers.kp_CustomerID,
                                    Customers.t_QBCustID AS CustomerRef_ListID,
                                    Customers.t_QBCustID AS ListID,
                                    basetables2.Employees.t_QBSalesRepListID as SalesRepRef_ListID,
                                    basetables2.Employees.t_QBSalesRepListID,
                                    '520000-1073589325' as ARAccountRef_ListID,
                                    '70000-1073917491' as TemplateRef_ListID,
                                    DATE_FORMAT(Orders.d_JobDue, '%c-%e-%Y') AS TxnDate,
                                    Orders.kp_OrderID as RefNumber,
                                    Addresses.t_CompanyName AS BillAddress_Addr1,
                                    Addresses.t_ContactNameFull AS BillAddress_Addr2,
                                    Addresses.t_Address1 AS BillAddress_Addr3,
                                    Addresses.t_Address2 AS BillAddress_Addr4,
                                    Addresses.t_City AS BillAddress_City,
                                    Addresses.t_StateOrProvince AS BillAddress_State,
                                    Addresses.t_PostalCode AS BillAddress_PostalCode,
                                    Addresses.t_Country AS BillAddress_Country,
                                    OrderShip.t_RecipientCompany AS ShipAddress_Addr1,
                                    OrderShip.t_RecipientContact AS ShipAddress_Addr2,
                                    OrderShip.t_RecipientAddress1 AS ShipAddress_Addr3,
                                    OrderShip.t_RecipientAddress2 AS ShipAddress_Addr4,
                                    OrderShip.t_RecipientCity AS ShipAddress_City,
                                    OrderShip.t_RecipientState AS ShipAddress_State,
                                    OrderShip.t_RecipientPostalCode AS ShipAddress_PostalCode,
                                    OrderShip.t_RecipientCountry AS ShipAddress_Country,
                                    Orders.t_CustomerPO AS PONumber,
                                    Orders.nb_IncompletePricing,
                                    Orders.nb_JobFinished,
                                    Orders.nb_PostedToQuickBooks,
                                    Orders.nb_UseTotalOrderPricing,
                                    Orders.n_TotalOrderPrice,
                                    Orders.t_TypeOfOrder,
                                    Employees.t_UserName as SalesRepRef_FullName,
                                    Orders.t_JobName as Memo,
                                    Orders.t_JobStatus as Status,
                                    Orders.nb_CustomerPOToBeDetermined,
                                    sum(ShipperService.nb_ShipPricedManually) as ManShip,
                                    basetables2.Orders.d_JobDue,
                                    basetables2.Orders.kf_ContactID
                                    FROM
                                      Orders
                                        LEFT JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
                                        LEFT JOIN Employees on Orders.kf_EmployeeIDSales = basetables2.Employees.kp_EmployeeID
                                        LEFT JOIN Addresses ON Orders.kf_CustomerID = Addresses.kf_OtherID AND Addresses.kf_TypeMain = 'Customer' AND Addresses.kf_TypeSub = 'BillTo'
                                        LEFT JOIN OrderShip ON Orders.kp_OrderID = OrderShip.kf_OrderID
                                        LEFT JOIN ShipperService ON OrderShip.kf_ShipperServiceID = ShipperService.kp_ShipperServiceID
                                      WHERE
                                         Orders.nb_JobFinished = 1
                                         AND Orders.nb_EmailSentTrackNumReadyToPickUp is Null
                                         AND Orders.t_TypeOfJobTicket = 'JobWithProductBuilds'
                                         AND Orders.t_JobStatus != 'Cancelled'
                                         AND basetables2.Orders.kp_OrderID='$orderID'
                                      GROUP BY Orders.kp_OrderID");

        return $query->row_array();
    }


    public function getOrdersComplete($date) {
      $query = $this->db->query("SELECT if(Orders.t_JobStatus = 'Multi Line Status', OI_Statuses.t_Department, Ord_Statuses.t_Department) AS t_Status,
                                	COUNT(OrderItems.kp_OrderItemID) AS n_TotalLineItems
                                FROM OrderItems LEFT OUTER JOIN Orders ON OrderItems.kf_OrderID = Orders.kp_OrderID
                                	 LEFT OUTER JOIN Statuses OI_Statuses ON OrderItems.t_OiStatus = OI_Statuses.t_StatusName
                                	 LEFT OUTER JOIN Statuses Ord_Statuses ON Orders.t_JobStatus = Ord_Statuses.t_StatusName
                                WHERE Orders.d_JobDue = '" . $date . "' and Orders.t_TypeOfJobTicket = 'JobWithProductBuilds'
                                GROUP BY t_Status");

      return $query->result_array();
    }

    public function getAllOrderRequests() {
      $query = $this->db->query("SELECT Orders.kp_OrderID,
                                  	Orders.t_JobName,
                                  	Orders.t_ServiceLevel,
                                  	Orders.n_OrderItemCount,
                                  	DATE_FORMAT(
                                  		basetables2.Orders.zCreated,
                                  		'%c-%e-%Y'
                                  	) AS zCreated,
                                  	Customers.t_CustCompany,
                                  	Orders.kf_EmployeeIDSales,
                                  	Orders.nb_CreditCardHoldTimeOrder,
                                  	Orders.nb_CreditCardHoldTimeOrderReleased,
                                  	Orders.nb_OverLimitTimeOrder,
                                  	Orders.nb_OverlimitTimeOrderReleased,
                                  	Orders.nb_DepositRequired,
                                  	Orders.nb_DepositRequiredReleased,
                                  	Orders.nb_AccountLockedTimeOrder,
                                  	Orders.nb_AccountLockedTimeOrderReleased,
                                  	Orders.t_TypeOfJobTicket,
                                  	Orders.d_ProofDue,
                                  	Orders.zModified,
                                  	Employees.t_UserName
                                  FROM Orders LEFT OUTER JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
                                  	 LEFT OUTER JOIN Employees ON Orders.kf_EmployeeIDSales = Employees.kp_EmployeeID
                                  WHERE (
                                  		ISNULL(Orders.nb_Inactive)
                                  		OR Orders.nb_Inactive = 0
                                  	)
                                  AND Orders.t_TypeOfJobTicket = 'InternalOrderForm'
                                  ORDER BY Orders.kp_OrderID ASC");

      return $query->result_array();
    }

    public function getOrdersListByCustomerMasterJob($customerID, $masterJobID) {
      $query = $this->db->query("SELECT Orders.kp_OrderID, Orders.kf_ContactID, Orders.kf_CustomerID, Orders.kf_EmployeeIDCSR, Orders.kf_EmployeeIDSales, Orders.kf_EmployeeIDEstimator,
      Orders.kf_EmployeeIDNameOnEstimate, Orders.kf_EquipmentID, Orders.kf_EquipmentModeID, Orders.kf_MasterJobID, Orders.kf_RedoOriginalJobID,
      Orders.kf_SalesLeadID, Orders.kf_TemporaryReportID, Orders.t_ArtLocation, Orders.t_CreateArtSendProof, Orders.t_CreditCardTransaction, Orders.t_CustomerPO, Orders.t_CustomerJobNum, Orders.t_DataLog,
      Orders.t_Drawer, Orders.t_FlexibleDueDate, Orders.t_JobDescription, Orders.t_JobFinishedYN, Orders.t_JobInvoicedYN, Orders.t_JobName, Orders.t_JobStatus,
      Orders.t_Notes, Orders.t_NotesHidden, Orders.t_NotesInventoryNeeded, Orders.t_OrderPricingType, Orders.t_OriginalDataType, Orders.t_OverideCreditHold,
      Orders.t_OverideZeroCost, Orders.t_OverrideAfterCutOffBy, Orders.t_PersonWritingOrder, Orders.t_PMSColor, Orders.t_PressProofNotes, Orders.t_PressProofSize,
      Orders.t_PressProofType, Orders.t_ProofApprovalType, Orders.t_ProofApprovedBy, Orders.t_ProofsApprovedBy, Orders.t_ProofType, Orders.t_QBEditSequence, Orders.t_QBInvoiceNumber,
      Orders.t_QBListID, Orders.t_QBTxnID, Orders.t_RedoApprovedBy, Orders.t_RedoDepartment, Orders.t_RedoDescription, Orders.t_SchedSqftIncludeOrder, Orders.t_ScheduleApproval,
      Orders.t_SendProof, Orders.t_ServiceLevel, Orders.t_ServiceLevelApprovedBy, Orders.t_StatusBeforeParked, Orders.t_SupervisorScheduleNotes, Orders.t_TypeOfJobTicket,
      Orders.t_TypeOfOrder, Orders.t_WebAccessKey, Orders.d_Invoiced, DATE_FORMAT(Orders.d_JobDue, '%c-%e-%Y') AS d_JobDue, Orders.d_PrintJobDue, Orders.d_ProofDue, Orders.d_Received, Orders.d_SchedDay, Orders.d_TentativeApproval,
      Orders.d_TentativeJobDue, TIME_FORMAT(Orders.ti_JobDue, '%l:%i %p') AS ti_JobDue, Orders.ti_PrintJobDue, Orders.ti_ProofDue, Orders.ti_TentativeJobDue, Orders.ts_JobMarkedAsFinished,
      Orders.ts_OverrideAfterCutOff, Orders.ts_PostedToQuickbooks, Orders.ts_ProofApproved, Orders.ts_ProofsApproved, Orders.ts_ProofSent, Orders.ts_ScheduleApproval,
      Orders.ts_ServiceLevelApproved, Orders.ts_TrackNumberEmailSent, Orders.n_CommisionFactor, Orders.n_Damaged1Side, Orders.n_Damaged2SidesNonUsable, Orders.n_DashNumberStored,
      Orders.n_DepositAmount, Orders.n_EstimatedShipping, Orders.n_OICount, Orders.n_PercentCOMTotalOrderPrice, Orders.n_PressError1Side, Orders.n_SqFtTotal,
      Orders.n_SqFtTotalDS, Orders.n_TotalOrderPrice, Orders.nb_CustomerPOToBeDetermined, Orders.nb_DontCountInSchedule, Orders.nb_DueSaturday, Orders.nb_DueSunday,
      Orders.nb_EmailSentOrderConfirmation, Orders.nb_EmailSentTrackNumReadyToPickUp, Orders.nb_FollowUpCallMade, Orders.nb_Inactive, Orders.nb_IncompletePricing,
      Orders.nb_JobFinished, Orders.nb_MustPrintPackingList, Orders.nb_OrderLoggedIntoSystem, Orders.nb_OverideAfterCutOffTime, Orders.nb_PostedToQuickBooks,
      Orders.nb_ReceivedBeforeCutOffTime, Orders.nb_ScheduleNow, Orders.nb_UseTotalOrderPricing, Orders.zCreated, Orders.zCreatedBy, Orders.zModified, Orders.zModifiedBy,
      Orders.nb_CreditHoldOveride, Orders.nb_CreditHoldTimeOrder, Orders.t_CreditHoldType, Orders.t_CreditHoldTypeOveride, Orders.t_CreditHoldOverideNote, Orders.t_CreditHoldReleasedBy,
      Orders.ts_CreditHoldReleased, Orders.n_TotalOrderItemPrice, Orders.n_TotalOtherCharges, Orders.n_TotalSubOrderItemOtherCharges, Orders.n_TotalShippingCharges, Orders.n_TotalSubtotal,
      Orders.n_TotalTax, Orders.n_TotalGrandTotal, Orders.nb_TotalChargeTax, Orders.n_TotalTaxRate, Orders.t_OrdShip, Orders.n_OICSqFtSum, Orders.n_OrderItemCount, Orders.n_Complexity,
      Orders.t_OrderItemAb, Orders.t_MachineAb, Orders.n_DurationTime, Orders.nb_SureDate, Orders.kf_ContactIDProjectManager, Orders.kf_ContactIDArtContact, Orders.kf_CustomerIDProjectManager,
      Orders.kf_OrderRedoID, Orders.t_NotesHTML, Orders.nb_QrcodeGeneratedOI, Orders.nb_OrderStepsComplete, Orders.nb_OverlimitTimeOrder, Orders.nb_AccountLockedTimeOrder,
      Orders.nb_DepositRequired, Orders.nb_CreditCardHoldTimeOrder, Orders.nb_CreditCardHoldTimeOrderReleased, Orders.nb_CreditCardTimeOrderReleased,
      Orders.nb_OverlimitTimeOrderReleased, Orders.nb_DepositRequiredReleased, Orders.t_DepositType, Orders.nb_AccountLockedTimeOrderReleased, Orders.t_AccountLockedTimeOrderReleasedBy,
      Orders.n_InspectionPics,
      contactAddress.t_ContactNameFull,
      Customers.t_CustCompany,
      EmployeesSales.t_UserName,
      projectAddress.t_ContactNameFull AS t_ProjectName,
      artAddress.t_ContactNameFull as t_ArtPersonName
      FROM Orders LEFT OUTER JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
      LEFT OUTER JOIN Addresses contactAddress ON Orders.kf_ContactID = contactAddress.kp_AddressID
      LEFT OUTER JOIN Employees EmployeesSales ON Orders.kf_EmployeeIDSales = EmployeesSales.kp_EmployeeID
      LEFT OUTER JOIN Addresses projectAddress ON Orders.kf_ContactIDProjectManager = projectAddress.kp_AddressID
      LEFT OUTER JOIN Addresses artAddress ON Orders.kf_ContactIDArtContact = artAddress.kp_AddressID
      LEFT OUTER JOIN OrderItems ON Orders.kp_OrderID = OrderItems.kf_OrderID
      WHERE (Orders.t_TypeOfJobTicket = 'JobWithProductBuilds' or Orders.t_TypeOfJobTicket = 'OldJob') and Orders.kf_CustomerID = ".$customerID."
      and Orders.kf_MasterJobID = " . $masterJobID . "
      GROUP BY Orders.kp_OrderID");

      return $query->result_array();
    }

    public function getOrdersListByCustomer($customerID, $pagination) {
      $query = $this->db->query("SELECT Orders.kp_OrderID, Orders.kf_ContactID, Orders.kf_CustomerID, Orders.kf_EmployeeIDCSR, Orders.kf_EmployeeIDSales, Orders.kf_EmployeeIDEstimator,
      Orders.kf_EmployeeIDNameOnEstimate, Orders.kf_EquipmentID, Orders.kf_EquipmentModeID, Orders.kf_MasterJobID, Orders.kf_RedoOriginalJobID,
      Orders.kf_SalesLeadID, Orders.kf_TemporaryReportID, Orders.t_ArtLocation, Orders.t_CreateArtSendProof, Orders.t_CreditCardTransaction, Orders.t_CustomerPO, Orders.t_CustomerJobNum, Orders.t_DataLog,
      Orders.t_Drawer, Orders.t_FlexibleDueDate, Orders.t_JobDescription, Orders.t_JobFinishedYN, Orders.t_JobInvoicedYN, Orders.t_JobName, Orders.t_JobStatus,
      Orders.t_Notes, Orders.t_NotesHidden, Orders.t_NotesInventoryNeeded, Orders.t_OrderPricingType, Orders.t_OriginalDataType, Orders.t_OverideCreditHold,
      Orders.t_OverideZeroCost, Orders.t_OverrideAfterCutOffBy, Orders.t_PersonWritingOrder, Orders.t_PMSColor, Orders.t_PressProofNotes, Orders.t_PressProofSize,
      Orders.t_PressProofType, Orders.t_ProofApprovalType, Orders.t_ProofApprovedBy, Orders.t_ProofsApprovedBy, Orders.t_ProofType, Orders.t_QBEditSequence, Orders.t_QBInvoiceNumber,
      Orders.t_QBListID, Orders.t_QBTxnID, Orders.t_RedoApprovedBy, Orders.t_RedoDepartment, Orders.t_RedoDescription, Orders.t_SchedSqftIncludeOrder, Orders.t_ScheduleApproval,
      Orders.t_SendProof, Orders.t_ServiceLevel, Orders.t_ServiceLevelApprovedBy, Orders.t_StatusBeforeParked, Orders.t_SupervisorScheduleNotes, Orders.t_TypeOfJobTicket,
      Orders.t_TypeOfOrder, Orders.t_WebAccessKey, Orders.d_Invoiced, DATE_FORMAT(Orders.d_JobDue, '%c-%e-%Y') AS d_JobDue, Orders.d_PrintJobDue, Orders.d_ProofDue, Orders.d_Received, Orders.d_SchedDay, Orders.d_TentativeApproval,
      Orders.d_TentativeJobDue, TIME_FORMAT(Orders.ti_JobDue, '%l:%i %p') AS ti_JobDue, Orders.ti_PrintJobDue, Orders.ti_ProofDue, Orders.ti_TentativeJobDue, Orders.ts_JobMarkedAsFinished,
      Orders.ts_OverrideAfterCutOff, Orders.ts_PostedToQuickbooks, Orders.ts_ProofApproved, Orders.ts_ProofsApproved, Orders.ts_ProofSent, Orders.ts_ScheduleApproval,
      Orders.ts_ServiceLevelApproved, Orders.ts_TrackNumberEmailSent, Orders.n_CommisionFactor, Orders.n_Damaged1Side, Orders.n_Damaged2SidesNonUsable, Orders.n_DashNumberStored,
      Orders.n_DepositAmount, Orders.n_EstimatedShipping, Orders.n_OICount, Orders.n_PercentCOMTotalOrderPrice, Orders.n_PressError1Side, Orders.n_SqFtTotal,
      Orders.n_SqFtTotalDS, Orders.n_TotalOrderPrice, Orders.nb_CustomerPOToBeDetermined, Orders.nb_DontCountInSchedule, Orders.nb_DueSaturday, Orders.nb_DueSunday,
      Orders.nb_EmailSentOrderConfirmation, Orders.nb_EmailSentTrackNumReadyToPickUp, Orders.nb_FollowUpCallMade, Orders.nb_Inactive, Orders.nb_IncompletePricing,
      Orders.nb_JobFinished, Orders.nb_MustPrintPackingList, Orders.nb_OrderLoggedIntoSystem, Orders.nb_OverideAfterCutOffTime, Orders.nb_PostedToQuickBooks,
      Orders.nb_ReceivedBeforeCutOffTime, Orders.nb_ScheduleNow, Orders.nb_UseTotalOrderPricing, Orders.zCreated, Orders.zCreatedBy, Orders.zModified, Orders.zModifiedBy,
      Orders.nb_CreditHoldOveride, Orders.nb_CreditHoldTimeOrder, Orders.t_CreditHoldType, Orders.t_CreditHoldTypeOveride, Orders.t_CreditHoldOverideNote, Orders.t_CreditHoldReleasedBy,
      Orders.ts_CreditHoldReleased, Orders.n_TotalOrderItemPrice, Orders.n_TotalOtherCharges, Orders.n_TotalSubOrderItemOtherCharges, Orders.n_TotalShippingCharges, Orders.n_TotalSubtotal,
      Orders.n_TotalTax, Orders.n_TotalGrandTotal, Orders.nb_TotalChargeTax, Orders.n_TotalTaxRate, Orders.t_OrdShip, Orders.n_OICSqFtSum, Orders.n_OrderItemCount, Orders.n_Complexity,
      Orders.t_OrderItemAb, Orders.t_MachineAb, Orders.n_DurationTime, Orders.nb_SureDate, Orders.kf_ContactIDProjectManager, Orders.kf_ContactIDArtContact, Orders.kf_CustomerIDProjectManager,
      Orders.kf_OrderRedoID, Orders.t_NotesHTML, Orders.nb_QrcodeGeneratedOI, Orders.nb_OrderStepsComplete, Orders.nb_OverlimitTimeOrder, Orders.nb_AccountLockedTimeOrder,
      Orders.nb_DepositRequired, Orders.nb_CreditCardHoldTimeOrder, Orders.nb_CreditCardHoldTimeOrderReleased, Orders.nb_CreditCardTimeOrderReleased,
      Orders.nb_OverlimitTimeOrderReleased, Orders.nb_DepositRequiredReleased, Orders.t_DepositType, Orders.nb_AccountLockedTimeOrderReleased, Orders.t_AccountLockedTimeOrderReleasedBy,
      Orders.n_InspectionPics,
      contactAddress.t_ContactNameFull,
      Customers.t_CustCompany,
      EmployeesSales.t_UserName,
      projectAddress.t_ContactNameFull AS t_ProjectName,
      artAddress.t_ContactNameFull as t_ArtPersonName
      FROM Orders LEFT OUTER JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
      LEFT OUTER JOIN Addresses contactAddress ON Orders.kf_ContactID = contactAddress.kp_AddressID
      LEFT OUTER JOIN Employees EmployeesSales ON Orders.kf_EmployeeIDSales = EmployeesSales.kp_EmployeeID
      LEFT OUTER JOIN Addresses projectAddress ON Orders.kf_ContactIDProjectManager = projectAddress.kp_AddressID
      LEFT OUTER JOIN Addresses artAddress ON Orders.kf_ContactIDArtContact = artAddress.kp_AddressID
      LEFT OUTER JOIN OrderItems ON Orders.kp_OrderID = OrderItems.kf_OrderID
      WHERE (Orders.t_TypeOfJobTicket = 'JobWithProductBuilds' or Orders.t_TypeOfJobTicket = 'OldJob') and Orders.kf_CustomerID = ".$customerID."
      GROUP BY Orders.kp_OrderID ORDER BY Orders.kp_OrderID DESC LIMIT " . $pagination*2500 . ", 2500");

      return $query->result_array();
    }

    public function getCustomerOrderRequests($customerID) {
      $query = $this->db->query("SELECT Orders.kp_OrderID, DATE_FORMAT(Orders.zCreated, '%c-%e-%Y %l:%i %p') AS zCreated, Orders.t_JobName
        FROM Orders
        WHERE (ISNULL(Orders.nb_Inactive) or Orders.nb_Inactive = 0) and Orders.t_TypeOfJobTicket = 'InternalOrderForm' and Orders.kf_CustomerID = " . $customerID . "
        ORDER BY Orders.kp_OrderID ASC");

      return $query->result_array();
    }

    public function getOrderViewByCustomer($filterInfo) {
      $queryString = "SELECT Orders.kp_OrderID, Orders.kf_ContactID, Orders.kf_CustomerID, Orders.kf_EmployeeIDCSR, Orders.kf_EmployeeIDSales, Orders.kf_EmployeeIDEstimator,
      Orders.kf_EmployeeIDNameOnEstimate, Orders.kf_EquipmentID, Orders.kf_EquipmentModeID, Orders.kf_MasterJobID, Orders.kf_RedoOriginalJobID,
      Orders.kf_SalesLeadID, Orders.kf_TemporaryReportID, Orders.t_ArtLocation, Orders.t_CreateArtSendProof, Orders.t_CreditCardTransaction, Orders.t_CustomerPO, Orders.t_CustomerJobNum, Orders.t_DataLog,
      Orders.t_Drawer, Orders.t_FlexibleDueDate, Orders.t_JobDescription, Orders.t_JobFinishedYN, Orders.t_JobInvoicedYN, Orders.t_JobName, Orders.t_JobStatus,
      Orders.t_Notes, Orders.t_NotesHidden, Orders.t_NotesInventoryNeeded, Orders.t_OrderPricingType, Orders.t_OriginalDataType, Orders.t_OverideCreditHold,
      Orders.t_OverideZeroCost, Orders.t_OverrideAfterCutOffBy, Orders.t_PersonWritingOrder, Orders.t_PMSColor, Orders.t_PressProofNotes, Orders.t_PressProofSize,
      Orders.t_PressProofType, Orders.t_ProofApprovalType, Orders.t_ProofApprovedBy, Orders.t_ProofsApprovedBy, Orders.t_ProofType, Orders.t_QBEditSequence, Orders.t_QBInvoiceNumber,
      Orders.t_QBListID, Orders.t_QBTxnID, Orders.t_RedoApprovedBy, Orders.t_RedoDepartment, Orders.t_RedoDescription, Orders.t_SchedSqftIncludeOrder, Orders.t_ScheduleApproval,
      Orders.t_SendProof, Orders.t_ServiceLevel, Orders.t_ServiceLevelApprovedBy, Orders.t_StatusBeforeParked, Orders.t_SupervisorScheduleNotes, Orders.t_TypeOfJobTicket,
      Orders.t_TypeOfOrder, Orders.t_WebAccessKey, Orders.d_Invoiced, DATE_FORMAT(Orders.d_JobDue, '%c-%e-%Y') AS d_JobDue, Orders.d_PrintJobDue, Orders.d_ProofDue, Orders.d_Received, Orders.d_SchedDay, Orders.d_TentativeApproval,
      Orders.d_TentativeJobDue, TIME_FORMAT(Orders.ti_JobDue, '%l:%i %p') AS ti_JobDue, Orders.ti_PrintJobDue, Orders.ti_ProofDue, Orders.ti_TentativeJobDue, Orders.ts_JobMarkedAsFinished,
      Orders.ts_OverrideAfterCutOff, Orders.ts_PostedToQuickbooks, Orders.ts_ProofApproved, Orders.ts_ProofsApproved, Orders.ts_ProofSent, Orders.ts_ScheduleApproval,
      Orders.ts_ServiceLevelApproved, Orders.ts_TrackNumberEmailSent, Orders.n_CommisionFactor, Orders.n_Damaged1Side, Orders.n_Damaged2SidesNonUsable, Orders.n_DashNumberStored,
      Orders.n_DepositAmount, Orders.n_EstimatedShipping, Orders.n_OICount, Orders.n_PercentCOMTotalOrderPrice, Orders.n_PressError1Side, Orders.n_SqFtTotal,
      Orders.n_SqFtTotalDS, Orders.n_TotalOrderPrice, Orders.nb_CustomerPOToBeDetermined, Orders.nb_DontCountInSchedule, Orders.nb_DueSaturday, Orders.nb_DueSunday,
      Orders.nb_EmailSentOrderConfirmation, Orders.nb_EmailSentTrackNumReadyToPickUp, Orders.nb_FollowUpCallMade, Orders.nb_Inactive, Orders.nb_IncompletePricing,
      Orders.nb_JobFinished, Orders.nb_MustPrintPackingList, Orders.nb_OrderLoggedIntoSystem, Orders.nb_OverideAfterCutOffTime, Orders.nb_PostedToQuickBooks,
      Orders.nb_ReceivedBeforeCutOffTime, Orders.nb_ScheduleNow, Orders.nb_UseTotalOrderPricing, Orders.zCreated, Orders.zCreatedBy, Orders.zModified, Orders.zModifiedBy,
      Orders.nb_CreditHoldOveride, Orders.nb_CreditHoldTimeOrder, Orders.t_CreditHoldType, Orders.t_CreditHoldTypeOveride, Orders.t_CreditHoldOverideNote, Orders.t_CreditHoldReleasedBy,
      Orders.ts_CreditHoldReleased, Orders.n_TotalOrderItemPrice, Orders.n_TotalOtherCharges, Orders.n_TotalSubOrderItemOtherCharges, Orders.n_TotalShippingCharges, Orders.n_TotalSubtotal,
      Orders.n_TotalTax, Orders.n_TotalGrandTotal, Orders.nb_TotalChargeTax, Orders.n_TotalTaxRate, Orders.t_OrdShip, Orders.n_OICSqFtSum, Orders.n_OrderItemCount, Orders.n_Complexity,
      Orders.t_OrderItemAb, Orders.t_MachineAb, Orders.n_DurationTime, Orders.nb_SureDate, Orders.kf_ContactIDProjectManager, Orders.kf_ContactIDArtContact, Orders.kf_CustomerIDProjectManager,
      Orders.kf_OrderRedoID, Orders.t_NotesHTML, Orders.nb_QrcodeGeneratedOI, Orders.nb_OrderStepsComplete, Orders.nb_OverlimitTimeOrder, Orders.nb_AccountLockedTimeOrder,
      Orders.nb_DepositRequired, Orders.nb_CreditCardHoldTimeOrder, Orders.nb_CreditCardHoldTimeOrderReleased, Orders.nb_CreditCardTimeOrderReleased,
      Orders.nb_OverlimitTimeOrderReleased, Orders.nb_DepositRequiredReleased, Orders.t_DepositType, Orders.nb_AccountLockedTimeOrderReleased, Orders.t_AccountLockedTimeOrderReleasedBy,
      Orders.n_InspectionPics,
      contactAddress.t_ContactNameFull,
      Customers.t_CustCompany,
      EmployeesSales.t_UserName,
      projectAddress.t_ContactNameFull AS t_ProjectName,
      artAddress.t_ContactNameFull as t_ArtPersonName
      FROM Orders LEFT OUTER JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
      LEFT OUTER JOIN Addresses contactAddress ON Orders.kf_ContactID = contactAddress.kp_AddressID
      LEFT OUTER JOIN Employees EmployeesSales ON Orders.kf_EmployeeIDSales = EmployeesSales.kp_EmployeeID
      LEFT OUTER JOIN Addresses projectAddress ON Orders.kf_ContactIDProjectManager = projectAddress.kp_AddressID
      LEFT OUTER JOIN Addresses artAddress ON Orders.kf_ContactIDArtContact = artAddress.kp_AddressID
      LEFT OUTER JOIN OrderItems ON Orders.kp_OrderID = OrderItems.kf_OrderID
      LEFT OUTER JOIN OrderItemComponents ON Orders.kp_OrderID = OrderItemComponents.kf_OrderID
      WHERE (Orders.t_TypeOfJobTicket = 'JobWithProductBuilds' OR Orders.t_TypeOfJobTicket = 'OldJob')";

      if($filterInfo->kf_CustomerID != "None") {
        $queryString .= " and Orders.kf_CustomerID = ".$filterInfo->kf_CustomerID;
      }

      if($filterInfo->t_Structure != "None") {
        $queryString .= " and OrderItems.t_Structure LIKE '%".$filterInfo->t_Structure . "%'";
      }

      if($filterInfo->t_CustomerPO != "None") {
        $queryString .= " and Orders.t_CustomerPO LIKE '%".$filterInfo->t_CustomerPO . "%'";
      }

      if($filterInfo->t_JobName != "None") {
        $queryString .= " and Orders.t_JobName LIKE '%".$filterInfo->t_JobName . "%'";
      }

      if($filterInfo->orderType != "None"){
        $queryString .= " and Orders.t_TypeOfOrder = '" . $filterInfo->orderType . "'";
      }

      if($filterInfo->kf_EmployeeIDSales != "None"){
        $queryString .= " and Customers.kf_EmployeeID_Sales = '" . $filterInfo->kf_EmployeeIDSales . "'";
      }

      if($filterInfo->kf_ProductBuildID != "None"){
        $queryString .= " and OrderItems.kf_ProductBuildID = '" . $filterInfo->kf_ProductBuildID . "'";
      }

      if($filterInfo->kf_BuildItemID != "None"){
        $queryString .= " and OrderItemComponents.kf_BuildItemID = '" . $filterInfo->kf_BuildItemID . "'";
      }

      if($filterInfo->dateEnd == "None" && $filterInfo->dateStart == "None") {
        // Add No Date Restriction
      } else if($filterInfo->dateEnd == "None") {
        $queryString .= " and Orders." . $filterInfo->dateType . " = '" . $filterInfo->dateStart . "'";
      } else {
        $queryString .= " and Orders." . $filterInfo->dateType . " >= '" . $filterInfo->dateStart . "' and Orders." . $filterInfo->dateType . "<= '" . $filterInfo->dateEnd . "'";
      }

      $queryString .= " GROUP BY Orders.kp_OrderID ORDER BY Orders.kp_OrderID DESC LIMIT " . $filterInfo->pagination*2500 . ", 2500";

      $query = $this->db->query($queryString);

      return $query->result_array();
    }

      public function getOrderViewByOrder($id) {
        $query = $this->db->query("SELECT Orders.kp_OrderID, Orders.kf_ContactID, Orders.kf_CustomerID, Orders.kf_EmployeeIDCSR, Orders.kf_EmployeeIDSales, Orders.kf_EmployeeIDEstimator,
          Orders.kf_EmployeeIDNameOnEstimate, Orders.kf_EquipmentID, Orders.kf_EquipmentModeID, Orders.kf_MasterJobID, Orders.kf_RedoOriginalJobID,
          Orders.kf_SalesLeadID, Orders.kf_TemporaryReportID, Orders.t_ArtLocation, Orders.t_CreateArtSendProof, Orders.t_CreditCardTransaction, Orders.t_CustomerPO, Orders.t_CustomerJobNum, Orders.t_DataLog,
          Orders.t_Drawer, Orders.t_FlexibleDueDate, Orders.t_JobDescription, Orders.t_JobFinishedYN, Orders.t_JobInvoicedYN, Orders.t_JobName, Orders.t_JobStatus,
          Orders.t_Notes, Orders.t_NotesHidden, Orders.t_NotesInventoryNeeded, Orders.t_OrderPricingType, Orders.t_OriginalDataType, Orders.t_OverideCreditHold,
          Orders.t_OverideZeroCost, Orders.t_OverrideAfterCutOffBy, Orders.t_PersonWritingOrder, Orders.t_PMSColor, Orders.t_PressProofNotes, Orders.t_PressProofSize,
          Orders.t_PressProofType, Orders.t_ProofApprovalType, Orders.t_ProofApprovedBy, Orders.t_ProofsApprovedBy, Orders.t_ProofType, Orders.t_QBEditSequence, Orders.t_QBInvoiceNumber,
          Orders.t_QBListID, Orders.t_QBTxnID, Orders.t_RedoApprovedBy, Orders.t_RedoDepartment, Orders.t_RedoDescription, Orders.t_SchedSqftIncludeOrder, Orders.t_ScheduleApproval,
          Orders.t_SendProof, Orders.t_ServiceLevel, Orders.t_ServiceLevelApprovedBy, Orders.t_StatusBeforeParked, Orders.t_SupervisorScheduleNotes, Orders.t_TypeOfJobTicket,
          Orders.t_TypeOfOrder, Orders.t_WebAccessKey, Orders.d_Invoiced, DATE_FORMAT(Orders.d_JobDue, '%c-%e-%Y') AS d_JobDue, Orders.d_PrintJobDue, Orders.d_ProofDue, Orders.d_Received, Orders.d_SchedDay, Orders.d_TentativeApproval,
          Orders.d_TentativeJobDue, TIME_FORMAT(Orders.ti_JobDue, '%l:%i %p') AS ti_JobDue, Orders.ti_PrintJobDue, Orders.ti_ProofDue, Orders.ti_TentativeJobDue, Orders.ts_JobMarkedAsFinished,
          Orders.ts_OverrideAfterCutOff, Orders.ts_PostedToQuickbooks, Orders.ts_ProofApproved, Orders.ts_ProofsApproved, Orders.ts_ProofSent, Orders.ts_ScheduleApproval,
          Orders.ts_ServiceLevelApproved, Orders.ts_TrackNumberEmailSent, Orders.n_CommisionFactor, Orders.n_Damaged1Side, Orders.n_Damaged2SidesNonUsable, Orders.n_DashNumberStored,
          Orders.n_DepositAmount, Orders.n_EstimatedShipping, Orders.n_OICount, Orders.n_PercentCOMTotalOrderPrice, Orders.n_PressError1Side, Orders.n_SqFtTotal,
          Orders.n_SqFtTotalDS, Orders.n_TotalOrderPrice, Orders.nb_CustomerPOToBeDetermined, Orders.nb_DontCountInSchedule, Orders.nb_DueSaturday, Orders.nb_DueSunday,
          Orders.nb_EmailSentOrderConfirmation, Orders.nb_EmailSentTrackNumReadyToPickUp, Orders.nb_FollowUpCallMade, Orders.nb_Inactive, Orders.nb_IncompletePricing,
          Orders.nb_JobFinished, Orders.nb_MustPrintPackingList, Orders.nb_OrderLoggedIntoSystem, Orders.nb_OverideAfterCutOffTime, Orders.nb_PostedToQuickBooks,
          Orders.nb_ReceivedBeforeCutOffTime, Orders.nb_ScheduleNow, Orders.nb_UseTotalOrderPricing, Orders.zCreated, Orders.zCreatedBy, Orders.zModified, Orders.zModifiedBy,
          Orders.nb_CreditHoldOveride, Orders.nb_CreditHoldTimeOrder, Orders.t_CreditHoldType, Orders.t_CreditHoldTypeOveride, Orders.t_CreditHoldOverideNote, Orders.t_CreditHoldReleasedBy,
          Orders.ts_CreditHoldReleased, Orders.n_TotalOrderItemPrice, Orders.n_TotalOtherCharges, Orders.n_TotalSubOrderItemOtherCharges, Orders.n_TotalShippingCharges, Orders.n_TotalSubtotal,
          Orders.n_TotalTax, Orders.n_TotalGrandTotal, Orders.nb_TotalChargeTax, Orders.n_TotalTaxRate, Orders.t_OrdShip, Orders.n_OICSqFtSum, Orders.n_OrderItemCount, Orders.n_Complexity,
          Orders.t_OrderItemAb, Orders.t_MachineAb, Orders.n_DurationTime, Orders.nb_SureDate, Orders.kf_ContactIDProjectManager, Orders.kf_ContactIDArtContact, Orders.kf_CustomerIDProjectManager,
          Orders.kf_OrderRedoID, Orders.t_NotesHTML, Orders.nb_QrcodeGeneratedOI, Orders.nb_OrderStepsComplete, Orders.nb_OverlimitTimeOrder, Orders.nb_AccountLockedTimeOrder,
          Orders.nb_DepositRequired, Orders.nb_CreditCardHoldTimeOrder, Orders.nb_CreditCardHoldTimeOrderReleased, Orders.nb_CreditCardTimeOrderReleased,
          Orders.nb_OverlimitTimeOrderReleased, Orders.nb_DepositRequiredReleased, Orders.t_DepositType, Orders.nb_AccountLockedTimeOrderReleased, Orders.t_AccountLockedTimeOrderReleasedBy,
          Orders.n_InspectionPics,
          contactAddress.t_ContactNameFull,
          Customers.t_CustCompany,
          EmployeesSales.t_UserName,
          projectAddress.t_ContactNameFull AS t_ProjectName,
          artAddress.t_ContactNameFull as t_ArtPersonName
          FROM Orders LEFT OUTER JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
          LEFT OUTER JOIN Addresses contactAddress ON Orders.kf_ContactID = contactAddress.kp_AddressID
          LEFT OUTER JOIN Employees EmployeesSales ON Orders.kf_EmployeeIDSales = EmployeesSales.kp_EmployeeID
          LEFT OUTER JOIN Addresses projectAddress ON Orders.kf_ContactIDProjectManager = projectAddress.kp_AddressID
          LEFT OUTER JOIN Addresses artAddress ON Orders.kf_ContactIDArtContact = artAddress.kp_AddressID
          WHERE Orders.kp_OrderID = ".$id." AND (Orders.t_TypeOfJobTicket = 'JobWithProductBuilds' OR Orders.t_TypeOfJobTicket = 'OldJob')
          GROUP BY Orders.kp_OrderID");

          return $query->result_array();
        }

    public function getOnPressOrders($date) {
      $query = $this->db->query("SELECT Orders.kp_OrderID,
        GROUP_CONCAT(DISTINCT OrderItems.t_OIStatus SEPARATOR ',') as OIDistinctStatuses,
        Orders.t_ServiceLevel,
        Customers.t_CustCompany,
        Addresses.t_ContactNameFull,
        Orders.t_JobName,
        Orders.t_JobStatus,
        Orders.d_JobDue,
        TIME_FORMAT(Orders.ti_JobDue, '%l:%i %p') AS ti_JobDue,
        Orders.d_PrintJobDue,
        Orders.ti_PrintJobDue,
        Orders.d_ProofDue,
        Orders.ti_ProofDue,
        SUBSTRING_INDEX(Orders.n_OrderItemCount, '.', 1) AS n_OrderItemCount,
        SUBSTRING_INDEX(Orders.n_OICSqFtSum, '.', 1) AS n_OICSqFtSum,
        Orders.nb_JobFinished,
        Orders.n_DurationTime,
        Orders.t_OrderItemAb,
        Orders.t_MachineAb,
        Orders.t_OrdShip,
        Orders.n_Complexity,
        Orders.nb_SureDate,
        Stat.n_SortOrder,
        Orders.n_TotalGrandTotal,
        EmployeesSales.t_UserName AS t_Sales,
        Orders.t_PersonWritingOrder,
        Orders.t_TypeOfOrder,
        Orders.kf_EmployeeIDSales,
        Orders.nb_PressProof,
        Orders.nb_PressProofApproved
        FROM Orders LEFT OUTER JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
        LEFT OUTER JOIN Statuses Stat ON Orders.t_JobStatus = Stat.t_StatusName
        LEFT OUTER JOIN Addresses ON Orders.kf_ContactID = Addresses.kp_AddressID
        LEFT OUTER JOIN Employees EmployeesSales ON Orders.kf_EmployeeIDSales = EmployeesSales.kp_EmployeeID
        LEFT OUTER JOIN OrderItems OI ON Orders.kp_OrderID = OI.kf_OrderID
        LEFT JOIN Statuses ON OI.t_OiStatus = Statuses.t_StatusName
        LEFT OUTER JOIN OrderItems ON Orders.kp_OrderID = OrderItems.kf_OrderID
        WHERE Orders.d_PrintJobDue = '".$date."' AND Orders.t_TypeOfJobTicket = 'JobWithProductBuilds' and isnull(Orders.nb_JobFinished) and (Stat.nb_Prepress = 1 or Statuses.nb_Prepress = 1)
        GROUP BY Orders.kp_OrderID
        ORDER BY Orders.kp_OrderID DESC");

        return $query->result_array();
    }

    public function getProofOrders($date) {
      $query = $this->db->query("SELECT Orders.kp_OrderID,
        GROUP_CONCAT(DISTINCT OrderItems.t_OIStatus SEPARATOR ',') as OIDistinctStatuses,
        Orders.t_ServiceLevel,
        Customers.t_CustCompany,
        Addresses.t_ContactNameFull,
        Orders.t_JobName,
        Orders.t_JobStatus,
        Orders.d_JobDue,
        TIME_FORMAT(Orders.ti_JobDue, '%l:%i %p') AS ti_JobDue,
        Orders.d_PrintJobDue,
        Orders.ti_PrintJobDue,
        Orders.d_ProofDue,
        Orders.ti_ProofDue,
        SUBSTRING_INDEX(Orders.n_OrderItemCount, '.', 1) AS n_OrderItemCount,
        SUBSTRING_INDEX(Orders.n_OICSqFtSum, '.', 1) AS n_OICSqFtSum,
        Orders.nb_JobFinished,
        Orders.n_DurationTime,
        Orders.t_OrderItemAb,
        Orders.t_MachineAb,
        Orders.t_OrdShip,
        Orders.n_Complexity,
        Orders.nb_SureDate,
        Stat.n_SortOrder,
        Orders.n_TotalGrandTotal,
        EmployeesSales.t_UserName AS t_Sales,
        Orders.t_PersonWritingOrder,
        Orders.t_TypeOfOrder,
        Orders.kf_EmployeeIDSales,
        Orders.nb_PressProof,
        Orders.nb_PressProofApproved
        FROM Orders LEFT OUTER JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
        LEFT OUTER JOIN Statuses Stat ON Orders.t_JobStatus = Stat.t_StatusName
        LEFT OUTER JOIN Addresses ON Orders.kf_ContactID = Addresses.kp_AddressID
        LEFT OUTER JOIN Employees EmployeesSales ON Orders.kf_EmployeeIDSales = EmployeesSales.kp_EmployeeID
        LEFT OUTER JOIN OrderItems OI ON Orders.kp_OrderID = OI.kf_OrderID
        LEFT JOIN Statuses ON OI.t_OiStatus = Statuses.t_StatusName
        LEFT OUTER JOIN OrderItems ON Orders.kp_OrderID = OrderItems.kf_OrderID
        WHERE Orders.d_ProofDue = '".$date."' AND Orders.t_TypeOfJobTicket = 'JobWithProductBuilds' and ISNULL(Orders.nb_JobFinished) and (Statuses.nb_UsedForProof = 1 OR Stat.nb_UsedForProof = 1)
        GROUP BY Orders.kp_OrderID
        ORDER BY Orders.kp_OrderID DESC");

        return $query->result_array();
    }

    public function getPickupOrders() {
      $query = $this->db->query("SELECT Orders.kp_OrderID,
        GROUP_CONCAT(DISTINCT OrderItems.t_OIStatus SEPARATOR ',') as OIDistinctStatuses,
        Orders.t_ServiceLevel,
        Customers.t_CustCompany,
        Addresses.t_ContactNameFull,
        Orders.t_JobName,
        Orders.t_JobStatus,
        Orders.d_JobDue,
        TIME_FORMAT(Orders.ti_JobDue, '%l:%i %p') AS ti_JobDue,
        Orders.d_PrintJobDue,
        Orders.ti_PrintJobDue,
        Orders.d_ProofDue,
        Orders.ti_ProofDue,
        SUBSTRING_INDEX(Orders.n_OrderItemCount, '.', 1) AS n_OrderItemCount,
        SUBSTRING_INDEX(Orders.n_OICSqFtSum, '.', 1) AS n_OICSqFtSum,
        Orders.nb_JobFinished,
        Orders.n_DurationTime,
        Orders.t_OrderItemAb,
        Orders.t_MachineAb,
        Orders.t_OrdShip,
        Orders.n_Complexity,
        Orders.nb_SureDate,
        Statuses.n_SortOrder,
        Orders.n_TotalGrandTotal,
        EmployeesSales.t_UserName AS t_Sales,
        Orders.t_PersonWritingOrder,
        Orders.t_TypeOfOrder,
        Orders.kf_EmployeeIDSales,
        Orders.nb_PressProof,
        Orders.nb_PressProofApproved
        FROM Orders LEFT OUTER JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
        LEFT OUTER JOIN Statuses ON Orders.t_JobStatus = Statuses.t_StatusName
        LEFT OUTER JOIN Addresses ON Orders.kf_ContactID = Addresses.kp_AddressID
        LEFT OUTER JOIN Employees EmployeesSales ON Orders.kf_EmployeeIDSales = EmployeesSales.kp_EmployeeID
        LEFT OUTER JOIN OrderItems OI ON Orders.kp_OrderID = OI.kf_OrderID AND OI.t_OiStatus = 'Ready to Pick Up'
        LEFT OUTER JOIN OrderItems ON Orders.kp_OrderID = OrderItems.kf_OrderID
        WHERE Orders.t_TypeOfJobTicket = 'JobWithProductBuilds' and Orders.nb_JobFinished = 1 and (Orders.t_JobStatus = 'Ready to Pick Up' OR (OrderItems.t_OiStatus = 'Ready to Pick Up' and Orders.t_JobStatus = 'Multi Line Status' ))
        GROUP BY Orders.kp_OrderID
        ORDER BY Orders.kp_OrderID DESC");

        return $query->result_array();
    }

    public function getHoldOrders() {
      $query = $this->db->query("SELECT Orders.kp_OrderID,
        GROUP_CONCAT(DISTINCT OrderItems.t_OIStatus SEPARATOR ',') as OIDistinctStatuses,
        Orders.t_ServiceLevel,
        Customers.t_CustCompany,
        Addresses.t_ContactNameFull,
        Orders.t_JobName,
        Orders.t_JobStatus,
        Orders.d_JobDue,
        TIME_FORMAT(Orders.ti_JobDue, '%l:%i %p') AS ti_JobDue,
        Orders.d_PrintJobDue,
        Orders.ti_PrintJobDue,
        Orders.d_ProofDue,
        Orders.ti_ProofDue,
        SUBSTRING_INDEX(Orders.n_OrderItemCount, '.', 1) AS n_OrderItemCount,
        SUBSTRING_INDEX(Orders.n_OICSqFtSum, '.', 1) AS n_OICSqFtSum,
        Orders.nb_JobFinished,
        Orders.n_DurationTime,
        Orders.t_OrderItemAb,
        Orders.t_MachineAb,
        Orders.t_OrdShip,
        Orders.n_Complexity,
        Orders.nb_SureDate,
        Stat.n_SortOrder,
        Orders.n_TotalGrandTotal,
        EmployeesSales.t_UserName AS t_Sales,
        Orders.t_PersonWritingOrder,
        Orders.t_TypeOfOrder,
        Orders.kf_EmployeeIDSales,
        Orders.nb_PressProof,
        Orders.nb_PressProofApproved
        FROM Orders LEFT OUTER JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
        LEFT OUTER JOIN Statuses Stat ON Orders.t_JobStatus = Stat.t_StatusName
        LEFT OUTER JOIN Addresses ON Orders.kf_ContactID = Addresses.kp_AddressID
        LEFT OUTER JOIN Employees EmployeesSales ON Orders.kf_EmployeeIDSales = EmployeesSales.kp_EmployeeID
        LEFT OUTER JOIN OrderItems OI ON Orders.kp_OrderID = OI.kf_OrderID
        LEFT JOIN Statuses ON OI.t_OiStatus = Statuses.t_StatusName
        LEFT OUTER JOIN OrderItems ON Orders.kp_OrderID = OrderItems.kf_OrderID
        WHERE Orders.t_TypeOfJobTicket = 'JobWithProductBuilds' and ISNULL(Orders.nb_JobFinished) and (Statuses.nb_UsedForHold = 1 OR Stat.nb_UsedForHold = 1)
        GROUP BY Orders.kp_OrderID
        ORDER BY Orders.kp_OrderID DESC");

        return $query->result_array();
    }

    public function getWOAOrders() {
      $query = $this->db->query("SELECT Orders.kp_OrderID,
        GROUP_CONCAT(DISTINCT OrderItems.t_OIStatus SEPARATOR ',') as OIDistinctStatuses,
        Orders.t_ServiceLevel,
        Customers.t_CustCompany,
        Addresses.t_ContactNameFull,
        Orders.t_JobName,
        Orders.t_JobStatus,
        Orders.d_JobDue,
        TIME_FORMAT(Orders.ti_JobDue, '%l:%i %p') AS ti_JobDue,
        Orders.d_PrintJobDue,
        Orders.ti_PrintJobDue,
        Orders.d_ProofDue,
        Orders.ti_ProofDue,
        SUBSTRING_INDEX(Orders.n_OrderItemCount, '.', 1) AS n_OrderItemCount,
        SUBSTRING_INDEX(Orders.n_OICSqFtSum, '.', 1) AS n_OICSqFtSum,
        Orders.nb_JobFinished,
        Orders.n_DurationTime,
        Orders.t_OrderItemAb,
        Orders.t_MachineAb,
        Orders.t_OrdShip,
        Orders.n_Complexity,
        Orders.nb_SureDate,
        Statuses.n_SortOrder,
        Orders.n_TotalGrandTotal,
        EmployeesSales.t_UserName AS t_Sales,
        Orders.t_PersonWritingOrder,
        Orders.t_TypeOfOrder,
        Orders.kf_EmployeeIDSales,
        Orders.nb_PressProof,
        Orders.nb_PressProofApproved
        FROM Orders LEFT OUTER JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
        LEFT OUTER JOIN Statuses ON Orders.t_JobStatus = Statuses.t_StatusName
        LEFT OUTER JOIN Addresses ON Orders.kf_ContactID = Addresses.kp_AddressID
        LEFT OUTER JOIN Employees EmployeesSales ON Orders.kf_EmployeeIDSales = EmployeesSales.kp_EmployeeID
        LEFT OUTER JOIN OrderItems OI ON Orders.kp_OrderID = OI.kf_OrderID AND OI.t_OiStatus = 'Waiting On Approval'
        LEFT OUTER JOIN OrderItems ON Orders.kp_OrderID = OrderItems.kf_OrderID
        WHERE Orders.t_TypeOfJobTicket = 'JobWithProductBuilds' and ISNULL(Orders.nb_JobFinished) and (Orders.t_JobStatus = 'Waiting on Approval' OR OrderItems.t_OiStatus = 'Waiting on Approval' or Orders.t_JobStatus = 'Press Proof WOA')
        GROUP BY Orders.kp_OrderID
        ORDER BY Orders.kp_OrderID DESC");

        return $query->result_array();
    }


    public function getMultiLineData() {
      $query = $this->db->query("SELECT Orders.kp_OrderID, Orders.kf_CustomerID, Customers.t_CustCompany, Orders.t_CustomerJobNum AS CusJobID,
        Orders.t_CustomerPO AS CustomerPO,
        DATE_FORMAT(Orders.d_Received, '%c-%e-%Y') AS DateReceived, DATE_FORMAT(Orders.d_JobDue, '%c-%e-%Y') AS JobDue,
        TIME_FORMAT(Orders.ti_JobDue, '%l:%i %p') AS TimeDue, Orders.t_JobName AS JobName, Orders.t_JobStatus AS OrderStatus,
        DATE_FORMAT(Orders.d_PrintJobDue, '%c-%e-%Y') AS OnPress, round(Orders.n_OrderItemCount, 0) AS TotalItems,
        sum( IF ( ( ( OrderItems.t_OiStatus = 'Hold - Waiting On Art' ) OR ( OrderItems.t_OiStatus = 'Hold - New Art' ) ), 1, 0 ) ) AS NeedArt,
        sum( IF ( ( ( OrderItems.t_OiStatus = 'Proof' ) OR ( OrderItems.t_OiStatus = 'Press Proof Setup' ) OR ( OrderItems.t_OiStatus = 'Press Proof Crop' )
              OR ( OrderItems.t_OiStatus = 'Press Proof Crop Check' ) OR ( OrderItems.t_OiStatus = 'Press Proof RTPrint' )
              OR ( OrderItems.t_OiStatus = 'Press Proof Finishing' ) ), 1, 0 ) ) AS Proof,
        sum( IF ( ( OrderItems.t_OiStatus = 'Waiting On Approval' ), 1, 0 ) ) AS WaitingonApproval,
        sum( IF ( ( ( OrderItems.t_OiStatus = 'Setup' ) OR ( OrderItems.t_OiStatus = 'Crop' )
              OR ( OrderItems.t_OiStatus = 'Crop Check' ) OR ( OrderItems.t_OiStatus = 'Art Received' ) ), 1, 0 ) ) AS Setup,
        sum( IF ( ( ( OrderItems.t_OiStatus = 'Ready to Print' ) OR ( OrderItems.t_OiStatus = 'Printing' ) OR ( OrderItems.t_OiStatus = 'Ready to Transfer' )
              OR ( OrderItems.t_OiStatus = 'Ready to Mount' ) ), 1, 0 ) ) AS ReadytoPrint,
        sum( IF ( ( OrderItems.t_OiStatus = 'Ready to Cut' ), 1, 0 ) ) AS ReadytoCut,
        sum(IF (((OrderItems.t_OiStatus = 'Ready to Lam')
          OR (OrderItems.t_OiStatus = 'Ready to Premask')
          OR (OrderItems.t_OiStatus = 'Finishing')
          OR (OrderItems.t_OiStatus = 'Finishing / Crop')
          OR (OrderItems.t_OiStatus = 'Finish / Crop 25%')
          OR (OrderItems.t_OiStatus = 'Finish / Crop 50%')
          OR (OrderItems.t_OiStatus = 'Finish / Crop 75%')),1,0)) AS Finishing,
        sum(IF (((OrderItems.t_OiStatus = 'Inspection')OR (OrderItems.t_OiStatus = 'Inspection - Hold')),1,0)) AS Inspection,
        sum(IF ((OrderItems.t_OiStatus = 'Ready to Pick Up'),1,0)) AS ReadytoPickup,
        sum(IF (((OrderItems.t_OiStatus = 'Ready to Ship')
          OR (OrderItems.t_OiStatus = 'Ready to Deliver')
          OR (OrderItems.t_OiStatus = 'Ready to Courier')),1,0)) AS ReadytoShip,
        sum(( CASE
          WHEN (OrderItems.t_OiStatus = 'Hold - In Sales') THEN 1
          WHEN (OrderItems.t_OiStatus = 'Hold - In Prepress') THEN 1
          WHEN (OrderItems.t_OiStatus = 'Hold - Accounting') THEN 1
          WHEN (OrderItems.t_OiStatus = 'Hold - Customer') THEN 1
          WHEN (OrderItems.t_OiStatus = 'Hold - Outsource') THEN 1
          WHEN (OrderItems.t_OiStatus = 'Hold - Material') THEN 1
          ELSE 0 END)) AS Hold,
        sum(( CASE
          WHEN (OrderItems.t_OiStatus = 'Shipped') THEN 1
          WHEN (OrderItems.t_OiStatus = 'Delivered') THEN 1
          ELSE 0 END)) AS Shipped,
        sum(IF ((OrderItems.t_OiStatus = 'Picked Up'),1,0)) AS PickedUp,
        sum(IF ((OrderItems.t_OiStatus = 'Cancelled'),1,0)) AS Cancelled
      FROM Orders JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
      JOIN OrderItems ON Orders.kp_OrderID = OrderItems.kf_OrderID
      WHERE Orders.t_TypeOfJobTicket = 'JobWithProductBuilds'
      AND Orders.t_JobStatus = 'Multi Line Status'
      AND (isnull(Orders.nb_JobFinished) OR Orders.nb_JobFinished = '0')
      GROUP BY Orders.kp_OrderID
      ORDER BY Orders.d_JobDue");

      return $query->result_array();
    }

    public function setCompleted($id) {
      $data = array('nb_JobFinished' => "1");
      $result = $this->db->update('Orders', $data, array('kp_OrderID'=>  $id));

      if(!$result) {
        return $this->db->_error_message();
      } else {
        return $this->db->affected_rows();
      }
    }

    public function setCompletedLog($id) {
      $data = array(
        'kf_OrderID' => $id,
        't_ApprovedByName' => "",
        't_StatusNew' => "Order Marked Complete",
        'zCreated'=> date("Y-m-d H:i:s", time())
      );

      $this->db->insert('StatusLog', $data);
    }

    public function getOrdersByCustomer($id) {
      $query = $this->db->query("SELECT Orders.kp_OrderID,
        Orders.t_ServiceLevel,
        Customers.t_CustCompany,
        Addresses.t_ContactNameFull,
        Orders.t_JobName,
        Orders.t_JobStatus,
        Orders.d_JobDue,
        TIME_FORMAT(Orders.ti_JobDue, '%l:%i %p') AS ti_JobDue,
        Orders.d_PrintJobDue,
        Orders.ti_PrintJobDue,
        Orders.d_ProofDue,
        Orders.ti_ProofDue,
        SUBSTRING_INDEX(Orders.n_OrderItemCount, '.', 1) AS n_OrderItemCount,
        SUBSTRING_INDEX(Orders.n_OICSqFtSum, '.', 1) AS n_OICSqFtSum,
        Orders.nb_JobFinished,
        Orders.n_DurationTime,
        Orders.t_OrderItemAb,
        Orders.t_MachineAb,
        Orders.t_OrdShip,
        Orders.n_Complexity,
        Orders.nb_SureDate,
        Statuses.n_SortOrder,
        Orders.n_TotalGrandTotal,
        EmployeesSales.t_UserName AS t_Sales,
        Orders.t_PersonWritingOrder,
        Orders.t_TypeOfOrder
        FROM Orders LEFT OUTER JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
        LEFT OUTER JOIN Statuses ON Orders.t_JobStatus = Statuses.t_StatusName
        LEFT OUTER JOIN Addresses ON Orders.kf_ContactID = Addresses.kp_AddressID
        LEFT OUTER JOIN Employees EmployeesSales ON Orders.kf_EmployeeIDSales = EmployeesSales.kp_EmployeeID
        WHERE Orders.kf_CustomerID = ".$id."
        GROUP BY Orders.kp_OrderID
        ORDER BY Orders.kp_OrderID DESC");

        return $query->result_array();
    }

    public function getOrderViewByID($id) {
      $query = $this->db->query("SELECT Orders.kp_OrderID, Orders.kf_ContactID, Orders.kf_CustomerID, Orders.kf_EmployeeIDCSR, Orders.kf_EmployeeIDSales, Orders.kf_EmployeeIDEstimator,
        Orders.kf_EmployeeIDNameOnEstimate, Orders.kf_EquipmentID, Orders.kf_EquipmentModeID, Orders.kf_MasterJobID, Orders.kf_RedoOriginalJobID,
        Orders.kf_SalesLeadID, Orders.kf_TemporaryReportID, Orders.t_ArtLocation, Orders.t_CreateArtSendProof, Orders.t_CreditCardTransaction, Orders.t_CustomerPO, Orders.t_CustomerJobNum, Orders.t_DataLog,
        Orders.t_Drawer, Orders.t_FlexibleDueDate, Orders.t_JobDescription, Orders.t_JobFinishedYN, Orders.t_JobInvoicedYN, Orders.t_JobName, Orders.t_JobStatus,
        Orders.t_Notes, Orders.t_NotesHidden, Orders.t_NotesInventoryNeeded, Orders.t_OrderPricingType, Orders.t_OriginalDataType, Orders.t_OverideCreditHold,
        Orders.t_OverideZeroCost, Orders.t_OverrideAfterCutOffBy, Orders.t_PersonWritingOrder, Orders.t_PMSColor, Orders.t_PressProofNotes, Orders.t_PressProofSize,
        Orders.t_PressProofType, Orders.t_ProofApprovalType, Orders.t_ProofApprovedBy, Orders.t_ProofsApprovedBy, Orders.t_ProofType, Orders.t_QBEditSequence, Orders.t_QBInvoiceNumber,
        Orders.t_QBListID, Orders.t_QBTxnID, Orders.t_RedoApprovedBy, Orders.t_RedoDepartment, Orders.t_RedoDescription, Orders.t_SchedSqftIncludeOrder, Orders.t_ScheduleApproval,
        Orders.t_SendProof, Orders.t_ServiceLevel, Orders.t_ServiceLevelApprovedBy, Orders.t_StatusBeforeParked, Orders.t_SupervisorScheduleNotes, Orders.t_TypeOfJobTicket,
        Orders.t_TypeOfOrder, Orders.t_WebAccessKey, Orders.d_Invoiced, Orders.d_JobDue, Orders.d_PrintJobDue, Orders.d_ProofDue, Orders.d_Received, Orders.d_SchedDay, Orders.d_TentativeApproval,
        Orders.d_TentativeJobDue, TIME_FORMAT(Orders.ti_JobDue, '%l:%i %p') AS ti_JobDue, TIME_FORMAT(Orders.ti_PrintJobDue, '%l:%i %p') AS ti_PrintJobDue, TIME_FORMAT(Orders.ti_ProofDue, '%l:%i %p') AS ti_ProofDue, Orders.ti_TentativeJobDue, Orders.ts_JobMarkedAsFinished,
        Orders.ts_OverrideAfterCutOff, Orders.ts_PostedToQuickbooks, Orders.ts_ProofApproved, Orders.ts_ProofsApproved, Orders.ts_ProofSent, Orders.ts_ScheduleApproval,
        Orders.ts_ServiceLevelApproved, Orders.ts_TrackNumberEmailSent, Orders.n_CommisionFactor, Orders.n_Damaged1Side, Orders.n_Damaged2SidesNonUsable, Orders.n_DashNumberStored,
        Orders.n_DepositAmount, Orders.n_EstimatedShipping, Orders.n_OICount, Orders.n_PercentCOMTotalOrderPrice, Orders.n_PressError1Side, Orders.n_SqFtTotal,
        Orders.n_SqFtTotalDS, Orders.n_TotalOrderPrice, Orders.nb_CustomerPOToBeDetermined, Orders.nb_DontCountInSchedule, Orders.nb_DueSaturday, Orders.nb_DueSunday,
        Orders.nb_EmailSentOrderConfirmation, Orders.nb_EmailSentTrackNumReadyToPickUp, Orders.nb_FollowUpCallMade, Orders.nb_Inactive, Orders.nb_IncompletePricing,
        Orders.nb_JobFinished, Orders.nb_MustPrintPackingList, Orders.nb_OrderLoggedIntoSystem, Orders.nb_OverideAfterCutOffTime, Orders.nb_PostedToQuickBooks,
        Orders.nb_ReceivedBeforeCutOffTime, Orders.nb_ScheduleNow, Orders.nb_UseTotalOrderPricing, Orders.zCreated, Orders.zCreatedBy, Orders.zModified, Orders.zModifiedBy,
        Orders.nb_CreditHoldOveride, Orders.nb_CreditHoldTimeOrder, Orders.t_CreditHoldType, Orders.t_CreditHoldTypeOveride, Orders.t_CreditHoldOverideNote, Orders.t_CreditHoldReleasedBy,
        Orders.ts_CreditHoldReleased, Orders.n_TotalOrderItemPrice, Orders.n_TotalOtherCharges, Orders.n_TotalSubOrderItemOtherCharges, Orders.n_TotalShippingCharges, Orders.n_TotalSubtotal,
        Orders.n_TotalTax, Orders.n_TotalGrandTotal, Orders.nb_TotalChargeTax, Orders.n_TotalTaxRate, Orders.t_OrdShip, Orders.n_OICSqFtSum, Orders.n_OrderItemCount, Orders.n_Complexity,
        Orders.t_OrderItemAb, Orders.t_MachineAb, Orders.n_DurationTime, Orders.nb_SureDate, Orders.kf_ContactIDProjectManager, Orders.kf_ContactIDArtContact, Orders.kf_CustomerIDProjectManager,
        Orders.kf_OrderRedoID, Orders.t_NotesHTML, Orders.nb_QrcodeGeneratedOI, Orders.nb_OrderStepsComplete, Orders.nb_OverlimitTimeOrder, Orders.nb_AccountLockedTimeOrder,
        Orders.nb_DepositRequired, Orders.nb_CreditCardHoldTimeOrder, Orders.nb_CreditCardHoldTimeOrderReleased, Orders.nb_CreditCardTimeOrderReleased,
        Orders.nb_OverlimitTimeOrderReleased, Orders.nb_DepositRequiredReleased, Orders.t_DepositType, Orders.nb_AccountLockedTimeOrderReleased, Orders.t_AccountLockedTimeOrderReleasedBy,
        Orders.n_InspectionPics, Orders.kf_PackingListOrderShipID, Orders.nb_Proof, Orders.t_ProofStatus, Orders.d_ProofDue, Orders.nb_PressProof, Orders.nb_PressProofApproved,
        contactAddress.t_ContactNameFull,
        Customers.t_CustCompany,
        EmployeesSales.t_UserName,
        projectAddress.t_ContactNameFull AS t_ProjectName,
        artAddress.t_ContactNameFull as t_ArtPersonName
        FROM Orders LEFT OUTER JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
        LEFT OUTER JOIN Addresses contactAddress ON Orders.kf_ContactID = contactAddress.kp_AddressID
        LEFT OUTER JOIN Employees EmployeesSales ON Orders.kf_EmployeeIDSales = EmployeesSales.kp_EmployeeID
        LEFT OUTER JOIN Addresses projectAddress ON Orders.kf_ContactIDProjectManager = projectAddress.kp_AddressID
        LEFT OUTER JOIN Addresses artAddress ON Orders.kf_ContactIDArtContact = artAddress.kp_AddressID
        LEFT OUTER JOIN OrderItems ON Orders.kp_OrderID = OrderItems.kf_OrderID
        WHERE Orders.kp_OrderID = ".$id."
        GROUP BY Orders.kp_OrderID");

      return $query->row_array();
    }

    public function getActiveFinishedOrders($date) {
      $query = $this->db->query("SELECT Orders.kp_OrderID,
        GROUP_CONCAT(DISTINCT OrderItems.t_OIStatus SEPARATOR ',') as OIDistinctStatuses,
        Orders.t_ServiceLevel,
        Customers.t_CustCompany,
        Addresses.t_ContactNameFull,
        Orders.t_JobName,
        Orders.t_JobStatus,
        Orders.d_JobDue,
        TIME_FORMAT(Orders.ti_JobDue, '%l:%i %p') AS ti_JobDue,
        Orders.d_PrintJobDue,
        Orders.ti_PrintJobDue,
        Orders.d_ProofDue,
        Orders.ti_ProofDue,
        SUBSTRING_INDEX(Orders.n_OrderItemCount, '.', 1) AS n_OrderItemCount,
        SUBSTRING_INDEX(Orders.n_OICSqFtSum, '.', 1) AS n_OICSqFtSum,
        Orders.nb_JobFinished,
        Orders.n_DurationTime,
        Orders.t_OrderItemAb,
        Orders.t_MachineAb,
        Orders.t_OrdShip,
        Orders.n_Complexity,
        Orders.nb_SureDate,
        Statuses.n_SortOrder,
        Orders.n_TotalGrandTotal,
        EmployeesSales.t_UserName AS t_Sales,
        Orders.t_PersonWritingOrder,
        Orders.t_TypeOfOrder,
        Orders.kf_EmployeeIDSales,
        Orders.nb_PressProof,
        Orders.nb_PressProofApproved
        FROM Orders LEFT OUTER JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
        LEFT OUTER JOIN Statuses ON Orders.t_JobStatus = Statuses.t_StatusName
        LEFT OUTER JOIN Addresses ON Orders.kf_ContactID = Addresses.kp_AddressID
        LEFT OUTER JOIN Employees EmployeesSales ON Orders.kf_EmployeeIDSales = EmployeesSales.kp_EmployeeID
        JOIN OrderItems ON Orders.kp_OrderID = OrderItems.kf_OrderID
        WHERE Orders.d_JobDue = '".$date."' AND Orders.t_TypeOfJobTicket = 'JobWithProductBuilds' and Orders.nb_JobFinished = 1
        GROUP BY Orders.kp_OrderID
        ORDER BY Orders.kp_OrderID DESC");

        return $query->result_array();
    }

    public function getActiveNotFinishedOrdersSign($type) {
      $date = date("Y-m-d");
      $query = $this->db->query("SELECT Orders.kp_OrderID,
      GROUP_CONCAT(DISTINCT OrderItems.t_OIStatus SEPARATOR ',') as OIDistinctStatuses,
      Orders.t_ServiceLevel,
      Customers.t_CustCompany,
      Addresses.t_ContactNameFull,
      Orders.t_JobName,
      Orders.t_JobStatus,
      Orders.d_JobDue,
      TIME_FORMAT(Orders.ti_JobDue, '%l:%i %p') AS ti_JobDue,
      Orders.d_PrintJobDue,
      Orders.ti_PrintJobDue,
      Orders.d_ProofDue,
      Orders.ti_ProofDue,
      SUBSTRING_INDEX(Orders.n_OrderItemCount, '.', 1) AS n_OrderItemCount,
      SUBSTRING_INDEX(Orders.n_OICSqFtSum, '.', 1) AS n_OICSqFtSum,
      Orders.nb_JobFinished,
      Orders.n_DurationTime,
      Orders.t_OrderItemAb,
      Orders.t_MachineAb,
      Orders.t_OrdShip,
      Orders.n_Complexity,
      Orders.nb_SureDate,
      Statuses.n_SortOrder,
      Orders.n_TotalGrandTotal,
      EmployeesSales.t_UserName AS t_Sales,
      Orders.t_PersonWritingOrder,
      Orders.t_TypeOfOrder,
      Orders.kf_EmployeeIDSales,
      Orders.nb_PressProof,
      Orders.nb_PressProofApproved
      FROM Orders LEFT OUTER JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
      LEFT OUTER JOIN Statuses ON Orders.t_JobStatus = Statuses.t_StatusName
      LEFT OUTER JOIN Addresses ON Orders.kf_ContactID = Addresses.kp_AddressID
      LEFT OUTER JOIN Employees EmployeesSales ON Orders.kf_EmployeeIDSales = EmployeesSales.kp_EmployeeID
      JOIN OrderItems ON Orders.kp_OrderID = OrderItems.kf_OrderID
      WHERE Orders.t_TypeOfJobTicket = 'JobWithProductBuilds' and ISNULL(Orders.nb_JobFinished) and (Orders.t_JobStatus = 'Inspection' or Orders.t_JobStatus = 'Multi Line Status')
            and Orders.d_JobDue = '$date'
      GROUP BY Orders.kp_OrderID
      ORDER BY Orders.kp_OrderID DESC");

      return $query->result_array();
    }

    public function getActiveNotFinishedOrders($date) {
      $query = $this->db->query("SELECT Orders.kp_OrderID,
      GROUP_CONCAT(DISTINCT OrderItems.t_OIStatus SEPARATOR ',') as OIDistinctStatuses,
      Orders.t_ServiceLevel,
      Customers.t_CustCompany,
      Addresses.t_ContactNameFull,
      Orders.t_JobName,
      Orders.t_JobStatus,
      Orders.d_JobDue,
      TIME_FORMAT(Orders.ti_JobDue, '%l:%i %p') AS ti_JobDue,
      Orders.d_PrintJobDue,
      Orders.ti_PrintJobDue,
      Orders.d_ProofDue,
      Orders.ti_ProofDue,
      SUBSTRING_INDEX(Orders.n_OrderItemCount, '.', 1) AS n_OrderItemCount,
      SUBSTRING_INDEX(Orders.n_OICSqFtSum, '.', 1) AS n_OICSqFtSum,
      Orders.nb_JobFinished,
      Orders.n_DurationTime,
      Orders.t_OrderItemAb,
      Orders.t_MachineAb,
      Orders.t_OrdShip,
      Orders.n_Complexity,
      Orders.nb_SureDate,
      Statuses.n_SortOrder,
      Orders.n_TotalGrandTotal,
      EmployeesSales.t_UserName AS t_Sales,
      Orders.t_PersonWritingOrder,
      Orders.t_TypeOfOrder,
      Orders.kf_EmployeeIDSales,
      Orders.nb_PressProof,
      Orders.nb_PressProofApproved
      FROM Orders LEFT OUTER JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
      LEFT OUTER JOIN Statuses ON Orders.t_JobStatus = Statuses.t_StatusName
      LEFT OUTER JOIN Addresses ON Orders.kf_ContactID = Addresses.kp_AddressID
      LEFT OUTER JOIN Employees EmployeesSales ON Orders.kf_EmployeeIDSales = EmployeesSales.kp_EmployeeID
      JOIN OrderItems ON Orders.kp_OrderID = OrderItems.kf_OrderID
      WHERE Orders.d_JobDue = '".$date."' AND Orders.t_TypeOfJobTicket = 'JobWithProductBuilds' and ISNULL(Orders.nb_JobFinished) and Orders.t_JobStatus != 'Press Proof WOA'
      GROUP BY Orders.kp_OrderID
      ORDER BY Orders.kp_OrderID DESC");

      return $query->result_array();
    }

    public function getOrdersDue($startDate, $endDate) {
      $query = $this->db->query("SELECT
        DATE_FORMAT(Orders.d_JobDue, '%a %c/%e') AS `d_JobDue`,
        DATE_FORMAT(Orders.d_JobDue, '%c/%e/%Y') AS `date`,
        count(Orders.kp_OrderID) AS `Total_Orders`,
        sum(Orders.n_OrderItemCount) AS `OiCount`,
        sum(Orders.n_OICSqFtSum) AS `SqFt`
        FROM
        Orders
        WHERE
        (Orders.d_JobDue >= '".$startDate."' and Orders.d_JobDue <= '".$endDate."' )

        AND
        Orders.t_TypeOfJobTicket = 'JobWithProductBuilds'

        AND (
          isnull(Orders.nb_JobFinished)
          OR (Orders.nb_JobFinished = 0)
        )

        GROUP BY
        Orders.d_JobDue
        ORDER BY
        Orders.d_JobDue");

      return $query->result_array();
    }

    public function getOrdersReceived($startDate, $endDate) {
      $query = $this->db->query("SELECT
        DATE_FORMAT(Orders.d_Received, '%a %c/%e') AS `d_Received`,
        DATE_FORMAT(Orders.d_Received, '%Y-%c-%e') AS `date`,
        count(Orders.kp_OrderID) AS `Total_Orders`,
        sum(Orders.n_OrderItemCount) AS `OiCount`,
        sum(Orders.n_OICSqFtSum) AS `SqFt`
        FROM
        Orders
        WHERE
        (Orders.d_Received >= '".$startDate."' and Orders.d_Received <= '".$endDate."' )

        AND
        Orders.t_TypeOfJobTicket = 'JobWithProductBuilds'

        GROUP BY
        Orders.d_Received
        ORDER BY
        Orders.d_Received");

        return $query->result_array();
      }

    public function getActiveOrders() {
      $query = $this->db->query("SELECT Orders.kp_OrderID
                                FROM Orders
                                WHERE Orders.nb_JobFinished = 0 or Orders.nb_JobFinished is null");

      return $query->result_array();
    }
    public function prepressTvData()
    {
        $query =  $this->db->query("SELECT
                            CASE
                            WHEN Orders.t_TypeOfJobTicket = 'JobWithProductBuilds' THEN 'Orders'
                                    WHEN Orders.t_TypeOfJobTicket = 'InternalOrderForm' THEN 'In Process'
                        END AS 'Ticket',
                            Count(Orders.kp_OrderID) AS Orders,
                            Round(Sum(Orders.n_OrderItemCount)) AS Items,
                            Round(Sum(Orders.n_OICSqFtSum)) AS SqFt
                    FROM Orders
                    WHERE Orders.d_Received = DATE(NOW()) and (Orders.t_TypeOfJobTicket = 'JobWithProductBuilds' or Orders.t_TypeOfJobTicket = 'InternalOrderForm')
                    GROUP BY Orders.t_TypeOfJobTicket
                    ORDER BY Ticket Desc");


        return $query->result_array();

    }

    public function prepressTvData2()
    {
        $query =  $this->db->query("
            SELECT
            DATE_FORMAT(tbl.Date,'%W %m-%d') as Date,
            sum(tbl.Orders) as Orders,
            sum(tbl.Items) as Items,
            sum(tbl.SqFt) as SqFt

            FROM
            (SELECT Orders.d_PrintJobDue AS Date,
                    Count(Orders.kp_OrderID) AS Orders,
                    Round(Sum(Orders.n_OrderItemCount)) AS Items,
                    Round(Sum(Orders.n_OICSqFtSum)) AS SqFt
            FROM Orders INNER JOIN Statuses OdrStatus ON Orders.t_JobStatus = OdrStatus.t_StatusName
            WHERE Orders.d_PrintJobDue >= DATE(NOW()) and OdrStatus.nb_Prepress = 1
            GROUP BY Orders.d_PrintJobDue

            UNION

            SELECT Orders.d_PrintJobDue AS Date,
                    Count(DISTINCT Orders.kp_OrderID) AS Orders,
                    Round(Sum(DISTINCT Orders.n_OrderItemCount)) AS Items,
                    Round(Sum(DISTINCT Orders.n_OICSqFtSum)) AS SqFt
            FROM Orders INNER JOIN OrderItems ON Orders.kp_OrderID = OrderItems.kf_OrderID
                     INNER JOIN Statuses ON OrderItems.t_OiStatus = Statuses.t_StatusName
            WHERE Orders.d_PrintJobDue >= DATE(NOW()) and Statuses.nb_Prepress = 1
            GROUP BY Orders.d_PrintJobDue) tbl

            GROUP BY tbl.date");


        return $query->result_array();

    }

    public function getIndividualOrderInvoiceDataByID($orderID)
    {
        $query = $this->db->query("SELECT Orders.kp_OrderID as TxnID,
                                    Customers.t_QBCustID AS CustomerRef_ListID,
                                    '520000-1073589325' as ARAccountRef_ListID,
                                    '70000-1073917491' as TemplateRef_ListID,
                                    DATE_FORMAT(Orders.d_JobDue, '%c-%e-%Y') as TxnDate,
                                    Orders.d_JobDue as TxnDateDB,
                                    Orders.kp_OrderID as RefNumber,
                                    Addresses.t_CompanyName AS BillAddress_Addr1,
                                    Addresses.t_ContactNameFull AS BillAddress_Addr2,
                                    Addresses.t_Address1 AS BillAddress_Addr3,
                                    Addresses.t_Address2 AS BillAddress_Addr4,
                                    Addresses.t_City AS BillAddress_City,
                                    Addresses.t_StateOrProvince AS BillAddress_State,
                                    Addresses.t_PostalCode AS BillAddress_PostalCode,
                                    Addresses.t_Country AS BillAddress_Country,
                                    OrderShip.t_RecipientCompany AS ShipAddress_Addr1,
                                    OrderShip.t_RecipientContact AS ShipAddress_Addr2,
                                    OrderShip.t_RecipientAddress1 AS ShipAddress_Addr3,
                                    OrderShip.t_RecipientAddress2 AS ShipAddress_Addr4,
                                    OrderShip.t_RecipientCity AS ShipAddress_City,
                                    OrderShip.t_RecipientState AS ShipAddress_State,
                                    OrderShip.t_RecipientPostalCode AS ShipAddress_PostalCode,
                                    OrderShip.t_RecipientCountry AS ShipAddress_Country,
                                    Orders.t_CustomerPO AS PONumber,
                                    Orders.nb_IncompletePricing,
                                    Orders.nb_JobFinished,
                                    Orders.nb_PostedToQuickBooks,
                                    Orders.nb_UseTotalOrderPricing,
                                    Orders.n_TotalOrderPrice,
                                    Orders.t_TypeOfOrder,
                                    Orders.nb_ManualShippingComplete,
                                    quickbooks_sql2.customer.SalesRepRef_FullName,
                                    quickbooks_sql2.customer.TermsRef_FullName,
                                    quickbooks_sql2.customer.SalesTaxCodeRef_FullName,
                                    quickbooks_sql2.customer.ItemSalesTaxRef_FullName,
                                    Orders.t_JobName as Memo,
                                    Orders.t_JobStatus as Status,
                                    Orders.nb_CustomerPOToBeDetermined,
                                    sum(ShipperService.nb_ShipPricedManually) as ManShip,
                                    Customers.t_QBInvoiceSendMethod,
                                    GROUP_CONCAT(COALESCE(OrderShip.nb_ShippingException, 'NULL') SEPARATOR ',') as GroupShippingException,
                                    GROUP_CONCAT(COALESCE(OrderShip.nb_ShippingExceptionCompleted, 'NULL') SEPARATOR ',') as GroupShippingExceptionCompleted,
                                    GROUP_CONCAT(DISTINCT OrderShip.t_ShippingExceptionReason SEPARATOR ', ') as ShippingExceptionReasons
                            FROM Orders LEFT JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
                                        LEFT JOIN quickbooks_sql2.customer ON Customers.t_QBCustID = quickbooks_sql2.customer.ListID
                                        LEFT JOIN Addresses ON Orders.kf_CustomerID = Addresses.kf_OtherID AND Addresses.kf_TypeMain = 'customer' AND Addresses.kf_TypeSub = 'BillTo'
                                        LEFT JOIN OrderShip ON Orders.kp_OrderID = OrderShip.kf_OrderID
                                        LEFT JOIN ShipperService ON OrderShip.kf_ShipperServiceID = ShipperService.kp_ShipperServiceID
                            WHERE Orders.kp_OrderID = $orderID");

        return $query->row_array();

    }

    public function getOrderInvoiceTblDataByID($orderID)
    {
        $query = $this->db->query("SELECT Orders.kp_OrderID as TxnID,
                                    Customers.t_QBCustID AS CustomerRef_ListID,
                                    '520000-1073589325' as ARAccountRef_ListID,
                                    '70000-1073917491' as TemplateRef_ListID,
                                    DATE_FORMAT(Orders.d_JobDue, '%c-%e-%Y') as TxnDate,
                                    Orders.d_JobDue as TxnDateDB,
                                    Orders.kp_OrderID as RefNumber,
                                    Addresses.t_CompanyName AS BillAddress_Addr1,
                                    Addresses.t_ContactNameFull AS BillAddress_Addr2,
                                    Addresses.t_Address1 AS BillAddress_Addr3,
                                    Addresses.t_Address2 AS BillAddress_Addr4,
                                    Addresses.t_City AS BillAddress_City,
                                    Addresses.t_StateOrProvince AS BillAddress_State,
                                    Addresses.t_PostalCode AS BillAddress_PostalCode,
                                    Addresses.t_Country AS BillAddress_Country,
                                    OrderShip.t_RecipientCompany AS ShipAddress_Addr1,
                                    OrderShip.t_RecipientContact AS ShipAddress_Addr2,
                                    OrderShip.t_RecipientAddress1 AS ShipAddress_Addr3,
                                    OrderShip.t_RecipientAddress2 AS ShipAddress_Addr4,
                                    OrderShip.t_RecipientCity AS ShipAddress_City,
                                    OrderShip.t_RecipientState AS ShipAddress_State,
                                    OrderShip.t_RecipientPostalCode AS ShipAddress_PostalCode,
                                    OrderShip.t_RecipientCountry AS ShipAddress_Country,
                                    Orders.t_CustomerPO AS PONumber,
                                    Orders.nb_IncompletePricing,
                                    Orders.nb_JobFinished,
                                    Orders.nb_PostedToQuickBooks,
                                    Orders.nb_UseTotalOrderPricing,
                                    Orders.n_TotalOrderPrice,
                                    Orders.t_TypeOfOrder,
                                    Orders.nb_ManualShippingComplete,
                                    quickbooks_sql2.customer.SalesRepRef_FullName,
                                    quickbooks_sql2.customer.TermsRef_FullName,
                                    quickbooks_sql2.customer.SalesTaxCodeRef_FullName,
                                    quickbooks_sql2.customer.ItemSalesTaxRef_FullName,
                                    Orders.t_JobName as Memo,
                                    Orders.t_JobStatus as Status,
                                    Orders.nb_CustomerPOToBeDetermined,
                                    sum(ShipperService.nb_ShipPricedManually) as ManShip,
                                    Customers.t_QBInvoiceSendMethod,
                                    GROUP_CONCAT(COALESCE(OrderShip.nb_ShippingException, 'NULL') SEPARATOR ',') as GroupShippingException,
                                    GROUP_CONCAT(COALESCE(OrderShip.nb_ShippingExceptionCompleted, 'NULL') SEPARATOR ',') as GroupShippingExceptionCompleted,
                                    GROUP_CONCAT(DISTINCT OrderShip.t_ShippingExceptionReason SEPARATOR ', ') as ShippingExceptionReasons
                            FROM Orders LEFT JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
                                        LEFT JOIN quickbooks_sql2.customer ON Customers.t_QBCustID = quickbooks_sql2.customer.ListID
                                        LEFT JOIN Addresses ON Orders.kf_CustomerID = Addresses.kf_OtherID AND Addresses.kf_TypeMain = 'customer' AND Addresses.kf_TypeSub = 'BillTo'
                                        LEFT JOIN OrderShip ON Orders.kp_OrderID = OrderShip.kf_OrderID
                                        LEFT JOIN ShipperService ON OrderShip.kf_ShipperServiceID = ShipperService.kp_ShipperServiceID
                            WHERE Orders.kp_OrderID = $orderID");

        return $query->row_array();

    }

    public function getOrderInvoiceTblData()
    {
        $query = $this->db->query("SELECT Orders.kp_OrderID as TxnID,
                                    GROUP_CONCAT(CONCAT_WS(':', OrderShip.kf_ShipperID, COALESCE(OrderShipTracking.t_TrackingID, 'NULL')) SEPARATOR ',') as TrackingCheck,
                                    Customers.t_QBCustID AS CustomerRef_ListID,
                                    '520000-1073589325' as ARAccountRef_ListID,
                                    '70000-1073917491' as TemplateRef_ListID,
                                    Orders.d_JobDue as TxnDateDB,
                                    DATE_FORMAT(Orders.d_JobDue, '%c-%e-%Y') as TxnDate,
                                    Orders.kp_OrderID as RefNumber,
                                    Addresses.t_CompanyName AS BillAddress_Addr1,
                                    Addresses.t_ContactNameFull AS BillAddress_Addr2,
                                    Addresses.t_Address1 AS BillAddress_Addr3,
                                    Addresses.t_Address2 AS BillAddress_Addr4,
                                    Addresses.t_City AS BillAddress_City,
                                    Addresses.t_StateOrProvince AS BillAddress_State,
                                    Addresses.t_PostalCode AS BillAddress_PostalCode,
                                    Addresses.t_Country AS BillAddress_Country,
                                    OrderShip.t_RecipientCompany AS ShipAddress_Addr1,
                                    OrderShip.t_RecipientContact AS ShipAddress_Addr2,
                                    OrderShip.t_RecipientAddress1 AS ShipAddress_Addr3,
                                    OrderShip.t_RecipientAddress2 AS ShipAddress_Addr4,
                                    OrderShip.t_RecipientCity AS ShipAddress_City,
                                    OrderShip.t_RecipientState AS ShipAddress_State,
                                    OrderShip.t_RecipientPostalCode AS ShipAddress_PostalCode,
                                    OrderShip.t_RecipientCountry AS ShipAddress_Country,
                                    Orders.t_CustomerPO AS PONumber,
                                    Orders.t_OrdShip,
                                    Orders.nb_IncompletePricing,
                                    Orders.nb_JobFinished,
                                    Orders.nb_PostedToQuickBooks,
                                    Orders.nb_UseTotalOrderPricing,
                                    Orders.n_TotalOrderPrice,
                                    Orders.t_TypeOfOrder,
                                    Orders.nb_ManualShippingComplete,
                                    Orders.t_JobName as Memo,
                                    Orders.t_JobStatus as Status,
                                    Orders.nb_CustomerPOToBeDetermined,
                                    sum(ShipperService.nb_ShipPricedManually) as ManShip,
                                    Customers.t_QBInvoiceSendMethod,
                                    GROUP_CONCAT(DISTINCT ShipperService.nb_ShipPricedManually SEPARATOR ', ') as AllShipPricedManually,
                                    GROUP_CONCAT(COALESCE(OrderShip.nb_ShippingException, 'NULL') SEPARATOR ',') as GroupShippingException,
                                    GROUP_CONCAT(COALESCE(OrderShip.nb_ShippingExceptionCompleted, 'NULL') SEPARATOR ',') as GroupShippingExceptionCompleted
                            FROM Orders LEFT JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
                                        LEFT JOIN Addresses ON Orders.kf_CustomerID = Addresses.kf_OtherID AND Addresses.kf_TypeMain = 'customer' AND Addresses.kf_TypeSub = 'BillTo'
                                        LEFT JOIN OrderShip ON Orders.kp_OrderID = OrderShip.kf_OrderID
                                        LEFT JOIN OrderShipTracking ON OrderShipTracking.kf_OrderShipID = OrderShip.kp_OrderShipID
                                        LEFT JOIN ShipperService ON OrderShip.kf_ShipperServiceID = ShipperService.kp_ShipperServiceID
                            WHERE (ISNULL(Orders.nb_PostedToQuickBooks) ||  Orders.nb_PostedToQuickBooks = 0) and Orders.t_TypeOfJobTicket = 'JobWithProductBuilds' and Orders.nb_JobFinished = 1
                            GROUP BY Orders.kp_OrderID");

        return $query->result_array();

    }

    public function getOrderInvoiceCCTblData() {
        $query = $this->db->query("SELECT Orders.kp_OrderID as TxnID,
                                    Customers.t_QBCustID AS CustomerRef_ListID,
                                    '520000-1073589325' as ARAccountRef_ListID,
                                    '70000-1073917491' as TemplateRef_ListID,
                                    Orders.d_JobDue as TxnDateDB,
                                    DATE_FORMAT(Orders.d_JobDue, '%c-%e-%Y') as TxnDate,
                                    Orders.kp_OrderID as RefNumber,
                                    Addresses.t_CompanyName AS BillAddress_Addr1,
                                    Addresses.t_ContactNameFull AS BillAddress_Addr2,
                                    Addresses.t_Address1 AS BillAddress_Addr3,
                                    Addresses.t_Address2 AS BillAddress_Addr4,
                                    Addresses.t_City AS BillAddress_City,
                                    Addresses.t_StateOrProvince AS BillAddress_State,
                                    Addresses.t_PostalCode AS BillAddress_PostalCode,
                                    Addresses.t_Country AS BillAddress_Country,
                                    OrderShip.t_RecipientCompany AS ShipAddress_Addr1,
                                    OrderShip.t_RecipientContact AS ShipAddress_Addr2,
                                    OrderShip.t_RecipientAddress1 AS ShipAddress_Addr3,
                                    OrderShip.t_RecipientAddress2 AS ShipAddress_Addr4,
                                    OrderShip.t_RecipientCity AS ShipAddress_City,
                                    OrderShip.t_RecipientState AS ShipAddress_State,
                                    OrderShip.t_RecipientPostalCode AS ShipAddress_PostalCode,
                                    OrderShip.t_RecipientCountry AS ShipAddress_Country,
                                    OrderShip.kf_ShipperID,
                                    OrderShip.kf_ShipperServiceID,
                                    OrderShipTracking.t_TrackingID,
                                    OrderShip.kp_OrderShipID,
                                    Orders.t_CustomerPO AS PONumber,
                                    Orders.t_OrdShip,
                                    Orders.nb_IncompletePricing,
                                    Orders.nb_JobFinished,
                                    Orders.nb_PostedToQuickBooks,
                                    Orders.nb_UseTotalOrderPricing,
                                    Orders.n_TotalOrderPrice,
                                    Orders.t_TypeOfOrder,
                                    Orders.t_JobName as Memo,
                                    Orders.t_JobStatus as Status,
                                    Orders.nb_CustomerPOToBeDetermined,
                                    sum(ShipperService.nb_ShipPricedManually) as ManShip,
                                    Customers.t_QBInvoiceSendMethod
                            FROM Orders LEFT JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
                                        LEFT JOIN Addresses ON Orders.kf_CustomerID = Addresses.kf_OtherID AND Addresses.kf_TypeMain = 'customer' AND Addresses.kf_TypeSub = 'BillTo'
                                        LEFT JOIN OrderShip ON Orders.kp_OrderID = OrderShip.kf_OrderID
                                        LEFT JOIN OrderShipTracking ON OrderShipTracking.kf_OrderShipID = OrderShip.kp_OrderShipID
                                        LEFT JOIN ShipperService ON OrderShip.kf_ShipperServiceID = ShipperService.kp_ShipperServiceID
                            WHERE Orders.t_TypeOfJobTicket = 'JobWithProductBuilds' and Orders.nb_JobFinished = 1 and Orders.nb_ProcessCreditCard = 1 and Orders.nb_PostedToQuickBooks = 1
                            GROUP BY Orders.kp_OrderID");

        return $query->result_array();
    }

    public function getOrderFromMonthYearDateRecived($year=null,$month=null) {
        if(empty($month)) {
            $query = $this->db->query("SELECT kp_OrderID FROM basetables2.Orders WHERE d_Received is not null and YEAR(d_Received)='$year'");
        } else {
            $query = $this->db->query("SELECT kp_OrderID FROM basetables2.Orders WHERE d_Received is not null and YEAR(d_Received)='$year' and MONTH(d_Received)='$month' order by kp_OrderID asc");
        }
          return $query->result_array();
    }

    public function checkPhysicalPathOFQrCodeImage($orderID,$dateReceived) {
        if(file_exists(realpath(APPPATH . '../../../images/.am_i_mounted'))&& !is_null($dateReceived)) {
            $dateOrderReceivedArr       = explode("-", $dateReceived);
            $yearOrder                  = $dateOrderReceivedArr[0];
            $monthOrder                 = $dateOrderReceivedArr[1];
            $path                       = $this->QRCodeImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/qrcode/qr-code.png';

             if(!file_exists($path)) {
                 $this->createQRCodeFromOrderID($orderID, $dateReceived);
             }
        }
    }

    public function reCreateQRCode($orderID) {
        $orderIDAry   = $this->getOrderByOrderID($orderID);
        $dateReceived = $orderIDAry->d_Received;

        if(file_exists(realpath(APPPATH . '../../../images/.am_i_mounted'))&& !is_null($dateReceived)) {
            $dateOrderReceivedArr       = explode("-", $dateReceived);
            $yearOrder                  = $dateOrderReceivedArr[0];
            $monthOrder                 = $dateOrderReceivedArr[1];

            // checks and creates Month and Year Folder
            if(!is_dir($this->QRCodeImagePath.'/'.$yearOrder.'/'.$monthOrder)) {
                if(!mkdir($this->QRCodeImagePath.'/'.$yearOrder.'/'.$monthOrder,0777,TRUE)) {
                    die("Failed to create Year and Month Folders");
                }
            }


            // checks and creates the Order Folder
            if(!is_dir($this->QRCodeImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID)) { // checks if the order# has a folder or not
                if(!mkdir($this->QRCodeImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID,0777,TRUE)) {
                    die('Failed to create Order and other folders...');
                } else {
                     //change the directory owner/group permission for OrderItemID folder
                    chmod($this->QRCodeImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID, 0777);
                }
            }
            $path              = $this->QRCodeImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/qrcode';

           // checks and creates the qrcode Folder
            if(!is_dir($path)) { // checks if the qrcode is a folder or not
                if(!mkdir($path,0777,TRUE)) {
                    die('Failed to create Order and other folders...');
                } else {
                     //change the directory owner/group permission for OrderItemID folder
                    chmod($path, 0777);
                }
            }

            if(file_exists($path."/qr-code.png"))
            {
                $msg = unlink($path."/qr-code.png"); // remove any image files.

            }
            $params['cacheable']    = false;
            $params['cacheable']    = 0;
            $params['cacheable']    = False;

            $params['data']         = $orderID;
            $params['level']        = 'H';
            $params['size']         = 25;
            $params['savename']     = $path."/qr-code.png";
            $this->ciqrcode->generate($params);
        }




    }
    public function createQRCodeFromOrderID($orderID,$dateReceived) {
        $this->load->library('ciqrcode');

        if(file_exists(realpath(APPPATH . '../../../images/.am_i_mounted'))&& !is_null($dateReceived)) {
            $dateOrderReceivedArr       = explode("-", $dateReceived);

            $yearOrder                  = $dateOrderReceivedArr[0];

            $monthOrder                 = $dateOrderReceivedArr[1];

            // checks and creates Month and Year Folder
            if(!is_dir($this->QRCodeImagePath.'/'.$yearOrder.'/'.$monthOrder)) {
                if(!mkdir($this->QRCodeImagePath.'/'.$yearOrder.'/'.$monthOrder,0777,TRUE)) {
                    die("Failed to create Year and Month Folders");
                }

            }

            // checks and creates the Order Folder
            if(!is_dir($this->QRCodeImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID)) { // checks if the order# has a folder or not
                if(!mkdir($this->QRCodeImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID,0777,TRUE)) {
                    die('Failed to create Order and other folders...');
                } else {
                     //change the directory owner/group permission for OrderItemID folder
                    chmod($this->QRCodeImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID, 0777);
                }
            }
            $path              = $this->QRCodeImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/qrcode';

           // checks and creates the qrcode Folder
            if(!is_dir($path)) { // checks if the qrcode is a folder or not
                if(!mkdir($path,0777,TRUE)) {
                    die('Failed to create Order and other folders...');
                } else {
                     //change the directory owner/group permission for OrderItemID folder
                    chmod($path, 0777);
                }
            }
            $params['cacheable']    = false;
            $params['cacheable']    = 0;
            $params['cacheable']    = False;

            $params['data']         = $orderID;
            $params['level']        = 'H';
            $params['size']         = 25;
            $params['savename']     = $path."/qr-code.png";
            $this->ciqrcode->generate($params);
        }




    }

    public function orderPricingQuickBook($orderID) {
        $this->db->select('ifnull(nb_IncompletePricing,\'\') as incompletePricing,
                           ifnull(nb_UseTotalOrderPricing,\'\') as useTotalOrderPricing,
                           ifnull(ROUND(n_TotalOrderPrice,2),\'\') as totalOrderPrice ,
                           ifnull(nb_PostedToQuickbooks,\'\') as postedToQuickbooks',false)
                 ->from('Orders')
                 ->where('kp_OrderID',$orderID);

         $query = $this->db->get();

         return $query->row_array();

    }

    public function getOrderByOrderID($OrderID) {
        $query = $this->db->query("SELECT * FROM Orders  WHERE kp_OrderID ='$OrderID'");
        return $query->row();
    }

    public function getOrderRelatedInfoForOrderView($orderID) {
        $query = $this->db->query("SELECT Orders.kp_OrderID, Orders.kf_ContactID, Orders.kf_CustomerID, Orders.kf_EmployeeIDCSR, Orders.kf_EmployeeIDSales,
                        Orders.kf_EmployeeIDEstimator, Orders.kf_EmployeeIDNameOnEstimate, Orders.kf_EquipmentID, Orders.kf_EquipmentModeID,
                        Orders.kf_MasterJobID, Orders.kf_RedoOriginalJobID, Orders.kf_SalesLeadID, Orders.kf_TemporaryReportID,
                        Orders.t_ArtLocation, Orders.t_CreateArtSendProof, Orders.t_CreditCardTransaction, Orders.t_CustomerPO,
                        Orders.t_CustomerJobNum, Orders.t_DataLog, Orders.t_Drawer, Orders.t_FlexibleDueDate, Orders.t_JobDescription,
                        Orders.t_JobFinishedYN, Orders.t_JobInvoicedYN, Orders.t_JobName, Orders.t_JobStatus, Orders.t_Notes,
                        Orders.t_NotesHidden, Orders.t_NotesInventoryNeeded, Orders.t_OrderPricingType, Orders.t_OriginalDataType, Orders.t_OverideCreditHold,
                        Orders.t_OverideZeroCost, Orders.t_OverrideAfterCutOffBy, Orders.t_PersonWritingOrder, Orders.t_PMSColor, Orders.t_PressProofNotes,
                        Orders.t_PressProofSize, Orders.t_PressProofType, Orders.t_ProofApprovalType, Orders.t_ProofApprovedBy, Orders.t_ProofsApprovedBy,
                        Orders.t_ProofType, Orders.t_QBEditSequence, Orders.t_QBInvoiceNumber, Orders.t_QBListID, Orders.t_QBTxnID,
                        Orders.t_RedoApprovedBy, Orders.t_RedoDepartment, Orders.t_RedoDescription, Orders.t_SchedSqftIncludeOrder, Orders.t_ScheduleApproval,
                        Orders.t_SendProof, Orders.t_ServiceLevel, Orders.t_ServiceLevelApprovedBy, Orders.t_StatusBeforeParked, Orders.t_SupervisorScheduleNotes,
                        Orders.t_TypeOfJobTicket, Orders.t_TypeOfOrder, Orders.t_WebAccessKey, Orders.d_Invoiced, Orders.d_JobDue,
                        Orders.d_PrintJobDue, Orders.d_ProofDue, Orders.d_Received, Orders.d_SchedDay, Orders.d_TentativeApproval,
                        Orders.d_TentativeJobDue, Orders.ti_JobDue, Orders.ti_PrintJobDue, Orders.ti_ProofDue, Orders.ti_TentativeJobDue,
                        Orders.ts_JobMarkedAsFinished, Orders.ts_OverrideAfterCutOff, Orders.ts_PostedToQuickbooks, Orders.ts_ProofApproved, Orders.ts_ProofsApproved,
                        Orders.ts_ProofSent, Orders.ts_ScheduleApproval, Orders.ts_ServiceLevelApproved, Orders.ts_TrackNumberEmailSent, Orders.n_CommisionFactor,
                        Orders.n_Damaged1Side, Orders.n_Damaged2SidesNonUsable, Orders.n_DashNumberStored, Orders.n_DepositAmount, Orders.n_EstimatedShipping,
                        Orders.n_OICount, Orders.n_PercentCOMTotalOrderPrice, Orders.n_PressError1Side, Orders.n_SqFtTotal, Orders.n_SqFtTotalDS, Orders.n_TotalOrderPrice,
                        Orders.nb_CustomerPOToBeDetermined, Orders.nb_DontCountInSchedule, Orders.nb_DueSaturday, Orders.nb_DueSunday, Orders.nb_EmailSentOrderConfirmation,
                        Orders.nb_EmailSentTrackNumReadyToPickUp, Orders.nb_FollowUpCallMade, Orders.nb_Inactive, Orders.nb_IncompletePricing, Orders.nb_JobFinished,
                        Orders.nb_MustPrintPackingList, Orders.nb_OrderLoggedIntoSystem, Orders.nb_OverideAfterCutOffTime, Orders.nb_PostedToQuickBooks, Orders.nb_ReceivedBeforeCutOffTime,
                        Orders.nb_ScheduleNow, Orders.nb_UseTotalOrderPricing, Orders.zCreated, Orders.zCreatedBy, Orders.zModified, Orders.zModifiedBy,
                        Orders.nb_CreditHoldOveride, Orders.nb_CreditHoldTimeOrder, Orders.t_CreditHoldType, Orders.t_CreditHoldTypeOveride, Orders.t_CreditHoldOverideNote,
                        Orders.t_CreditHoldReleasedBy, Orders.ts_CreditHoldReleased, Orders.n_TotalOrderItemPrice, Orders.n_TotalOtherCharges, Orders.t_OrdShip,
                        Orders.n_OICSqFtSum, Orders.n_OrderItemCount, Orders.n_Complexity, Orders.t_OrderItemAb, Orders.t_MachineAb, Orders.n_DurationTime,
                        Orders.nb_SureDate, Orders.kf_ContactIDProjectManager, Orders.kf_ContactIDArtContact, Orders.kf_CustomerIDProjectManager,
                        Orders.kf_OrderRedoID, Orders.t_NotesHTML, Orders.nb_QrcodeGeneratedOI,
                        OContact.t_ContactNameFull,
                        Sales.t_UserName AS t_SalesPerson,
                        ProjectManager.t_ContactNameFull AS t_ProjectManager,
                        ArtContact.t_ContactNameFull AS t_ArtContact,
                        MasterJobs.t_Name as t_MasterJobName,
                        Customers.t_CustCompany
                FROM Orders LEFT OUTER JOIN Addresses OContact ON Orders.kf_ContactID = OContact.kp_AddressID
                         LEFT OUTER JOIN Employees Sales ON Orders.kf_EmployeeIDSales = Sales.kp_EmployeeID
                         LEFT OUTER JOIN Addresses ProjectManager ON Orders.kf_ContactIDProjectManager = ProjectManager.kp_AddressID
                         LEFT OUTER JOIN Addresses ArtContact ON Orders.kf_ContactIDArtContact = ArtContact.kp_AddressID
                         LEFT OUTER JOIN MasterJobs ON Orders.kf_MasterJobID = MasterJobs.kp_MasterJobID
                         LEFT JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
                WHERE Orders.kp_OrderID = '$orderID'");
        return $query->row();

    }
    public function getCreditReleaseData($orderID)
    {
         $this->db
                ->select('Orders.kp_OrderID, Customers.t_CustCompany, Addresses.t_ContactNameFull, Orders.nb_CreditHoldTimeOrder,
                        Orders.t_CreditHoldType, Orders.nb_CreditHoldOveride, Orders.t_CreditHoldTypeOveride, Orders.t_CreditHoldReleasedBy,
                        Orders.t_CreditHoldOverideNote, Orders.ts_CreditHoldReleased')
                  ->from('Orders')
                  ->join('Customers', ' Orders.kf_CustomerID = Customers.kp_CustomerID','left')
                  ->join('Addresses','Orders.kf_ContactID = Addresses.kp_AddressID ','left')
                 ->where('Addresses.kf_TypeMain',"Customer")
                 ->where('kf_TypeSub',"Contact")
                 ->where('kp_OrderID',$orderID);

         $query = $this->db->get();

         return $query->row_array();
    }

    public function orderJobStatus($orderID)
    {
        $this->db
                ->select('kp_OrderID,t_JobStatus')
                 ->from('Orders')
                 ->where('kp_OrderID',$orderID);

        $query = $this->db->get();

        return $query->row_array();

    }
    public function updateOrderStatus($jobStatus,$orderID)
    {
         $data = array(
            't_JobStatus'=> $jobStatus
             );
         $this->db->update('Orders', $data, array('kp_OrderID'=>  $orderID));
    }
    public function orderJobStatusCompanyName($orderID)
    {
         $where ="((isnull(Orders.nb_JobFinished)) || (Orders.nb_JobFinished = 0))  ";

         $this->db
                ->select('Orders.kp_OrderID,t_CustCompany,ifnull(Orders.d_JobDue,\'No Date Given:\') as d_JobDue,
                          ifnull(Orders.t_JobStatus,\'\') as t_JobStatus,Orders.t_JobName,Statuses.n_SortOrder,
                          ifnull(DATE_FORMAT(Orders.ti_JobDue,\'%l:%i %p\'),\'\') as ti_JobDue ,Orders.t_MachineAb,Orders.t_OrderItemAb,
                          Orders.t_OrdShip,Orders.n_OrderItemCount,n_OICSqFtSum,n_DurationTime,n_Complexity',false)
                 ->from('Orders')
                 ->join('Customers','Orders.kf_CustomerID = Customers.kp_CustomerID','left')
                 ->join('Statuses', 'Orders.t_JobStatus = Statuses.t_StatusName','left')
                 ->where('kp_OrderID',$orderID)
                 ->where($where);
         $query = $this->db->get();

        return $query->row_array();

    }

    public function orderJobStatusCompanyNameDueDate($date=null) {
        $where ="((isnull(Orders.nb_JobFinished)) || (Orders.nb_JobFinished = 0))  ";
        $this->db->select('Orders.kp_OrderID,t_CustCompany,Orders.d_JobDue,
                          ifnull(Orders.t_JobStatus,\'No Status Name Given:\') as t_JobStatus,Orders.t_JobName,Statuses.n_SortOrder,
                          DATE_FORMAT(Orders.ti_JobDue,\'%l:%i %p\') as ti_JobDue ,Orders.t_MachineAb,Orders.t_OrderItemAb,
                          Orders.t_OrdShip,Orders.n_OrderItemCount',false)
                 ->from('Orders')
                 ->join('Customers','Orders.kf_CustomerID = Customers.kp_CustomerID','left')
                 ->join('Statuses', 'Orders.t_JobStatus = Statuses.t_StatusName','left')
                 ->where('d_JobDue',$date)
                 ->where($where)
                 ->order_by("Statuses.n_SortOrder ", "asc");


        $query = $this->db->get();

        return $query->result_array();

    }

    public function getOrderByID($orderID) {
        $query = $this->db->get_where('Orders', array('kp_OrderID' => $orderID));
        return $query->row_array();
    }

    public function insertOrderData($data=null) {
         $this->db->insert('Orders', $data);
         return $this->db->insert_id();
    }

    public function updateOrderTbl($data,$orderID) {
         $result = $this->db->update('Orders', $data, array('kp_OrderID'=>  $orderID));
         if(!$result) {
             return $this->db->_error_message();
         } else {
             return $this->db->affected_rows();
         }
    }

    public function deleteOrderDataFromOrderID($orderID) {
        /*
        * Any non-digit character will be excluded after passing $id
        * from intval function. This is done for security reason.
        */
        $kp_OrderID = intval( $orderID );

        // below delete operation generates this query DELETE FROM users WHERE id = $id
        $this->db->delete( 'Orders', array( 'kp_OrderID' => $kp_OrderID ) );

    }

    public function countOrderInspectionImg($data) {

        $fullPath                   = $this->webRootPath.'/'.'inspection_pics'.'/'.$data['shortOrderNum'].'/'.$data['orderID'];

        $orderID                    = $data['orderID'];

        if(file_exists($fullPath)) {
            $files              = scandir( $fullPath);
            $filesArry          = array_diff($files,array('.','..','.DS_Store','.AppleDouble'));
            $updateOrderData['n_InspectionPics']  = sizeof($filesArry);

            $result = $this->updateOrderTbl($updateOrderData,$orderID);
        } else {
            $updateOrderData['n_InspectionPics']  = null;
            $result                               = $this->updateOrderTbl($updateOrderData,$orderID);
        }

        return $result;
    }

    public function getOrderInfoFromJobDueDate($orderJobDueDate) {
        $query = $this->db->get_where('Orders', array('d_JobDue' => $orderJobDueDate));

        return $query->result_array();
    }


}
?>
