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

class ManCat_Api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        if(!checkPermissionsByModule($privSet, 'mancat')) {
          $this->response(array('error' => 'You\'re privilege set doesn\'t allow access to this content'), 400);
        }
        date_default_timezone_set('America/Indianapolis');
        $this->load->model('mancatmodel');

    }
    public function manCatCategory_get()
    {
        $result = $this->mancatmodel->getManCatCategory();

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'ManCat could not be found'), 404);
        }


    }
    public function manCatSort_put() {
      if(!$this->put('formData')) {
        $this->response(NULL, 400);
      }
      $manCatData = $this->put('formData');

      for($i=0;$i<sizeof($manCatData);$i++) {
        $this->mancatmodel->updateManCatSort($manCatData[$i]);
      }
    }
    public function manCatSubCatSort_put() {
      if(!$this->put('formData')) {
        $this->response(NULL, 400);
      }
      $manCatSubCatData = $this->put('formData');

      for($i=0;$i<sizeof($manCatSubCatData);$i++) {
        $this->mancatmodel->updateManCatSubCatSort($manCatSubCatData[$i]);
      }
    }
    public function manCatSubCatDirectionSort_put() {
      if(!$this->put('formData')) {
        $this->response(NULL, 400);
      }
      $manCatSubCatDirectionData = $this->put('formData');

      for($i=0;$i<sizeof($manCatSubCatDirectionData);$i++) {
        $this->mancatmodel->updateManCatSubCatDirectionSort($manCatSubCatDirectionData[$i]);
      }
    }


}
