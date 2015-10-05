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

class Statuses_Api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        if(!checkPermissionsByModule($privSet, 'status')) {
          $this->response(array('error' => 'You\'re privilege set doesn\'t allow access to this content'), 400);
        }
        $this->load->model('statusmodel');

    }
    public function statusSort_put() {
      if(!$this->put('formData')) {
        $this->response(NULL, 400);
      }
      $statusData = $this->put('formData');

      for($i=0;$i<sizeof($statusData);$i++) {
        $this->statusmodel->updateStatusSort($statusData[$i]);
      }
    }
    public function newStatusName_get()
    {
        $result = $this->statusmodel->newStatusName();

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Statuses could not be found'), 404);
        }

    }
    public function newStatusNamePressProof_get()
    {
        $result = $this->statusmodel->newStatusNamePressProof();

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Statuses could not be found'), 404);
        }

    }
}
