<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OrderShipTrackingController  extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        if(!checkPermissionsByModule($privSet, 'ordership')) {
          $this->response(array('error' => 'You\'re privilege set doesn\'t allow access to this content'), 400);
        }
        $this->load->model('ordershiptrackingmodel');

       //$this->load->model('orderShipTracking/ordershiptrackingmodel');

    }

    public function index($orderID=null)
    {
        //echo "hello";
        if(isset($orderID))
        {
            $data['orderID'] = $orderID;
            $this->load->view('orderShipTracking',$data);
        }
        else
        {
             $this->load->view('testingTrack');
        }
    }
    public function deleteOrderShipTrackingRow($deleteModalOrderShipTrackingID=null)
    {
        if(is_null($deleteModalOrderShipTrackingID))
        {
            echo 'Error: Id not provided';
            return;
        }
        else
        {
            $this->ordershiptrackingmodel->deleteOrderShipTrackingTableRecord($deleteModalOrderShipTrackingID);
            echo 'Records deleted successfully';
        }

    }
    public function updateOderShipTrackingTable()
    {
        if(!empty($_POST))
        {
            // Get the shipping charge on the Type of Charge Selected
            //echo("hi".$this->input->post('modalPackageCost',true));
            //echo getShippingCharge("Flat Rate",'24','87101','13');
            $noChargeFlatStan    = $this->input->post('modalNoChargeFlatStandardRate',true);
            $customerCharge      = $this->input->post('modalCharge',true);
            $orderShipTrackingID = $this->input->post('modalOrderShipTrackingID',true);
            $packageCost         = $this->input->post('modalPackageCost',true);
            $shippingCharge      = $this->getShippingCharge($noChargeFlatStan, $customerCharge, $orderShipTrackingID, $packageCost);


            $this->ordershiptrackingmodel->updateOrderShipTrackingTable($shippingCharge);
            //echo json_encode($shippingCharge);
            echo $shippingCharge;

        }
    }

    public function orderShipTrackingTable($orderID)
    {
        //echo $orderID;
        //echo "hi";
        echo json_encode($this->ordershiptrackingmodel->orderShipTrackingData($orderID));
    }
    public function getShippingCharge($noChargeFlatStan,$customerCharge,$orderShipTrackingID,$packageCost)
    {
        // if 'No Charge' was checked ->no Calculation of Customer Charge (nb_ShippingCharge)
        // // Customer Charge (nb_ShippingCharge) set to zero - Done in JS
        // // Shipping Cost (t_PackageCharge)  set to zero  - Done in JS
        // ----
        //echo "fyu";
        if($noChargeFlatStan == "No Charge")
        {
            $shippingCharge = str_replace("$", '',$customerCharge);
        }
        // if flat rate is checked ->no calculation of Customer Charge (nb_ShippingCharge)
        // // Shipping Cost (t_PackageCharge)  set to zero
        // ----
        if($noChargeFlatStan == "Flat Rate")
        {
            $shippingCharge = str_replace("$", '',$customerCharge);
        }
        // if Standard rate is checked ->calculate Customer Charge (nb_ShippingCharge)
        // ----
        if($noChargeFlatStan == "Standard Rate" || $noChargeFlatStan=="")
        {
             //Now Calculate the Shipping Cost: Shipping Charge Calculated from kp_OrderShipTrackingID and shippingCost
             $shippingCharge = $this->ordershiptrackingmodel->getShippingChargeQuery($orderShipTrackingID,$packageCost);
        }
        //echo $shippingCharge;
        return $shippingCharge;

    }
    public function orderShipTrackingSubmit()
    {
        if(!empty($_POST))
          {
            //echo "in";
            $orderShipTrackingID = $this->ordershiptrackingmodel->submitOrderShipTracking();

            // Get the shipping charge on the Type of Charge Selected
            $shippingCharge = $this->getShippingCharge($this->input->post('noChargeFlatStandardRate',true),$this->input->post('customerCharge',true),$orderShipTrackingID,$this->input->post('packageCost',true));
            //echo "out1";

            // update the new insert record with the Shipping Charge
            $this->db->update('OrderShipTracking', array('n_ShippingCharge'=>$shippingCharge),  array('kp_OrderShipTrackingID'=>$orderShipTrackingID));

            //echo "out2";
            // for testing purpose kp_OrderShipTrackingID is echoed
            //echo $orderShipTrackingID ;
            //$this->index($this->input->post('orderIDHidden',true));

            //get the entire table
            echo json_encode($this->ordershiptrackingmodel->orderShipTrackingData($this->input->post('orderIDHidden',true)));
            //echo "out3";
          }
    }
    public function orderShipSelect1($orderID)
    {
        //echo $orderID;
        //echo "hi";
        echo $this->ordershiptrackingmodel->orderShipSelect($orderID);
    }
    public function orderShipTrackingOrderShipSelect($orderID)
    {
        echo Modules::run('orderShip/ordershipcontroller/orderShipSelect',$orderID);
        //echo json_encode($this->ordershiptrackingmodel->orderShipSelect($orderID));
    }
    public function getOrderShipTrackingRecord($orderShipTrackingID = null)
    {
         echo json_encode($this->ordershiptrackingmodel->getOrderShipTrackingTableById($orderShipTrackingID));
    }


}
?>
