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

class OrderShipTracking_Api extends REST_Controller
{

    public function __construct() {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        if(!checkPermissionsByModule($privSet, 'order')) {
          $this->response(array('error' => 'You\'re privilege set doesn\'t allow access to this content'), 400);
        }
        $this->load->model('ordershiptrackingmodel');
    }

    public function shippingCharges_get() {
      if(!$this->get('id')) {
          $this->response(NULL, 400);
      }

      $id = $this->get('id');

      $result  = $this->ordershiptrackingmodel->getShippingCharges($id);

      if($result) {
          $this->response($result, 200); // 200 being the HTTP response code
      } else {
          $this->response(array('error' => 'Order could not be found'), 404);
      }
    }



}
