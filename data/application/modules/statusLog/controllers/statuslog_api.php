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

class StatusLog_Api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        $this->load->model('statuslogmodel');

    }

    public function statusLogByOrder_get() {
      if(!$this->get('id')) {
        $this->response("id not found", 400);
      }

      $id = $this->get('id');

      $result = $this->statuslogmodel->statusLogTableData($id);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No status logs found.')), 404);
      }
    }

    public function statusLogArry_put()
    {
//        if($this->put('kp_AddressID')) // if there is an value for kp_addressID throw an error
//        {
//            $this->response(array('error' => 'somethign went wrong with \'put\'. Address ID cannot have a value for insert'), 400);
//        }
    }
}
