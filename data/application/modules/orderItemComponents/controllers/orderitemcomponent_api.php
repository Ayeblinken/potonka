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

class OrderItemComponent_Api extends REST_Controller
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
        $this->load->model('orderitemcomponentmodel');
    }

    public function updateComponentsAfterProductChange_put() {
      if(!$this->put('orderItemID') || !$this->put('productBuildID')) {
        $this->response(NULL, 400);
      }

      $orderItemID = $this->put('orderItemID');
      $productBuildID = $this->put('productBuildID');

      //Get Order Item Components
      $components = $this->orderitemcomponentmodel->getOrderItemComponents($orderItemID);

      //Check if the new product build has a matching product build item
      for($i = 0; $i < sizeof($components); $i++) {
        $newProductBuildItemID = null;
        if($components[$i]->t_Category == 'Equipment') {
          if($components[$i]->kf_EquipmentModeID) {
            $newProductBuildItemID = $this->orderitemcomponentmodel->checkForNewProductBuildItemIDEquipment($productBuildID, $components[$i]->kf_EquipmentModeID);
            if($newProductBuildItemID) {
              $this->orderitemcomponentmodel->updateComponents($components[$i]->kp_OrderItemComponentID, $newProductBuildItemID[0]);
            }
          }
        } else if($components[$i]->t_Category == 'Print Material') {

        } else {
          if($components[$i]->kf_BuildItemID) {
            $newProductBuildItemID = $this->orderitemcomponentmodel->checkForNewProductBuildItemID($components[$i]->kf_BuildItemID, $productBuildID);
            if($newProductBuildItemID) {
              $this->orderitemcomponentmodel->updateComponents($components[$i]->kp_OrderItemComponentID, $newProductBuildItemID[0]);
            }
          }
        }
      }

      $this->response(array('success' => "Done"), 200);
    }

    public function insertComponents_post() {
      if(!$this->post('formData') || !$this->post('ids')) {
        $this->response(NULL, 400);
      }

      $formData = $this->post('formData');
      $ids = $this->post('ids');

      for($i = 0; $i < sizeof($ids); $i++) {
        $tempData = $formData;
        $tempData['kf_OrderItemID'] = $ids[$i];
        $this->orderitemcomponentmodel->insertComponents($tempData);
      }

      $this->response(array('success' => 'Done'), 200);
    }

    public function components_put() {
      if(!$this->put('formData') || !$this->put('ids')) {
        $this->response(NULL, 400);
      }

      $formData = $this->put('formData');
      $ids = $this->put('ids');

      for($i = 0; $i < sizeof($ids); $i++) {
        $this->orderitemcomponentmodel->updateComponents($ids[$i], $formData);
      }

      $this->response(array('success' => 'Done'), 200);
    }

    public function duplicateOrderItemComponents_post() {
      if(!$this->post('data')) {
        $this->response(NULL, 400);
      }

      $components = $this->post('data');

      for($i=0; $i < sizeof($components); $i++) {
        $result = $this->orderitemcomponentmodel->insertDuplicateOrderItemComponents($components[$i]);
      }

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array('error' => 'Something went wrong.'), 404);
      }
    }

    public function orderItemComponents_get() {
      if(!$this->get('id')) {
        $this->response(NULL, 400);
      }

      $id = $this->get('id');

      $result = $this->orderitemcomponentmodel->getOrderItemComponents($id);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No Components')), 200);
      }
    }



}


?>
