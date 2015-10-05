<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OrderShipController extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
       $this->load->model('ordershipmodel');

    }
    public function index($orderID=null)
    {
        if(isset($orderID))
        {
            $data['orderID'] = $orderID;
            //echo $orderID.": 1: ";
            $this->load->view('shipping',$data);
            //echo $orderID;
        }
        else
        {
            $this->load->view('testing');
        }
    }
    // blind indicator method
    public function abShip($orderID)
    {
        echo json_encode($this->ordershipmodel->blindIndicator($orderID));

    }
    public function addressesRecipientDetails($orderID)
    {
        echo Modules::run('addresses/addresscontroller/recipientDetails',$orderID);
    }
    public function addressesBlindDetails($orderID)
    {
        echo Modules::run('addresses/addresscontroller/blindDetails',$orderID);
    }
    public function orderShipAddressForm($typeSub,$orderID=null,$customerID=null)
    {
        if($typeSub == "shipTo")
        {
             $data['title']   = "Ship To Form";
             $data['typeSub'] = "ShipTo";
             $data['orderID'] = $orderID;
             echo Modules::run('addresses/addresscontroller/addressForm',$data);
             //$this->load->view('ship_view/addressForm',$data);
        }
        if($typeSub == "shipBlindFrom")
        {
             $data['title']   = "Blind Form";
             $data['typeSub'] = "ShipBlindFrom";
             $data['orderID'] = $orderID;
             echo Modules::run('addresses/addresscontroller/addressForm',$data);
             //$this->load->view('ship_view/addressForm',$data);
        }
        if($typeSub == "Contact")
        {
             $data['title']      = "Contact";
             $data['typeSub']    = "Contact";
             $data['orderID']    = $orderID;
             $data['customerID'] = $customerID;
             echo Modules::run('addresses/addresscontroller/addressForm',$data);
             //$this->load->view('ship_view/addressForm',$data);
        }

    }
    public function orderShipSelect($orderID)
    {
         echo json_encode($this->ordershipmodel->orderShipSelectTbl($orderID));
    }
    public function barCodeView($orderID)
    {
        $this->load->view('shippingReport');
    }
    public function barCode($orderID)
    {
        $dateReceived               = Modules::run('orderItems/orderitemcontroller/getDateReceived',$orderID);

        $dateOrderReceivedArr       = explode("-", $dateReceived);

        $yearOrder                  = $dateOrderReceivedArr[0];

        $monthOrder                 = $dateOrderReceivedArr[1];

        $barCodeGenerationData =    $this->ordershipmodel->barCodeGeneration($orderID);
        //var_dump($barCodeGenerationData);
        //echo "<br/><br/>";
        //echo sizeof($barCodeGenerationData);
        for($i=0;$i<sizeof($barCodeGenerationData);$i++)
        {
            $barCodeGenerationData[$i]->yearOrder  = $yearOrder;
            $barCodeGenerationData[$i]->monthOrder = $monthOrder;
        }
        //echo "<br/><br/>";
        //var_dump($barCodeGenerationData);
        //echo json_encode($this->ordershipmodel->barCodeGeneration($orderID));
        echo json_encode($barCodeGenerationData);
    }

    public function billCheckOnCreate($orderID)
    {
        echo json_encode($this->ordershipmodel->billQueryOnCreate($orderID));

    }
