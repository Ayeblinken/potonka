<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class StatusLogController extends MX_Controller
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('statuslogmodel');
        $this->load->library('user_agent');
        
    }
    
    public function index($orderID=null)
    {
        if(isset($orderID))
        {
            $data['orderID'] = $orderID;
            
            $this->load->view('statuslog',$data);
            
            //echo $orderID.": 1: ";
//            if ($this->agent->is_mobile())
//            {
//                $this->load->view('mstatuslog',$data);
//                
//            }
//            else
//            {
//                $this->load->view('statuslog',$data);
//            }
            
            //echo $orderID;
        }
        else
        {
            $this->load->view('testing'); 
        }  
        
    }
    public function getJobStatus($changeID,$typeOfChange)
    {
        if($typeOfChange == "orderChange")
        {
            echo Modules::run('orders/ordercontroller/getOrderJobStatus',$changeID);
            
        }
        if($typeOfChange == "orderItemChange")
        {
            echo Modules::run('orderItems/orderitemcontroller/getOrderItemJobStatus',$changeID);
            
            
        }
         
        
    }
          
    public function getEmployeeUserNameFromEmployeeTable()
    {
        //$empl = Modules::run('employees/employeecontroller/getEmployeeUserName');
        echo Modules::run('employees/employeecontroller/getEmployeeUserName');
        
    }
    public function getNewStatusNameFromStatusesTable()
    {
         echo Modules::run('statuses/statuscontroller/getNewStatusName');
    }
    public function orderChange($orderID)
    {
        if(isset($orderID))
        {
            $data['changeID'] = $orderID;
            $data['statusChange'] = "orderChange";
            $this->load->view('statusChange',$data);
            //echo $orderID;
        }
        else
        {
            $this->load->view('testing'); 
        }  
       
        
        
    }
    public function orderItemChange($orderItemID)
    {
        if(isset($orderItemID))
        {
            $data['changeID'] = $orderItemID;
            $data['statusChange'] = "orderItemChange";
            $this->load->view('statusChange',$data);
            
            //echo $orderID;
        }
        else
        {
            $this->load->view('testing'); 
        }  
        
        
    }
    public function customOrderStatusChange($orderID=null,$newStatus=null,$userName=null)
    {
        date_default_timezone_set('America/Indianapolis');
        // update in the orders Table
        if(isset($orderID) && isset($newStatus) && isset($userName))
        {
            $newStatus=urldecode($newStatus);
            //$newStatus  = htmlspecialchars($newStatus);
            
            $userName        = urldecode($userName);
            $newStatus       = urldecode($newStatus);
            
            $orderResultData = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$orderID);
            
            //print_r($orderResultData);
            
            $oldStatus       = $orderResultData['t_JobStatus'];
            
            //echo $oldStatus;
            
            //echo $newStatus."<br/>";
            //echo $userName."<br/>";
            $updateOrderTable = Modules::run('orders/ordercontroller/updateOrderJobStatus',$newStatus,$orderID);
                
            //get the data needed to insert into Status Log Table
            $data[0] = array(
            'kf_OrderID' => $orderID,
            't_StatusOld'=> $oldStatus,    
            't_StatusNew'=> $newStatus,
            't_ApprovedByName'=> $userName,

            'zCreated'=> date("Y-m-d H:i:s", time())
            );

            $message = $this->statuslogmodel->insertCreatedStatusLog($data);
            if($message)
            {
                echo "success";
            }    
            //echo $message;
        }
        else
        {
            echo "please check your url.parameters orderID, newStatus and UserName missing";
        }    
        
        
    }       
    public function submitStatusChange()
    {
        $orderItemIDJobStatus     =   "";
        $jobStatus                =   "";
        $confirm                  =   "";
        
        date_default_timezone_set('America/Indianapolis');
        //print_r($_POST);
        if(!empty($_POST))
        {
            if($this->input->post('statusChangeRequestHidden',true) == "orderChange")
            {
                
                // update in the orders Table
                $updateOrderTable = Modules::run('orders/ordercontroller/updateOrderJobStatus',$this->input->post('newStatus',true),$this->input->post('orderIDHidden',true));
                
                //get the data needed to insert into Status Log Table
                $data[0] = array(
                'kf_OrderID' => $this->input->post('orderIDHidden',true),
                't_StatusOld'=> $this->input->post('currentStatus',true),
                't_StatusNew'=> $this->input->post('newStatus',true),
                't_ApprovedByName'=> $this->input->post('userName',true),
                't_Notes'=> $this->input->post('notes',true),
                'zCreated'=> date("Y-m-d H:i:s", time())
                );
                
            }
            if($this->input->post('statusChangeRequestHidden',true) == "orderItemChange" && $this->input->post('applyToAll',true) == "")
            {
                // update in the orderItems Table
                $updateOrderTable = Modules::run('orderItems/orderitemcontroller/updateOrderItemJobStatus',$this->input->post('newStatus',true),$this->input->post('orderItemIDHidden',true));
                
                //get the data needed to insert into Status Log Table
                 $data[0] = array(
                'kf_OrderID' => $this->input->post('orderIDHidden',true),
                'kf_OrderItemID'=> $this->input->post('orderItemIDHidden',true),
                't_StatusOld'=> $this->input->post('currentStatus',true),
                't_StatusNew'=> $this->input->post('newStatus',true),
                't_ApprovedByName'=> $this->input->post('userName',true),
                't_Notes'=> $this->input->post('notes',true),
                'zCreated'=> date("Y-m-d H:i:s", time())
                );
            }
            if($this->input->post('statusChangeRequestHidden',true) == "orderItemChange" && $this->input->post('applyToAll',true) == "1")
            {
                //get the orderItem rows for the current orderID
                $row    = Modules::run('orderItems/orderitemcontroller/getOrderItemsFromOrderID',$this->input->post('orderIDHidden',true));
                
                //store the individual  job status and orderitemid's  
                for($i = 0; $i<sizeof($row); $i++)
                {
                    $orderItemIDJobStatus[$i]['kp_OrderItemID']        = $row[$i]['kp_OrderItemID'];
                    $orderItemIDJobStatus[$i]['t_OiStatus']            = $row[$i]['t_OiStatus'];
                    $jobStatus[$i]                                     = $row[$i]['t_OiStatus'];
                }
                // check for unique statuses.
                // If unique statuses are found (i.e, status Count value > 1)
                // alert the user (with a modal window) to confirm the 'apply to all status' request.
                
                $status      = array_unique($jobStatus);
               
                $statusCount = sizeof($status);
               
                //echo $statusCount;
                
                if($statusCount > 1)
                {
                    $confirm = "Confirmation Needed";
                }
                else
                {
                    //apply the change for all orderitems record updating only the orderitem job status
                    $updateOrderTable = Modules::run('orderItems/orderitemcontroller/updateAllOrderItemJobStatus',$this->input->post('newStatus',true),$this->input->post('orderIDHidden',true));

                    //get the data needed to insert into Status Log Table
                    for($i = 0; $i<sizeof($orderItemIDJobStatus); $i++)
                    {
                        $data[$i] = array(
                        'kf_OrderID' => $this->input->post('orderIDHidden',true),
                        'kf_OrderItemID' => $orderItemIDJobStatus[$i]['kp_OrderItemID'],
                        't_StatusOld'=> $orderItemIDJobStatus[$i]['t_OiStatus'],
                        't_StatusNew'=> $this->input->post('newStatus',true),
                        't_ApprovedByName'=> $this->input->post('userName',true),
                        't_Notes'=> $this->input->post('notes',true),
                        'zCreated'=> date("Y-m-d H:i:s", time()));

                    }
                    
                }    
                
               
                
            }
            if($confirm == "Confirmation Needed")
            {
                echo "CONFIRM";
            }
            else
            {
                //insert the newly created data into  Status Log table
                $message = $this->statuslogmodel->insertCreatedStatusLog($data);
                echo $message;
            }    
           
            
        }
        
    }
    public function statusChangeConfirmed()
    {
        $orderItemIDJobStatus     =   "";
        date_default_timezone_set('America/Indianapolis');
         
        //get the orderItem rows for the current orderID
        $row    = Modules::run('orderItems/orderitemcontroller/getOrderItemsFromOrderID',$this->input->post('orderIDHiddenModal',true));
        
        //store the individual  job status and orderitemid's  
        for($i = 0; $i<sizeof($row); $i++)
        {
            $orderItemIDJobStatus[$i]['kp_OrderItemID']        = $row[$i]['kp_OrderItemID'];
            $orderItemIDJobStatus[$i]['t_OiStatus']            = $row[$i]['t_OiStatus'];
           
        }
        //apply the change for all orderitems record updating only the orderitem job status
        $updateOrderTable = Modules::run('orderItems/orderitemcontroller/updateAllOrderItemJobStatus',$this->input->post('newStatusHiddenModal',true),$this->input->post('orderIDHiddenModal',true));

        //get the data needed to insert into Status Log Table
        for($i = 0; $i<sizeof($orderItemIDJobStatus); $i++)
        {
            $data[$i] = array(
            'kf_OrderID' => $this->input->post('orderIDHiddenModal',true),
            'kf_OrderItemID' => $orderItemIDJobStatus[$i]['kp_OrderItemID'],
            't_StatusOld'=> $orderItemIDJobStatus[$i]['t_OiStatus'],
            't_StatusNew'=> $this->input->post('newStatusHiddenModal',true),
            't_ApprovedByName'=> $this->input->post('userNameHiddenModal',true),
            't_Notes'=> $this->input->post('notesHiddenModal',true),
            'zCreated'=> date("Y-m-d H:i:s", time()));
        }
        //insert the newly created data into  Status Log table
        $message = $this->statuslogmodel->insertCreatedStatusLog($data);
        echo $message;

    }        
    public function statuslogTable($orderID)
    {
        echo json_encode($this->statuslogmodel->statusLogTableData($orderID));
    }
    public function partialStatusChangeSubmit()
    {
        date_default_timezone_set('America/Indianapolis');
       
        $orderItemIDArry = $this->input->post('partialOrderItemArry',true);
        
        //for each orderItemID update the status
        for($i = 0; $i<sizeof($orderItemIDArry); $i++)
        {
            // update in the orderItems Table
            $updateOrderItemTable = Modules::run('orderItems/orderitemcontroller/updateOrderItemJobStatus',$this->input->post('newStatus',true),$orderItemIDArry[$i]);
            $data[$i] = array(
                'kf_OrderID' => $this->input->post('orderIDHidden',true),
                'kf_OrderItemID' => $orderItemIDArry[$i],
                't_StatusOld'=> $this->input->post('currentStatus',true),
                't_StatusNew'=> $this->input->post('newStatus',true),
                't_ApprovedByName'=> $this->input->post('userName',true),
                't_Notes'=> $this->input->post('notes',true),
                'zCreated'=> date("Y-m-d H:i:s", time())
            );
        }
        
        $message = $this->statuslogmodel->insertPartialStatusLog($data);
        
        echo $message;
        
    }        
}

?>
