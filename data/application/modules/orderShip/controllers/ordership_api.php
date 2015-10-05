<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class OrderShip_Api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        if(!checkPermissionsByModule($privSet, 'ordership')) {
          $this->response(array('error' => 'You\'re privilege set doesn\'t allow access to this content'), 400);
        }
        $this->load->model('ordershipmodel');

    }

    public function orderShipInvoiceEmailTrackFromOrderID_get() {
        if(!$this->get('orderID')) {
            $this->response(NULL, 400);
        }

        $orderID     = $this->get('orderID');
        $result      = $this->ordershipmodel->getOrderShipInvoiceEmailTrackDataFromOrderID($orderID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        } else {
            $this->response($result, 200);
        }

    }

    public function orderShipEmailTrackingDataFromOrderID_get() {
        if(!$this->get('orderID')) {
            $this->response(NULL, 400);
        }

        $orderID     = $this->get('orderID');

        $result = $this->ordershipmodel->getOrderShipEmailTrackingDataFromOrderID($orderID);

        if($result) {
            $this->response($result, 200);
        } else {
            $this->response(array('error' => 'ordership Data for'.$orderID. ' could not be found'), 404);
        }

    }

    public function defaultBlindAddress_get() {
      if(!$this->get('id')) {
        $this->response(NULL, 400);
      }

      $orderID = $this->get('id');

      $result = $this->ordershipmodel->getDefaultBlindAddress($orderID);

      if($result) {
          $this->response($result, 200); // 200 being the HTTP response code
      } else {
          $this->response(array('fail' => 'No default address found'), 200);
      }
    }

    public function orderShipByOrderID_get() {
      if(!$this->get('id')) {
        $this->response(NULL, 400);
      }

      $orderID = $this->get('id');

      $result = $this->ordershipmodel->getOrderShipByOrderID($orderID);

      $this->response($result, 200);
    }

    public function orderShipInvoiceFromOrderID_get()
    {
        if(!$this->get('orderID'))
        {
            $this->response(NULL, 400);
        }

        $orderID     = $this->get('orderID');
        $result      = $this->ordershipmodel->getOrderShipInvoiceDataFromOrderID($orderID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response($result, 200);
        }

    }
    public function orderShipTblFromOrderID_get()
    {
        if(!$this->get('orderID'))
        {
            $this->response(NULL, 400);
        }

        $orderID     = $this->get('orderID');
        //echo $orderID."<br/>";
        $result = $this->ordershipmodel->shipDChaining($orderID,"");
        //print_r($result);
        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'ordership could not be found'), 404);
        }
    }
    public function orderShipTblData_delete($id)
    {
        //$this->response(array('deleteOrderShipID' => $id));
        $result = $this->ordershipmodel->deleteOrderShipTableRow($id);

        if($result)
        {
            $this->response(array('deleteOrderShipID' => $id));
        }

    }
    public function orderShipDataFromOrderID_get()
    {
        if(!$this->get('orderID'))
        {
            $this->response(NULL, 400);
        }

        $orderID     = $this->get('orderID');

        $result = $this->ordershipmodel->getOrderShipDataFromOrderID($orderID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'ordership Data for'.$orderID. ' could not be found'), 404);
        }

    }

    public function orderShipDataFromOrderID_put()
    {
        if($this->get('kp_OrderShipID'))
        {
            $this->response(NULL, 400);
        }
        else
        {
            $data                           = $this->put('formData',false);


            $orderShipID                    = $this->ordershipmodel->insertOrderShipTblData($data);
            // barcode image created here. No barcode image creation in UPDATE process.
            $orderID                        = $data['kf_OrderID'];

            // sample $orderIDBarCode = $orderShipID."$"."|";
            //$orderIDBarCode = $orderShipID."$"."I";
            $orderIDBarCode                 = $orderShipID;

            // get the Bar code
            $this->ordershipmodel->getBarCode($orderIDBarCode,$orderID,$orderShipID);

            $result                         = array('kp_OrderShipID'=>$orderShipID,'kf_OrderID'=>$orderID);

            if($result)
            {
                 $this->response($result, 200);

            }
            else
            {
                 $this->response(array('error' => 'ordership Data for'.$orderID. ' could not be found'), 404);

            }

        }

    }
    public function blindIndicatorData_get()
    {
        if(!$this->get('orderID'))
        {
            $this->response(NULL, 400);
        }

        $orderID     = $this->get('orderID');

        $result = $this->ordershipmodel->blindIndicator($orderID);


        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            // we do this bec the $result can have no values and this is ok
            $this->response(array('error' => 'blindIndicator Data for '.$orderID. ' could not be found'), 200);
            //$this->response(array(), 200);
        }

    }
    public function billCheckOnCreate_get()
    {
        if(!$this->get('orderID'))
        {
            $this->response(NULL, 400);
        }

        $orderID     = $this->get('orderID');

        $result = $this->ordershipmodel->billQueryOnCreate($orderID);
        //$result=json_encode($result);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            // we do this bec the $result can have no values and this is ok
            $this->response(array('error' => 'bill Check On create Data for '.$orderID. ' could not be found'), 200);
        }

    }
    public function orderShipBarCode_get()
    {
        if(!$this->get('orderID'))
        {
            $this->response(NULL, 400);
        }

        $orderID                    = $this->get('orderID');

        $dateReceived               = Modules::run('orderItems/orderitemcontroller/getDateReceived',$orderID);

        $dateOrderReceivedArr       = explode("-", $dateReceived);

        $yearOrder                  = $dateOrderReceivedArr[0];

        $monthOrder                 = $dateOrderReceivedArr[1];

        $barCodeGenerationData      =    $this->ordershipmodel->barCodeGeneration($orderID);

        for($i=0;$i<sizeof($barCodeGenerationData);$i++)
        {
            $barCodeGenerationData[$i]->yearOrder  = $yearOrder;
            $barCodeGenerationData[$i]->monthOrder = $monthOrder;
        }

        //echo json_encode($barCodeGenerationData);

        if($barCodeGenerationData)
        {
            $this->response($barCodeGenerationData, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'barCodeGenerationData could not be found'), 404);
        }

    }
    public function orderShipDataFromOrderShipID_get()
    {
        if(!$this->get('kp_OrderShipID'))
        {
            $this->response(NULL, 400);
        }

        $orderShipID = $this->get('kp_OrderShipID');

        $result = $this->ordershipmodel->getOrderShipDataFromOrderShipID($orderShipID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'ordership Data could not be found'), 404);
        }

    }
    public function orderShipDataFromOrderShipID_post()
    {
        if(!$this->post('kp_OrderShipID'))
        {
            $this->response(array('orderShipID' => $this->post('kp_OrderShipID'),'error' => 'somethign went wrong kp_OrderShipID not found'), 400);
        }
        else
        {
            $data      = $this->post();
            $orderShipID = $this->post('kp_OrderShipID');

            //echo $orderShipID."<br/><br/>"; print_r($data);

            $result    = $this->ordershipmodel->updateOrderShipTbl($data,$orderShipID);
        }
        if($result == "0" || $result == "1")
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'somethign went wrong orderShip Tbl cannot be updated'), 404);
        }



    }
    public function ordershipTblByOrderShipID_get()
    {
        if(!$this->get('orderShipID'))
        {
            $this->response(NULL, 400);
        }

        $orderShipID = $this->get('orderShipID');

        $result = $this->ordershipmodel->shipDChaining('',$orderShipID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'ordership Data could not be found'), 404);
        }

    }


}
