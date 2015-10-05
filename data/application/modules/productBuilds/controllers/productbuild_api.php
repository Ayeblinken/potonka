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

class ProductBuild_Api extends REST_Controller
{
  public function __construct()
  {
    
      $this->load->model('productbuildmodel');

  }

  public function productInProcessList_get() {
    $result = $this->productbuildmodel->getProductInProcessList();

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array(array('error' => 'No Products found.')), 200);
    }
  }

  public function productBuildItemsByBuildItem_get() {
    if(!$this->get('kp_BuildItemID')) {
      $this->response(NULL, 400);
    }

    $buildItemID = $this->get('kp_BuildItemID');

    $result = $this->productbuildmodel->getProductBuildItemsByBuildItem($buildItemID);

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array(), 200);
    }
  }
  public function productBuildByName_get()
  {
      if(!$this->get('Name'))
      {
        $this->response(NULL, 400);
      }

      $productBuildName = $this->get('Name');


      $result = $this->productbuildmodel->getProductBuildByName($productBuildName);

      if($result)
      {
          $this->response($result, 200); // 200 being the HTTP response code
      }
      else
      {
          $this->response(array(), 200);
      }

  }
}
