<?php

class OrderShipTrackingModel extends CI_Model
{
    //put your code here
    public function __construct()
    {
        parent::__construct();
    }

    public function getShippingCharges($id) {
      $query = $this->db->query("SELECT OrderShipTracking.kp_OrderShipTrackingID, OrderShipTracking.t_TrackingID, OrderShipTracking.n_ShippingCharge,
                                        OrderShipTracking.t_PackageCharge, OrderShipTracking.t_ChargeType,
                                        Shippers.t_Company, ShipperService.t_ShipperService
                                  FROM OrderShipTracking
                                  LEFT OUTER JOIN OrderShip ON OrderShipTracking.kf_OrderShipID = OrderShip.kp_OrderShipID
                                  LEFT OUTER JOIN Shippers ON OrderShip.kf_ShipperID = Shippers.kp_ShipperID
                                  LEFT OUTER JOIN ShipperService ON OrderShip.kf_ShipperServiceID = ShipperService.kp_ShipperServiceID
                                  WHERE OrderShipTracking.kf_OrderShipID = $id");

      return $query->result_array();
    }

    public function orderShipTrackingData($orderID)
    {
        $query = $this->db->query("SELECT kp_OrderShipTrackingID as ID,t_Company as shippingCompany,t_ShipperService as shippingService ,
                                          t_TrackingID as trackingID ,round(n_ShippingCharge,2) as shippingCharge
                                          FROM basetables2.OrderShipTracking
                                          LEFT JOIN basetables2.OrderShip
                                          on OrderShip.kp_OrderShipID = OrderShipTracking.kf_OrderShipID
                                          left join Shippers
                                          on OrderShip.kf_ShipperID = Shippers.kp_ShipperID
                                          left join ShipperService
                                          on OrderShip.kf_ShipperServiceID = ShipperService.kp_ShipperServiceID
                                          WHERE OrderShipTracking.kf_OrderID = '$orderID' ");
        return $query->result();

    }
    public function orderShipSelect1($orderID)
    {
        $where = "kf_OrderID = '$orderID'";
        $this->datatables
        ->select('kp_OrderShipID')
        ->from('OrderShip')
        ->join('Shippers', 'Shippers.kp_ShipperID = OrderShip.kf_ShipperID', 'left')
        ->select('t_Company')
        ->join('ShipperService', 'ShipperService.kp_ShipperServiceID = OrderShip.kf_ShipperServiceID', 'left')
        ->select('t_ShipperService,kp_ShipperID,kp_ShipperServiceID')
        ->where($where);

        return $this->datatables->generate();
    }
    public function submitOrderShipTracking()
    {
         $data = array(
            'kf_OrderID'=> $this->input->post('orderIDHidden',true),
            'kf_OrderShipID'=> $this->input->post('orderShipIDHidden',true),
            't_TrackingID'=> $this->input->post('trackingNumber',true),
            't_PackageCharge'=>str_replace("$", '',$this->input->post('packageCost',true)),
            't_ChargeType' =>  $this->input->post('noChargeFlatStandardRate',true),
            'n_ShippingCharge' =>  $this->input->post('customerCharge',true)
            //'n_ShippingCharge'=>$shippingCharge
//            'n_ShippingCharge'=> str_replace("$", '',$this->input->post('charge',true))

        );
        //print_r($data);

        $this->db->insert('OrderShipTracking', $data);
        return $this->db->insert_id();
    }

    public function getShippingChargeQuery($orderShipTrackingID,$packageCharge)
    {
        $query = $this->db->query("SELECT OrderShipTracking.kp_OrderShipTrackingID,
                                          ShipperService.n_PercentDiscountCorrected,OrderShipTracking.n_ShippingCharge,
                                          Customers.nb_NoChargeForGroundShipping,ProductBuilds.t_Category,
                                          Customers.n_CustShipBox,ShipperService.t_TypeOfShipping,Customers.nb_NoChargeForDelivery
                                   FROM OrderShipTracking INNER JOIN OrderShip ON OrderShipTracking.kf_OrderShipID = OrderShip.kp_OrderShipID
                                            INNER JOIN Customers ON OrderShip.kf_CustomerID = Customers.kp_CustomerID
                                            INNER JOIN OrderItems ON OrderShip.kf_OrderID = OrderItems.kf_OrderID
                                            INNER JOIN ProductBuilds ON OrderItems.kf_ProductBuildID = ProductBuilds.kp_ProductBuildID
                                            INNER JOIN ShipperService ON OrderShip.kf_ShipperServiceID = ShipperService.kp_ShipperServiceID
                                        WHERE OrderShipTracking.kp_OrderShipTrackingID = '$orderShipTrackingID'
                                        GROUP BY OrderShipTracking.kp_OrderShipTrackingID");
        $returnedArray  = $query->result_array();
        $shippingCharge = $this->calculateShippingCharge($returnedArray,$packageCharge);
        return $shippingCharge;

    }
    protected function calculateShippingCharge($returnedArray,$packageCharge)
    {
        $cleanPackageCharge = str_replace("$", '',$packageCharge);
        //print_r($returnedArray);
        if(empty($returnedArray))
        {
            $calculatedShippingCharge = "Data Corrupt";

        }
        else
        {
            $nb_NoChargeForGroundShipping = $returnedArray[0]['nb_NoChargeForGroundShipping'];
            $t_TypeOfShipping             = $returnedArray[0]['t_TypeOfShipping'];
            $t_Category                   = $returnedArray[0]['t_Category'];

            $n_PercentDiscountCorrected   = $returnedArray[0]['n_PercentDiscountCorrected'];
            $n_CustShipBox                = $returnedArray[0]['n_CustShipBox'];


            if($nb_NoChargeForGroundShipping == '1' && $t_TypeOfShipping == 'Ground' && $t_Category  == 'Billboard')
            {
                $calculatedShippingCharge = 0;
            }
            else if($nb_NoChargeForGroundShipping == '1' && $t_TypeOfShipping =='Delivery')
            {
                 $calculatedShippingCharge = 0;
            }
            else
            {
                $calculatedShippingCharge = ($cleanPackageCharge/(1-$n_PercentDiscountCorrected))+$n_CustShipBox;
            }

        }
//        if($returnedArray[0]['nb_NoChargeForGroundShipping'] == '1' && $returnedArray[0]['t_TypeOfShipping'] == 'Ground' && $returnedArray[0]['t_Category'] == 'Billboard')
//        {
//            $calculatedShippingCharge = 0;
//
//        }
//        else if($returnedArray[0]['nb_NoChargeForDelivery'] == '1' && $returnedArray[0]['t_TypeOfShipping'] =='Delivery')
//        {
//             $calculatedShippingCharge = 0;
//
//        }
//        else
//        {
//            $calculatedShippingCharge = ($cleanPackageCharge/(1-$returnedArray[0]['n_PercentDiscountCorrected']))+$returnedArray[0]['n_CustShipBox'];
//        }

        //print_r($returnedArray);
        //echo $returnedArray[0]['t_PackageCharge']."<br/>";
//        $lenArr=sizeof($returnedArray);
//        echo "sizeof: ".$lenArr."<br>";
//                for($i = 0; $i<$lenArr; $i++)
//                {
//                    echo $returnedArray[$i]['t_PackageCharge'];
//
//
//
//                }

        return $calculatedShippingCharge;

    }
    public function deleteOrderShipTrackingTableRecord($deleteModalOrderShipTrackingID)
    {
        /*
        * Any non-digit character will be excluded after passing $id
        * from intval function. This is done for security reason.
        */
        $id = intval($deleteModalOrderShipTrackingID);

        // below delete operation generates this query DELETE FROM users WHERE id = $id
        $this->db->delete( 'OrderShipTracking', array( 'kp_OrderShipTrackingID' => $id ) );

    }
    public function updateOrderShipTrackingTable($shippingCharge)
    {
        // check to see no charge is applied or not
        // if applied make modalCharge and modal


        $data = array(
            't_TrackingID'=> $this->input->post('modalTrackingNumber',true),
            't_PackageCharge'=>str_replace("$", '',$this->input->post('modalPackageCost',true)),
            'n_ShippingCharge'=> $shippingCharge,
            't_ChargeType' =>  $this->input->post('modalNoChargeFlatStandardRate',true)

        );

        $this->db->update('OrderShipTracking', $data, array('kp_OrderShipTrackingID'=>  $this->input->post('modalOrderShipTrackingID',true)));


    }
    public function getOrderShipTrackingTableById($orderSipTrackingID)
    {
        $query = $this->db->query("Select kp_OrderShipTrackingID,kf_OrderShipID, t_TrackingID,n_ShippingCharge,t_PackageCharge,t_ChargeType
                                    FROM basetables2.OrderShipTracking
                                    WHERE kp_OrderShipTrackingID = \"$orderSipTrackingID\" ");
         return  $query->row();
    }

}

?>
