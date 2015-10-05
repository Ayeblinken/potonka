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

class OrderRedo_Api extends REST_Controller
{
    public function __construct() {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        // if(!checkPermissionsByModule($privSet, 'orderredo')) {
        //   $this->response(array('error' => 'You\'re privilege set doesn\'t allow access to this content'), 400);
        // }
        $this->load->model('orderredomodel');

    }

    public function redoTableData_get() {
      $result = $this->orderredomodel->getRedoTableData();

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'Redos could not be found')), 200);
      }
    }

    public function orderRedoImages_get() {
      if(!$this->get('orderID') || !$this->get('orderRedoID')) {
        $this->response(NULL, 400);
      }

      $orderID = $this->get('orderID');
      $orderRedoID = $this->get('orderRedoID');

      $dateReceived = Modules::run('orderItems/orderitemcontroller/getDateReceived',$orderID);

      $img = $this->orderredomodel->getOrderRedoImageContent($orderRedoID,$orderID,$dateReceived);

      $this->response($img, 200);
    }

    public function orderRedoImgUpload_post() {
      $orderRedoID = $this->input->post('kp_OrderRedoID');
      $orderID = $this->input->post('kp_OrderID');

      $orderDateReceived = Modules::run('orderItems/orderitemcontroller/getDateReceived',$orderID);

      $dateReceivedArry  = explode("-", $orderDateReceived);

      $yearOrder         = $dateReceivedArry[0];
      $monthOrder        = $dateReceivedArry[1];

      $result       = $this->orderredomodel->doRedoImageUpload($orderRedoID,$orderID,$yearOrder,$monthOrder);

      $this->response($result, 200);
    }

    public function orderRedoRequest_put() {
      if(!$this->put('data') || !$this->put('orderItems') || !$this->put('orderItemsToAdd') || !$this->put('orderItemsToDelete')) {
        $this->response(NULL, 400);
      }

      $partialOrderItemsWithRedo = $this->put('orderItems');
      $partialOrderItemsWithRedoArry = explode(",", $partialOrderItemsWithRedo);
      $orderRedoData = $this->put('data');
      $orderID = $orderRedoData['kf_OrderID'];
      $orderRedoID = $orderRedoData['kp_OrderRedoID'];
      $orderItemsToAdd = $this->put('orderItemsToAdd');
      $orderItemsToDelete = $this->put('orderItemsToDelete');

      date_default_timezone_set('America/Indianapolis');

      $orderIDArry                          = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$orderID);
      $customerID                           = $orderIDArry['kf_CustomerID'];
      $customerIDArry                       = Modules::run('customers/customercontroller/getCustomerFieldsFromCustomerID',$customerID);
      $customerCompanyName                  = $customerIDArry['t_CustCompany'];
      $salesPersonEmail                     = $this->orderredomodel->getSalesEmailFromID($customerIDArry['kf_EmployeeID_Sales']);

      $emailData['salesEmail']              = $salesPersonEmail[0]->t_EmployeeEmail;
      $emailData['companyName']             = "<strong>Company Name:</strong> ".$customerCompanyName;
      $emailData['requestedBy']             = "<strong>Request By</strong>: ".$orderRedoData['t_RequestedBy'];
      $emailData['oldOrderID']              = $orderID;
      $emailData['orderRedoID']             = $orderRedoID;

      $emailSubject                         = "Redo - ".$customerCompanyName." - ".$orderID;
      $emailData['emailSubject']            = $emailSubject;
      $emailData['redoStatus']              = $orderRedoData['t_Status'];
      $emailData['redoRequestedBy']         = Modules::run('orderRedo/orderredocontroller/orderRedoEmployeeEmailAddress', $orderRedoData['t_RequestedBy']);

      if($orderItemsToAdd != "None") {
        for($i = 0; $i < sizeof($orderItemsToAdd); $i++) {
          $orderItemsToAdd[$i]['kf_OrderRedoID'] = $orderRedoID;
          $this->orderredomodel->insertOrderRedoItem($orderItemsToAdd[$i]);
        }
      }

      if($orderItemsToDelete != "None") {
        for($i = 0; $i < sizeof($orderItemsToDelete); $i++) {
          $this->orderredomodel->deleteOrderRedoItem($orderItemsToDelete[$i]);
        }
      }

      if($orderRedoData['t_Status'] == "Approved") {
        //Duplicate Order and all associated data
        $typeOfJobTicket            = $orderIDArry['t_TypeOfJobTicket'];
        $typeOfOrder                = "Redo";

        //1. duplicate Order from old OrderID then make QR Code
        $newOrderID                 = Modules::run('orders/ordercontroller/duplicateOrder',$orderID,$typeOfJobTicket,$typeOfOrder,$orderID,$orderRedoID);
        Modules::run('orders/ordercontroller/createQRCodeFromOrderID', $newOrderID);

        //2. duplicate OrderItem and OrderItem Components - Based on Partial and All Items
        if($orderRedoData['t_ItemsRedo'] == "Partial") {
          $partialAllorderItemArry    = array();
          $y                          = 0;
          $oldOrderItemArry = Modules::run('orderItems/orderitemcontroller/getOrderItemsFromOrderID',$orderID);

          $errors = array_filter($partialOrderItemsWithRedoArry);

          if(!empty($errors)) {
            foreach($partialOrderItemsWithRedoArry as $orderItemID) {
              for($i=0;$i<sizeof($oldOrderItemArry);$i++) {
                $orderItemIDFound = in_array($orderItemID, $oldOrderItemArry[$i]);
                if($orderItemIDFound) {
                  $partialAllorderItemArry[$y]=$oldOrderItemArry[$i];
                  $y++;
                }
              }
            }
            for($i=0; $i<sizeof($partialAllorderItemArry); $i++) {
              $oldOrderItemID                                                = $partialAllorderItemArry[$i]['kp_OrderItemID'];
              $partialAllorderItemArry[$i]['kp_OrderItemID']                 = "";
              $partialAllorderItemArry[$i]['kf_OrderID']                     = $newOrderID;
              $partialAllorderItemArry[$i]['t_OiStatus']                     = null;
              $partialAllorderItemArry[$i]['t_SportJobNumber']               = null;
              $partialAllorderItemArry[$i]['t_SportItemNumber']              = null;
              $partialAllorderItemArry[$i]['t_SportLocationNumber']          = null;
              $partialAllorderItemArry[$i]['d_ArtReceived']                  = null;
              $partialAllorderItemArry[$i]['nb_ArtReceivedProduction']       = null;
              $partialAllorderItemArry[$i]['t_ArtReceivedBy']                = null;
              $partialAllorderItemArry[$i]['t_ArtContact']                   = null;
              $partialAllorderItemArry[$i]['t_OrderItemImage']               = null;
              $partialAllorderItemArry[$i]['t_OrderItemProof']               = null;
              $partialAllorderItemArry[$i]['n_Price']                        = "0.00";

              $orderItemArryResult                                           = array($partialAllorderItemArry[$i]);

              $newOrderItemID                                                = Modules::run('orderItems/orderitemcontroller/insertIntoOrderItemTable',$orderItemArryResult);

              $oldOrderItemComponentArry = Modules::run('orderItemComponents/orderitemcomponentcontroller/getOrderItemComponentArrayFromOrderItemID',$oldOrderItemID);

              for($x=0; $x<sizeof($oldOrderItemComponentArry); $x++) {
                $oldOrderItemComponentArry[$x]['kp_OrderItemComponentID']  = "";
                $oldOrderItemComponentArry[$x]['kf_OrderID']               = $newOrderID;
                $oldOrderItemComponentArry[$x]['kf_OrderItemID']           = $newOrderItemID;

              }
              //7. Insert the duplicated OIC array in the OIC able.
              Modules::run('orderItemComponents/orderitemcomponentcontroller/submitOrderItemComponentTable',$oldOrderItemComponentArry);

            }
          }
        } else if($orderRedoData['t_ItemsRedo'] == "All Items") {
          Modules::run('orders/ordercontroller/dupOrderItemOrderItemComponents',$orderID,$newOrderID);
        }

        //3. duplicate OrderShip
        //3.1 get the orderShipArry from the old OrderID
        $getOrderShipArrFromOrderID           = Modules::run('orderShip/ordershipcontroller/getOrderShipTblFromOrderID',$orderID);

        if(!empty($getOrderShipArrFromOrderID)) {
          //3. Duplicate orderShipArr with the new OrderID and Insert the duplicated orderShipArr array in the orderShip table.
          Modules::run('orderShip/ordershipcontroller/duplicateOrderShipDataFromOrderID',$getOrderShipArrFromOrderID,$newOrderID);
        }
        $orderRedoData['kf_OrderIDRedo'] =  $newOrderID;
        $orderRedoData['ts_DateApproved'] = date('Y-m-d H:i:s');

        $this->orderredomodel->updateOrderRedoTable($orderRedoID,$orderRedoData);


        $emailData['newOrderID']   = $newOrderID;

        Modules::run('orderRedo/orderredocontroller/orderRedoSendEmail', $emailData);

        $this->response(array('id' => $newOrderID), 200);

      } else {
        // do a regular update
        $orderRedoData['kf_OrderIDRedo'] = null;
        $orderRedoData['ts_DateApproved'] = null;
        $this->orderredomodel->updateOrderRedoTable($orderRedoID,$orderRedoData);

        $orderRedoItemDataArry                = array();
        $orderRedoItemDataArry['orderItemID'] = $partialOrderItemsWithRedoArry;
        $orderRedoItemDataArry['orderRedoID'] = $orderRedoID;

        $orderItemIDArry                      = Modules::run('orderRedoItems/orderredoitemcontroller/getOrderItemsFromOrderRedoItemArry',$orderRedoID);

        Modules::run('orderRedoItems/orderredoitemcontroller/deleteOrderRedoItems',$orderRedoID);
        Modules::run('orderRedo/orderredocontroller/insertIntoOrderRedoItemTable', $orderRedoItemDataArry);
        Modules::run('orderRedo/orderredocontroller/orderRedoSendEmail', $emailData);

        $this->response(array('done' => "Done"), 200);
      }
    }

    public function orderRedoRequest_post() {
      if(!$this->post('id') || !$this->post('redoItemsToAdd') || !$this->post('data')) {
        $this->response(NULL, 400);
      }

      $orderID = $this->post('id');
      $redoItemsToAdd = $this->post('redoItemsToAdd');
      $orderRedoData = $this->post('data');

      date_default_timezone_set('America/Indianapolis');

      //Insert Redo Request
      $orderRedoData['ts_DateRequested']    = date('Y-m-d H:i:s');
      $orderRedoData['kf_OrderID']          = $orderID;

      $orderRedoDataArr                     = array();
      $orderRedoDataArr[0]                  = $orderRedoData;

      $newInsertedOrderRedoID               = $this->orderredomodel->insertOrderRedoTable($orderRedoDataArr);

      //Create Redo Items
      for($i = 0; $i < sizeof($redoItemsToAdd); $i++) {
        $redoItemsToAdd[$i]['kf_OrderRedoID'] = $newInsertedOrderRedoID;
        $this->orderredomodel->insertOrderRedoItem($redoItemsToAdd[$i]);
      }

      $orderIDArry                          = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$orderID);

      $customerID                           = $orderIDArry['kf_CustomerID'];

      $customerIDArry                       = Modules::run('customers/customercontroller/getCustomerFieldsFromCustomerID',$customerID);
      $customerCompanyName                  = $customerIDArry['t_CustCompany'];

      $salesPersonEmail                     = $this->orderredomodel->getSalesEmailFromID($customerIDArry['kf_EmployeeID_Sales']);



      $emailSubject                         = "Redo Request Sales";
      $emailData['salesEmail']              = $salesPersonEmail[0]->t_EmployeeEmail;
      $emailData['companyName']             = "<strong>Company Name:</strong> ".$customerCompanyName;
      $emailData['requestedBy']             = "<strong>Request By</strong>: ".$orderRedoData['t_RequestedBy'];
      $emailData['oldOrderID']              = $orderID;
      $emailData['orderRedoID']             = $newInsertedOrderRedoID;


      $emailSubject                         = "Redo - ".$customerCompanyName." - ".$orderID;

      $emailData['emailSubject']            = $emailSubject;
      $emailData['redoStatus']              = "Pending";

      $emailData['redoRequestedBy']         = Modules::run('orderRedo/orderredocontroller/orderRedoEmployeeEmailAddress', $orderRedoData['t_RequestedBy']);;

      Modules::run('orderRedo/orderredocontroller/orderRedoSendEmail', $emailData);
      $this->response(array('id' => $newInsertedOrderRedoID), 200);

    }

    public function redoList_get() {
      if(!$this->get('id')) {
        $this->response(array('error' => "id not found"), 400);
      }

      $orderID = $this->get('id');

      $result = $this->orderredomodel->getRedoList($orderID);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'Redos could not be found')), 200);
      }
    }

}


?>
