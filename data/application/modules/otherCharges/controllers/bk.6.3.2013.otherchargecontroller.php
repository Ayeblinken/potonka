<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class OtherChargeController extends MX_Controller
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('otherchargemodel');
       
       //$this->load->model('orderShipTracking/ordershiptrackingmodel');
        
    }
    
    public function index($orderID=null)
    {
        if(isset($orderID))
        {
            $data['orderID'] = $orderID;
            //echo $orderID.": 1: ";
            $this->load->view('othercharge',$data);
            //echo $orderID;
        }
        else
        {
            $this->load->view('testing'); 
        }  
        
    }
    
    public function otherChargeTable($orderID)
    {
        echo json_encode($this->otherchargemodel->otherChargeTableData($orderID));
    }
    public function getOtherChargeTableData($otherChargeID)
    {
        echo json_encode($this->otherchargemodel->modalOtherChargeData($otherChargeID));
        
    }
    
    public function updateCreateOtherChargeTable()
    {
        
        // check to see for posted values
        if(!empty($_POST))
        {
           
            // if the type of submit is create 
            if($this->input->post('typeOfSubmitHidden',true) == "Create")
            {
                //call the create action
                $orderShipID = $this->otherchargemodel->createAction();
                
                //echo "inside wass up1";
                
                //get the entire table
                echo json_encode($this->otherchargemodel->otherChargeTableData($this->input->post('modalOrderIDHidden',true)));
                
            }
            
            // if the type of submit is update 
            if($this->input->post('typeOfSubmitHidden',true) == "Update")
            {
                //echo "Update";
                //call the update action
                $this->otherchargemodel->updateAction();
            
                // get the row that was just updated in the previous step.
                echo json_encode($this->otherchargemodel->modalOtherChargeData($this->input->post('modalOtherChargeID',true)));
                
            }
            
            
        }
        
        
    }
    
    public function deleteOtherChargeTableRow($otherChargeID)
    {
        if(is_null($otherChargeID))
        {
            echo 'Error: Id not provided';
            return;
        }
        else
        {
            //echo "hi";
            $this->otherchargemodel->deleteModalAction($otherChargeID);
            //$this->load->view('ship_view/testing');
            //echo '<p>Record deleted successfully</p>';
            //$this->index($orderID);
            echo 'Records deleted successfully';
        }
        
    }
    
}
?>
