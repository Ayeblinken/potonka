<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OrderContactController extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        $this->load->model('ordercontactmodel');

       //$this->load->model('orderShipTracking/ordershiptrackingmodel');

    }

    public function index($orderID=null)
    {
        if(isset($orderID))
        {
            $data['orderID'] = $orderID;
            //echo $orderID.": 1: ";
            $this->load->view('orderContact',$data);
            //echo $orderID;
        }
        else
        {
            $this->load->view('testing');
        }

    }

    public function orderContactTable($orderID)
    {
        echo json_encode($this->ordercontactmodel->orderContactTableData($orderID));
    }

    public function deleteOrderContactTableRow($orderContactID)
    {
        if(is_null($orderContactID))
        {
            echo 'Error: Id not provided';
            return;
        }
        else
        {
            //echo "hi";
            $this->ordercontactmodel->deleteModalAction($orderContactID);
            //$this->load->view('ship_view/testing');
            //echo '<p>Record deleted successfully</p>';
            //$this->index($orderID);
            echo 'Records deleted successfully';
        }

    }

    public function getContactNameFullFromAddressesTable($orderID,$customerID=null)
    {
        $customerID = json_decode(Modules::run('customers/customercontroller/getCustomerFromOrderID',$orderID),true);
        //echo $customerID['kf_CustomerID'];
        echo Modules::run('addresses/addresscontroller/addressesContactNameFull',$orderID,$customerID['kf_CustomerID']);
    }

    public function createOrderContactRow()
    {
        // check to see for posted values
        if(!empty($_POST))
        {
            //call the create action
            $orderContactID = $this->ordercontactmodel->createAction();

            //get the entire table
            echo json_encode($this->ordercontactmodel->orderContactTableData($this->input->post('modalOrderIDHidden',true)));

        }

    }
    public function getOrderContactInfoFromOrderID($orderID)
    {
        $result = $this->ordercontactmodel->getOrderContactDataFromOrderID($orderID);
        //echo json_encode($row);
        return $result;
    }

}

?>
