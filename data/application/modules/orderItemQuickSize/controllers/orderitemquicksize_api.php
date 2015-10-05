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

class OrderItemQuickSize_Api extends REST_Controller
{
    public function __construct() {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        if(!checkPermissionsByModule($privSet, 'orderitem')) {
          $this->response(array('error' => 'You\'re privilege set doesn\'t allow access to this content'), 400);
        }
        $this->load->model('orderitemquicksizemodel');
    }

    public function quickSizeSort_put() {
      if(!$this->put('formData')) {
        $this->response(NULL, 400);
      }
      $data = $this->put('formData');

      for($i=0;$i<sizeof($data);$i++) {
        $this->orderitemquicksizemodel->updateQuickSizeSort($data[$i]);
      }

      // $this->response($data, 200);
    }



}


?>
