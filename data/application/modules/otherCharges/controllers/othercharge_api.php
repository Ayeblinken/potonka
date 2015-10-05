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

class OtherCharge_Api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        $this->load->model('otherchargemodel');
    }

    public function insertCharges_post() {
      if(!$this->post('formData')) {
        $this->response(NULL, 400);
      }

      $formData = $this->post('formData');

      for($i = 0; $i < sizeof($formData); $i++) {
        $this->otherchargemodel->insertCharge($formData[$i]);
      }

      $this->response(array('success' => 'Done'), 200);
    }

    public function otherChargeTableData_get() {
      if(!$this->get('orderID')) {
        $this->response(array('error' => 'orderID not found.'), 404);
      }

      $orderID = $this->get('orderID');

      $result = $this->otherchargemodel->otherChargeTableData($orderID);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response($result, 400);
      }
    }

    public function otherChargeInvoiceDataFromOrderID_get()
    {
        if(!$this->get('orderID'))
        {
            $this->response(array('error' => 'orderID not there'), 400);
        }
        $orderID = $this->get('orderID');

        $result  = $this->otherchargemodel->otherChargeInvoiceDataFromOrderID($orderID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response($result, 200);
        }


    }

}
