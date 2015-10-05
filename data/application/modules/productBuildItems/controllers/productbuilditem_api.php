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

class ProductBuildItem_Api extends REST_Controller {
  public function __construct() {
      parent::__construct();
      $privSet = verifyToken();
      if(!$privSet) {
        $this->response(array('error' => 'Invalid or missing token.'), 401);
      }
      if(!checkPermissionsByModule($privSet, 'product')) {
        $this->response(array('error' => 'You\'re privilege set doesn\'t allow access to this content'), 400);
      }
      $this->load->model('productbuilditemmodel');
  }

  public function productBuildItemTblDataByID_get() {
    if(!$this->get('kf_ProductBuildID')) {
      $this->response(NULL, 400);
    }

    $productBuildID = $this->get('kf_ProductBuildID');

    $result = $this->productbuilditemmodel->getProductBuildItemTblDataByID($productBuildID);

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array(), 200);
    }
  }

  public function productBuildItemDataByID_get() {
    if(!$this->get('kp_ProductBuildItemID')) {
      $this->response(NULL, 400);
    }

    $productBuildItemID = $this->get('kp_ProductBuildItemID');

    $result = $this->productbuilditemmodel->getProductBuildItemDataByID($productBuildItemID);

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array(), 200);
    }
  }


}
