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

class Customer_Api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        if(!checkPermissionsByModule($privSet, 'customer')) {
          $this->response(array('error' => 'You\'re privilege set doesn\'t allow access to this content'), 400);
        }
        date_default_timezone_set('America/Indianapolis');
        $this->load->model('customermodel');
    }

    public function customerDocumentName_put() {
      if(!$this->put('id') || !$this->put('newName') || !$this->put('oldName')) {
        $this->response(array(array('error' => 'ID and/or Names Not Found')), 400);
      }
      $id = $this->put('id');
      $newName = $this->put('newName');
      $oldName = $this->put('oldName');

      $result       = $this->customermodel->updateCustomerDocumentName($id, $oldName, $newName);

      $this->response($result, 200);
    }

    public function customerDocument_delete($id, $name) {
      if(!$id || !$name) {
        $this->response(array(array('error' => 'ID and/or Name Not Found')), 400);
      }

      $result       = $this->customermodel->deleteCustomerDocument($id, $name);

      $this->response($result, 200);
    }

    public function customerDocumentUpload_post() {
      if(!$this->post('id')) {
        $this->response(array(array('error' => 'ID Not Found')), 400);
      }
      $id = $this->input->post('id');

      $result       = $this->customermodel->customerDocumentUpload($id);

      $this->response($result, 200);
    }

    public function customerTermsAuthUpload_post() {
      if(!$this->post('id')) {
        $this->response(array(array('error' => 'ID Not Found')), 400);
      }
      $id = $this->input->post('id');

      $result       = $this->customermodel->termsAuthUpload($id);

      $this->response($result, 200);
    }

    public function customerTermsTaxUpload_post() {
      if(!$this->post('id')) {
        $this->response(array(array('error' => 'ID Not Found')), 400);
      }
      $id = $this->input->post('id');

      $result       = $this->customermodel->termsTaxUpload($id);

      $this->response($result, 200);
    }

    public function customerTermsApplicationUpload_post() {
      if(!$this->post('id')) {
        $this->response(array(array('error' => 'ID Not Found')), 400);
      }
      $id = $this->input->post('id');

      $result       = $this->customermodel->termsApplicationUpload($id);

      $this->response($result, 200);
    }

    public function customerTermsReferencesUpload_post() {
      if(!$this->post('id')) {
        $this->response(array(array('error' => 'ID Not Found')), 400);
      }
      $id = $this->input->post('id');

      $result       = $this->customermodel->termsReferencesUpload($id);

      $this->response($result, 200);
    }

    public function customerCCEmail_put() {
      if(!$this->put('type') || !$this->put('id') || !$this->put('message') || !$this->put('name')) {
          $this->response(NULL, 400);
      }

      $type = $this->put('type');
      $id = $this->put('id');
      $message = $this->put('message');
      $name = $this->put('name');

      if($type == 'Initial') {
        $subject = 'Credit Card Override Request - ' . $name;
        $body = "Company: $name<br/>$message<br/>" . 'https://apps.indyimaging.com/shopbot/#/customer/edit/modal/' . $id . '/addr';

        $result = $this->customermodel->getSalesEmailFromCustomerID($id);
        $to = $result->t_EmployeeEmail;
      }

      if(!$to) {
        $this->response("To address not found", 404);
      }

      // $to = "matthew.sickler@indyimaging.com";
      Modules::run('rest/customemailcontroller/genericEmail', $to, $body, $subject);

      $this->response(null, 200);
    }

    public function customerTermsEmail_put() {
        if(!$this->put('type') || !$this->put('id') || !$this->put('message') || !$this->put('name')) {
            $this->response(NULL, 400);
        }

        $type = $this->put('type');
        $id = $this->put('id');
        $message = $this->put('message');
        $name = $this->put('name');

        if($type == 'Initial') {
          $subject = 'Customer Credit Approval - In Progress';
          $body = 'https://apps.indyimaging.com/shopbot/#/customer/edit/modal/' . $id . '/addr';

          $result = $this->customermodel->getSalesEmailFromCustomerID($id);
          $to = $result->t_EmployeeEmail;
        } else if($type == 'AccountingReview') {
          $subject = 'Customer Needs Credit Approval';
          $body = 'https://apps.indyimaging.com/shopbot/#/customer/edit/modal/' . $id . '/addr';

          $result = $this->customermodel->getCustomerTermsApprovalEmailAddresses();
          $to = $result->emails;
        } else if($type == 'Approved') {
          $subject = 'Customer Credit Approved';
          $body = "Your Customer: $name has had their credit approved. https://apps.indyimaging.com/shopbot/#/customer/edit/modal/" . $id . '/addr';

          $result = $this->customermodel->getSalesEmailFromCustomerID($id);
          $to = $result->t_EmployeeEmail;
        } else if($type == 'Incomplete') {
          $subject = 'Customer Credit Marked Incomplete';
          $body = "Your Customer: $name has had their credit marked as incomplete. https://apps.indyimaging.com/shopbot/#/customer/edit/modal/" . $id . '/addr';

          $result = $this->customermodel->getSalesEmailFromCustomerID($id);
          $to = $result->t_EmployeeEmail;
        } else if($type == 'TermsApproved') {
          $subject = 'Customer Credit Terms Approved';
          $body = "Your Customer: $name has had their terms approved. https://apps.indyimaging.com/shopbot/#/customer/edit/modal/" . $id . '/addr';

          $result = $this->customermodel->getSalesEmailFromCustomerID($id);
          $to = $result->t_EmployeeEmail;
        } else if($type == 'TermsDenied') {
          $subject = 'Customer Credit Terms Denied';
          $body = "Your Customer: $name has had their terms denied. https://apps.indyimaging.com/shopbot/#/customer/edit/modal/" . $id . '/addr';

          $result = $this->customermodel->getSalesEmailFromCustomerID($id);
          $to = $result->t_EmployeeEmail;
        }

        if(!$to) {
          $this->response("To address not found", 404);
        }

        // $to = "matthew.sickler@indyimaging.com";
        Modules::run('rest/customemailcontroller/genericEmail', $to, $body, $subject);

        $this->response(null, 200);
    }

    public function customerApprovalEmail_put() {
        if(!$this->put('type') || !$this->put('id') || !$this->put('message') || !$this->put('name')) {
            $this->response(NULL, 400);
        }

        $type = $this->put('type');
        $id = $this->put('id');
        $message = $this->put('message');
        $name = $this->put('name');
        // $from = 'noreply<noreply@indyimaging.com>';
        // $cc = '';

        if($type == 'Initial') {
          $subject = 'New Customer Needs Approval';
          $body = 'https://apps.indyimaging.com/shopbot/#/customer/edit/modal/' . $id . '/addr';

          $result = $this->customermodel->getCustomerSalesApprovalEmailAddresses();
          $to = $result->emails;
        } else if($type == 'Approved') {
          $subject = 'Your Customer Has Been Approved';
          $body = "Your customer: " . $name . " has been approved to do business with Indy Imaging.<br/><br/>It will take approximately 10 minutes from the time of this email for the system to allow you to create orders for this customer.<br/><br/>If your customer has applied for terms, this will be processed separately.<br/><br/>https://apps.indyimaging.com/shopbot/#/customer/edit/modal/" . $id . "/addr";

          $result = $this->customermodel->getSalesEmailFromCustomerID($id);
          $to = $result->t_EmployeeEmail;
        } else if($type == 'SalesApproved') {
          $subject = 'New Customer Needs Approval';
          $body = 'https://apps.indyimaging.com/shopbot/#/customer/edit/modal/' . $id . '/addr';

          $result = $this->customermodel->getCustomerAccountingApprovalEmailAddresses();
          $to = $result->emails;
        } else if($type == 'SalesRejected') {
          $subject = 'Your Customer Has Been Marked "Does Not Qualify" By Sales';
          $body = "Your customer: " . $name . " https://apps.indyimaging.com/shopbot/#/customer/edit/modal/" . $id . "/addr" . " has been marked 'Does Not Qualify' by sales with the following reason: " . $message;

          $result = $this->customermodel->getSalesEmailFromCustomerID($id);
          $to = $result->t_EmployeeEmail;
        } else if($type == 'SalesMore') {
          // $result = $this->customermodel->getSalesEmailFromCustomerID($id);
          // $fromCC = $result->t_EmployeeEmail;
          // $fromCCArr = explode(', ', $fromCC);
          // $count = count($fromCCArr);
          // $from = $fromCCArr[0];
          // if($count > 1) {
          //   $CCArr = array_splice($fromCCArr, 0);
          //   $cc = implode(', ', $CCArr);
          // }

          $subject = 'Customer Request - Info Needed';
          $body = "Sales has requested more information about your customer: " . $name . " https://apps.indyimaging.com/shopbot/#/customer/edit/modal/" . $id . "/addr" . " with the following message: " . $message;

          $result = $this->customermodel->getSalesEmailFromCustomerID($id);
          $to = $result->t_EmployeeEmail;
        } else if($type == 'AccountingMore') {
          $subject = 'Customer Request - Info Needed';
          $body = "Accounting has requested more information about your customer: " . $name . " https://apps.indyimaging.com/shopbot/#/customer/edit/modal/" . $id . "/addr" . " with the following message: " . $message;

          $result = $this->customermodel->getSalesEmailFromCustomerID($id);
          $to = $result->t_EmployeeEmail;
        } else if($type == 'SalesReview') {
          $subject = 'Customer Needs Approval - Update';
          $body = 'This customer that was marked "' . $message . '" has been updated: https://apps.indyimaging.com/shopbot/#/customer/edit/modal/' . $id . '/addr';

          $result = $this->customermodel->getCustomerSalesApprovalEmailAddresses();
          $to = $result->emails;
        } else if($type == 'AccountingReview') {
          $subject = 'Customer Needs Approval - Update';
          $body = 'This customer that was marked "Need More Info" has been updated: https://apps.indyimaging.com/shopbot/#/customer/edit/modal/' . $id . '/addr';

          $result = $this->customermodel->getCustomerAccountingApprovalEmailAddresses();
          $to = $result->emails;
        }

        if(!$to) {
          $this->response("To address not found", 404);
        }

        // $to = "matthew.sickler@indyimaging.com";
        Modules::run('rest/customemailcontroller/genericEmail', $to, $body, $subject);

        $this->response(null, 200);
    }

    public function insertCustomerData_post() {
      if(!$this->post('formData')) {
        $this->response(array('error' => 'Form data not found.'), 400);
      }

      $CI = get_instance();
      $tokenHeader      = $CI->input->get_request_header('Authorization', TRUE);
      $token            = JWT::decode($tokenHeader, JWT_KEY);
      if(!checkPermissionAddCustomer($token->privilegeSet)) {
        $this->response(array('error' => 'You\'re privilege set doesn\'t allow access to this content'), 400);
      }

      $data = $this->post('formData');
      $data['zCreated'] = date("Y-m-d H:i:s", time());

      $insertedID = $this->customermodel->insertCustomerData($data);

      $result = array('id'=>$insertedID);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array('error' => 'Insert Failed'), 404);
      }
    }

    public function inProcessCustomerTableData_get() {
      $result = $this->customermodel->getInProcessCustomerTableData();

      if($result) {
          $this->response($result, 200);
      } else {
          $this->response(array(), 200);
      }
    }

    public function termsCustomerTableData_get() {
      $result = $this->customermodel->getTermsCustomerTableData();

      if($result) {
          $this->response($result, 200);
      } else {
          $this->response(array(), 200);
      }
    }

    public function requestedCustomerTableData_get() {
      $result = $this->customermodel->getRequestedCustomerTableData();

      if($result) {
          $this->response($result, 200);
      } else {
          $this->response(array(), 200);
      }
    }

    public function customerTableData_get() {
      $result = $this->customermodel->getCustomerTableData();

      if($result) {
          $this->response($result, 200);
      } else {
          $this->response(array(), 200);
      }
    }

    public function activeCustomerNames_get() {
        $result = $this->customermodel->getActiveCustomerNames();

        if($result) {
            $this->response($result, 200);
        } else {
            $this->response(array(), 200);
        }

    }
    public function customerByName_get()
    {
        if(!$this->get('Name'))
        {
          $this->response(NULL, 400);
        }

        $customerName = $this->get('Name');


        $result = $this->customermodel->getCustomerByName($customerName);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array(), 200);
        }

    }

    public function qbCustomerByID_get() {
      if(!$this->get('QBCustID')) {
          $this->response(NULL, 400);
      }
      $QBCustID= $this->get('QBCustID');

      $result = Modules::run('quickBooks/quickbookcontroller/getQuickBooksCustomerTableInfo',$QBCustID);

      if($result) {
          $this->response($result, 200);
      } else {
          $this->response(array('error' => 'Customer could not be found'), 404);
      }
    }

    public function customerByID_get() {
        if(!$this->get('customerID')) {
            $this->response(NULL, 400);
        }
        $customerID= $this->get('customerID');

        $result = $this->customermodel->getCustomerDataByID($customerID);

        if($result) {
            $this->response($result, 200);
        } else {
            $this->response(array('error' => 'Customer could not be found'), 404);
        }
    }

    public function custIDFromOrderID_get()
    {
        if(!$this->get('orderID'))
        {
            $this->response(NULL, 400);
        }
        $orderID= $this->get('orderID');

        $result = $this->customermodel->getCustomerID($orderID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'CustomerID could not be found'), 404);
        }


    }

}
