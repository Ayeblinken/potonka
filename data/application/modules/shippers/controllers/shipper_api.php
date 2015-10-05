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

class Shipper_Api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        $this->load->model('shippermodel');

    }

    public function shipperServices_get() {
      $result = $this->shippermodel->getShipperServices();

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array('error' => 'No Services Found'), 404);
      }

    }

    public function shipperInfo_get()
    {
        $result = $this->shippermodel->getShipperCompanyInfo();

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Shipper api down. please contact IT'), 404);
        }

    }

    public function shipperByName_get()
    {
        if(!$this->get('Name'))
        {
          $this->response(NULL, 400);
        }

        $shipperName = $this->get('Name');


        $result = $this->shippermodel->getShipperByName($shipperName);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array(), 200);
        }

    }

    public function shippingTblData_get() {
      $result = $this->shippermodel->getShippingTblData();

      if($result) {
        $this->response($result, 200);
      } else {
        $this.response(array('error' => 'Couldn\'t find shipping table data!'), 404);
      }
    }
    public function activeShippingTblData_get() {
      $result = $this->shippermodel->getActiveShippingTblData();

      if($result) {
        $this->response($result, 200);
      } else {
        $this.response(array('error' => 'Couldn\'t find shipping table data!'), 404);
      }
    }
    public function shippingDataByID_get() {
      if(!$this->get('kp_ShipperID')) {
        $this->response(array('error' => 'something went wrong with \'get\'.shipperID ID cannot be null'), 400);
      }

      $shipperID = $this->get('kp_ShipperID');
      $result = $this->shippermodel->getShippingDataByID($shipperID);


      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array('error' => 'Couldn\'t find shipping data!'), 404);
      }
    }

}
