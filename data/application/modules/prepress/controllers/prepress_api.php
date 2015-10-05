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

class Prepress_Api extends REST_Controller {
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
        $this->load->model('prepressmodel');
    }

    public function printAll_put() {
      if(!$this->put('data')) {
        $this->response(array(array('error' => 'Data not found.')), 400);
      }

      $data = $this->put('data');

      for($x=0; $x<count($data); $x++) {
        $this->prepressmodel->updatePrintAll($data[$x]);
      }

      $this->response(array('result' => "Success"), 200);
    }

    public function pressbotAddTicket_post() {
      if(!$this->post('data')) {
        $this->response(array(array('error' => 'Data param not found.')), 400);
      }

      $data = json_decode($this->post('data'));
      $lineItem = explode('-', $data->lineItem);
      $orderID = $lineItem[0];
      $dashNum = $lineItem[1];

      $orderItem = $this->prepressmodel->getOrderItemFromOrderDashNum($orderID, $dashNum);
      $orderItemID = $orderItem['kp_OrderItemID'];

      $employeeInfo = $this->prepressmodel->getEmployeeIDFromUserName($data->createdBy);

      $ticket = array();
      $ticket['kf_OrderID'] = $orderID;
      $ticket['kf_OrderItemID'] = $orderItemID;
      $ticket['n_FileHeight'] = $data->documentHeight;
      $ticket['n_FileWidth'] = $data->documentWidth;
      $ticket['t_CompletedBy'] = $data->createdBy;
      $ticket['n_QtyUp'] = $data->quantityUp;
      $ticket['n_QtyToPrint'] = ceil($orderItem['n_Quantity'] /  $data->quantityUp);
      $ticket['t_OtherLineItem'] = $data->otherLineItems;
      $ticket['ts_TimeCreate'] = date('Y-m-d H:i:s');
      $ticket['t_Filename'] = $data->fileName;
      $ticket['kf_PrepressID'] = $employeeInfo['kp_EmployeeID'];
      $ticket['t_Instructions'] = $data->t_Instructions;
      $ticket['nb_NeedsPrinted'] = $data->nb_NeedsPrinted;

      if($data->ticketType == 'Print') {
        $ticket['t_TicketType'] = 'Press Ticket';
        $ticket['t_TypeOfPrint'] = $data->printType;
      } else {
        $ticket['t_TicketType'] = 'Cut Ticket';
        $ticket['t_TypeOfCut'] = $data->cutType;
      }

      // if(isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
      //   $fileName   = $_FILES['file']['name'];
      //   $this->response(array('ticket' => $ticket, 'fileName' => $fileName), 200);
      // } else {
      //   $this->response(array('error' => "Either file wasn't found or error number " . $_FILES['file']['error']), 400);
      // }


      $ticketID = $this->prepressmodel->insertPrepressTicketData($ticket);

      //Ticket Made, upload the image
      $orderDateReceived = Modules::run('orderItems/orderitemcontroller/getDateReceived',$orderID);
      $dateReceivedArry  = explode("-", $orderDateReceived);
      $yearOrder         = $dateReceivedArry[0];
      $monthOrder        = $dateReceivedArry[1];

      $result       = $this->prepressmodel->prepressTicketImageUpload($ticketID,$orderID,$yearOrder,$monthOrder);

      $ticketURL = "https://apps.indyimaging.com/shopbot/#/order/view/" . $orderID . "/prepress/" . $ticketID;

      $this->response(array('result' => $result, 'ticketID' => $ticketID, 'ticketURL' => $ticketURL), 200);
    }

    public function customerArtTableData_get() {
      if(!$this->get('date')) {
        $this->response(array(array('error' => 'ID or Date Not Found')), 400);
      }

      $date = $this->get('date');

      $result = $this->prepressmodel->getCustomerArtTableData($date);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No Customer Art Tickets Found')), 200);
      }
    }

    public function prepressTicketImageUpload_post() {
      if(!$this->post('kp_TicketID') || !$this->post('kp_OrderID')) {
        $this->response(array(array('error' => 'ID(s) Not Found')), 400);
      }
      $ticketID = $this->input->post('kp_TicketID');
      $orderID = $this->input->post('kp_OrderID');

      $orderDateReceived = Modules::run('orderItems/orderitemcontroller/getDateReceived',$orderID);

      $dateReceivedArry  = explode("-", $orderDateReceived);

      $yearOrder         = $dateReceivedArry[0];
      $monthOrder        = $dateReceivedArry[1];

      $result       = $this->prepressmodel->prepressTicketImageUpload($ticketID,$orderID,$yearOrder,$monthOrder);

      $this->response($result, 200);
    }

    public function prepressCustomerArtTicketImageUpload_post() {
      if(!$this->post('kp_TicketID') || !$this->post('year') || !$this->post('monthDay')) {
        $this->response(array(array('error' => 'Ticket ID Not Found')), 400);
      }
      $ticketID = $this->input->post('kp_TicketID');
      $year = $this->post('year');
      $monthDay = $this->post('monthDay');

      $result       = $this->prepressmodel->prepressCustomerArtTicketImageUpload($ticketID, $year, $monthDay);

      $this->response($result, 200);
    }

    public function duplicatePrepressTicket_post() {
      if(!$this->post('id')) {
        $this->response(array(array('error' => 'ID Not Found')), 400);
      }
      $id = $this->post('id');

      $data = $this->prepressmodel->getPrepressTicketDataForDuplicate($id);

      $data['kp_TicketID'] = "";
      $data['ts_TimeCreate'] = date('Y-m-d H:i:s');
      $data['t_ImageName'] = null;

      $newID = $this->prepressmodel->insertPrepressTicketData($data);

      if($newID) {
        $this->response(array('newID' => $newID), 200);
      } else {
        $this->response(array('error' => 'Duplication Failed'), 404);
      }
    }

    public function prepressTicketData_get() {
      if(!$this->get('id')) {
        $this->response(array(array('error' => 'ID Not Found')), 400);
      }
      $id = $this->get('id');

      $result = $this->prepressmodel->getPrepressTicketData($id);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No Prepress Ticket Found')), 404);
      }
    }

    public function prepressTableData_get() {
      if(!$this->get('id')) {
        $this->response(array(array('error' => 'ID Not Found')), 400);
      }
      $id = $this->get('id');

      $result = $this->prepressmodel->getPrepressTableData($id);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No Prepress Tickets Found')), 200);
      }
    }

    public function index_get()
    {
        $orderDashNum     = $this->get('orderDashNum');

        $orderDashNumArry = explode("-", $orderDashNum);

        //isset evalutes to true for empty string '' -- so use empty
        if(!empty($orderDashNumArry[0]) && !empty($orderDashNumArry[1]))
        {
            $orderID        = $orderDashNumArry[0];
            $dashNum        = $orderDashNumArry[1];

            $orderItemArry  = Modules::run('orderItems/orderitemcontroller/getOrderItemIDFromOrderIDDashNumPrepress',$orderID,$dashNum);

            $result         = $this->getOrderItemComponentInfoFromOrderItemID($orderItemArry['kp_OrderItemID']);

        }
        else if(!empty($orderDashNumArry[0]) && empty($orderDashNumArry[1]))
        {
            $orderID        = $orderDashNumArry[0];

            $orderItemArry  = Modules::run('orderItems/orderitemcontroller/getOrderItemsFromOrderID',$orderID);

            for($x=0;$x<count($orderItemArry);$x++)
            {
                $result[$x] = $this->getOrderItemComponentInfoFromOrderItemID($orderItemArry[$x]['kp_OrderItemID']);
            }
        }

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'Prepress orderItemComponent could not be found. Contact IT','errorResult'=>$result), 404);
        }

    }
    public function getOrderItemComponentInfoFromOrderItemID($orderItemID)
    {
        $result  = $this->prepressmodel->prepressOrderItemComponentInfoFromOrderID($orderItemID);

        return $result;


    }

}
