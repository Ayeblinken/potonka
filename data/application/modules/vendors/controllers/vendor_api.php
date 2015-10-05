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

class Vendor_Api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        if(!checkPermissionsByModule($privSet, 'vendor')) {
          $this->response(array('error' => 'You\'re privilege set doesn\'t allow access to this content'), 400);
        }
        $this->load->model('vendormodel');

    }

    public function vendorInProcessTblData_get() {
      $result = $this->vendormodel->getVendorInProcessTblData();

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No vendors syncing or in error.')), 200);
      }
    }

    public function vendorTblData_get() {
      $result = $this->vendormodel->getVendorTblData();

      if($result) {
        $this->response($result, 200);
      } else {
        $this.response(array('error' => 'Couldn\'t find vendor table data!'), 404);
      }
    }
    public function vendorByName_get()
    {
        if(!$this->get('Name'))
        {
          $this->response(NULL, 400);
        }

        $vendorName = $this->get('Name');


        $result = $this->vendormodel->getVendorByName($vendorName);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array(), 200);
        }

    }
    public function vendorDataByID_get() {
      if(!$this->get('kp_VendorID')) {
        $this->response(array('error' => 'something went wrong with \'get\'.vendorID ID cannot be null'), 400);
      }

      $vendorID = $this->get('kp_VendorID');
      $result = $this->vendormodel->getVendorDataByID($vendorID);

      date_default_timezone_set('America/New_York');

      if(!empty($result['ti_POCutOffTime']) && !is_null($result['ti_POCutOffTime'])) {
        date_default_timezone_set('America/New_York'); // this was hapening on .213 old php
        $result['ti_POCutOffTime'] = strftime('%I:%M %p', strtotime($result['ti_POCutOffTime']));
      }

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array('error' => 'Couldn\'t find vendor data!'), 404);
      }
    }
    public function vendorDataByID_post() {
      if(!$this->post('kp_VendorID')) {
        $this->response(array('error' => 'something went wrong with \'post\'.vendorID ID cannot be null'), 400);
      }

      $data = $this->post('formData');

      date_default_timezone_set('America/New_York');

      if(!empty($data['ti_POCutOffTime']) && !is_null($data['ti_POCutOffTime']) && $data['ti_POCutOffTime'] !== "") {
        $data['ti_POCutOffTime'] = strftime('%H:%M:%S', strtotime($data['ti_POCutOffTime']));
      } else {
        $data['ti_POCutOffTime'] = null;
      }

      $vendorID = $this->post('kp_VendorID');
      $result = $this->vendormodel->updateVendorDataByID($data, $vendorID);
    }
    public function vendors_get()
    {
        $result = $this->vendormodel->getAllVendors();

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'Couldn\'t find any vendors!'), 404);
        }

    }
    public function vendorDistinctCompanyName_get()
    {
        $result = $this->vendormodel->getDistinctVendorCompanyNames();

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'Couldn\'t find any vendors comapny nAMES!'), 404);
        }
    }

}