//    public function completeCreateAction()
//    {
//        if(!empty($_POST))
//        {
//            //$result_id = $this->shippingmodel->completeCreateAction();
//            $orderShipID = $this->ordershipmodel->completeCreateAction();
//
//            $this->index($this->input->post('orderIDHidden',true));
//
//            $orderID =$this->input->post('orderIDHidden',true);
//
//            //$orderIDBarCode = $orderShipID."$"."|";
//            $orderIDBarCode = $orderShipID."$"."I";
//            $this->shippingmodel->getBarCode($orderIDBarCode,$orderID,$orderShipID);
//
//
//            //$this->load->view('ship_view/testing');
//            //$this->load->view('ship_view/shippingTest');
//            //$this->load->view('ship_view/testing',$result_id);
//
//            //$result['data'] = $this->shippingModel->updateAction();
//            //$result['data']=$this->shippingModel->completeCreateAction();
//            //$this->load->view('ship_view/testing',$result);
//            //$this->load->view('ship_view/shippingTest');
//        }
//
//
//    }
    public function completeUpdateCreateAction($orderID=null,$orderShipID=null,$outsideCall=null)
    {
        if(!empty($_POST))
        {
            //if the type of submit is 'CREATE'
            // for create inser a new row in ordership table
            if($this->input->post('typeOfSubmitHidden',true) == "Create")
            {

                $orderShipID = $this->ordershipmodel->createAction();

                // barcode image created here. No barcode image creation in UPDATE process.
                $orderID =$this->input->post('orderIDHidden',true);

                // sample $orderIDBarCode = $orderShipID."$"."|";
                //$orderIDBarCode = $orderShipID."$"."I";
                $orderIDBarCode = $orderShipID;

                // get the Bar code
                $this->ordershipmodel->getBarCode($orderIDBarCode,$orderID,$orderShipID);

                echo json_encode ($this->ordershipmodel->shipDChaining($this->input->post('orderIDHidden',true),''));



            }
            // if the type of submit is 'UPDATE'
            // for create inser a new row in ordership table
            if($this->input->post('typeOfSubmitHidden',true) == "Update")
            {

                $this->ordershipmodel->updateAction();

                // extracts the data after update With posted OrdershipID
                // echo the extracted data in json format
                echo json_encode ($this->ordershipmodel->shipDChaining('',$this->input->post('orderShipIDHidden',true)));
            }

        }
        else
        {
            //echo "<br/>inside hereh<br/>";
            //$orderIDBarCode = $orderShipID."$"."I";
            if($outsideCall == "true")
            {
                $orderIDBarCode = $orderShipID;
                // get the Bar code
                $this->ordershipmodel->getBarCode($orderIDBarCode,$orderID,$orderShipID);

            }

        }


    }
    // this function needs to go the order controller
    public function deleteNewOldShippingBarCode($orderID,$orderShipID,$newOld)
    {
        $this->ordershipmodel->deleteNewOldBarCodeImage($orderID,$orderShipID,$newOld);

    }
    public function deleteShip($orderShipID)
    {
        if(is_null($orderShipID))
        {
            echo 'Error: Id not provided';
            return;
        }
        else
        {
            //echo "hi";
            $this->ordershipmodel->deleteModalAction($orderShipID);
            //$this->load->view('ship_view/testing');
            //echo '<p>Record deleted successfully</p>';
            //$this->index($orderID);
            echo 'Records deleted successfully';
        }

    }
    public function OrderShipStatesGetCountryFromStatesTable($countryAbb=null)
    {
        echo Modules::run('states/statecontroller/getCountryFromStatesTable',$countryAbb);

    }
    public function OrderShipStatesGetStatesFromStatesTable()
    {
        echo Modules::run('states/statecontroller/getStatesFromStatesTable');

    }
    public function OrderShipStatesGetStatesFromCountryChange($countryAbb,$stateAbb=null)
    {
        echo Modules::run('states/statecontroller/getStatesFromCountryChange',$countryAbb,$stateAbb);

    }
    public function orderShipAddressesGetModalAddressData($addressID)
    {
        echo Modules::run('addresses/addresscontroller/getModalAddressData',$addressID);

    }
    public function orderShipAddressesModalAddressSubmit()
    {
        echo Modules::run('addresses/addresscontroller/modalAddressSubmit');

    }

    public function shipDChain($orderID=null,$orderShipID="")
    {
        //echo $orderID."<br>";
        //echo $orderShipID."<br>";
        echo json_encode($this->ordershipmodel->shipDChaining($orderID,$orderShipID));
    }
    public function shipDet($orderID)
    {
        echo json_encode($this->ordershipmodel->shipD($orderID));
    }
    public function shipperService($shipperID=null)
    {
        //echo "hi";
        echo Modules::run('shipperService/shipperservicecontroller/getShipperServiceID',$shipperID);

    }
    public function shipperInfoSelect($shipperID=null)
    {
        echo Modules::run('shippers/shippercontroller/getShipperInfo',$shipperID);

    }
    public function getOrderShipTblFromOrderID($orderID)
    {
        $row = $this->ordershipmodel->getOrderShipDataFromOrderID($orderID);
        //echo json_encode($row);
        return $row;
    }
    public function duplicateOrderShipDataFromOrderID($getOrderShipArrFromOrderID,$newOrderID)
    {
        $action = "duplicateOrderShipFromOrderID";
        for($i=0; $i<sizeof($getOrderShipArrFromOrderID); $i++)
        {
              $getOrderShipArrFromOrderID[$i]['kp_OrderShipID'] = "";
              $getOrderShipArrFromOrderID[$i]['kf_OrderID']     = $newOrderID;
              $getOrderShipArrFromOrderID[$i]['nb_ShippingException'] = "";
              $getOrderShipArrFromOrderID[$i]['nb_ShippingExceptionCompleted'] = "";
              $getOrderShipArrFromOrderID[$i]['t_ShippingExceptionName'] = "";
              $getOrderShipArrFromOrderID[$i]['t_ShippingExceptionReason'] = "";

              // if($getOrderShipArrFromOrderID[$i]['kf_ShippingExceptionOriginalShipperID'] != "") {
              //   $getOrderShipArrFromOrderID[$i]['kf_ShipperID'] = $getOrderShipArrFromOrderID[$i]['kf_ShippingExceptionOriginalShipperID'];
              // }
              // if($getOrderShipArrFromOrderID[$i]['kf_ShippingExceptionOriginalShipperServiceID'] != "") {
              //   $getOrderShipArrFromOrderID[$i]['kf_ShipperServiceID'] = $getOrderShipArrFromOrderID[$i]['kf_ShippingExceptionOriginalShipperServiceID'];
              // }

              $getOrderShipArrFromOrderID[$i]['kf_ShippingExceptionOriginalShipperID'] = "";
              $getOrderShipArrFromOrderID[$i]['kf_ShippingExceptionOriginalShipperServiceID'] = "";
        }


        //echo "<br/><br/><br/>";
        //print_r($getOrderShipArrFromOrderID);
        //echo "<br/><br/><br/>";


        //Insert the modified OrderShipArray into the table
        $insertedOrderShipData = $this->ordershipmodel->createAction($action,$getOrderShipArrFromOrderID);

    }
    public function createBarCodeForduplicateOrderShipData($getOrderShipArrFromNewOrderID,$newOrderID)
    {
        for($i=0; $i<sizeof($getOrderShipArrFromNewOrderID); $i++)
        {
              $orderShipID    = $getOrderShipArrFromNewOrderID[$i]['kp_OrderShipID'];

              $orderID        = $newOrderID;

              //$orderIDBarCode = $orderShipID."$"."I";
              $orderIDBarCode = $orderShipID;
              //echo "<br/>".$orderIDBarCode."<br/>";
              // get the Bar code
              $this->ordershipmodel->getBarCode($orderIDBarCode,$orderID,$orderShipID);

        }

    }

}
?>
