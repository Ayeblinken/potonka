<?php

class OrderItemModel extends CI_Model
{
    var $imageUploadPath;
    //put your code here
    public function __construct()
    {
        //Server image path
        $this->imageUploadPath = realpath(APPPATH . '../../../images/Orders');

        $this->loadImagePath   = 'https://'.$_SERVER['SERVER_NAME'].'/images/Orders';

        $this->load->library('image_lib');
    }

    public function orderItemEmailTrackingInfoDataFromOrderID($orderID) {
         $query = $this->db->query("SELECT  OrderItems.kp_OrderItemID as ID,
                                           ProductBuilds.t_QBListID as ItemRef_ListID,
                                           CONCAT(TRUNCATE((OrderItems.n_HeightInInches/12),0),\"' \", TRIM(TRAILING '.' FROM TRIM(TRAILING '0' from ((TRUNCATE((OrderItems.n_HeightInInches),3))-(TRUNCATE((OrderItems.n_HeightInInches/12),0))*12))), '\" H x ',
                                           TRUNCATE((OrderItems.n_WidthInInches/12),0),\"' \", TRIM(TRAILING '.' FROM TRIM(TRAILING '0' from ((TRUNCATE((OrderItems.n_WidthInInches),3))-(TRUNCATE((OrderItems.n_WidthInInches/12),0))*12))), '\" W', ' - ' ,
                                           ProductBuilds.t_Category,' ', '>', ' ', ifnull(Concat(ProductBuilds.t_Name, ',', ' ', newTable1.BuildItemName), ProductBuilds.t_Name), ' - ', OrderItems.t_Description, ' ' , ifnull(OrderItems.t_Structure,'')) as Description,
                                           OrderItems.n_Quantity AS Quantity,
                                           ProductBuilds.t_Category,
                                           if(OrderItems.t_Pricing = \"SQ.FT. Pricing\",
                                           ROUND(((n_HeightInInches*n_WidthInInches)/144)*OrderItems.n_Price,2),
                                           ROUND(OrderItems.n_Price,2)) AS Rate,
                                           ROUND(OrderItems.n_Quantity * (if(OrderItems.t_Pricing = \"SQ.FT. Pricing\",
                                           ROUND(((n_HeightInInches*n_WidthInInches)/144)*OrderItems.n_Price,2),
                                           ROUND(OrderItems.n_Price, 2))),2) as Amount
                                    FROM basetables2.OrderItems
                                    INNER JOIN ProductBuilds ON ProductBuilds.kp_ProductBuildID = OrderItems.kf_ProductBuildID
                                    LEFT JOIN
                                    (
                                        SELECT OrderItemComponents.kf_OrderItemID,GROUP_CONCAT(DISTINCT BuildItems.t_Name SEPARATOR ', ') as BuildItemName
                                        FROM basetables2.OrderItemComponents
                                        INNER JOIN BuildItems
                                        ON OrderItemComponents.kf_BuildItemID = BuildItems.kp_BuildItemID
                                        WHERE OrderItemComponents.kf_OrderID = '$orderID' and  OrderItemComponents.nb_ShowOnInvoice = 1
                                        GROUP BY OrderItemComponents.kf_OrderItemID
                                    ) as newTable1
                                    ON OrderItems.kp_OrderItemID =newTable1.kf_OrderItemID
                                    WHERE OrderItems.kf_OrderID = '$orderID' and (OrderItems.nb_DoNotInvoice is null || OrderItems.nb_DoNotInvoice = 0)
                                    ORDER BY OrderItems.n_DashNum ASC");
        return $query->result_array();
    }

    public function insertOrderItem($data) {
      $this->db->insert('OrderItems', $data);
      return $this->db->insert_id();
    }

    public function insertOrderItemComponent($data) {
      $this->db->insert('OrderItemComponents', $data);
      return $this->db->insert_id();
    }

    public function updateOrderItem($id, $data) {
      $this->db->where('kp_OrderItemID', $id);
      $this->db->update('OrderItems', $data);
    }

    public function updateOrder($id, $data) {
      $this->db->where('kp_OrderID', $id);
      $this->db->update('Orders', $data);
    }

    public function getOrderItemComponents($orderItemID) {
      $query = $this->db->query("SELECT *
                                  FROM OrderItemComponents
                                  WHERE OrderItemComponents.kf_OrderItemID = " . $orderItemID);

      return $query->result_array();
    }

    public function addOrderItemTemplates($customerID, $productBuildID) {
      $query = $this->db->query("SELECT OrderItems.kp_OrderItemID,
                                  	OrderItems.kf_ProductBuildID,
                                  	'Customer Template' as t_Type,
                                  	ProductBuilds.t_Name as t_ProductBuildName,
                                  	OrderItems.t_TemplateName,
                                  	OrderItems.kf_CustomerID
                                  FROM OrderItems INNER JOIN ProductBuilds ON OrderItems.kf_ProductBuildID = ProductBuilds.kp_ProductBuildID
                                  WHERE OrderItems.nb_Template = 1 and OrderItems.kf_CustomerID = " . $customerID . " and OrderItems.kf_ProductBuildID = " . $productBuildID . "
                                  -- Update OrderItems.kf_CustomerID = Current kf_CustomerID and OrderItems.kf_ProductBuildID = Current kf_ProductBuildID

                                  Union

                                  SELECT OrderItems.kp_OrderItemID,
                                  	OrderItems.kf_ProductBuildID,
                                  	'Reference Guide' as t_Type,
                                  	ProductBuilds.t_Name as t_ProductBuildName,
                                  	OrderItems.t_TemplateName,
                                  	OrderItems.kf_CustomerID
                                  FROM OrderItems INNER JOIN ProductBuilds ON OrderItems.kf_ProductBuildID = ProductBuilds.kp_ProductBuildID
                                  WHERE OrderItems.nb_Template = 1 and OrderItems.kf_CustomerID = 2877 and OrderItems.kf_ProductBuildID = " . $productBuildID . "
                                  -- This query OrderItems.kf_CustomerID = 2877 is always 2877 the customer is 'Refernce Guide'
                                  -- Update OrderItems.kf_ProductBuildID");

      return $query->result_array();
    }



    public function getCustomerTemplates($id) {
      $query = $this->db->query("SELECT OrderItems.kp_OrderItemID,
        OrderItems.kf_ProductBuildID,
        OrderItems.t_TemplateName,
        ProductBuilds.t_Category,
        ProductBuilds.t_Name,
        OrderItems.n_Quantity,
        OrderItems.n_HeightInInches,
        OrderItems.n_WidthInInches,
        OrderItems.t_Pricing,
        OrderItems.n_Price
        FROM OrderItems INNER JOIN ProductBuilds ON OrderItems.kf_ProductBuildID = ProductBuilds.kp_ProductBuildID
        WHERE OrderItems.kf_CustomerID = " . $id . " and OrderItems.nb_Template = 1");

      return $query->result_array();
    }

    public function updatePackingQuantity($id, $data) {
      $this->db->where('kp_OrderItemID', $id);
      $this->db->update('OrderItems', $data);
    }

    public function getOrderItemsStatusForOrder($id) {
      $query = $this->db->query("SELECT t_OIStatus FROM basetables2.OrderItems WHERE kf_OrderID=".$id);

      return $query->result_array();
    }

    public function orderItemInvoiceDataFromOrderID($orderID)
    {
        $query = $this->db->query(" SELECT  OrderItems.kp_OrderItemID as ID,
                                           ProductBuilds.t_QBListID as ItemRef_ListID,
                                           CONCAT(TRUNCATE((OrderItems.n_HeightInInches/12),0),\"' \", TRIM(TRAILING '.' FROM TRIM(TRAILING '0' from ((TRUNCATE((OrderItems.n_HeightInInches),3))-(TRUNCATE((OrderItems.n_HeightInInches/12),0))*12))), '\" H x ',
                                           TRUNCATE((OrderItems.n_WidthInInches/12),0),\"' \", TRIM(TRAILING '.' FROM TRIM(TRAILING '0' from ((TRUNCATE((OrderItems.n_WidthInInches),3))-(TRUNCATE((OrderItems.n_WidthInInches/12),0))*12))), '\" W', ' - ' ,
                                           ProductBuilds.t_Category,' ', '>', ' ', ifnull(Concat(ProductBuilds.t_Name, ',', ' ', newTable1.BuildItemName), ProductBuilds.t_Name), ' - ', OrderItems.t_Description, ' ' , ifnull(OrderItems.t_Structure,'')) as Description,
                                           OrderItems.n_Quantity AS Quantity,
                                           if(OrderItems.t_Pricing = \"SQ.FT. Pricing\",
                                           ROUND(((n_HeightInInches*n_WidthInInches)/144)*OrderItems.n_Price,2),
                                           ROUND(OrderItems.n_Price,2)) AS Rate,
                                           ROUND(OrderItems.n_Quantity * (if(OrderItems.t_Pricing = \"SQ.FT. Pricing\",
                                           ROUND(((n_HeightInInches*n_WidthInInches)/144)*OrderItems.n_Price,2),
                                           ROUND(OrderItems.n_Price, 2))),2) as Amount,
                                           quickbooks_sql2.itemservice.SalesTaxCodeRef_FullName as Taxable
                                    FROM basetables2.OrderItems
                                    INNER JOIN ProductBuilds ON ProductBuilds.kp_ProductBuildID = OrderItems.kf_ProductBuildID
                                            LEFT Join quickbooks_sql2.itemservice ON ProductBuilds.t_QBListID = quickbooks_sql2.itemservice.ListID
                                    LEFT JOIN
                                    (
                                        SELECT OrderItemComponents.kf_OrderItemID,GROUP_CONCAT(DISTINCT BuildItems.t_Name SEPARATOR ', ') as BuildItemName
                                        FROM basetables2.OrderItemComponents
                                        INNER JOIN BuildItems
                                        ON OrderItemComponents.kf_BuildItemID = BuildItems.kp_BuildItemID
                                        WHERE OrderItemComponents.kf_OrderID = '$orderID' and  OrderItemComponents.nb_ShowOnInvoice = 1
                                        GROUP BY OrderItemComponents.kf_OrderItemID
                                    ) as newTable1
                                    ON OrderItems.kp_OrderItemID =newTable1.kf_OrderItemID
                                    WHERE OrderItems.kf_OrderID = '$orderID' and (OrderItems.nb_DoNotInvoice is null || OrderItems.nb_DoNotInvoice = 0)
                                    ORDER BY OrderItems.n_DashNum ASC");
        return $query->result_array();

    }
    public function orderItemTableData($orderItemID)
    {
        $this->db
                 ->select('kp_OrderItemID,t_Description, t_Structure,
                     kf_OrderID,kf_CustomerID,n_Quantity,n_HeightInInches,n_WidthInInches,n_Price,
                     t_SportJobNumber,t_SportItemNumber,t_SportLocationNumber')
                 ->from('OrderItems')
                 ->where('kp_OrderItemID',$orderItemID);

         $query = $this->db->get();

         return $query->row_array();

    }
    public function oldOrderItemInvoiceDetails($orderID)
    {
        $orderItemComponentShowInvoice = 1;

        $query = $this->db->query("SELECT  OrderItems.kp_OrderItemID AS ID, OrderItems.nb_DoNotInvoice AS doNotInvoice,
            concat(CAST(OrderItems.kf_OrderID AS char),'-',CAST(OrderItems.n_DashNum AS char)) as orderIDDashNum,
            OrderItems.n_Quantity AS Qty,
            Concat(ProductBuilds.t_Category,' ', '>',' ', ProductBuilds.t_Name,' ',' ', ifnull(newTable1.BuildItemName,'')) as product,
            OrderItems.t_Description AS description,
            OrderItems.t_Pricing AS pricingType,
			 if(OrderItems.t_Pricing = \"SQ.FT. Pricing\",
			  ROUND(((n_HeightInInches*n_WidthInInches)/144)*OrderItems.n_Price,2),
			  ROUND(OrderItems.n_Price,2)) AS price,
                          ROUND(OrderItems.n_Quantity * (if(OrderItems.t_Pricing = \"SQ.FT. Pricing\",
			  ROUND(
                                ((n_HeightInInches*n_WidthInInches)/144)*OrderItems.n_Price
                             ,2),
			  ROUND(
                                 OrderItems.n_Price,
                              2)
                               )),2) as total
        FROM basetables2.OrderItems
        INNER JOIN ProductBuilds ON ProductBuilds.kp_ProductBuildID = OrderItems.kf_ProductBuildID
        LEFT JOIN
        (
            SELECT OrderItemComponents.kf_OrderItemID,GROUP_CONCAT(DISTINCT BuildItems.t_Name) as BuildItemName
            FROM basetables2.OrderItemComponents
            INNER JOIN BuildItems
            ON OrderItemComponents.kf_BuildItemID = BuildItems.kp_BuildItemID
            WHERE OrderItemComponents.kf_OrderID = '$orderID' and  OrderItemComponents.nb_ShowOnInvoice = '$orderItemComponentShowInvoice'
            GROUP BY OrderItemComponents.kf_OrderItemID
        ) as newTable1
        ON OrderItems.kp_OrderItemID =newTable1.kf_OrderItemID
        WHERE OrderItems.kf_OrderID = '$orderID'");

        return $query->result_array();

    }
    public function orderItemInvoiceDetails($orderID)
    {
        $orderItemComponentShowInvoice = 1;

        $query = $this->db->query("SELECT  OrderItems.kp_OrderItemID AS ID, OrderItems.nb_DoNotInvoice AS doNotInvoice,
            concat(CAST(OrderItems.kf_OrderID AS char),'-',CAST(OrderItems.n_DashNum AS char)) as orderIDDashNum,
            OrderItems.n_Quantity AS Qty,
            Concat(ProductBuilds.t_Category,' ', '>', ' ', ProductBuilds.t_Name,', ', ifnull(newTable1.BuildItemName,'')) as product,
            OrderItems.t_Description AS description,
            OrderItems.t_Pricing AS pricingType,
			if(OrderItems.t_Pricing = \"SQ.FT. Pricing\",
			  ROUND(((n_HeightInInches*n_WidthInInches)/144)*OrderItems.n_Price,2),
			  ROUND(OrderItems.n_Price,2)) AS price,
              ROUND(OrderItems.n_Quantity * (if(OrderItems.t_Pricing = \"SQ.FT. Pricing\",
			  ROUND(((n_HeightInInches*n_WidthInInches)/144)*OrderItems.n_Price,2),
			  ROUND(OrderItems.n_Price, 2))),2) as total
        FROM basetables2.OrderItems
        INNER JOIN ProductBuilds ON ProductBuilds.kp_ProductBuildID = OrderItems.kf_ProductBuildID
        LEFT JOIN
        (
            SELECT OrderItemComponents.kf_OrderItemID,GROUP_CONCAT(DISTINCT BuildItems.t_Name SEPARATOR ', ') as BuildItemName
            FROM basetables2.OrderItemComponents
            INNER JOIN BuildItems
            ON OrderItemComponents.kf_BuildItemID = BuildItems.kp_BuildItemID
            WHERE OrderItemComponents.kf_OrderID = '$orderID' and  OrderItemComponents.nb_ShowOnInvoice = '$orderItemComponentShowInvoice'
            GROUP BY OrderItemComponents.kf_OrderItemID
        ) as newTable1
        ON OrderItems.kp_OrderItemID =newTable1.kf_OrderItemID
        WHERE OrderItems.kf_OrderID = '$orderID'");

        return $query->result_array();


    }
    public function orderItemJobStatus($orderItemID)
    {
        $this->db
                ->select('kp_OrderItemID,kf_OrderID,ifnull(t_OiStatus,\'\') as orderItemJobStatus,n_DashNum',false)
                 ->from('OrderItems')
                 ->where('kp_OrderItemID',$orderItemID);

        $query = $this->db->get();

        return $query->row_array();


    }
    public function updateAllOrderItemStatus($jobStatus,$orderID)
    {
         $data = array(
            't_OiStatus'=> $jobStatus
             );
         $this->db->update('OrderItems', $data, array('kf_OrderID'=>  $orderID));

    }
    public function updateOrderItemStatus($jobStatus,$orderItemID)
    {
        $data = array(
            't_OiStatus'=> $jobStatus
             );
         $this->db->update('OrderItems', $data, array('kp_OrderItemID'=>  $orderItemID));

    }
    public function updateOrderItemTable($orderItemID,$data)
    {
        $this->db->where('kp_OrderItemID', $orderItemID);
        $this->db->update('OrderItems',$data);

    }
    public function getOrderItemFromOrderItemID($orderItemID)
    {
         $query = $this->db->get_where('OrderItems',array('kp_OrderItemID'=>$orderItemID));

         return $query->row_array();

    }
    public function getOrderItemByID($orderItemID)
    {
         $query = $this->db->get_where('OrderItems',array('kp_OrderItemID'=>$orderItemID));

         return $query->result_array();

    }
    public function getOrderItemByOrderID($orderID)
    {
         $query = $this->db->get_where('OrderItems',array('kf_OrderID'=>$orderID));

         return $query->result_array();

    }
    public function getCustomerTemplateListData($customerID)
    {
        $this->datatables->select('OrderItems.t_TemplateName,
                                   concat(ProductBuilds.t_Category," ",ProductBuilds.t_Name) as productCategoryName,
                                   OrderItems.n_Quantity,
                                   concat(TRIM(TRAILING "." FROM(ifnull(CAST(TRIM(TRAILING "0" FROM OrderItems.n_HeightInInches)as CHAR),"")))
                                        ," H x ",TRIM(TRAILING "." FROM(ifnull(CAST(TRIM(TRAILING "0" FROM OrderItems.n_WidthInInches)as CHAR),"")))," W") as size,
                                   OrderItems.t_Pricing,
                                   concat("$",TRUNCATE(ifnull(OrderItems.n_Price,0),2)) as price,
                                   ProductBuilds.t_Category,
                                   ProductBuilds.t_Name,
                                   OrderItems.kp_OrderItemID
'                                ,false)
                        ->add_column('Edit', '<a class="editBtn" href="orderItemUpSideFrm/template/$1">Edit</a>','OrderItems.kp_OrderItemID')
                        ->from('OrderItems')
                        ->join('ProductBuilds', 'OrderItems.kf_ProductBuildID = ProductBuilds.kp_ProductBuildID','inner')


                        ->where('OrderItems.kf_CustomerID',$customerID)
                        ->where('OrderItems.nb_Template','1')
                        ->where('OrderItems.nb_Inactive',null);


        return $this->datatables->generate();

    }
    public function getOrderItemTblFromOrderIDResultObject($orderID)
    {
        $query = $this->db->query("SELECT   CONCAT('<a target=\"_blank\" href=\"https://apps.indyimaging.com/apps/orderItemUpSideFrm/read/',OrderItems.kp_OrderItemID,'\">',OrderItems.kf_OrderID, '-', OrderItems.n_DashNum,'</a>') AS OrderAnchor,
                                            OrderItems.kp_OrderItemID as OrderItemID,
                                            OrderItems.kf_OrderID as OrderID,
                                            OrderItems.kf_OrderID as orderItemOrderID,
                                            OrderItems.n_DashNum as orderItemDashNum,
                                            OrderItems.t_OiStatus as Status,
                                            OrderItems.n_Quantity as Qty,
                                            CONCAT(TRUNCATE((OrderItems.n_HeightInInches/12),0),\"' \", TRIM(TRAILING '.' FROM TRIM(TRAILING '0' from ((TRUNCATE((OrderItems.n_HeightInInches),3))-(TRUNCATE((OrderItems.n_HeightInInches/12),0)) * 12))), '\" H', ' X ', TRUNCATE((OrderItems.n_WidthInInches/12),0),\"' \", TRIM(TRAILING '.' FROM TRIM(TRAILING '0' from ((TRUNCATE((OrderItems.n_WidthInInches),3))-(TRUNCATE((OrderItems.n_WidthInInches/12),0)) * 12))), '\" W') AS HeightWidth,
                                            Concat(ProductBuilds.t_Category,' ', '>',' ', ProductBuilds.t_Name,'<br/>',ifnull(newTable1.BuildItemName,\"\") ) as product,
                                            OrderItems.t_Description as Description,
                                            OrderItems.t_Structure,
                                            OrderItems.t_SportItemNumber,
                                            Concat(Equipment.t_EquipAbr, ' - ' ,EquipmentModes.t_Name) as Equip,
                                            CONCAT('../images/Orders/',YEAR(Orders.d_Received),'/', DATE_FORMAT(Orders.d_Received, '%m'),'/',OrderItems.kf_OrderID,'/',OrderItems.kp_OrderItemID,'/',trim(trailing \".jpg\" from OrderItems.t_OrderItemImage),'_thumb.jpg') as Image
                                   FROM OrderItems LEFT JOIN Orders ON OrderItems.kf_OrderID = Orders.kp_OrderID
                                        LEFT JOIN ProductBuilds ON OrderItems.kf_ProductBuildID = ProductBuilds.kp_ProductBuildID
                                        LEFT JOIN OrderItemComponents ON OrderItems.kp_OrderItemID = OrderItemComponents.kf_OrderItemID and isnull(kf_BuildItemID)
                                        LEFT JOIN Equipment ON OrderItemComponents.kf_EquipmentID = Equipment.kp_EquipmentID
                                        LEFT JOIN EquipmentModes ON OrderItemComponents.kf_EquipmentModeID = EquipmentModes.kp_EquipmentModeID
                                        LEFT JOIN
                                       (
                                           SELECT OrderItemComponents.kf_OrderItemID,GROUP_CONCAT(DISTINCT BuildItems.t_Name SEPARATOR ',  ') as BuildItemName
                                           FROM basetables2.OrderItemComponents
                                           LEFT JOIN BuildItems
                                           ON OrderItemComponents.kf_BuildItemID = BuildItems.kp_BuildItemID
                                           WHERE OrderItemComponents.kf_OrderID ='$orderID' and  OrderItemComponents.nb_ShowOnInvoice = 1
                                           GROUP BY OrderItemComponents.kf_OrderItemID
                                       ) as newTable1
                                         ON OrderItems.kp_OrderItemID =newTable1.kf_OrderItemID
                               WHERE Orders.kp_OrderID ='$orderID'
                               Group by OrderItems.kp_OrderItemID
                               ORDER BY OrderItems.n_DashNum");

        return $query->result_array();

    }
    public function getOrderItemsFromOrderIDCheckList($orderID)
    {
        $query = $this->db->query("SELECT   OrderItems.kp_OrderItemID as OrderItemID,
                                            OrderItems.n_DashNum as orderItemDashNum,
                                            OrderItems.t_OiStatus as Status,
                                            OrderItems.t_Description as Description
                                   FROM OrderItems
                                   WHERE OrderItems.kf_OrderID ='$orderID'
                                   Group by OrderItems.kp_OrderItemID
                                   ORDER BY OrderItems.n_DashNum");

        return $query->result_array();

    }
    public function getOrderItemRowsByOrderIDResultObject($orderID,$dateReceived)
    {
        $dateOrderReceivedArr       = explode("-", $dateReceived);

        $yearOrder                  = $dateOrderReceivedArr[0];

        $monthOrder                 = $dateOrderReceivedArr[1];

        //$query = $this->db->query("SELECT * FROM OrderItems  WHERE kf_OrderID ='$orderID'");
//        $query = $this->db->query("SELECT Orders.d_Received,
//                                    OrderItems.kp_OrderItemID,
//                                    OrderItems.t_OrderItemImage,
//                                    OrderItems.t_OrderItemProof,
//                                    OrderItems.n_DashNum,
//                                    OrderItems.kf_OrderID,
//                                    OrderItems.n_Quantity,
//                                    OrderItems.n_HeightInInches,
//                                    OrderItems.n_WidthInInches,
//                                    OrderItems.t_ProductType,
//                                    OrderItems.t_Description,
//                                    OrderItems.t_Structure,
//                                    OrderItems.nb_ArtReceivedProduction,
//                                    OrderItems.t_ArtReceivedBy,
//                                    OrderItems.d_ArtReceived,
//                                    OrderItems.t_ArtContact,
//                                    OrderItems.t_OiStatus
//                                    FROM OrderItems INNER JOIN Orders ON OrderItems.kf_OrderID = Orders.kp_OrderID
//                                    WHERE kf_OrderID = '$orderID'");
//
//        return $query->result();


        $this->datatables->select('concat(OrderItems.kf_OrderID,"-",OrderItems.n_DashNum) as IDNum,
                                    OrderItems.n_Quantity as Qty,
                                    concat(TRIM(TRAILING "." FROM(ifnull(CAST(TRIM(TRAILING "0" FROM OrderItems.n_HeightInInches)as CHAR),"")))
                                        ," H x ",TRIM(TRAILING "." FROM(ifnull(CAST(TRIM(TRAILING "0" FROM OrderItems.n_WidthInInches)as CHAR),"")))," W") as size,
                                    OrderItems.t_ProductType as product,
                                    OrderItems.t_Description as des,
                                    OrderItems.t_Structure as ID,
                                    concat(ifnull(nullif(OrderItems.nb_ArtReceivedProduction,0),""),"<br/>",
                                    ifnull(nullif(OrderItems.t_ArtReceivedBy,""),"")," ",ifnull(if(OrderItems.d_ArtReceived = "0000-00-00","",OrderItems.d_ArtReceived),"")," ",
                                    ifnull(nullif(OrderItems.t_ArtContact,""),"")," ",
                                    ifnull(nullif(OrderItems.t_OiStatus,""),"")) as artInfo,
                                    OrderItems.n_DashNum as DashNum,
                                    OrderItems.kp_OrderItemID,
                                    OrderItems.kf_OrderID,
                                    concat(trim(trailing ".jpg" from OrderItems.t_OrderItemImage),"_thumb.jpg") as OrderItemImageThumb,
                                    OrderItems.n_HeightInInches,
                                    OrderItems.n_WidthInInches',false)
                ->edit_column('ID#','<a href="orderItemUpSideFrm/read/$1" target="_blank">$2</a>','OrderItems.kp_OrderItemID,IDNum')
                ->add_column('Picture','<img  class="img-responsive" src="../../images/Orders/'.$yearOrder.'/'.$monthOrder.'/'.'$1/$2/$3 ">','OrderItems.kf_OrderID,OrderItems.kp_OrderItemID,OrderItemImageThumb')
                //->add_column('Picture','<img src="../../images/Orders/'.$yearOrder.'/'.$monthOrder.'/'.'$1/$2/$3 " height="200" width="250">','OrderItems.kf_OrderID,OrderItems.kp_OrderItemID,OrderItems.t_OrderItemImage')
                ->add_column('Delete','<a style="color:black;" name="deleteLineItem" id="deleteLineItem" href="$1"><span class="glyphicon glyphicon-remove"></span></a> ','OrderItems.kp_OrderItemID')
                ->from('OrderItems')
                ->where('OrderItems.kf_OrderID',$orderID)
                ->join('Orders', 'OrderItems.kf_OrderID = Orders.kp_OrderID');

        return $this->datatables->generate();

    }
    public function deleteOrderItemDataFromOrderItemID($orderItemID)
    {
        $this->db->delete('OrderItems', array('kp_OrderItemID' => $orderItemID));

    }
    public function insertOrderItemTable($orderItemArray)
    {
         $lenArr                  = sizeof($orderItemArray);
         //echo $lenArr."<br/><br/>";
         //print_r($orderItemArray);
         //echo "<br/><br/>";
         for($i = 0; $i<$lenArr; $i++)
         {
             $this->db->insert('OrderItems', $orderItemArray[$i]);

         }
        //print_r($orderItemArray[0]);
        //$this->db->insert('OrderItems', $orderItemArray[0]);

        return $this->db->insert_id();


    }
    public function orderItemDashNumber($orderID)
    {
         $this->db
                 ->select('COUNT(kp_OrderItemID) as dashNum',false)
                 ->from('OrderItems')
                 ->where('kf_OrderID',$orderID);

         $query = $this->db->get();

         return $query->row_array();

    }
    public function orderIDWithDashNumber($orderID)
    {
        $query = $this->db->query("SELECT kp_OrderItemID,
                                    CONCAT(cast(kf_OrderID as CHAR),CAST('-' AS CHAR),CAST(n_DashNum as CHAR)) as orderIDDashNum
                                    FROM OrderItems
                                    WHERE kf_OrderID ='$orderID'");
//        $this->db
//                 ->select('kp_OrderItemID,CONCAT(cast(kf_OrderID as CHAR),CAST('-' AS CHAR),CAST(n_DashNum as CHAR)) as orderIDDashNum',false)
//                 ->from('OrderItems')
//                 ->where('kf_OrderID',$orderID);

         //$query = $this->db->get();

         return $query->result_array();

    }
    public function getOrderItemIDFromOrderIDDashNum($orderID,$dashNum)
    {
        $query = $this->db->get_where('OrderItems',array('kf_OrderID'=>$orderID,'n_DashNum' => $dashNum));

        return $query->row_array();

    }
    public function deleteProofImage($orderItemID,$orderID,$dateReceived)
    {
        $dateOrderReceivedArr = explode("-", $dateReceived);

        $yearOrder            = $dateOrderReceivedArr[0];

        $monthOrder           = $dateOrderReceivedArr[1];

        $path                 = $this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID;

        $msg                  = "";
        if(file_exists($path.'/'.$orderItemID.'/'."proof"))
        {
            $files = scandir( $path.'/'.$orderItemID.'/'."proof");
            //var_dump($files);
            $files = array_diff($files,array('.','..','.DS_Store'));
            //var_dump($files);
            foreach($files as $file)
            {
                $msg = unlink($path.'/'.$orderItemID.'/'."proof".'/'.$file); // remove any image files.
            }

            return $msg;
        }

    }
    public function getProofImageContent($orderItemID,$orderID,$dateReceived)
    {
        $dateOrderReceivedArr = explode("-", $dateReceived);

        $yearOrder               = $dateOrderReceivedArr[0];

        $monthOrder              = $dateOrderReceivedArr[1];
        $orderItemArry           = $this->getOrderItemByID($orderItemID);

        $orderItemProofImageName = $orderItemArry[0]['t_OrderItemProof'];



        $path                 = $this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID;
        $full_path            = "";
        if(!is_null($orderItemProofImageName))
        {
            $full_path=$path.'/'.$orderItemID.'/'.$orderItemProofImageName;

        }
        if(file_exists($full_path))
        {
            $files = scandir( $path.'/'.$orderItemID);
            //var_dump($files);
            $files = array_diff($files,array('.','..','.DS_Store'));

            //$imagePath           = $this->loadImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID.'/'.$orderItemProofImageName;
            $actualImageName     = $orderItemProofImageName;

            $orderItemProofImageNameWithoutExtension = basename($orderItemProofImageName,'.jpg');

            $imagePath           = $this->loadImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID.'/'.$orderItemProofImageNameWithoutExtension.'_thumb.jpg';

            $image[] = array(
            'imageUrl' => $imagePath,
            'imageName' => $actualImageName);

            return $image;

//            foreach($files as $file)
//            {
//                if($file == $orderItemProofImageName)
//                {
//                    echo $file;
//                    $imagePath           = $this->loadImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID.'/'.$file;
//                    $actualImageName     = $file;
//
//                    $image[] = array(
//                   'imageUrl' => $imagePath,
//                   'imageName' => $actualImageName);
//                }
//                return $image;
//
//            }
        }
        else
        {
            return null;
        }

    }
    public function doProofCustomUpload($orderItemID,$orderID,$dateReceived)
    {
        $msg = "";
        //$orderArry    = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$orderID);
        //$dateReceived = $orderArry['d_Received'];
        //check for the mount file if mount file exist do uploading or exit the uploading process.
        $allowed = array('jpeg','jpg');

        if(file_exists(realpath(APPPATH . '../../../images/.am_i_mounted'))&& !is_null($dateReceived))
        {
            $dateOrderReceivedArr  = explode("-", $dateReceived);

             $yearOrder            = $dateOrderReceivedArr[0];

            $monthOrder            = $dateOrderReceivedArr[1];


            if(isset($_FILES['proofUploadImage']) && $_FILES['proofUploadImage']['error'] == 0)
            {
                $extension = pathinfo($_FILES['proofUploadImage']['name'], PATHINFO_EXTENSION);
                if(!in_array(strtolower($extension), $allowed))
                {
                     echo '{"status":"error"}';
                     exit;
                }
                $tmpName          = $_FILES['proofUploadImage']['tmp_name'];

                //$uploadedFileName = $_FILES['proofUploadImage']['name'];
                //$uploadedFileName = "proof_".$uploadedFileName;
                $uploadedFileName = $orderItemID."_proof.jpg";
                // checks and creates Year and Month Folder
                if(!is_dir($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder))
                {
                    if(!mkdir($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder,0777,TRUE))
                    {
                        die("Failed to create Year and Month Folders");
                    }

                }
                $path              = $this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID;

                // checks and creates the Order Folder
                if(!is_dir($path)) // checks if the order# has a folder or not
                {
                    if(!mkdir($path.'/'.$orderItemID,0777,TRUE))
                    {
                        die('Failed to create Order and other folders...');
                    }
                    else
                    {
                        chmod($path, 0777);

                         //change the directory owner/group permission for OrderItemID folder
                        chmod($path.'/'.$orderItemID, 0777);
                    }
                }

                // checks and creates the OrderItem Folder
                if(!is_dir($path.'/'.$orderItemID)) // checks if the orderitemid# has a folder or not, if the order# already has a folder.
                {
                    if(!mkdir($path.'/'.$orderItemID,0777,TRUE))
                    {
                        die('Failed to create OrderItem and other folders...');
                    }
                    else
                    {
                        //change the directory owner/group permission for OrderItemID folder
                        chmod($path.'/'.$orderItemID, 0777);
                    }
                }
                $newFileName      = $path.'/'.$orderItemID.'/'.$uploadedFileName;
                if(move_uploaded_file($tmpName, $newFileName))
                {
                    //echo '{"status":"success"}';

                    $tmp = getimagesize($newFileName);

                    //set the maxWidth and maxHeight Values
                    $maxWidth  = 1500;
                    $maxHeight = 1200;
                    if($tmp[0] <= $maxWidth && $tmp[1] <= $maxHeight)
                    {

                         //[ THUMB IMAGE ]
                         $img_config_0['image_library']   = 'gd2';
                         $img_config_0['source_image']    = $newFileName;
                         $img_config_0['maintain_ratio']  = TRUE;
                         $img_config_0['width']           = 250;
                         $img_config_0['height']          = 200;
                         $img_config_0['create_thumb']    = TRUE;
                         $this->image_lib->initialize($img_config_0);
                         if($this->image_lib->resize())
                         {
                             $this->image_lib->clear();
                             $imageProofResize['thumbNailCreation'] = "yes";

                             $imageProofResize['msg']               = "success";

                         }
                         else
                         {
                             $imageProofResize['thumbNailCreation'] = "no";
                             $imageProofResize['thumbImageError']   = "Failed.". $this->image_lib->display_errors();

                         }


                    }
                    else
                    {
                         //[ THUMB IMAGE ]
                         $img_config_0['image_library']   = 'gd2';
                         $img_config_0['source_image']    = $newFileName;
                         $img_config_0['maintain_ratio']  = TRUE;
                         $img_config_0['width']           = 250;
                         $img_config_0['height']          = 200;
                         $img_config_0['create_thumb']    = TRUE;

                         //[ MAIN IMAGE ]
                         $img_config_1['image_library']   = 'gd2';
                         $img_config_1['source_image']    = $newFileName;
                         $img_config_1['maintain_ratio']  = TRUE;
                         $img_config_1['width']           = 1500;
                         $img_config_1['height']          = 1200;
                         $img_config_1['create_thumb']    = FALSE;

                         for($i=0;$i<2;$i++)
                         {
                             eval("\$this->image_lib->initialize(\$img_config_".$i.");");
                             if($this->image_lib->resize())
                             {
                                 $this->image_lib->clear();
                                 $imageProofResize['thumbNailCreation']   = "yes";
                                 $imageProofResize['originalImageReSize'] = "yes";
                                 $imageProofResize['msg']               = "success";

                             }
                            else
                            {
                                 $imageProofResize['thumbNailCreation']   = "no";
                                 $imageProofResize['originalImageReSize'] = "no";
                                 $imageProofResize['thumbImageError']     = "Failed." .$i . $this->image_lib->display_errors();
                            }
                        }

                    }

                        // do the update here
                       $data = array(
                          't_OrderItemProof'=> $uploadedFileName
                       );

                       $this->updateOrderItemTable($orderItemID, $data);

                       return $imageProofResize;
                }
            }
        }

    }
    public function resizeOriginalImageCreateThumbNailConvertPdfToJpegConversion($imagePathName,$maxWidth=null,$maxHeight=null)
    {

        if(is_null($maxWidth))
        {
            $maxWidth=1500; //Default Value
        }
        if(is_null($maxHeight))
        {
            $maxHeight=1200;//Default Value
        }
        $tmp                    = getimagesize($imagePathName);
        $allowed                = array('jpeg','jpg','pdf','png');
        $extension              = pathinfo($imagePathName, PATHINFO_EXTENSION);

        if(!in_array(strtolower($extension), $allowed))
        {
             $imageResizeSummary['ExtensionMsg']="Image Not Valid Extension";
        }
        else
        {
            $imageResizeSummary="";
            $imageResizeSummary['ExtensionMsg'] = "Image Valid Extension";

            $fileNameWithoutExtesnion           = basename($imagePathName,".jpg");

            $uploadedFileNameWithoutExtesnion   = basename($imagePathName,".pdf");

            $dirname                            = pathinfo($imagePathName, PATHINFO_DIRNAME);

            $jpegThumbImagePath                 = $dirname.'/'.$fileNameWithoutExtesnion.'_thumb.jpg';

            $exportPath                         = $dirname.'/'.$uploadedFileNameWithoutExtesnion.'.jpg';

            if(strtolower($extension) == "pdf")
            {
                $imageResizeSummary['ExtensionFile'] = "pdf";
                $quality                             = 90;
                $res                                 = '300x300';
                //$uploadedFileNameWithoutExtesnion    = basename($imagePathName,".pdf");

                //$dirname                             = pathinfo($imagePathName, PATHINFO_DIRNAME);

                //$exportPath                          = $dirname.'/'.$uploadedFileNameWithoutExtesnion.'.jpg';

                if(!file_exists($dirname.'/'.$uploadedFileNameWithoutExtesnion.'_thumb.jpg'))
                {
                    if(!file_exists($exportPath))
                    {
                         exec("'gs' '-dNOPAUSE' '-sDEVICE=jpeg' '-dUseCIEColor' '-dTextAlphaBits=4' '-dGraphicsAlphaBits=4' '-o$exportPath' '-g$res' '-dPDFFitPage' '-dJPEGQ=$quality' '$imagePathName'",$output);

                    }
                    else
                    {
                        $imageResizeSummary['pdfJpegImage'] = "pdf Image Already Exists";

                    }
                    $img_config_deck_0['image_library']   = 'gd2';
                    $img_config_deck_0['source_image']    = $exportPath;
                    $img_config_deck_0['maintain_ratio']  = TRUE;
                    $img_config_deck_0['width']           = 250;
                    $img_config_deck_0['height']          = 200;
                    $img_config_deck_0['create_thumb']    = TRUE;
                    $img_config_deck_0['thumb_marker']    = "_thumb";

                    $this->image_lib->initialize($img_config_deck_0);

                    if($this->image_lib->resize())
                    {
                        $this->image_lib->clear();

                        $imageResizeSummary['pdfToJpegConversion']         = "yes";
                        $imageResizeSummary['msg']                         = "success";

                    }
                    else
                    {
                        $imageResizeSummary['pdfToJpegConversion']        = "no";
                        $imageResizeSummary['pdfToJpegConversionError']   = "Failed.". $this->image_lib->display_errors();
                    }
                    unlink($exportPath);

                }
                else
                {
                     $imageResizeSummary['pdfToJpegConversion'] = "pdf thumbnail Image Already Exists";
                }


            }
            else
            {
                if($tmp[0] <= $maxWidth && $tmp[1] <= $maxHeight)
                {
                    if(!file_exists($jpegThumbImagePath))
                    {
                        //[ THUMB IMAGE ]
                        $img_config_thumbnail['image_library']   = 'gd2';
                        $img_config_thumbnail['source_image']    = $imagePathName;
                        $img_config_thumbnail['maintain_ratio']  = TRUE;
                        $img_config_thumbnail['width']           = 250;
                        $img_config_thumbnail['height']          = 200;
                        $img_config_thumbnail['create_thumb']    = TRUE;
                        $img_config_thumbnail['thumb_marker']    = "_thumb";

                        $this->image_lib->initialize($img_config_thumbnail);
                        if($this->image_lib->resize())
                        {
                            $this->image_lib->clear();
                            $imageResizeSummary['thumbNailCreation']   = "yes";
                            $imageResizeSummary['originalImageReSize'] = "no";

                            $imageResizeSummary['msg']                 = "success";
                        }
                        else
                        {
                            $imageResizeSummary['thumbNailCreation'] = "no";
                            $imageResizeSummary['thumbImageError']   = "Failed.". $this->image_lib->display_errors();
                        }

                    }
                    else
                    {
                        $imageResizeSummary['thumbNailCreation'] = "thumbnail Image Already Exists";

                    }
                }
                else
                {
                      //[ THUMB IMAGE ]
                      $img_config_1['image_library']   = 'gd2';
                      $img_config_1['source_image']    = $imagePathName;
                      $img_config_1['maintain_ratio']  = TRUE;
                      $img_config_1['width']           = 250;
                      $img_config_1['height']          = 200;
                      $img_config_1['create_thumb']    = TRUE;
                      $img_config_1['thumb_marker']    = "_thumb";

                      //[ ORIGINAL IMAGE ]
                      $img_config_0['image_library']   = 'gd2';
                      $img_config_0['source_image']    = $imagePathName;
                      $img_config_0['maintain_ratio']  = TRUE;
                      $img_config_0['width']           = 1500;
                      $img_config_0['height']          = 1200;
                      $img_config_0['create_thumb']    = FALSE;

                      for($i=0;$i<2;$i++)
                      {
                          eval("\$this->image_lib->initialize(\$img_config_".$i.");");
                          if($this->image_lib->resize())
                          {
                              $this->image_lib->clear();
                              $imageResizeSummary['thumbNailCreation']   = "yes";
                              $imageResizeSummary['originalImageReSize'] = "yes";
                              $imageResizeSummary['msg']                 = "success";

                          }
                          else
                          {
                              $imageResizeSummary['thumbNailCreation']     = "no";
                              $imageResizeSummary['originalImageReSize']   = "no";
                              $imageResizeSummary['ResizethumbImageError'] = "Failed.". $this->image_lib->display_errors();
                          }
                      }
                }

            }
            return $imageResizeSummary;
        }


    }

    public function processOrderImgArryData($dateReceived,$orderID,$orderItemArry)
    {
         $dateOrderReceivedArr = explode("-", $dateReceived);
         $yearOrder            = $dateOrderReceivedArr[0];
         $monthOrder           = $dateOrderReceivedArr[1];

         $imageResizeSummary = "";
         for($r=0;$r<sizeof($orderItemArry);$r++)
         {
              $orderItemID          = $orderItemArry[$r]['OrderItemID'];
              $orderItemImage       = $orderItemArry[$r]['OrderItemImage'];
              $deckSheet            = $orderItemArry[$r]['DeckSheet'];
              $orderItemProof       = $orderItemArry[$r]['OrderItemProof'];

              $path                 = $this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID;

              if(!file_exists($path))
              {
                  $imageResizeSummary[$orderID]['OrderIDpathError'] = $orderID. " or ".$orderItemID." Path Error";
                  continue;
              }
              //echo "<br/>".$path."<br/>";
              if((!empty($orderItemImage) && !is_null($orderItemImage)) && (!empty($orderItemProof) && !is_null($orderItemProof)))
              {
                  if($orderItemImage == $orderItemProof)
                  {
                      $imageResizeSummary[$orderID]['OrderItemImage_ProofImage'] ="Both have same values";
                      $orderItemImageNamePath = $path.'/'.$orderItemImage;
                      if(file_exists($orderItemImageNamePath))
                      {
                          $orderItemImagePathFileName                                    = pathinfo($orderItemImageNamePath, PATHINFO_FILENAME);
                          $orderItemImagePathFileNameWithExtension                       = $orderItemImagePathFileName."_1.".pathinfo($orderItemImageNamePath, PATHINFO_EXTENSION);
                          $imageResizeSummary[$orderID]['OrderItemImage_Same_FileExist'] = "yes";

                          if(file_exists($path.'/'.$orderItemImagePathFileNameWithExtension))
                          {
                              unlink($path.'/'.$orderItemImagePathFileNameWithExtension);
                              $imageResizeSummary[$orderID]['deleted_ProofImageFileWith_1'] = "yes";

                          }
                          else
                          {
                              $imageResizeSummary[$orderID]['deleted_ProofImageFileWith_1']  = "no";
                          }
                          $imageResizeSummary[$orderItemID]['OrderItemImage_ProofImage_Summary'] =  $this->resizeOriginalImageCreateThumbNailConvertPdfToJpegConversion($orderItemImageNamePath);
                      }
                      else
                      {
                          $imageResizeSummary['OrderItemImage_Same_FileExist'] = "OrderItemImage: Path Error"; //physcial location doesn't exit so no resizing or creation of thumbnail
                          $imageResizeSummary['OrderItemProof_Same_FileExist'] = "OrderItemProof: Path Error"; //physcial location doesn't exit so no resizing or creation of thumbnail
                      }
                  }
                  else
                  {
                      $orderItemImageNamePath      = $path.'/'.$orderItemImage;

                      $orderItemProofImageNamePath = $path.'/'.$orderItemProof;

                      if(file_exists($orderItemImageNamePath))
                      {
                          $imageResizeSummary['orderItemImageFileExist']     = "Diff values. orderItem Image file Exist";
                          $imageResizeSummary[$orderItemID]['OrderItemImage_Summary']      =  $this->resizeOriginalImageCreateThumbNailConvertPdfToJpegConversion($orderItemImageNamePath);
                          //resize the image creation of thumbnail


                      }
                      if(file_exists($orderItemProofImageNamePath))
                      {
                          $imageResizeSummary[$orderID]['ProofImageFileExist']          = "Diff values. Proof file Exist";
                          $imageResizeSummary[$orderItemID]['orderItemProofImage_Summary'][$orderItemID]  = $this->resizeOriginalImageCreateThumbNailConvertPdfToJpegConversion($orderItemProofImageNamePath);
                          //resize the image creation of thumbnail


                      }

                  }
              }
              else if((empty($orderItemImage) || is_null($orderItemImage)) && (!empty($orderItemProof) && !is_null($orderItemProof)))
              {
                  $orderItemProofImageNamePath = $path.'/'.$orderItemProof;
                  if(file_exists($orderItemProofImageNamePath))
                  {
                      $imageResizeSummary[$orderID]['ProofImageFileExist']             = "yes";
                      $imageResizeSummary[$orderItemID]['orderItemProofImage_Summary']     = $this->resizeOriginalImageCreateThumbNailConvertPdfToJpegConversion($orderItemProofImageNamePath);

                  }
                  else
                  {
                      $imageResizeSummary[$orderID]['ProofImageFileExist'] =$orderItemID." OrderItemProof: Path Error";

                  }

              }
              else if((!empty($orderItemImage) || !is_null($orderItemImage)) && (empty($orderItemProof) && is_null($orderItemProof)))
              {
                  $orderItemImageNamePath = $path.'/'.$orderItemImage;
                  if(file_exists($orderItemImageNamePath))
                  {
                      $imageResizeSummary[$orderID]['orderItemImageFileExist']     = "yes";
                      $imageResizeSummary[$orderItemID]['OrderItemImage_Summary']  = $this->resizeOriginalImageCreateThumbNailConvertPdfToJpegConversion($orderItemImageNamePath);

                  }
                  else
                  {
                      $imageResizeSummary[$orderID]['orderItemImageFileExist'] = $orderItemID." OrderItemImage: Path Error";

                  }
              }
              else
              {
                  $imageResizeSummary[$orderID]['ProofImageFileExist']     = $orderItemID." OrderItemProof: Path Error";
                  $imageResizeSummary[$orderID]['orderItemImageFileExist'] = $orderItemID." OrderItemImage: Path Error";

              }
              if(!empty($deckSheet) && !is_null($deckSheet))
              {
                  $deckSheetImageNamePath = $path.'/'.$deckSheet;
                  if(file_exists($deckSheetImageNamePath))
                  {
                      $imageResizeSummary[$orderID]['deckSheetImageNamePath'] = "yes";
                      $imageResizeSummary[$orderItemID]['DeckSheetImage_Summary'] = $this->resizeOriginalImageCreateThumbNailConvertPdfToJpegConversion($deckSheetImageNamePath);

                  }
                  else
                  {
                      $imageResizeSummary[$orderID]['deckSheetImageNamePath'] = "no";

                  }

              }


         }


         return $imageResizeSummary;
    }
    public function doPrepressCustomUpload($orderItemID,$orderID,$dateReceived,$inputFile)
    {
        //$this->load->library('image_lib');
        //$msg = "";
        //$orderArry    = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$orderID);
        //$dateReceived = $orderArry['d_Received'];
        //check for the mount file if mount file exist do uploading or exit the uploading process.
        $allowed = array('jpeg','jpg','pdf');

        if(file_exists(realpath(APPPATH . '../../../images/.am_i_mounted'))&& !is_null($dateReceived))
        {
            $dateOrderReceivedArr = explode("-", $dateReceived);

            $yearOrder            = $dateOrderReceivedArr[0];

            $monthOrder           = $dateOrderReceivedArr[1];


            if(isset($_FILES['file']) && $_FILES['file']['error'] == 0)
            {
                $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                //echo "<br/> Extension: ".$extension."<br/>";
                if(!in_array(strtolower($extension), $allowed))
                {
                     echo '{"status":"error"}';
                     exit;
                }
                $tmpName          = $_FILES['file']['tmp_name'];


                // renaming the file with the OrderItemID and Deck Sheet
                //$uploadedFileName = $_FILES['file']['name'];

                if($inputFile == "upl")
                {
                    $uploadedFileName = $orderItemID.".jpg";

                }
                else if($inputFile == "sportUploadFile")
                {
                    //if it has the extension pdf then do the _pdf
                    if($extension == "pdf")
                    {
                        $uploadedFileName = $orderItemID."_deck.pdf";

                    }
                    else
                    {
                        $uploadedFileName = $orderItemID."_deck.jpg";
                    }
                } else if($inputFile == "proof") {
                  $uploadedFileName = $orderItemID."-proof.jpg";
                }

                // checks and creates Month and Year Folder
                if(!is_dir($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder))
                {
                    if(!mkdir($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder,0777,TRUE))
                    {
                        die("Failed to create Year and Month Folders");
                    }

                }
                $path              = $this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID;
//                if(file_exists($path.'/'.$orderItemID))
//                {
//                    $files = scandir( $path.'/'.$orderItemID);
//                    //var_dump($files);
//                    $files = array_diff($files,array('.','..','.DS_Store'));
//                    //var_dump($files);
//                    foreach($files as $file)
//                    {
//                        unlink($path.'/'.$orderItemID.'/'.$file); // remove any image files.
//
//                    }
//                }
                // checks and creates the Order Folder
                if(!is_dir($path)) // checks if the order# has a folder or not
                {
                    if(!mkdir($path.'/'.$orderItemID,0777,TRUE))
                    {
                        die('Failed to create Order and other folders...');
                    }
                    else
                    {
                        chmod($path, 0777);

                         //change the directory owner/group permission for OrderItemID folder
                        chmod($path.'/'.$orderItemID, 0777);
                    }
                }

                // checks and creates the OrderItem Folder
                if(!is_dir($path.'/'.$orderItemID)) // checks if the orderitemid# has a folder or not, if the order# already has a folder.
                {
                    if(!mkdir($path.'/'.$orderItemID,0777,TRUE))
                    {
                        die('Failed to create OrderItem and other folders...');
                    }
                    else
                    {
                        //change the directory owner/group permission for OrderItemID folder
                        chmod($path.'/'.$orderItemID, 0777);
                    }
                }

                $newFileName      = $path.'/'.$orderItemID.'/'.$uploadedFileName;
                if(move_uploaded_file($tmpName, $newFileName))
                {
//                    if($inputFile == "upl" && $extension == "pdf")
//                    {
//                        echo "File Name: ".$newFileName."<br/>";
//                        exec("convert $newFileName imageTest.jpg");
//
//                    }


                    //get the image size
                    $tmp = getimagesize($newFileName);
                    //print_r($tmp);
                    //set the maxWidth and maxHeight Values
                    $maxWidth  = 1500;
                    $maxHeight = 1200;


                    //print_r($imageResize);
                    //echo '{"status":"success"}';
                    //$msg = "success";
                    // do the update here
                    if($inputFile == "upl" || $inputFile == "proof")
                    {
                        $imageResize['customer']          = "nonSportCustomer";
                        // do the resizing and also thumbnail creation
                        // if the width or Height of the image is greater than the maxWidth or maxHeight

                        // Just create a thumbnail
                        if($tmp[0] <= $maxWidth && $tmp[1] <= $maxHeight)
                        {
                            //[ THUMB IMAGE ]
                            $img_config_0['image_library']   = 'gd2';
                            $img_config_0['source_image']    = $newFileName;
                            $img_config_0['maintain_ratio']  = TRUE;
                            $img_config_0['width']           = 250;
                            $img_config_0['height']          = 200;
                            $img_config_0['create_thumb']    = TRUE;
                            $this->image_lib->initialize($img_config_0);

                            if($this->image_lib->resize())
                            {
                                $this->image_lib->clear();
                                $imageResize['thumbNailCreation'] = "yes";

                                $imageResize['msg']               = "success";
                                //echo "Success";
                            }
                            else
                            {
                                $imageResize['thumbNailCreation'] = "no";
                                $imageResize['thumbImageError']   = "Failed.". $this->image_lib->display_errors();
                                //echo "Failed." .$i . $this->image_lib->display_errors();
                            }


                        }
                        //resize orignial Image and create the thumbnail
                        else
                        {
                            //[ THUMB IMAGE ]
                            //$this->image_lib->library_path   = '/Applications/MAMP/htdocs/bin/ImageMagick';
                            //$img_config_0['library_path']    = "/opt/local/bin/";
                            //$img_config_0['library_path']    = "/Applications/MAMP/bin/ImageMagick/bin/convert";
                            //$img_config_0['image_library']   = 'imagemagick';
                            $img_config_0['image_library']   = 'gd2';
                            $img_config_0['source_image']    = $newFileName;
                            $img_config_0['maintain_ratio']  = TRUE;
                            $img_config_0['width']           = 250;
                            $img_config_0['height']          = 200;
                            $img_config_0['create_thumb']    = TRUE;

                            //[ MAIN IMAGE ]
                            //$img_config_1['library_path']    = "/opt/local/bin/";
                            //$img_config_0['library_path']    = "/Applications/MAMP/bin/ImageMagick/bin/convert";
                            //$img_config_0['image_library']   = 'imagemagick';
                            $img_config_1['image_library']   = 'gd2';
                            $img_config_1['source_image']    = $newFileName;
                            //$img_config_1['source_image']    = $newFileName;
                            $img_config_1['maintain_ratio']  = TRUE;
                            $img_config_1['width']           = 1500;
                            $img_config_1['height']          = 1200;
                            $img_config_1['create_thumb']    = FALSE;
                            //echo "Path to Img Library: ". $img_config_0['library_path']."<br/>";
                            //echo "Path to Img Library Path : ". $img_config_0['image_library']."<br/>";
                            for($i=0;$i<2;$i++)
                            {
                                eval("\$this->image_lib->initialize(\$img_config_".$i.");");
                                if($this->image_lib->resize())
                                {

                                    $this->image_lib->clear();
                                    $imageResize['thumbNailCreation']   = "yes";
                                    $imageResize['originalImageReSize'] = "yes";
                                    $imageResize['msg']               = "success";
                                    //echo "Success";
                                }
                                else
                                {
                                    $imageResize['thumbNailCreation']   = "no";
                                    $imageResize['originalImageReSize'] = "no";
                                    $imageResize['thumbImageError']     = "Failed." .$i . $this->image_lib->display_errors();
                                    //echo "Failed." .$i . $this->image_lib->display_errors();
                                }
                            }

                        }
                        if($inputFile == "upl" || $inputFile == "proof")
                        if($inputFile == "upl") {
                          $data = array('t_OrderItemImage'=> $uploadedFileName);
                        } else {
                          $data = array('t_OrderItemProof'=> $uploadedFileName);
                        }

                        $this->updateOrderItemTable($orderItemID, $data);


                    }
                    if($inputFile == "sportUploadFile")
                    {
                        $imageResize['customer']          = "sportCustomer";
                        if(strtolower($extension) == "pdf")
                        {
                             $quality                          = 90;
                             $res                              = '300x300';
                             $uploadedFileNameWithoutExtesnion = basename($uploadedFileName,".pdf");
                             //echo "<br/> FileNameWithoutExtension: ".$uploadedFileNameWithoutExtesnion."<br/>";
                             $exportPath                       = $path.'/'.$orderItemID.'/'.$uploadedFileNameWithoutExtesnion.'.jpg';
                             //echo "export PATH: ".$exportPath."<br/>";
                             //echo "NewFileName: ".$newFileName."<br/>";
                             //$path.'/'.$orderItemID.'/'.$uploadedFileName;
                             //$exportPath=$path."/".$exportName."/fullres/%03d.jpg";
                             //save the pdf as an jpeg image
                             //Ghostscript OSX installer http://pages.uoregon.edu/koch/
                             exec("'gs' '-dNOPAUSE' '-sDEVICE=jpeg' '-dUseCIEColor' '-dTextAlphaBits=4' '-dGraphicsAlphaBits=4' '-o$exportPath' '-g$res' '-dPDFFitPage' '-dJPEGQ=$quality' '$newFileName'",$output);

//                             for($i=0;$i<count($output);$i++)
//                             {
//                                  echo($output[$i] .'<br/>');
//
//                             }

                             //exec("'gswin32' '-dPDFFitPage' '-dPARANOIDSAFER' '-dBATCH' '-dNOPAUSE' '-dNOPROMPT' '-dMaxBitmap=500000000' '-dAlignToPixels=0' '-dGridFitTT=0'
                             //      '-dDEVICEWIDTH=250' '-dDEVICEHEIGHT=200' '-dORIENT1=true' '-sOutputFile=$exportPath $newFileName'");
                             //echo memory_get_usage(true) ;
                             //echo memory_get_usage(true) ;
                             //echo "<br/>MemorySize1:<br/>";

                             //[ THUMB IMAGE ]
                            //$img_config_sport_0['library_path']    = "/opt/local/bin";
                            $img_config_sport_0['image_library']   = 'gd2';
                            $img_config_sport_0['source_image']    = $exportPath;
                            $img_config_sport_0['maintain_ratio']  = TRUE;
                            $img_config_sport_0['width']           = 250;
                            $img_config_sport_0['height']          = 200;
                            $img_config_sport_0['create_thumb']    = TRUE;

                            //print_r($img_config_sport_0);
                            $this->image_lib->initialize($img_config_sport_0);

                            if($this->image_lib->resize())
                            {
                                $this->image_lib->clear();
                                $imageResize['sportThumbNailCreation'] = "yes";

                                $imageResize['msg']                    = "success";
                                 //echo "Success";
                            }
                            else
                            {
                                $imageResize['sportThumbNailCreation'] = "no";
                                $imageResize['sportThumbImageError']   = "Failed.". $this->image_lib->display_errors();
                                 //echo "Failed." .$i . $this->image_lib->display_errors();
                            }
                            //echo memory_get_usage(true) ;
                            if(file_exists($path.'/'.$orderItemID.'/'.$uploadedFileNameWithoutExtesnion.'.jpg')) {
                              unlink($path.'/'.$orderItemID.'/'.$uploadedFileNameWithoutExtesnion.'.jpg'); // remove any image files.
                            }
                            //$sportTmpPdf = getimagesize($exportPath);

//                             if($sportTmpPdf[0] <= $maxWidth && $sportTmpPdf[1] <= $maxHeight)
//                             {
//                                 //create thumbnail creation
//                                 $img_config_sport_0['library_path']    = "/opt/local/bin";
//                                 $img_config_sport_0['image_library']   = 'imagemagick';
//                                 $img_config_sport_0['source_image']    = $exportPath;
//                                 $img_config_sport_0['maintain_ratio']  = TRUE;
//                                 $img_config_sport_0['width']           = 250;
//                                 $img_config_sport_0['height']          = 200;
//                                 $img_config_sport_0['create_thumb']    = TRUE;
//
//                                 $this->image_lib->initialize($img_config_sport_0);
//
//                                 if($this->image_lib->resize())
//                                 {
//                                     $this->image_lib->clear();
//                                     $imageResize['sportThumbNailCreation'] = "yes";
//
//                                     $imageResize['msg']                    = "success";
//                                     //echo "Success";
//                                 }
//                                 else
//                                 {
//                                     $imageResize['sportThumbNailCreation'] = "no";
//                                     $imageResize['sportThumbImageError']   = "Failed.". $this->image_lib->display_errors();
//                                     //echo "Failed." .$i . $this->image_lib->display_errors();
//                                 }
//
//                             }
//                             else
//                             {
//                                 //create thumbnail creation
//                                 $img_config_sport_0['library_path']    = "/opt/local/bin";
//                                 $img_config_sport_0['image_library']   = 'imagemagick';
//                                 $img_config_sport_0['source_image']    = $exportPath;
//                                 $img_config_sport_0['maintain_ratio']  = TRUE;
//                                 $img_config_sport_0['width']           = 250;
//                                 $img_config_sport_0['height']          = 200;
//                                 $img_config_sport_0['create_thumb']    = TRUE;
//
//                                 //resize the image
//                                 $img_config_sport_0['library_path']    = "/opt/local/bin";
//                                 $img_config_sport_1['image_library']   = 'imagemagick';
//                                 $img_config_sport_1['source_image']    = $exportPath;
//                                 $img_config_sport_1['maintain_ratio']  = TRUE;
//                                 $img_config_sport_1['width']           = 1500;
//                                 $img_config_sport_1['height']          = 1200;
//                                 $img_config_sport_1['create_thumb']    = FALSE;
//
//                                 for($i=0;$i<2;$i++)
//                                 {
//                                     eval("\$this->image_lib->initialize(\$img_config_sport_".$i.");");
//                                     if($this->image_lib->resize())
//                                     {
//                                         $this->image_lib->clear();
//                                         $imageResize['sportThumbNailCreation']   = "yes";
//                                         $imageResize['originalImageReSize']      = "yes";
//                                         $imageResize['msg']                      = "success";
//                                         //echo "Success";
//                                     }
//                                    else
//                                    {
//                                        $imageResize['sportThumbNailCreation']    = "no";
//                                        $imageResize['originalImageReSize']       = "no";
//                                        $imageResize['sportThumbImageError']      = "Failed." .$i . $this->image_lib->display_errors();
//                                    }
//                                 }
//
//                             }

                             //print_r($img_config_sport);
                             //echo "<br/>MemorySize2:<br/>";
                             //echo memory_get_usage(true) ;
                             //$this->image_lib->initialize($img_config_sport);
                             //echo "<br/>MemorySize3:<br/>";
                             //echo memory_get_usage(true) ;
//                             if($this->image_lib->resize())
//                             {
//                                 $this->image_lib->clear();
//                                 $imageResize['sportThumbNailCreation'] = "yes";
//
//                                 $imageResize['msg']                    = "success";
//                                 //echo "Success";
//                             }
//                             else
//                             {
//                                 $imageResize['sportThumbNailCreation'] = "no";
//                                 $imageResize['sportThumbImageError']   = "Failed.". $this->image_lib->display_errors();
//                                 //echo "Failed." .$i . $this->image_lib->display_errors();
//                             }
                             //echo "how are you!";
                             //print_r($imageResize);
                             //echo "<br/><br/>";
                             //echo memory_get_peak_usage(true);
//                            for($i=0;$i<count($output);$i++)
//                            {
//                                echo($output[$i] .'<br/>');
//
//                            }
                            // do the resizing and also thumbnail creation
                            // if the width or Height of the image is greater than the maxWidth or maxHeight
                        }
                        else
                        {
                            $sportTmp = getimagesize($newFileName);

                            if($sportTmp[0] <= $maxWidth && $sportTmp[1] <= $maxHeight)
                            {
                                 //create thumbnail creation
                                 $img_config_sport_0['library_path']    = "/opt/local/bin";
                                 $img_config_sport_0['image_library']   = 'imagemagick';
                                 $img_config_sport_0['source_image']    = $newFileName;
                                 $img_config_sport_0['maintain_ratio']  = TRUE;
                                 $img_config_sport_0['width']           = 250;
                                 $img_config_sport_0['height']          = 200;
                                 $img_config_sport_0['create_thumb']    = TRUE;

                                 $this->image_lib->initialize($img_config_sport_0);

                                 if($this->image_lib->resize())
                                 {
                                     $this->image_lib->clear();
                                     $imageResize['sportThumbNailCreation'] = "yes";

                                     $imageResize['msg']                    = "success";
                                     //echo "Success";
                                 }
                                 else
                                 {
                                     $imageResize['sportThumbNailCreation'] = "no";
                                     $imageResize['sportThumbImageError']   = "Failed.". $this->image_lib->display_errors();
                                     //echo "Failed." .$i . $this->image_lib->display_errors();
                                 }

                             }
                             else
                             {
                                 //create thumbnail creation
                                 $img_config_sport_0['library_path']    = "/opt/local/bin";
                                 $img_config_sport_0['image_library']   = 'imagemagick';
                                 $img_config_sport_0['source_image']    = $newFileName;
                                 $img_config_sport_0['maintain_ratio']  = TRUE;
                                 $img_config_sport_0['width']           = 250;
                                 $img_config_sport_0['height']          = 200;
                                 $img_config_sport_0['create_thumb']    = TRUE;

                                 //resize the image
                                 $img_config_sport_0['library_path']    = "/opt/local/bin";
                                 $img_config_sport_1['image_library']   = 'imagemagick';
                                 $img_config_sport_1['source_image']    = $newFileName;
                                 $img_config_sport_1['maintain_ratio']  = TRUE;
                                 $img_config_sport_1['width']           = 1500;
                                 $img_config_sport_1['height']          = 1200;
                                 $img_config_sport_1['create_thumb']    = FALSE;

                                 for($i=0;$i<2;$i++)
                                 {
                                     eval("\$this->image_lib->initialize(\$img_config_sport_".$i.");");
                                     if($this->image_lib->resize())
                                     {
                                         $this->image_lib->clear();
                                         $imageResize['sportThumbNailCreation']   = "yes";
                                         $imageResize['originalImageReSize']      = "yes";
                                         $imageResize['msg']                      = "success";
                                         //echo "Success";
                                     }
                                    else
                                    {
                                        $imageResize['sportThumbNailCreation']    = "no";
                                        $imageResize['originalImageReSize']       = "no";
                                        $imageResize['sportThumbImageError']      = "Failed." .$i . $this->image_lib->display_errors();
                                    }
                                 }

                             }

                        }
                        $data = array(
                        't_DeckSheet'=> $uploadedFileName
                        );
                        //print_r($data);
                        $this->updateOrderItemTable($orderItemID, $data);
                        $imageResize['msg'] = "success";


                    }
                    //print_r($imageResize);
                    //return $msg;
                    return $imageResize;
                    //exit;

                }
            } else {
              //isset($_FILES['file']) && $_FILES['file']['error'] == 0
              //"isset" => isset($_FILES['file']), "error" => $_FILES['file']['error'] == 0
              return array("msg" => "No Image Found", "Files" => $_FILES);
            }
        } else {
          return array("msg" => "Needed mounted file");
        }

    }
    public function getDeckSheetFileExtension($deckSheetImageFileName)
    {
        $extensionDeckSheet         = substr($deckSheetImageFileName,-4);

        $deckSheetFileNameExtension = "";

        if(substr(strtolower($extensionDeckSheet),0) == ".pdf")
        {
            $deckSheetFileNameExtension = "pdf";

        }
        else
        {
            $deckSheetFileNameExtension = substr(strtolower($extensionDeckSheet),1);

        }

        return $deckSheetFileNameExtension;

    }
    public function getPrepressImageContent($orderItemID,$orderID,$dateReceived)
    {
        $dateOrderReceivedArr          = explode("-", $dateReceived);

        $yearOrder                     = $dateOrderReceivedArr[0];

        $monthOrder                    = $dateOrderReceivedArr[1];

        $orderItemArry                 = $this->getOrderItemByID($orderItemID);

        $orderItemPrepressImageName    = $orderItemArry[0]['t_OrderItemImage'];

        $orderItemSportImageName       = $orderItemArry[0]['t_DeckSheet'];

        //check whether the deck sheet end with pdf extension
        //if it does end with pdf extension we need to show a link and open a new window to view the pdf.
        $sportActualImageNameExtension = $this->getDeckSheetFileExtension($orderItemSportImageName);


        $path                          = $this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID;
        //echo $path;
        $image = array();

        $fullPathPrepress              = "";

        $fullPathSport                 = "";

        if(!is_null($orderItemPrepressImageName))
        {
            $fullPathPrepress = $path.'/'.$orderItemID.'/'.$orderItemPrepressImageName;

        }
        if(!is_null($orderItemSportImageName))
        {
            $fullPathSport = $path.'/'.$orderItemID.'/'.$orderItemSportImageName;

        }
        if(file_exists($fullPathSport))
        {
            $files = scandir( $path.'/'.$orderItemID);
            //var_dump($files);
            $files = array_diff($files,array('.','..','proof','.DS_Store'));

            $sportImagePath           = $this->loadImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID.'/'.$orderItemSportImageName;
            //echo $sportImagePath."<br/>";
            if($sportActualImageNameExtension == "pdf")
            {
                $orderItemSportImageNameWithoutExtension   = basename($orderItemSportImageName,".pdf");
                $orderItemSportImageNameWithJpegExtension  = $orderItemSportImageNameWithoutExtension."_thumb.jpg";

                $sportImagePath                            = $this->loadImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID.'/'.$orderItemSportImageNameWithJpegExtension;
                //echo $sportImagePath."1<br/>";
            }
            else
            {
                $orderItemSportImageNameWithoutJpegExtension  = basename($orderItemSportImageName,".jpg");
                //$sportImagePath                               = $this->loadImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID.'/'.$orderItemSportImageName;
                $sportImagePath                               = $this->loadImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID.'/'.$orderItemSportImageNameWithoutJpegExtension."_thumb.jpg";;

            }
            $sportActualImageName     = $orderItemSportImageName;

            $image['sportImagePath']       = $sportImagePath;
            $image['sportActualImageName'] = $sportActualImageName;
            $image['$deckSheetExtension']  = $sportActualImageNameExtension;



        }
        if(file_exists($fullPathPrepress))
        {

            $files = scandir( $path.'/'.$orderItemID);
            //var_dump($files);
            $files = array_diff($files,array('.','..','proof','.DS_Store'));
            //var_dump($files);

            //$imagePath           = $this->loadImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID.'/'.$orderItemPrepressImageName;

            $actualImageName     = $orderItemPrepressImageName;

            $orderItemPrepressImageNameWithoutExtension = basename($orderItemPrepressImageName,'.jpg');

            $imagePath = $this->loadImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID.'/'.$orderItemPrepressImageNameWithoutExtension.'_thumb.jpg';

            $image['imageUrl']  = $imagePath;

            $image['imageName'] = $actualImageName;


//            foreach($files as $file)
//            {
//                //echo $file;
//                $imagePath           = $this->loadImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID.'/'.$file;
//
//                $actualImageName     = $file;
//                //echo "<br/>".$imagePath."<br/>";
//                //echo $this->imageUploadPath;
//                $image[] = array(
//                   'imageUrl' => $imagePath,
//                    'imageName' => $actualImageName);
//               //var_dump($image);
//               return $image;
//
//            }
//            foreach($files as $file)
//            {
//                //$thumbExtension = "_thumb";
//                $imagePath       = $this->loadImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID.'/'.$file;
//                $thumbPath      = $imagePath;
//                //$thumbPath      = $imagePath.$thumbExtension;
//                $image[] = array(
//                    'imageUrl' => $imagePath,
//                    'thumbUrl' => $thumbPath
//                );
//
//            }
//            return $image;

        }
        if(!is_null($image))
        {
            //var_dump($image);
            //echo "hi";
            return $image;
        }
        else
        {
            return null;
        }

    }
}

?>
