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

class SelectBoxes_Api extends REST_Controller {
  public function __construct() {
    parent::__construct();
    $privSet = verifyToken();
    if(!$privSet) {
      $this->response(array('error' => 'Invalid or missing token.'), 401);
    }
    $this->load->model('selectboxesmodel');
  }

  public function employeeDirectory_get() {
    $result = $this->selectboxesmodel->getEmployeeDirectory();

    if($result) {
      $this->response($result, 200); // 200 being the HTTP response code
    } else {
      $this->response(array(array('error' => "No employees found.")), 200);
    }
  }

  public function vendorsByListID_get() {
    if(!$this->get('id')) {
      $this->response(array('error' => 'List ID could not be found'), 400);
    }

    $listID = $this->get('id');
    $result = $this->selectboxesmodel->getVendorsByListID($listID);

    if($result) {
        $this->response($result, 200); // 200 being the HTTP response code
    } else {
        $this->response(array('error' => 'Vendors could not be found'), 404);
    }
  }

  public function image_post() {
    if(!$this->post('url')) {
      $this->response(array('error' => 'no image address given'));
    }

    $url = $this->post('url');

    return $this->output->set_header("X-Sendfile: $url")->set_header("responseType: arraybuffer");
  }

  public function purchaseRequestItemFromInvtItemID_get() {
      if(!$this->get('kp_InventoryItemID')) {
        $this->response(array('error' => 'InventoryItem could not be found'), 400);
      }

      $inventoryItemID = $this->get('kp_InventoryItemID');
      $result = $this->selectboxesmodel->getPurchaseRequestItemPurchaseRequestFromInvtItemID($inventoryItemID);

      if($result) {
          $this->response($result, 200); // 200 being the HTTP response code
      } else {
          $this->response(array('error' => 'InventoryItem could not be found'), 404);
      }
  }

  public function vendorDistinctCompanyName_get() {
      $result = $this->selectboxesmodel->getDistinctVendorCompanyNames();

      if($result) {
          $this->response($result, 200);
      } else {
          $this->response(array('error' => 'Couldn\'t find any vendors comapny names!'), 404);
      }
  }

