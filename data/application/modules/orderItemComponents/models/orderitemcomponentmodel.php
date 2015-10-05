<?php

class orderItemComponentModel extends CI_Model
{

  public function checkForNewProductBuildItemID($buildItemID, $productBuildID) {
    $query = $this->db->query("SELECT ProductBuildItems.kp_ProductBuildItemID as kf_ProductBuildItemID
                                FROM ProductBuildItems JOIN BuildItems ON ProductBuildItems.kf_BuildItemID = BuildItems.kp_BuildItemID
                                WHERE ProductBuildItems.kf_BuildItemID = " . $buildItemID . " and ProductBuildItems.kf_ProductBuildID = " . $productBuildID);

    return $query->result();
  }

  public function checkForNewProductBuildItemIDEquipment($buildItemID, $equipmentModeID) {
    $query = $this->db->query("SELECT ProductBuildItems.kp_ProductBuildItemID as kf_ProductBuildItemID
                                FROM ProductBuildItems
                                WHERE ProductBuildItems.kf_ProductBuildID = " . $buildItemID . " and ProductBuildItems.kf_EquipmentModeID = " . $equipmentModeID);

    return $query->result();
  }

  public function insertComponents($formData) {
    $this->db->insert("OrderItemComponents", $formData);
    return $this->db->insert_id();
  }

  public function updateComponents($id, $formData) {
    $this->db->where('kp_OrderItemComponentID', $id);
    $this->db->update('OrderItemComponents', $formData);
  }

  public function insertDuplicateOrderItemComponents($component) {
    $this->db->insert("OrderItemComponents", $component);
    return $this->db->insert_id();
  }

  public function getOrderItemComponents($orderItemID) {
    $query = $this->db->query("SELECT OrderItemComponents.kp_OrderItemComponentID,OrderItemComponents.kf_OrderID,OrderItemComponents.kf_OrderItemID,OrderItems.kf_ProductBuildID, -- from orderitems
                                	ProductBuilds.t_Category, -- relationship through orderitems
                                	ProductBuilds.t_Name as t_ProductBuildName, -- relationship through orderitems
                                	OrderItemComponents.kf_ProductBuildItemID,OrderItemComponents.t_Tiled, OrderItemComponents.n_OverlapTile,
                                	-- if nb_Invalid = 1 then you need to search 'ProductBuildItem' for a possible new ID
                                  -- for BuildItem use kf_BuildItem to search ProuductBuildItem.kf_BuildItemID for new ProuductBuildItem.kp_ProductBuildItemID
                                	-- for Equipment use kf_EquipmentID and kf_EquipmentModeID to search ProuductBuildItem.kf_EquipmentID and kf_EquipmentModeID for new ProuductBuildItem.kp_ProductBuildItemID
                                	OrderItemComponents.kf_BuildItemID,BuildItems.t_Name as t_BuildItemName,OrderItemComponents.kf_EquipmentID,Equipment.t_EquipmentName,
                                	OrderItemComponents.kf_EquipmentModeID,EquipmentModes.t_Name as t_EquipmentModeName,OrderItemComponents.kf_EquipmentSubModeID,
                                	EquipmentSubModes.t_Name as t_EquipmentSubModeName,OrderItemComponents.kf_LinkedEquipmentID,linkedEquipment.t_EquipmentName as t_LinkedEquipmentName,
                                	OrderItemComponents.kf_LinkedEquipmentModeID,linkedEquipmentModes.t_Name as t_LinkedEquipmentModeName,OrderItemComponents.kf_InventoryItemID,
                                	OrderItemComponents.n_Quantity,OrderItemComponents.n_HeightInInches,OrderItemComponents.n_WidthInInches,OrderItemComponents.n_BleedTop,OrderItemComponents.nb_PrintDoubleSided,
                                	OrderItemComponents.n_BleedBottom,OrderItemComponents.n_BleedLeft,OrderItemComponents.n_BleedRight,OrderItemComponents.n_WhiteTop,
                                	OrderItemComponents.n_WhiteBottom,OrderItemComponents.n_WhiteLeft,OrderItemComponents.n_WhiteRight,OrderItemComponents.n_PocketTop,
                                	OrderItemComponents.n_PocketBottom,OrderItemComponents.n_PocketLeft,OrderItemComponents.n_PocketRight,OrderItemComponents.t_Description,
                                	OrderItemComponents.t_Directions,OrderItemComponents.nb_ShowOnInvoice,OrderItemComponents.nb_CustomSize,EquipmentSubModes.t_Name as t_SubModeName,
                                	IF ( ISNULL(biManCats.t_Category), CONCAT( Equipment.t_EquipmentName, ' - ', EquipmentModes.t_Name ), BuildItems.t_Name ) AS t_DisplayName,
                                	IF ( ISNULL(biManCats.t_Category), ManCats.t_Category, biManCats.t_Category ) AS t_Category,
                                	IF ( ISNULL(biManCats.t_Category), ManCats.n_Sort, biManCats.n_Sort ) AS n_DisplaySort,
                                	IF ( ISNULL(biManCats.t_FormView), ManCats.t_FormView, biManCats.t_FormView ) AS t_FormView,
                                	BuildItems.nb_NotConnectedToInventory,
                                	IFNULL( CONCAT( '- OH ', TRIM( TRAILING '.' FROM ( CAST( TRIM( TRAILING '0' FROM SUM( InventoryLocationItems.n_QntyOnHand )) AS CHAR )))), '' ) AS t_InvOH,
                                	InventoryItems.t_description AS t_InvDesc,
                                	IF (ISNULL(ProductBuildItems.kf_ProductBuildID) or (OrderItems.kf_ProductBuildID != ProductBuildItems.kf_ProductBuildID), 1, '' ) AS nb_Invalid
                                FROM OrderItemComponents
                                	LEFT OUTER JOIN OrderItems ON OrderItemComponents.kf_OrderItemID = OrderItems.kp_OrderItemID
                                	LEFT OUTER JOIN ProductBuilds ON OrderItems.kf_ProductBuildID = ProductBuilds.kp_ProductBuildID
                                	LEFT OUTER JOIN ProductBuildItems ON OrderItemComponents.kf_ProductBuildItemID = ProductBuildItems.kp_ProductBuildItemID
                                	LEFT OUTER JOIN BuildItems ON OrderItemComponents.kf_BuildItemID = BuildItems.kp_BuildItemID
                                	LEFT OUTER JOIN ManCats biManCats ON BuildItems.kf_ManCatID = biManCats.kp_ManCatID
                                	LEFT OUTER JOIN Equipment ON OrderItemComponents.kf_EquipmentID = Equipment.kp_EquipmentID
                                	LEFT OUTER JOIN ManCats ON Equipment.kf_ManCatID = ManCats.kp_ManCatID
                                	LEFT OUTER JOIN EquipmentModes ON OrderItemComponents.kf_EquipmentModeID = EquipmentModes.kp_EquipmentModeID
                                	LEFT OUTER JOIN EquipmentSubModes ON OrderItemComponents.kf_EquipmentSubModeID = EquipmentSubModes.kp_EquipmentSubModeID
                                	LEFT OUTER JOIN Equipment as linkedEquipment ON OrderItemComponents.kf_LinkedEquipmentID = linkedEquipment.kp_EquipmentID
                                	LEFT OUTER JOIN EquipmentModes as linkedEquipmentModes ON OrderItemComponents.kf_LinkedEquipmentModeID = linkedEquipmentModes.kp_EquipmentModeID
                                	LEFT OUTER JOIN InventoryItems ON OrderItemComponents.kf_InventoryItemID = InventoryItems.kp_InventoryItemID
                                	LEFT OUTER JOIN InventoryLocationItems ON InventoryItems.kp_InventoryItemID = InventoryLocationItems.kf_InventoryItemID
                                WHERE OrderItemComponents.kf_OrderItemID = ". $orderItemID . "
                                GROUP BY OrderItemComponents.kp_OrderItemComponentID
                                ORDER BY n_DisplaySort ASC");

    return $query->result();
  }

    //put your code here
    public function getOrderItemComponentByOrderItemID($selectedOrderItemID)
    {
        //echo "oicmodel".$selectedOrderItemID;

        $query = $this->db->get_where('OrderItemComponents',array('kf_OrderItemID'=>$selectedOrderItemID));

//        foreach ($query->result_array() as $row)
//        {
//           echo $row['kf_OrderItemID'];
//           echo $row['kp_OrderItemComponentID'];
//           echo $row['t_Description'];
//        }

        return $query->result_array();

    }
    public function getProductBuildData($orderItemID)
    {
        $query = $this->db->query("SELECT OrderItemComponents.kp_OrderItemComponentID AS OrderItemComponentID,
                                   IF(ISNULL(biManCats.t_Category) ,ManCats.t_Category,biManCats.t_Category) AS t_Category,
                                   IF(ISNULL(biManCats.t_Category) ,CONCAT(Equipment.t_EquipmentName,' - ',EquipmentModes.t_Name),BuildItems.t_Name) AS DisplayName,
                                   ifnull(OrderItemComponents.t_Directions,'') AS Direction,
                                   IF(ISNULL(biManCats.t_Category) ,ManCats.n_Sort,biManCats.n_Sort) AS DisplaySort,
                                   IF(ISNULL(biManCats.t_FormView) ,ManCats.t_FormView,biManCats.t_FormView) AS t_FormView,
                                   BuildItems.nb_NotConnectedToInventory,
                                   InventoryItems.kp_InventoryItemID AS InvID,
                                   IFNULL(CONCAT(\"- OH \",TRIM(TRAILING '.' FROM(CAST(TRIM(TRAILING '0' FROM SUM(InventoryLocationItems.n_QntyOnHand)) AS CHAR)
                                   ))),'')AS INVOH,
                                   InventoryItems.t_description AS InvDesc,OrderItemComponents.nb_InvalidProductBuild
                                   FROM OrderItemComponents LEFT OUTER JOIN BuildItems ON OrderItemComponents.kf_BuildItemID = BuildItems.kp_BuildItemID
                                   LEFT OUTER JOIN ManCats biManCats ON BuildItems.kf_ManCatID = biManCats.kp_ManCatID
                                   LEFT OUTER JOIN Equipment ON OrderItemComponents.kf_EquipmentID = Equipment.kp_EquipmentID
                                   LEFT OUTER JOIN ManCats ON Equipment.kf_ManCatID = ManCats.kp_ManCatID
                                   LEFT OUTER JOIN EquipmentModes ON OrderItemComponents.kf_EquipmentModeID = EquipmentModes.kp_EquipmentModeID
                                   LEFT OUTER JOIN InventoryItems ON OrderItemComponents.kf_InventoryItemID = InventoryItems.kp_InventoryItemID
                                   LEFT OUTER JOIN InventoryLocationItems ON InventoryItems.kp_InventoryItemID = InventoryLocationItems.kf_InventoryItemID
                                   WHERE OrderItemComponents.kf_OrderItemID =".$orderItemID."
                                   GROUP BY OrderItemComponents.kp_OrderItemComponentID
                                   ORDER BY DisplaySort ASC");
//        $query = $this->db->query("SELECT OrderItemComponents.kp_OrderItemComponentID as OrderItemComponentID,
//                                   IF(ISNULL(biManCats.t_Category) ,ManCats.t_Category,biManCats.t_Category) AS t_Category,
//                                   IF(ISNULL(biManCats.t_Category) ,CONCAT(Equipment.t_EquipmentName,' - ',EquipmentModes.t_Name),BuildItems.t_Name)
//                                   AS DisplayName,ifnull(OrderItemComponents.t_Directions,'') as Direction,
//                                   IF(ISNULL(biManCats.t_Category) ,ManCats.n_Sort,biManCats.n_Sort) AS DisplaySort,
//                                   IF(ISNULL(biManCats.t_FormView) ,ManCats.t_FormView,biManCats.t_FormView) AS t_FormView,
//                                   BuildItems.nb_NotConnectedToInventory
//                                   FROM OrderItemComponents LEFT JOIN BuildItems ON OrderItemComponents.kf_BuildItemID = BuildItems.kp_BuildItemID
//                                   LEFT JOIN ManCats biManCats ON BuildItems.kf_ManCatID = biManCats.kp_ManCatID
//                                   LEFT JOIN Equipment ON OrderItemComponents.kf_EquipmentID = Equipment.kp_EquipmentID
//                                   LEFT JOIN ManCats ON Equipment.kf_ManCatID = ManCats.kp_ManCatID
//                                   LEFT JOIN EquipmentModes ON OrderItemComponents.kf_EquipmentModeID = EquipmentModes.kp_EquipmentModeID
//                                   WHERE OrderItemComponents.kf_OrderItemID =".$orderItemID."
//                                   ORDER BY DisplaySort");
        return $query->result();


    }
    public function checkOrderItemComponentsAreValid($orderItemID,$productBuildID)
    {
        $query = $this->db->query("SELECT OrderItemComponents.kp_OrderItemComponentID,
                                   OrderItemComponents.kf_OrderItemID,
                                   OrderItemComponents.kf_ProductBuildItemID,
                                   OrderItemComponents.kf_BuildItemID,
                                   OrderItemComponents.kf_EquipmentID,
                                   OrderItemComponents.kf_EquipmentModeID,
                                   ProductBuildItems.kf_BuildItemID as ValidBuildItemID,
                                   ProductBuildItems.kp_ProductBuildItemID as ValidProductBuildID,
                                   CASE
                                        WHEN OrderItemComponents.kf_BuildItemID = ProductBuildItems.kf_BuildItemID THEN  'Valid'
                                        WHEN OrderItemComponents.kf_BuildItemID != ProductBuildItems.kf_BuildItemID THEN  'inValid'
                                        WHEN OrderItemComponents.kf_BuildItemID > 1 and ISNULL(ProductBuildItems.kf_BuildItemID ) THEN  'inValid'
                                        WHEN (ISNULL(OrderItemComponents.kf_BuildItemID) or OrderItemComponents.kf_BuildItemID = 0)  and (OrderItemComponents.kf_EquipmentID = EquipValid.kf_EquipmentID) and (OrderItemComponents.kf_EquipmentModeID = EquipValid.kf_EquipmentModeID) THEN  'Valid'
                                        WHEN (ISNULL(OrderItemComponents.kf_BuildItemID) or OrderItemComponents.kf_BuildItemID = 0)  and ISNULL(EquipValid.kp_ProductBuildItemID) THEN  'inValid'
                                        WHEN (ISNULL(OrderItemComponents.kf_BuildItemID) or OrderItemComponents.kf_BuildItemID = 0)  and ((OrderItemComponents.kf_EquipmentID != EquipValid.kf_EquipmentID) or (OrderItemComponents.kf_EquipmentModeID != EquipValid.kf_EquipmentModeID)) THEN  'inValid'
                                  ELSE NULL End AS 'Valid',

                                  CASE
                                        WHEN OrderItemComponents.kf_BuildItemID = ProductBuildItems.kf_BuildItemID THEN  ProductBuildItems.kp_ProductBuildItemID
                                        WHEN (ISNULL(OrderItemComponents.kf_BuildItemID) or OrderItemComponents.kf_BuildItemID = 0)
                                  and (OrderItemComponents.kf_EquipmentID = EquipValid.kf_EquipmentID)
                                  and (OrderItemComponents.kf_EquipmentModeID = EquipValid.kf_EquipmentModeID) THEN  EquipValid.kp_ProductBuildItemID
                                  ELSE NULL End AS 'NewProductBuild',
                                  OrderItemComponents.nb_InvalidProductBuild
                                  FROM OrderItemComponents
                                  LEFT OUTER JOIN ProductBuildItems ON OrderItemComponents.kf_BuildItemID = ProductBuildItems.kf_BuildItemID
                                  AND ProductBuildItems.kf_ProductBuildID =".$productBuildID."
                                  LEFT OUTER JOIN ProductBuildItems As EquipValid ON OrderItemComponents.kf_EquipmentID = EquipValid.kf_EquipmentID
                                  AND OrderItemComponents.kf_EquipmentModeID = EquipValid.kf_EquipmentModeID
                                  AND EquipValid.kf_ProductBuildID =".$productBuildID."
                                  WHERE OrderItemComponents.kf_OrderItemID =".$orderItemID."
                                  GROUP BY OrderItemComponents.kp_OrderItemComponentID");

        return $query->result_array();
    }
    public function getRelatedProductBuildData($productBuildID)
    {
        $query = $this->db->query("SELECT   IF(ISNULL(biManCats.t_Category) ,ManCats.t_Category,biManCats.t_Category) AS DisplayCategory,
                                            IF(ISNULL(biManCats.t_Category),
                                            CONCAT(Equipment.t_EquipmentName,' - ',EquipmentModes.t_Name),
                                            BuildItems.t_Name)   AS DisplayName,
                                            ProductBuildItems.kp_ProductBuildItemID as productBuildItemID,
                                            ProductBuildItems.kf_BuildItemID as buildItemID,
                                            ProductBuildItems.kf_EquipmentID as equipmentID,
                                            ProductBuildItems.kf_EquipmentModeID as equipmentModeID,
                                            IF(ISNULL(biManCats.t_Category) ,ManCats.n_Sort,biManCats.n_Sort) AS DisplaySort,
                                            IF(ISNULL(biManCats.t_FormView) ,ManCats.t_FormView,biManCats.t_FormView) AS t_FormView,
                                            BuildItems.nb_NotConnectedToInventory,
                                            ProductBuildItems.nb_ShowOnInvoice
                                    FROM ProductBuildItems
                                    LEFT JOIN BuildItems ON ProductBuildItems.kf_BuildItemID = BuildItems.kp_BuildItemID
                                    LEFT JOIN ManCats biManCats ON BuildItems.kf_ManCatID = biManCats.kp_ManCatID
                                    LEFT JOIN Equipment ON ProductBuildItems.kf_EquipmentID = Equipment.kp_EquipmentID
                                    LEFT JOIN ManCats ON Equipment.kf_ManCatID = ManCats.kp_ManCatID
                                    LEFT JOIN EquipmentModes ON ProductBuildItems.kf_EquipmentModeID = EquipmentModes.kp_EquipmentModeID
                                    WHERE ProductBuildItems.kf_ProductBuildID =".$productBuildID."
                                    and (isnull(BuildItems.nb_Inactive) or (BuildItems.nb_Inactive=\"0\"))
                                    and (isnull(Equipment.nb_Inactive) or (Equipment.nb_Inactive=\"0\"))
                                    and (isnull(EquipmentModes.nb_Inactive) or (EquipmentModes.nb_Inactive=\"0\"))
                                    ORDER BY DisplaySort,DisplayName ASC");
        return $query->result_array();
    }

    public function insertOrderItemComponentTable($orderItemComponentArray)
    {
        //print_r($orderItemComponentArray);
        $lenArr                  = sizeof($orderItemComponentArray);
        //echo "<br>".$lenArr."<br>";
        for($i = 0; $i<$lenArr; $i++)
        {
            $this->db->insert('OrderItemComponents', $orderItemComponentArray[$i]);

        }


        return $this->db->insert_id();
    }

    public function getOrderItemComponentByID($orderItemComponentInsertedID)
    {


        $query = $this->db->get_where('OrderItemComponents',array('kp_OrderItemComponentID'=>$orderItemComponentInsertedID));

        return $query->row_array();

    }
    public function printMaterial($orderItemInsertedID)
    {
        $where = "OrderItemComponents.kf_OrderItemID =". $orderItemInsertedID." and BuildItems.kf_ManCatID = \"2\"
                    and (OrderItemComponents.nb_CustomPrintSpecs != \"1\" || OrderItemComponents.nb_CustomPrintSpecs is null)  ";

        $this->db->select('kp_OrderItemComponentID,n_Quantity,n_HeightInInches,n_WidthInInches')
                 ->from('OrderItemComponents')
                 ->join('BuildItems', 'OrderItemComponents.kf_BuildItemID = BuildItems.kp_BuildItemID')
                 ->where($where);

        $query  = $this->db->get();
        $status = "Completed";
        if ($query->num_rows() > 0)
        {
            return $query->row_array();

        }
        else
        {
            return $status;
        }


    }
//    public function updateOrderItemComponent($orderItemComponentID,$quantity="",$height="",$width="",$linkedEquipmentID="",$linkedEquipmentModeID="")
//    {
//
//          $data = array(
//                        'n_Quantity' => $quantity,
//                        'n_HeightInInches' => $height,
//                        'n_WidthInInches' => $width,
//                        'kf_LinkedEquipmentID'=>$linkedEquipmentID,
//                        'kf_LinkedEquipmentModeID'=>$linkedEquipmentModeID
//
//                     );
//          //print_r($data);
//          $this->db->where('kp_OrderItemComponentID', $orderItemComponentID);
//          $this->db->update('OrderItemComponents', $data);
//
//    }
    public function updateOrderItemComponent($orderItemComponentID,$data)
    {

//          $data = array(
//                        'n_Quantity' => $quantity,
//                        'n_HeightInInches' => $height,
//                        'n_WidthInInches' => $width,
//                        'kf_LinkedEquipmentID'=>$linkedEquipmentID,
//                        'kf_LinkedEquipmentModeID'=>$linkedEquipmentModeID
//
//                     );
          //print_r($data);
          $this->db->where('kp_OrderItemComponentID', $orderItemComponentID);
          $this->db->update('OrderItemComponents', $data);

    }
    public function deleteOrderItemComponentRow($orderItemComponentID)
    {
        $kp_OrderItemComponentID = intval($orderItemComponentID);

        // below delete operation generates this query DELETE FROM users WHERE id = $id
        $this->db->delete( 'OrderItemComponents', array( 'kp_OrderItemComponentID' => $kp_OrderItemComponentID ) );

    }
    public function dupFinAllLineItems($orderID,$data)
    {
        $this->db->query("UPDATE OrderItemComponents
                          INNER JOIN BuildItems ON OrderItemComponents.kf_BuildItemID = BuildItems.kp_BuildItemID
                          SET n_BleedTop     =".$data['n_BleedTop'].",
                          n_BleedBottom      =".$data['n_BleedBottom'].",
                          n_BleedLeft        =".$data['n_BleedLeft'].",
                          n_BleedRight       =".$data['n_BleedRight'].",
                          n_WhiteTop         =".$data['n_WhiteTop'].",
                          n_WhiteBottom      =".$data['n_WhiteBottom'].",
                          n_WhiteLeft        =".$data['n_WhiteLeft'].",
                          n_WhiteRight       =".$data['n_WhiteRight'].",
                          n_PocketTop        =".$data['n_PocketTop'].",
                          n_PocketBottom     =".$data['n_PocketBottom'].",
                          n_PocketLeft       =".$data['n_PocketLeft'].",
                          n_PocketRight     =".$data['n_PocketRight']."
                          WHERE OrderItemComponents.Kf_OrderID =".$orderID." and BuildItems.kf_ManCatID =". "\"2\"");

    }
    public function getOrderItemComponentByOrderID($orderID)
    {

        $query = $this->db->get_where('OrderItemComponents',array('kf_OrderID'=>$orderID));

        return $query->result_array();

    }
}

?>
