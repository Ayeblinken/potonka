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

class OrderItem_Api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        if(!checkPermissionsByModule($privSet, 'orderitem')) {
          $this->response(array('error' => 'You\'re privilege set doesn\'t allow access to this content'), 400);
        }
        $this->load->model('orderitemmodel');

    }

    public function orderItemEmailTrackingInfoFromOrderID_get() {
        if(!$this->get('orderID')) {
            $this->response(array('error' => 'orderID not there'), 400);
        }
        $orderID = $this->get('orderID');

        $result  = $this->orderitemmodel->orderItemEmailTrackingInfoDataFromOrderID($orderID);

        if($result) {
            $this->response($result, 200);
        } else {
            $this->response(array('error' => 'we cant get orderItemInvoiceData'), 404);
        }
    }

    public function dupeOrderItem_post() {
      if(!$this->post('id') || !$this->post('data')) {
        $this->response(array(array('error' => 'Order Item Info Not Found')), 400);
      }
      $oldOrderItemID = $this->post('id');
      $newOrderItemData = $this->post('data');

      //Get Order Item Data
      $orderItemDataArr = $this->orderitemmodel->getOrderItemByID($oldOrderItemID);
      $orderItemData = $orderItemDataArr[0];
      // $this->response(array('result' => $orderItemData), 200);

      //Modify order item data
      if($newOrderItemData['nb_DoNotInvoice'] != "1") {
        $orderItemData['n_Price'] = $newOrderItemData['n_Price'];
      }
      $orderItemData['kp_OrderItemID'] = null;
      $orderItemData['n_DashNum'] = $newOrderItemData['n_DashNum'];
      $orderItemData['n_HeightInInches'] = $newOrderItemData['n_HeightInInches'];
      $orderItemData['n_WidthInInches'] = $newOrderItemData['n_WidthInInches'];
      $orderItemData['n_Quantity'] = $newOrderItemData['n_Quantity'];
      $orderItemData['t_Description'] = $newOrderItemData['t_Description'];
      $orderItemData['t_Structure'] = $newOrderItemData['t_Structure'];
      $orderItemData['t_Pricing'] = $newOrderItemData['t_Pricing'];
      $orderItemData['nb_DoNotInvoice'] = $newOrderItemData['nb_DoNotInvoice'];
      $orderItemData['t_SportJobNumber'] = $newOrderItemData['t_SportJobNumber'];
      $orderItemData['t_SportItemNumber'] = $newOrderItemData['t_SportItemNumber'];
      $orderItemData['t_SportLocationNumber'] = $newOrderItemData['t_SportLocationNumber'];
      $orderItemData['n_PackingListQuantity']     = null;
      $orderItemData['nb_PackingList']            = null;

      // $this->response(array('result' => $orderItemData), 200);

      //Insert New Order Item
      $newOrderItemID = $this->orderitemmodel->insertOrderItem($orderItemData);

      //Get Old Order Item Components
      $orderItemComponents = $this->orderitemmodel->getOrderItemComponents($oldOrderItemID);

      //Modify and insert components
      for($i = 0; $i < sizeof($orderItemComponents); $i++) {
        if($orderItemComponents[$i]['kf_LinkedEquipmentID']) {
          if($orderItemComponents[$i]['nb_CustomSize'] != "1") {
            $orderItemComponents[$i]['n_HeightInInches'] = $newOrderItemData['n_HeightInInches'];
            $orderItemComponents[$i]['n_WidthInInches'] = $newOrderItemData['n_WidthInInches'];
            $orderItemComponents[$i]['n_Quantity'] = $newOrderItemData['n_Quantity'];
          }
          $orderItemComponents[$i]['t_Description'] = $newOrderItemData['t_Description'];
        }
        $orderItemComponents[$i]['kp_OrderItemComponentID'] = null;
        $orderItemComponents[$i]['kf_OrderItemID'] = $newOrderItemID;
        $this->orderitemmodel->insertOrderItemComponent($orderItemComponents[$i]);
      }

      $this->response(array('id' => $newOrderItemID), 200);
    }


    public function clearPricingData_put() {
      if(!$this->put('id') || !$this->put('formData')) {
        $this->response(array('error' => "id or formData not found"), 400);
      }

      $orderID = $this->put('id');
      $formData = $this->put('formData');

      $result = $this->orderitemmodel->getOrderItemTblFromOrderIDResultObject($orderID);

      for($i = 0; $i < sizeof($result); $i++) {
        $this->orderitemmodel->updateOrderItem($result[$i]['OrderItemID'], $formData);
      }

      $orderUpdate = array();
      $orderUpdate['nb_UseTotalOrderPricing'] = "1";

      $this->orderitemmodel->updateOrder($orderID, $orderUpdate);

      $this->response(array('success' => 'Pricing has been cleared'), 200);
    }

    public function addOrderItemTemplates_get() {
      if(!$this->get('customerID') || !$this->get('productBuildID')) {
        $this->response(NULL, 400);
      }

      $customerID = $this->get('customerID');
      $productBuildID = $this->get('productBuildID');

      $result = $this->orderitemmodel->addOrderItemTemplates($customerID, $productBuildID);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array('error' => 'Templates could not be found'), 404);
      }
    }

    public function dupeTemplateToOrder_post() {
      if(!$this->post('orderID') || !$this->post('orderItemID') || !$this->post('customerID') || !$this->post('dashNum')) {
        $this->response(NULL, 400);
      }

      $orderID = $this->post('orderID');
      $orderItemID = $this->post('orderItemID');
      $customerID = $this->post('customerID');
      $dashNum = $this->post('dashNum');

      //Get template order item from id
      $templateData  = Modules::run('orderItems/orderitemcontroller/getOrderItemFieldsFromOrderItemID',$orderItemID);
      $templateData[0]['n_DashNum'] = $dashNum;
      $templateData[0]['kf_CustomerID'] = $customerID;
      $templateData[0]['kf_OrderID'] = $orderID;
      $templateData[0]['kp_OrderItemID'] = null;
      $templateData[0]['t_OrderItemImage'] = null;
      $templateData[0]['t_DeckSheet'] = null;
      $templateData[0]['t_OrderItemProof'] = null;
      $templateData[0]['nb_Template'] = null;
      $templateData[0]['t_TemplateName'] = null;

      //Insert template data with new dash number and customer id
      $newOrderItemID  = $this->orderitemmodel->insertOrderItemTable($templateData);

      //Get Template Components
      $orderItemComponents = Modules::run('orderItemComponents/orderitemcomponentcontroller/getOrderItemComponentArrayFromOrderItemID',$orderItemID);

      //Replace order and order item ids in component data
      for($i = 0; $i < sizeof($orderItemComponents); $i++) {
        $orderItemComponents[$i]['kf_OrderID'] = $orderID;
        $orderItemComponents[$i]['kf_OrderItemID'] = $newOrderItemID;
        $orderItemComponents[$i]['kp_OrderItemComponentID'] = null;
      }

      //Insert edited component data
      Modules::run('orderItemComponents/orderitemcomponentcontroller/submitOrderItemComponentTable',$orderItemComponents);

      $this->response(array('id' => $newOrderItemID), 200);
    }

    public function dupeOrderItemCreateTemplate_post() {
      if(!$this->post('templateChoice') || !$this->post('id') || !$this->post('name')) {
        $this->response(NULL, 400);
      }

      $templateChoice = $this->post('templateChoice');
      $orderItemID = $this->post('id');
      $name = $this->post('name');

      // $oldOrderItemArray                      = $this->getOrderItemFieldsFromOrderItemID($orderItemID);
      $oldOrderItemArray                      = Modules::run('orderItems/orderitemcontroller/getOrderItemFieldsFromOrderItemID',$orderItemID);

      if(!is_null($oldOrderItemArray[0]['kf_OrderID']) && !empty($oldOrderItemArray[0]['kf_OrderID'])) {
        $oldOrderIDArry                         = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$oldOrderItemArray[0]['kf_OrderID']);
        // $this->response(array('error' =>$oldOrderIDArry), 404);
        $customerArryForOldOrderID              = Modules::run('customers/customercontroller/getCustomerFieldsFromCustomerID',$oldOrderIDArry['kf_CustomerID']);
        // $this->response(array('error' =>$customerArryForOldOrderID), 404);
      }

      $oldOrderItemArray[0]['kp_OrderItemID'] = "";
      $oldOrderItemArray[0]['kf_OrderID']     = null;
      $oldOrderItemArray[0]['nb_Template']    = 1;
      $oldOrderItemArray[0]['t_TemplateName'] = $name;

      $oldOrderItemComponentArry              = Modules::run('orderItemComponents/orderitemcomponentcontroller/getOrderItemComponentArrayFromOrderItemID',$orderItemID);

      if($templateChoice == "guide") {
        $oldOrderItemArray[0]['kf_CustomerID'] = "2877";

      } else if($templateChoice == "customer") {
        $oldOrderItemArray[0]['kf_CustomerID'] = $customerArryForOldOrderID['kp_CustomerID'];
      }

      $lastInsertedOrderItemID                = $this->orderitemmodel->insertOrderItemTable($oldOrderItemArray);

      $lenArr                                 = sizeof($oldOrderItemComponentArry);

      for($i = 0; $i<$lenArr; $i++) {
        $oldOrderItemComponentArry[$i]['kp_OrderItemComponentID']   = '';
        $oldOrderItemComponentArry[$i]['kf_OrderItemID']            = $lastInsertedOrderItemID;
        $oldOrderItemComponentArry[$i]['kf_OrderID']                = null;
      }

      Modules::run('orderItemComponents/orderitemcomponentcontroller/submitOrderItemComponentTable',$oldOrderItemComponentArry);

      return $lastInsertedOrderItemID;


    }

    public function uploadPrepressImages_post() {
      $inputFile = "upl";

      $orderID      = $this->input->post('orderID');
      $dateReceived = $this->input->post('dateReceived');

      $fileName = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);
      $splitName = explode('-', $fileName);
      if(count($splitName) != 2) {
        $this->response(array('error' => "Invalid file name"), 400);
      }
      $dashNum = $splitName[1];

      $orderItem    = $this->orderitemmodel->getOrderItemIDFromOrderIDDashNum($orderID, $dashNum);
      $orderItemID  = $orderItem['kp_OrderItemID'];

      $result       = $this->orderitemmodel->doPrepressCustomUpload($orderItemID,$orderID,$dateReceived,$inputFile);
      $result['kp_OrderItemID'] = $orderItemID;



      if($result['msg'] == "success") {
        $this->response($result, 200);
      } else {
        $this->response(array('error' => $result), 400);
      }
    }

    public function uploadProofImages_post() {
      $inputFile = "proof";

      $orderID      = $this->input->post('orderID');
      $dateReceived = $this->input->post('dateReceived');
      $data         = json_decode($this->input->post('data'));

      $fileName = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);
      $splitName = explode('-', $fileName);
      if(count($splitName) != 2) {
        $this->response(array('error' => "Invalid file name"), 400);
      }
      $dashNum = $splitName[1];

      $orderItem    = $this->orderitemmodel->getOrderItemIDFromOrderIDDashNum($orderID, $dashNum);
      $orderItemID  = $orderItem['kp_OrderItemID'];

      $result       = $this->orderitemmodel->doPrepressCustomUpload($orderItemID,$orderID,$dateReceived,$inputFile);
      $result['kp_OrderItemID'] = $orderItemID;

      $this->orderitemmodel->updateOrderItem($orderItemID, $data);



      if($result['msg'] == "success") {
        $this->response($result, 200);
      } else {
        $this->response(array('error' => $result), 400);
      }
    }

    public function uploadPrepressImage_post() {
      $sportUploadImage = $this->input->post('sportUploadImage');

      if($sportUploadImage == "1") {
        $inputFile = "sportUploadFile";
      } else if($sportUploadImage == "2") {
        $inputFile = "proof";
      } else {
        $inputFile = "upl";
      }

      $orderItemID  = $this->input->post('orderItemID');
      $orderID      = $this->input->post('orderID');
      $dateReceived = $this->input->post('dateReceived');
      $result       = $this->orderitemmodel->doPrepressCustomUpload($orderItemID,$orderID,$dateReceived,$inputFile);



      if($result['msg'] == "success") {
        $this->response($result, 200);
      } else {
        $this->response(array('error' => $result), 400);
      }
    }

    // public function orderItemImgUpload_post() {
    //   $data = $this->post();
    //   $orderItemID = $data['orderItemID'];
    //   $orderID = $data['orderID'];
    //   $dateReceived = $data['dateReceived'];
    //
    //   if(!empty($_FILES['file']['name'])) {
    //     $msg            = $this->orderitemmodel->doPrepressCustomUpload($orderItemID,$orderID,$dateReceived);
    //
    //     if($msg) {
    //       $this->response(array('path' => $msg), 200); // 200 being the HTTP response code
    //     } else {
    //       $this->response($msg, 404);
    //     }
    //
    //   }
    // }

    public function customerTemplates_get() {
      if(!$this->get('id')) {
        $this->response(NULL, 400);
      }

      $id = $this->get('id');

      $result = $this->orderitemmodel->getCustomerTemplates($id);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array('error' => 'Templates could not be found'), 404);
      }
    }

    public function packingQuantity_put() {
      if(!$this->put('formData')) {
        $this->response(NULL, 400);
      }

      $orderItems = $this->put('formData');

      for($i=0;$i<sizeof($orderItems);$i++) {
        $this->orderitemmodel->updatePackingQuantity($orderItems[$i]['kp_OrderItemID'], $orderItems[$i]);
      }

      $this->response(NULL, 200);
    }

    public function orderItemsStatusForOrder_get() {
      if(!$this->get('id')) {
        $this->response(NULL, 400);
      }

      $id = $this->get('id');

      $result = $this->orderitemmodel->getOrderItemsStatusForOrder($id);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array('error' => 'Address could not be found'), 404);
      }
    }

    public function OrderItemTblRowsFromOrderIDResultObject_get()
    {
        if(!$this->get('orderID'))
        {
            $this->response(array('error' => 'orderID not there'), 400);
        }
        $orderID= $this->get('orderID');

        $result = $this->orderitemmodel->getOrderItemTblFromOrderIDResultObject($orderID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Address could not be found'), 404);
        }
    }
    public function OrderItemsFromOrderIDCheckList_get()
    {
        if(!$this->get('orderID'))
        {
            $this->response(array('error' => 'orderID not there'), 400);
        }
        $orderID= $this->get('orderID');

        $result = $this->orderitemmodel->getOrderItemsFromOrderIDCheckList($orderID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Address could not be found'), 404);
        }
    }
    public function orderItemInvoiceDataFromOrderID_get()
    {
        if(!$this->get('orderID'))
        {
            $this->response(array('error' => 'orderID not there'), 400);
        }
        $orderID = $this->get('orderID');

        $result  = $this->orderitemmodel->orderItemInvoiceDataFromOrderID($orderID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'we cant get orderItemInvoiceData'), 404);
        }

    }
    public function orderItem_delete($id)
    {
        $row = $this->orderitemmodel->getOrderItemByID($id);
        //echo $row[0]['kp_OrderItemID'];


        $oicArrayFromOrderItemID    = Modules::run('orderItemComponents/orderitemcomponentcontroller/getOrderItemComponentArrayFromOrderItemID',$row[0]['kp_OrderItemID']);



        for($x=0; $x<sizeof($oicArrayFromOrderItemID); $x++)
        {
            $msg=Modules::run('orderItemComponents/orderitemcomponentcontroller/deleteOrderItemComponentTableRow',$oicArrayFromOrderItemID[$x]['kp_OrderItemComponentID']);

        }
        $this->orderitemmodel->deleteOrderItemDataFromOrderItemID($id);

        $this->response(array(
            'returned from delete:' => $id,
        ));
    }

}


?>
