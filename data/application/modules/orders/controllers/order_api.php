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

class Order_Api extends REST_Controller
{

    public function __construct() {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        if(!checkPermissionsByModule($privSet, 'order')) {
          $this->response(array('error' => 'You\'re privilege set doesn\'t allow access to this content'), 400);
        }
        date_default_timezone_set('America/Indianapolis');
        $this->load->model('ordermodel');
    }

    public function uploadInspectionImages_post() {
      $orderID = $this->input->post('orderID');
      $shortID = substr($orderID, 0, 3);

      $result = $this->ordermodel->uploadInspectionImage($orderID, $shortID);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array('error' => $result), 400);
      }
    }

    public function orderInspectionPictures_get() {
      if(!$this->get('id')) {
        $this->response(array('error' => 'Order ID not provided.'));
      }

      $orderID = $this->get('id');

      $data['shortOrderNum'] = substr($orderID, 0, 3); //get the first three character of the orderID #
      $data['orderID']       = $orderID;
      $img                   = $this->ordermodel->getOrderInspectionImageContent($data);

      $this->response($img, 200);
    }

    public function orderShipToProductionGroupConversion_get() {
      $result = $this->ordermodel->getOrderShipToProductionGroupConversion();

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No Records found.')), 404);
      }
    }

    public function getReadyToFinishSign_get() {
      $date = $this->get('date');
      $result = $this->ordermodel->getReadyToFinishSign($date);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No orders found.')), 200);
      }
    }

    public function getReadyToCutSign_get() {
      $date = $this->get('date');
      $result = $this->ordermodel->getReadyToCutSign($date);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No orders found.')), 200);
      }
    }

    public function getReadyToLamSign_get() {
      $date = $this->get('date');
      $result = $this->ordermodel->getReadyToLamSign($date);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No orders found.')), 200);
      }
    }

    public function sendProof_post() {
      if(!$this->post('id') || !$this->post('to') || !$this->post('type') || !$this->post('po') || !$this->post('jobName')) {
        $this->response(NULL, 400);
      }

      $orderID = $this->post('id');
      $to = $this->post('to');
      $type = $this->post('type');
      $po = $this->post('po');
      $jobName = $this->post('jobName');
      $url = '"http://reports.indyimaging.com:8080/jasperserver/flow.html?_flowId=viewReportFlow&standAlone=true&_flowId=viewReportFlow&ParentFolderUri=%2Freports%2FPrepress&reportUnit=%2Freports%2FPrepress%2FproofAll&j_username=joeuser&j_password=joeuser&orderID=' . $orderID . '&output=pdf"';
      $replyTo = 'proofapproved@indyimaging.com';

      // $filePath = "/Applications/XAMPP/xamppfiles/htdocs/jasperSoap/pressProof/";
      $filePath = "/var/www/html/jasperSoap/pressProof";
      $fileName = $orderID . ".pdf";
      $file = $filePath . $fileName;
      // $cmd = '/usr/local/bin/wget -O ' . $file . ' ' . $url . ' 2>&1';
      $cmd = 'wget -O ' . $file . ' ' . $url . ' 2>&1';
      $output = array();
      exec($cmd, $output);

      // $to = 'matthew.sickler@indyimaging.com';
      if($type == 'Not Approved') {
        $body = 'These Proofs were marked <strong>Not Approved</strong>. You will need to review and approve the updated proofs.

Reply: Approved or Not Approved

Possible reasons you are receiving this email: Customer sent New Art
Customer Change something in the file.';
        $subject = "Sales Please Approve - Order# $orderID - $jobName PO: $po";
      } else {
        $body = 'Hello,<br/><br/>

Attached are your proofs for ' . $jobName . ' on PO: ' . $po . ' on our Order# ' . $orderID . '.<br/><br/>

Please make sure to check your proof(s) carefully for correct telephone numbers, email, web addresses, name spellings, addresses and other text as well as dimensions(size) and design(layout). Colors will be matched to the Pantone numbers listed on the proof, otherwise colors will be examined and setup using our printing experience and best judgment.<br/><br/>

To <strong>Approve</strong> the proof, simply reply to this email with "Approved", or print, sign and fax approved proof to (317) 917-7998.<br/><br/>

If you <strong>Do Not Approve</strong> the proof, simply reply to this email with "Not Approved", stating the changes that need to be made, or print, mark as "Not Approved", and fax the proof to (317) 917-7998.<br/><br/>

If you have any additional questions, please contact your "insert name and ext" your sales representative or the Prepress department (please have your order number ready) at (317) 917-7938 extension 3.<br/><br/>

Thanks,<br/>
Prepress';
        $subject = "Order# $orderID - $jobName PO: $po";
      }
      $bcc = '';

      Modules::run('rest/customemailcontroller/genericEmailFile', $to, $body, $subject, $file, $replyTo);

      unlink($file);

      $this->response($output, 200);
    }

    public function sendProofTest_post() {
      if(!$this->post('id') || !$this->post('to') || !$this->post('type') || !$this->post('po') || !$this->post('jobName')) {
        $this->response(NULL, 400);
      }

      $orderID = $this->post('id');
      $to = $this->post('to');
      $type = $this->post('type');
      $po = $this->post('po');
      $jobName = $this->post('jobName');
      $url = '"http://reports.indyimaging.com:8080/jasperserver/flow.html?_flowId=viewReportFlow&standAlone=true&_flowId=viewReportFlow&ParentFolderUri=%2Freports%2FPrepress&reportUnit=%2Freports%2FPrepress%2FproofAll&j_username=joeuser&j_password=joeuser&orderID=' . $orderID . '&output=pdf"';
      $replyTo = 'proofapproved@indyimaging.com';

      // $filePath = "/Applications/XAMPP/xamppfiles/htdocs/jasperSoap/pressProof/";
      $filePath = "/var/www/html/jasperSoap/pressProof";
      $fileName = $orderID . ".pdf";
      $file = $filePath . $fileName;
      // $cmd = '/usr/local/bin/wget -O ' . $file . ' ' . $url . ' 2>&1';
      $cmd = 'wget -O ' . $file . ' ' . $url . ' 2>&1';
      $output = array();
      exec($cmd, $output);

      $to = 'matthew.sickler@indyimaging.com';
      if($type == 'Not Approved') {
        $body = 'These Proofs were marked <strong>Not Approved</strong>. You will need to review and approve the updated proofs.

Reply: Approved or Not Approved

Possible reasons you are receiving this email: Customer sent New Art
Customer Change something in the file.';
        $subject = "Sales Please Approve - Order# $orderID - $jobName PO: $po";
      } else {
        $body = 'Hello,

Attached are your proofs for ' . $jobName . ' on PO: ' . $po . ' on our Order# ' . $orderID . '.

Please make sure to check your proof(s) carefully for correct telephone numbers, email, web addresses, name spellings, addresses and other text as well as dimensions(size) and design(layout). Colors will be matched to the Pantone numbers listed on the proof, otherwise colors will be examined and setup using our printing experience and best judgment.

To <strong>Approve</strong> the proof, simply reply to this email with "Approved", or print, sign and fax approved proof to (317) 917-7998.

If you <strong>Do Not Approve</strong> the proof, simply reply to this email with "Not Approved", stating the changes that need to be made, or print, mark as "Not Approved", and fax the proof to (317) 917-7998.

If you have any additional questions, please contact your "insert name and ext" your sales representative or the Prepress department (please have your order number ready) at (317) 917-7938 extension 3.

Thanks,
Prepress';
        $subject = "Order# $orderID - $jobName PO: $po";
      }
      $bcc = '';

      Modules::run('rest/customemailcontroller/genericEmailFile', $to, $body, $subject, $file, $replyTo);

      unlink($file);

      $this->response($output, 200);
    }

    public function deleteOrderRelatedInfo_delete($orderID = null) {
      // delete orderItem, orderItemComponents,OrderShip,OtherCharges,OrderContacts,Orders
      $deleteInfo = array();

      if(isset($orderID) && !is_null($orderID)) {
        // check whether the order exists or not
        $orderIDArry = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID', $orderID);

        if($orderIDArry) {
          $deleteInfo = Modules::run('orders/ordercontroller/processOrderDeleteInfo', $orderIDArry);
        } else {
          $this->response(array('error' => "Order Doesn't Exits"), 404);
        }
      } else {
        $this->response(array('error' => "Error"), 404);
      }

      $this->response(array('Success' => $deleteInfo), 200);
    }

    public function checkOrderTotals_post() {
      if(!$this->post('id')) {
          $this->response(NULL, 400);
      }

      $id = $this->post('id');

      $data = $this->ordermodel->getOrderTotals($id);

      $updateData = array();
      if($data['n_TotalOtherCharges'] == null) {
        $other = 0;
      } else {
        $other = (float)$data['n_TotalOtherCharges'];
      }
      $updateData['n_TotalOtherCharges'] = (float)$data['n_TotalOtherCharges'];

      if($data['n_TotalOrderItemPrice'] == null) {
        $orderItem = 0;
      } else {
        $orderItem = (float)$data['n_TotalOrderItemPrice'];
      }
      $updateData['n_TotalOrderItemPrice'] = (float)$data['n_TotalOrderItemPrice'];


      if($data['n_TotalShippingCharges'] == null) {
        $ship = 0;
      } else {
        $ship = (float)$data['n_TotalShippingCharges'];
      }
      $updateData['n_TotalShippingCharges'] = (float)$data['n_TotalShippingCharges'];

      $updateData['n_TotalSubOrderItemOtherCharges'] = $orderItem + $other;

      $subtotal = $orderItem + $other + $ship;
      $updateData['n_TotalSubtotal'] = $orderItem + $other + $ship;

      if($data['t_StateOrProvince'] == 'IN' && $data['t_CustReseller'] == null) {
        $totalTax = $subtotal * $data['n_SalesTaxRate'];
        $updateData['n_TotalTax'] = $data['n_SalesTaxRate'] * $updateData['n_TotalSubtotal'];
      } else {
        $totalTax = 0;
        $updateData['n_TotalTax'] = null;
      }

      $updateData['n_TotalGrandTotal'] = $totalTax + $subtotal;
      if($updateData['n_TotalGrandTotal'] == 0) {
        $updateData['n_TotalGrandTotal'] = null;
      }


      $result = $this->ordermodel->updateOrderTbl($updateData, $id);

      // $this->response($updateData, 404);

      if($result || $result == 0) {
          $this->response($result, 200); // 200 being the HTTP response code
      } else {
          $this->response(array('error' => $result), 404);
      }
    }

    public function validTrackingEmailTable_put() {
        if(!$this->put('body')) {
            $this->response(NULL, 400);
        }

        $body = $this->put('body');
        $to = "csr@indyimaging.com";
        // $to = "matthew.sickler@indyimaging.com";
        $subject = "Tracking Emails Sent - " . date("m-d-Y h:i:s A");

        Modules::run('rest/customemailcontroller/genericEmail', $to, $body, $subject);

        $this->response(null, 200); // 200 being the HTTP response code
    }

    public function singleInvoiceEmail_put() {
        if(!$this->put('body')) {
            $this->response(NULL, 400);
        }

        $body = $this->put('body');
        $to = "csr@indyimaging.com,accounting@indyimaging.com";
        // $to = "matthew.sickler@indyimaging.com";
        $subject = "Invoice Created - " . date("m-d-Y h:i:s A");

        Modules::run('rest/customemailcontroller/genericEmail', $to, $body, $subject);

        $this->response(null, 200); // 200 being the HTTP response code
    }

    public function validInvoicesEmailTable_put() {
        if(!$this->put('body')) {
            $this->response(NULL, 400);
        }

        $body = $this->put('body');
        $to = "csr@indyimaging.com,accounting@indyimaging.com";
        // $to = "matthew.sickler@indyimaging.com";
        $subject = "Invoices Created - " . date("m-d-Y h:i:s A");

        Modules::run('rest/customemailcontroller/genericEmail', $to, $body, $subject);

        $this->response(null, 200); // 200 being the HTTP response code
    }

    public function orderEmailTrackingInfoByOrderID_get() {
        if(!$this->get('orderID')) {
            $this->response(NULL, 400);
        }

        $orderID = $this->get('orderID');

        $result  = $this->ordermodel->getOrderEmailTrackingDataByOrderID($orderID);

        if($result) {
            $this->response($result, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'Order could not be found'), 404);
        }
    }

    public function insertOrderData_post() {
      if(!$this->post('formData')) {
        $this->response(array('error' => 'Form data not found.'), 400);
      }

      $CI = get_instance();
      $tokenHeader      = $CI->input->get_request_header('Authorization', TRUE);
      $token            = JWT::decode($tokenHeader, JWT_KEY);
      if(!checkPermissionAddNewOrder($token->privilegeSet)) {
        $this->response(array('error' => 'You\'re privilege set doesn\'t allow access to this content'), 400);
      }

      $data = $this->post('formData');

      if(!$data['ti_JobDue']) {
        $data['ti_JobDue'] = null;
      }
      if(!$data['ti_PrintJobDue']) {
        $data['ti_PrintJobDue'] = null;
      }
      if(!$data['ti_ProofDue']) {
        $data['ti_ProofDue'] = null;
      }

      $insertedID = $this->ordermodel->insertOrderData($data);

      Modules::run('orders/ordercontroller/createQRCodeFromOrderID', $insertedID);

      $result = array('id'=>$insertedID);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array('error' => 'Insert Failed'), 404);
      }
    }

    public function ordersComplete_get() {
      if(!$this->get('date')) {
        $this->response(array(array('error' => 'Date Not Found')), 400);
      }

      $date = $this->get('date');

      $result = $this->ordermodel->getOrdersComplete($date);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No Order Data Found')), 200);
      }
    }

    public function allOrderRequests_get() {
      $result = $this->ordermodel->getAllOrderRequests();

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No Orders Found')), 404);
      }
    }

    public function createQRCodeFromOrderID_post() {
      if(!$this->post('id')) {
        $this->response(array(array('error' => 'Order ID Not Found')), 400);
      }

      $orderID = $this->post('id');

      $orderArry        = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID', $orderID);
      $qrCodeGenerated  = $orderArry['nb_QrcodeGeneratedOI'];
      $dateReceived     = $orderArry['d_Received'];
      //check if QRCode already exists
      if($qrCodeGenerated == 1) {
        // check the physical path of the image
        $this->ordermodel->checkPhysicalPathOFQrCodeImage($orderID,$dateReceived);
        $createQRCode['msg'] = "yesQRcode";
      } else {
        $this->ordermodel->createQRCodeFromOrderID($orderID,$dateReceived);
        $qrCodeGenerated              = 1;
        $data['nb_QrcodeGeneratedOI'] = $qrCodeGenerated;
        $result = Modules::run('orders/ordercontroller/updateOrderTbl', $data, $orderID);
        $createQRCode['msg']          = "yesQRcode";
      }

      $this->response($createQRCode, 200);
    }

    //Duplicates Order, Order Items, Order Item Components, Other Charges, and Order Ship
    public function dupeCompleteOrder_post() {
      if(!$this->post('id')) {
        $this->response(array(array('error' => 'Order Info Not Found')), 400);
      }
      $oldOrderID = $this->post('id');
      $typeOfJobTicket = "InternalOrderForm";
        //0. duplicates Order from OrderID  //$result_array = $this->duplicateOrder($oldOrderID);  //$oldOrderID = $result_array[0];
        $newOrderID                           = Modules::run('orders/ordercontroller/duplicateOrder', $oldOrderID, $typeOfJobTicket);
        Modules::run('orders/ordercontroller/createQRCodeFromOrderID', $newOrderID);
        // $this->response(array(array('error' => $newOrderID)), 400);


        //1. duplicates OrderItems and OrderItemComponents from OrderID
        Modules::run('orders/ordercontroller/dupOrderItemOrderItemComponents', $oldOrderID, $newOrderID);

        //2. get the orderShipArry from the old OrderID
        $getOrderShipArrFromOrderID           = Modules::run('orderShip/ordershipcontroller/getOrderShipTblFromOrderID',$oldOrderID);

        if(!empty($getOrderShipArrFromOrderID)) {
            //3. Duplicate orderShipArr with the new OrderID and Insert the duplicated orderShipArr array in the orderShip table.
            Modules::run('orderShip/ordershipcontroller/duplicateOrderShipDataFromOrderID',$getOrderShipArrFromOrderID,$newOrderID);
        }

        //3.1 get the orderShipArry from the new OrderID
        $getOrderShipArrFromNewOrderID          = Modules::run('orderShip/ordershipcontroller/getOrderShipTblFromOrderID',$newOrderID);

        //3.2 Create the BarCode for the newly Creted OrderShipArry

        if(!empty($getOrderShipArrFromNewOrderID)) {
             Modules::run('orderShip/ordershipcontroller/createBarCodeForduplicateOrderShipData',$getOrderShipArrFromNewOrderID,$newOrderID);
        }

        //4. get the otherChargesArry from the old OrderID
        $getOtherChargesArrFromOrderID           = Modules::run('otherCharges/otherchargecontroller/getOtherChargeTblFromOrderID',$oldOrderID);

        if(!empty($getOtherChargesArrFromOrderID)) {
            //5. Duplicate otherChargesArry with the new OrderID and Insert the duplicated otherChargesArry array in the otherCharges table.
            Modules::run('otherCharges/otherchargecontroller/duplicateOtherChargesDataFromOrderID',$getOtherChargesArrFromOrderID,$newOrderID);
        }

        $this->response(array('id' => $newOrderID), 200);
    }

    public function ordersListByCustomerMasterJob_get() {
      if(!$this->get('customerID') || !$this->get('masterJobID')) {
        $this->response(array(array('error' => 'Filter Info Not Found')), 400);
      }
      $customerID = $this->get('customerID');
      $masterJobID = $this->get('masterJobID');

      $result = $this->ordermodel->getOrdersListByCustomerMasterJob($customerID, $masterJobID);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No Orders Found')), 200);
      }
    }

    public function ordersListByCustomer_get() {
      if(!$this->get('id') || !$this->get('page')) {
        $this->response(array(array('error' => 'Filter Info Not Found')), 400);
      }
      $customerID = $this->get('id');
      $page = json_decode($this->get('page'));

      $result = $this->ordermodel->getOrdersListByCustomer($customerID, $page->pagination);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No Orders Found')), 200);
      }
    }

    public function customerOrderRequests_get() {
      if(!$this->get('id')) {
        $this->response(array(array('error' => 'ID Not Found')), 400);
      }
      $customerID = $this->get('id');

      $result = $this->ordermodel->getCustomerOrderRequests($customerID);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No Orders Found')), 200);
      }
    }

    public function ordersByFilter_get() {
      if(!$this->get('filterInfo')) {
        $this->response(array(array('error' => 'Filter Information Not Found')), 400);
      }
      $filterInfo = json_decode($this->get('filterInfo'));


      if($filterInfo->orderID != "None") {
        $result = $this->ordermodel->getOrderViewByOrder($filterInfo->orderID);
      } else {
        $result = $this->ordermodel->getOrderViewByCustomer($filterInfo);
      }

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No Orders Found')), 200);
      }
    }

    public function activeNotFinishedOrdersSign_get() {
      if(!$this->get('type')) {
        $this->response(NULL, 400);
      }
      $type = $this->get('type');

      $result = $this->ordermodel->getActiveNotFinishedOrdersSign($type);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No orders found.')), 200);
      }
    }

    public function activeTableData_get() {
      if(!$this->get('date') || !$this->get('type')) {
        $this->response(NULL, 400);
      }
      $date = $this->get('date');
      $type = $this->get('type');

      if($type == 'onPress') {
        $result = $this->ordermodel->getOnPressOrders($date);
      } else if($type == 'proof') {
        $result = $this->ordermodel->getProofOrders($date);
      } else if($type == 'pickup') {
        $result = $this->ordermodel->getPickupOrders();
      } else if($type == 'hold') {
        $result = $this->ordermodel->getHoldOrders();
      } else if($type == 'woa') {
        $result = $this->ordermodel->getWOAOrders();
      } else if($type == 'finished') {
        $result = $this->ordermodel->getActiveFinishedOrders($date);
      } else if($type == 'inProcess') {
        $result = $this->ordermodel->getActiveNotFinishedOrders($date);
      }


      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No orders for that day')), 200);
      }
    }


    public function multiLineData_get() {
      $result = $this->ordermodel->getMultiLineData();

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'Could not find data.')), 404);
      }
    }

    public function completed_put() {
        if(!$this->put('id')) {
          $this->response("id not found", 400);
        }

        $id = $this->put('id');

        $result = $this->ordermodel->setCompleted($id);

        if($result) {
            $this->ordermodel->setCompletedLog($id);
            $this->response($result, 200);
        } else {
          $this->response(array(array('error' => 'Could not mark as complete.')), 404);
        }
    }

    public function ordersByCustomer_get() {
      if(!$this->get('kp_CustomerID')) {
        $this->response(NULL, 400);
      }
      $id = $this->get('kp_CustomerID');

      $result = $this->ordermodel->getOrdersByCustomer($id);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No orders for that customer!')), 404);
      }
    }

    public function orderViewByID_get() {
      if(!$this->get('id')) {
        $this->response(NULL, 400);
      }
      $id = $this->get('id');

      $result = $this->ordermodel->getOrderViewByID($id);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No order matching that id!')), 404);
      }
    }


    public function ordersDue_get() {
      if(!$this->get('startDate') || !$this->get('endDate')) {
        $this->response(NULL, 400);
      }
      $startDate= $this->get('startDate');
      $endDate= $this->get('endDate');

      $result = $this->ordermodel->getOrdersDue($startDate, $endDate);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No orders for this week')), 200);
      }
    }

    public function ordersReceived_get() {
      if(!$this->get('startDate') || !$this->get('endDate')) {
        $this->response(NULL, 400);
      }
      $startDate= $this->get('startDate');
      $endDate= $this->get('endDate');

      $result = $this->ordermodel->getOrdersReceived($startDate, $endDate);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No orders for this week')), 200);
      }
    }

    public function activeOrders_get() {
      $result = $this->ordermodel->getActiveOrders();

      if($result) {
        $this->response($result, 200);
      } else {
        $this.response(array('error' => 'Couldn\'t find order table data!'), 404);
      }
    }
    public function proofJobOnPressTimeFormat_get($orderArry)
    {
        if(!empty($orderArry['ti_ProofDue']) && !is_null($orderArry['ti_ProofDue']))
        {
            $orderArry['ti_ProofDue']  = strftime('%I:%M %p', strtotime($orderArry['ti_ProofDue']));

        }
        else
        {
            $orderArry['ti_ProofDue']  = null;

        }
        if(!empty($orderArry['ti_JobDue']) && !is_null($orderArry['ti_JobDue']))
        {
            $orderArry['ti_JobDue']  = strftime('%I:%M %p', strtotime($orderArry['ti_JobDue']));

        }
        else
        {
            $orderArry['ti_JobDue']  = null;

        }
        if(!empty($orderArry['ti_PrintJobDue']) && !is_null($orderArry['ti_PrintJobDue']))
        {
            $orderArry['ti_PrintJobDue']  = strftime('%I:%M %p', strtotime($orderArry['ti_PrintJobDue']));

        }
        else
        {
            $orderArry['ti_PrintJobDue']  = null;

        }

        return $orderArry;

    }
    public function orderByID_get()
    {
        if(!$this->get('orderID'))
        {
            $this->response(NULL, 400);
        }
        $orderID= $this->get('orderID');

        $result = $this->ordermodel->getOrderByID($orderID);
        //echo sizeof($result);
        //echo "<br/>";
        $result = $this->proofJobOnPressTimeFormat_get($result);
        //echo sizeof($result);
        //var_dump($x);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'OrderID could not be found'), 404);
        }
    }

    public function orderInvoiceCCData_get() {
        $result = $this->ordermodel->getOrderInvoiceCCTblData();
        if($result) {
            $this->response($result, 200);
        } else {
            $this->response(array(array('error' => 'Order data could not be found')), 200);
        }
    }

    public function orderInvoiceData_get() {
        $result = $this->ordermodel->getOrderInvoiceTblData();
        if($result) {
            $this->response($result, 200);
        } else {
            $this->response(array('error' => 'OrderID could not be found'), 404);
        }
    }

    public function individualOrderInvoiceDataByID_get() {
      if(!$this->get('id')) {
        $this->response(null, 404);
      }

      $orderID = $this->get('id');
      $result = $this->ordermodel->getIndividualOrderInvoiceDataByID($orderID);

      if($result) {
          $this->response($result, 200);
      } else {
          $this->response(array('error' => 'OrderID could not be found'), 404);
      }
    }

    public function orderInvoiceDataByID_get() {
      if(!$this->get('id')) {
        $this->response(null, 404);
      }

      $orderID = $this->get('id');
      $result = $this->ordermodel->getOrderInvoiceTblDataByID($orderID);

      if($result) {
          $this->response($result, 200);
      } else {
          $this->response(array('error' => 'OrderID could not be found'), 404);
      }
    }

    public function orderByID_post()
    {

        if(!$this->post('kp_OrderID'))
        {
            $this->response(array('kp_OrderID' => $this->post('kp_OrderID'),'error' => 'somethign went wrong hellow OrderID could not be found'), 400);
        }
        else
        {


             $data      = $this->post();
//             $ti_ProofDue                         = $data['ti_ProofDue'];

            if(!empty($data['ti_ProofDue']))
            {
                $data['ti_ProofDue']             = strftime('%H:%M:%S', strtotime($data['ti_ProofDue']));//strtotime($ti_ProofDue, '%h:%i %p');

            }
            else
            {
                $data['ti_ProofDue']             = null;

            }
            if(!empty($data['ti_JobDue']))
            {
                $data['ti_JobDue']             = strftime('%H:%M:%S', strtotime($data['ti_JobDue']));//strtotime($ti_ProofDue, '%h:%i %p');

            }
            else
            {
                $data['ti_JobDue']             = null;

            }
            if(!empty($data['ti_PrintJobDue']))
            {
                $data['ti_PrintJobDue']             = strftime('%H:%M:%S', strtotime($data['ti_PrintJobDue']));//strtotime($ti_ProofDue, '%h:%i %p');

            }
            else
            {
                $data['ti_PrintJobDue']             = null;

            }
             //print_r($data);
             $orderID   = $this->post('kp_OrderID');


             $result  = $this->ordermodel->updateOrderTbl($data,$orderID);
        }
        if($result == "0" || $result == "1")
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'somethign went wrong gogoog Order could not be posted (updated)'. $result), 404);
        }
    }
    public function prepressTvInfo_get()
    {
        $result  = $this->ordermodel->prepressTvData();

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'somethign went wrong  with prepressTvInfo'), 404);
        }
    }

    public function prepressTvInfo2_get()
    {
        $result  = $this->ordermodel->prepressTvData2();

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'somethign went wrong  with prepressTvInfo2'), 404);
        }
    }

}
