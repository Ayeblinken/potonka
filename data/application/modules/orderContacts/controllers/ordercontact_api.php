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

class OrderContact_Api extends REST_Controller {

  public function __construct() {
    parent::__construct();
    $privSet = verifyToken();
    if(!$privSet) {
      $this->response(array('error' => 'Invalid or missing token.'), 401);
    }
    if(!checkPermissionsByModule($privSet, 'order')) {
      $this->response(array('error' => 'You\'re privilege set doesn\'t allow access to this content'), 400);
    }
    $this->load->model('ordercontactmodel');
  }

  public function orderContactJoinAddByOrderID_get()
  {
      if(!$this->get('orderID'))
      {
        $this->response(NULL, 400);
      }
      $orderID= $this->get('orderID');

      $result = $this->ordercontactmodel->orderContactTableData($orderID);

      if($result)
      {
          $this->response($result, 200); // 200 being the HTTP response code
      }

      else
      {
          $this->response(array('error' => 'Order Contact could not be found'), 404);
      }

  }  

  public function orderContacts_post() {
    if(!$this->post('data')) {
      $this->response(NULL, 400);
    }

    $data = $this->post('data');

    $lenArr = sizeof($data);

    for($i = 0; $i<$lenArr; $i++) {
      $this->ordercontactmodel->insertOrderContact($data[$i]);
    }

    $this->response(NULL, 200);
  }

  public function contactAddressData_get() {
    if(!$this->get('id')) {
      $this->response(NULL, 400);
    }

    $orderID = $this->get('id');

    $result = $this->ordercontactmodel->getContactAddressData($orderID);

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No contacts found'), 404);
    }
  }


}


?>