  public function customerCreditHoldInfo_get() {
    if(!$this->get('id')) {
      $this->response(NULL, 400);
    }

    $customerID = $this->get('id');

    $result = $this->selectboxesmodel->getCustomerCreditHoldInfo($customerID);

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No Customer found.'), 404);
    }
  }

  public function customerNotes_get() {
    if(!$this->get('id')) {
      $this->response(NULL, 400);
    }

    $customerID = $this->get('id');

    $result = $this->selectboxesmodel->getCustomerNotes($customerID);

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No Customer found.'), 404);
    }
  }

  public function customerNotes_post() {
    if(!$this->post('kp_CustomerID')) {
      $this->response(NULL, 400);
    }

    $customerID = $this->post('kp_CustomerID');

    $data      = $this->post();

    $result = $this->selectboxesmodel->setCustomerNotes($customerID, $data);

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('msg' => 'no update found'), 200);
    }
  }

  public function activeMasterJobsByCustomer_get() {
    if(!$this->get('id')) {
      $this->response(NULL, 400);
    }

    $customerID = $this->get('id');

    $result = $this->selectboxesmodel->getActiveMasterJobsByCustomer($customerID);

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No Jobs found.'), 404);
    }
  }

  public function buildItemDirections_get() {
    if(!$this->get('id')) {
      $this->response(NULL, 400);
    }

    $buildItemID = $this->get('id');

    $result = $this->selectboxesmodel->getBuildItemDirections($buildItemID);

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No Directions found.'), 404);
    }
  }

  public function equipmentSubModes_get() {
    if(!$this->get('id')) {
      $this->response(NULL, 400);
    }

    $equipmentModeID = $this->get('id');

    $result = $this->selectboxesmodel->getEquipmentSubModes($equipmentModeID);

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No Equipment Sub Modes found.'), 404);
    }
  }

  public function inventoryItemList_get() {
    if(!$this->get('id')) {
      $this->response(NULL, 400);
    }

    $buildItemID = $this->get('id');

    $result = $this->selectboxesmodel->getInventoryItemList($buildItemID);

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No Inventory Items found.'), 404);
    }
  }

  public function printMaterialBuildItemsByProduct_get() {
    if(!$this->get('id')) {
      $this->response(NULL, 400);
    }

    $productID = $this->get('id');

    $result = $this->selectboxesmodel->getPrintMaterialBuilditemsByProduct($productID);

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array(array('error' => 'No Print Materials found.')), 200);
    }
  }

  public function printMaterialInvItems_get() {
    if(!$this->get('id')) {
      $this->response(NULL, 400);
    }

    $id  = $this->get('id');

    $result = $this->selectboxesmodel->getPrintMaterialInvItems($id);

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array(array('error' => 'No Product Categories found.')), 200);
    }
  }

  public function relatedBuilditems_get() {
    if(!$this->get('id')) {
      $this->response(NULL, 400);
    }

    $productBuildID  = $this->get('id');

    $result = $this->selectboxesmodel->getRelatedBuilditems($productBuildID);

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No Product Categories found.'), 404);
    }
  }

  public function buildItemsList_get() {
    $result = $this->selectboxesmodel->getBuildItemsList();

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No Build Items found.'), 404);
    }
  }

  public function productBuildsCategoryList_get() {
    $result = $this->selectboxesmodel->getProductBuildsCategoryList();

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No Product Categories found.'), 404);
    }
  }

  public function productBuildsListByCategory_get() {
    if(!$this->get('category')) {
      $this->response(NULL, 400);
    }

    $category  = $this->get('category');

    $result = $this->selectboxesmodel->getProductBuildsListByCategory($category);

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No Product Categories found.'), 404);
    }
  }

  public function productBuildsList_get() {
    $result = $this->selectboxesmodel->getProductBuildsList();

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No Products found.'), 404);
    }
  }

  public function employeeAdminManagerAccounting_get() {
    $result = $this->selectboxesmodel->getEmployeeAdminManagerAccounting();

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No Employees found.'), 404);
    }
  }

  public function employeeSupervisorManagerAccounting_get() {
    $result = $this->selectboxesmodel->getEmployeeSupervisorManagerAccounting();

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No Employees found.'), 404);
    }
  }

  public function creditHoldEmployeeList_get() {
    $result = $this->selectboxesmodel->getCreditHoldEmployeeList();

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No Employees found.'), 404);
    }
  }

  public function employeeList_get() {
    $result = $this->selectboxesmodel->getEmployeeList();

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No Employees found.'), 404);
    }
  }

  public function employeeListCustomerApproval_get() {
    $result = $this->selectboxesmodel->getEmployeeListCustomerApproval();

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No Employees found.'), 404);
    }
  }

  public function employeeSalesList_get() {
    $result = $this->selectboxesmodel->getEmployeeSalesList();

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No Employees found.'), 404);
    }
  }

  public function employeePrepressList_get() {
    $result = $this->selectboxesmodel->getEmployeePrepressList();

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No Employees found.'), 404);
    }
  }

  public function limitedEmployeeDataByName_get() {
    if(!$this->get('name')) {
      $this->response(NULL, 400);
    }

    $name  = $this->get('name');

    $result = $this->selectboxesmodel->getLimitedEmployeeDataByName($name);

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'Address could not be found'), 404);
    }
  }

  public function limitedEmployeeDataByID_get() {
    if(!$this->get('id')) {
      $this->response(NULL, 400);
    }

    $id  = $this->get('id');

    $result = $this->selectboxesmodel->getLimitedEmployeeDataByID($id);

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'Address could not be found'), 404);
    }
  }

  public function orderContactsFromCustomerID_get() {
    if(!$this->get('id')) {
      $this->response(NULL, 400);
    }

    $customerID = $this->get('id');

    $result = $this->selectboxesmodel->getOrderContactsFromCustomerID($customerID);

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No contacts found'), 404);
    }
  }

  public function activeCustomerNames_get() {
    $result = $this->selectboxesmodel->getActiveCustomerNames();

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array(), 200);
    }

  }

  public function activeCustomerNamesWithSales_get() {
    $result = $this->selectboxesmodel->getActiveCustomerNamesWithSales();

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array(), 200);
    }

  }

  public function activeCustomerNamesWithOrderCount_get() {
    $result = $this->selectboxesmodel->getActiveCustomerNames();

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array(), 200);
    }

  }

  public function shipperServices_get() {
    $result = $this->selectboxesmodel->getShipperServices();

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No Services Found'), 404);
    }

  }

  public function machineList_get() {
    $result = $this->selectboxesmodel->getMachineList();

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No Machines Found'), 404);
    }

  }

  public function statusList_get() {
    $result = $this->selectboxesmodel->getStatusList();

    if($result) {
      $this->response($result, 200);
    } else {
      $this->response(array('error' => 'No Statuses Found'), 404);
    }

  }

}
