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

class EmailAng_Api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        //$this->QRCodeImagePath   = realpath(APPPATH . '../../../images/Orders');
        $this->load->model('emailangmodel');

    }

    public function restAngMarkEmailSent_post() {
        if(!$this->post('kp_OrderID')) {
            $this->response(array('kp_OrderID' => $this->post('kp_OrderID'),'error' => 'somethign went wrong hellow OrderID could not be found'), 400);
        }

        $updateOrderData                                      = array();
        $kp_OrderID                                           = $this->post('kp_OrderID');
        $updateOrderData['nb_EmailSentTrackNumReadyToPickUp'] = "1";

        $result                            = Modules::run('orders/ordercontroller/updateOrderTbl',$updateOrderData,$kp_OrderID);

        if($result) {
            $this->response($result, 200);
        } else {
            $this->response(array('error' => 'Email Tracking Data could not be found'), 404);
        }
    }

    public function index2_get()
    {
        $this->load->helper('file');

        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.gmail.com',
            'smtp_port' => 465,
            'smtp_user' => 'noreply@indyimaging.com',
            'smtp_pass' => 'n0rEp1y',
            'mailtype'  => 'html',
            'charset'   => 'iso-8859-1',
            'wordwrap' => TRUE,
            'multipart' => "related"
        );

        $this->load->library('email',$config);
        $this->email->set_newline("\r\n");

        //echo realpath(APPPATH . '../../../images/indyLogoWeb1.png');

        //$image = realpath(APPPATH . '../../../images/');// image path

        //$fileExt = get_mime_by_extension($image); // <- what the file helper is used for (to get the mime type)

        $from    = "sraparla@indyimaging.com";
        $subject = "testing inline image";

        $to = "raparlateja@gmail.com";


        $this->email->from($from);
        $this->email->to($to);
        $this->email->subject($subject);

        $imagePath = realpath(APPPATH . '../../../images/');

        echo realpath(APPPATH . '../../../images/');

        //$this->email->message('<img src="data:'.$fileExt.';base64,'.base64_encode(file_get_contents($image)).'" alt="Test Image" />'); // Get the content of the file, and base64 encode it
        //$this->email->message('<img src="https://apps.indyimaging.com/shopbot/images/indyLogoWeb1.png" />'); // Get the content of the file, and base64 encode it

        $this->email->attach($imagePath.'/indyLogoWeb1.png');

        $this->email->message("<b><img src=\"cid:indyLogoWeb1.png\" />");

        $this->email->send();
        echo print_r($this->email->print_debugger(), true);
    }

    public function index_get()
    {


        //'<img src="data:'.$fileExt.';base64,'.base64_encode(file_get_contents($image)).'" alt="Test Image" />';

        $data['kp_OrderID'] = '143466';
        $data['t_JobName']  = 'some Job Name';
        $data['poNumber']   = "Motion IndustriesREORDER";
        $data['shipDate']   = "2014-10-28";


        $data['invoiceItems'][0]['Description'] = 'sdsdsd';
        $data['invoiceItems'][0]['Quantity']    = "3";
        $data['invoiceItems'][1]['Description'] = "Fedex Ground - Ground - Tracking: 606411226476";
        $data['invoiceItems'][1]['Quantity']    = "1";

        $data['billAddress_Addr1']         = "TGI Systems";
        $data['billAddress_Addr2']         = "Rich Zeffrio";
        $data['billAddress_Addr3']         = "188 N Wells St";
        $data['billAddress_Addr4']         = "Ste 202";
        $data['billAddress_City']          = "Chicago";

        $data['billAddress_State']         = "IL";
        $data['billAddress_PostalCode']    = "60606-3506";
        $this->load->view('emailTemp3',$data);

    }

    public function validEmailTrackingTblData_get() {
      $result = $this->emailangmodel->getValidEmailTrackingTableListData();

      $this->response($result, 200);
    }

    public function emailTrackingTblData_get()
    {
        $result = $this->emailangmodel->getEmailTrackingTableListData();

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'Email Tracking Data could not be found'), 404);
        }
    }

    public function restAngEmail_post() {
        $data                = $this->post();
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.gmail.com',
            'smtp_port' => 465,
            'smtp_user' => 'noreply@indyimaging.com',
            'smtp_pass' => 'n0rEp1y',
            'mailtype'  => 'html',
            'charset'   => 'iso-8859-1',
            'wordwrap' => TRUE,
            //"multipart" =>"related"
        );

        $this->load->library('email',$config);
        $this->email->set_newline("\r\n");

        if(!empty($data['emailData'])) {
            $emailData['kp_OrderID']              = $data['emailData']['kp_OrderID'];
            $emailData['CustomerRef_ListID']      = $data['emailData']['CustomerRef_ListID'];
            $emailData['SalesRepRef_ListID']      = $data['emailData']['SalesRepRef_ListID'];
            $emailData['t_QBSalesRepListID']      = $data['emailData']['t_QBSalesRepListID'];
            $emailData['ListID']                  = $data['emailData']['ListID'];

            if(isset($data['emailData']['from'])) {
                $from = $data['emailData']['from'];

                //get username from employee table
                $employeeRowData = $this->emailangmodel->getEmployeeEmailAddress($from);
                $employeeUserName =  $employeeRowData['t_UserName'];

            }

            if(isset($data['emailData']['to'])) {
                $to = $data['emailData']['to'];
                // $to = "matthew.sickler@indyimaging.com";
                $this->email->to($to);
            }

            // need the and condition so that it won't send any cc emails if the main contact is not there
            if(isset($data['emailData']['cc']) && $data['emailData']['contactEmailFound'] == true) {
                $cc = $data['emailData']['cc'];
                // $cc = "";
                $this->email->cc($cc);
            }

            // need the and condition so that it won't send any bcc emails if the main contact is not there
            if(isset($data['emailData']['bcc']) && $data['emailData']['contactEmailFound'] == true) {
                $bcc = $data['emailData']['bcc'];
                // $bcc = "";
                $this->email->bcc($bcc);
            }

            if(isset($data['emailData']['subject'])) {
                $subject = $data['emailData']['subject'];
            }

            if(isset($data['emailData']['message'])) {
                $message = $data['emailData']['message'];
            }

            if(!empty($employeeRowData['t_UserName']) && !empty($to)) {
                $typeOfEmailTemplate                  = $data['emailData']['emailTemplate'];

                $emailData['kp_OrderID']              = $data['emailData']['kp_OrderID'];
                $emailData['t_JobName']               = $data['emailData']['t_JobName'];
                $emailData['poNumber']                = $data['emailData']['poNumber'];
                $emailData['shipDate']                = $data['emailData']['shipDate'];

                if($emailData['shipDate']) {
                  $emailData['shipDate']                = date('m/d/Y',strtotime($emailData['shipDate']));
                }


                // billing address
                $emailData['billAddress_Addr1']       = $data['emailData']['BillAddress_Addr1'];

                $emailData['orderContact']            = $data['emailData']['orderContact'];

                // bill to address changes
                $emailData['billAddress_Addr3']       = $data['emailData']['BillAddress_Addr3'];
                $emailData['billAddress_Addr4']       = $data['emailData']['BillAddress_Addr4'];
                $emailData['billAddress_City']        = $data['emailData']['BillAddress_City'];

                $emailData['billAddress_State']       = $data['emailData']['BillAddress_State'];
                $emailData['billAddress_PostalCode']  = $data['emailData']['BillAddress_PostalCode'];


                // shipTo address changes
                $emailData['shipAddress_Addr1']       = $data['emailData']['ShipAddress_Addr1'];
                $emailData['shipAddress_Addr2']       = $data['emailData']['ShipAddress_Addr2'];
                $emailData['shipAddress_Addr3']       = $data['emailData']['ShipAddress_Addr3'];

                $emailData['shipAddress_Addr4']       = $data['emailData']['ShipAddress_Addr4'];
                $emailData['shipAddress_City']        = $data['emailData']['ShipAddress_City'];
                $emailData['shipAddress_State']       = $data['emailData']['ShipAddress_State'];
                $emailData['shipAddress_PostalCode']  = $data['emailData']['ShipAddress_PostalCode'];


                $emailData['employeeUserName']        = $data['emailData']['empUserName'];

                $emailData['t_EmployeeEmail']        = $data['emailData']['t_EmployeeEmail'];

                $imagePath = realpath(APPPATH . '../../../images/');

                $t_EmployeeEmail               = $emailData['t_EmployeeEmail'];

                $kp_OrderID                    = $emailData['kp_OrderID'];

                $emailData['employeeLink']          = "<a href='mailto:'sraparla@indyimaging.com'?subject='1234'>Surya Raparla</a>";

                $emailData['employeeEmailUsername'] = $data['emailData']['employeeEmailUsername'];

                $emailData['t_JobStatus']   = $data['emailData']['t_JobStatus'];
                // set this to one
                $updateOrderData    = array();

                $updateOrderData['nb_EmailSentTrackNumReadyToPickUp'] = "1";
                $orderUpdateMsg    = Modules::run('orders/ordercontroller/updateOrderTbl',$updateOrderData,$kp_OrderID);


                if($typeOfEmailTemplate == "orderShipEmailTemplate" && $data['emailData']['contactEmailFound'] == true) {
                    // Ask robbie what would be the from address be
                    $this->email->from($from,$employeeUserName);



                    $this->email->subject($subject);

                    $this->email->reply_to($emailData['t_EmployeeEmail'],$emailData['employeeUserName']);

                    foreach($data['emailData']['emailTemplateData'] as $key=>$value) {
                        $emailData['invoiceItems'][$key] = $value;

                        if(isset($value['t_Category'])) {
                            $emailData['t_Category'] = $value['t_Category'];
                        }

                    }

                    $msg = $this->load->view('orderShipEmailTemplate',$emailData, true);

                } else if($typeOfEmailTemplate == "standardEmailTemplate" && $data['emailData']['contactEmailFound'] == false) {
                    $subject                     = "Order Contact Email Address Missing";
                    $from                        = "noreply@indyimaging.com";

                    $emailData['t_CustCompany']  =  $data['emailData']['t_CustCompany'];
                    $emailData['kp_CustomerID'] = $data['emailData']['kp_CustomerID'];


                    $this->email->from($from);
                    $this->email->subject($subject);
                    $this->email->to("csr@indyimaging.com");
                    $this->email->reply_to($emailData['t_EmployeeEmail'],$emailData['employeeUserName']);

                    $msg = $this->load->view('standardEmailTemplate',$emailData, true);
                }

                $this->email->message($msg);
                $this->email->send();

            } else {
                $errorCause                         = array();

                if(empty($employeeRowData['t_UserName']) && empty($to)) {
                  $errorCause['msg'] = "Invalid 'From' and 'To' addresses";
                } else if(empty($employeeRowData['t_UserName'])) {
                  $errorCause['msg'] = "Invalid 'From' address";
                } else if(empty($to)) {
                  $errorCause['msg'] = "Invalid 'To' address";
                }

                $errorCause['location']             = "occured when restAngEmail api was called";
                $errorCause['kp_OrderID']           = $emailData['kp_OrderID'];

                $errorCause['CustomerRef_ListID']   = $emailData['CustomerRef_ListID'];

                $errorCause['SalesRepRef_ListID']   = $emailData['SalesRepRef_ListID'];

                $errorCause['t_QBSalesRepListID']   = $emailData['t_QBSalesRepListID'];

                $errorCause['ListID']               = $emailData['ListID'];
                $errorCause['t_UserName']           = $employeeRowData['t_UserName'];

                if(isset($to)) {
                    $errorCause['to']               = $to;
                }

                if(isset($from)) {
                    $errorCause['from']             = $from;
                }

                $orderItemArry                      = Modules::run('rest/customemailcontroller/orderEmailTrackingError',$errorCause);

                $this->response(array('error' => $errorCause), 200);

            }
        }
    }

}
