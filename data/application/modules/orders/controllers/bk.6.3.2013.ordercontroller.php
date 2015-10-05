<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class OrderController extends MX_Controller 
{
    //put your code here
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('ordermodel');
        
    }
    public function getOrderPricingQuickBook($orderID)
    {
           echo json_encode($this->ordermodel->orderPricingQuickBook($orderID));
        
    }
    public function getOrderJobStatus($orderID)
    {
         echo json_encode($this->ordermodel->orderJobStatus($orderID));
        
    }
    public function getOrderJobStatusCompanyName($orderID)
    {
         echo json_encode($this->ordermodel->orderJobStatusCompanyName($orderID));
        
    }
    public function getOrderJobStatusCategoryFromDueDate($date)
    {
        $displayJobStatusCategory= array();
        
        $getOrderJobStausByDueDate  = $this->ordermodel->orderJobStatusCompanyNameDueDate($date);
       
        for($i=0; $i<sizeof($getOrderJobStausByDueDate); $i++)
        {
            $displayJobStatusCategory[$i] = $getOrderJobStausByDueDate[$i]['t_JobStatus'];
            
        }
        //print_r($displayCategory);
        
        
        $category = array_unique($displayJobStatusCategory);
        //print_r($category);
        $comma_separated = implode(",", $category);
        $category = explode(",", $comma_separated);
        //echo sizeof($category)."<br/>";
        //print_r($category);
        return $category;
     
        
    }        
    public function getOrderJobStatusCompanyNameDueDate($date=null)
    {
        $ul    = "";
        if($date==null)
        {
            date_default_timezone_set('America/Indianapolis');
            $date         = date("Y-m-d", time());
        }
        $jobCategory                = $this->getOrderJobStatusCategoryFromDueDate($date);

        
        $getOrderJobStausByDueDate  = $this->ordermodel->orderJobStatusCompanyNameDueDate($date);
        //var_dump($getOrderJobStausByDueDate);
        foreach($jobCategory as $category)
        {
            $ul  .= "<li data-role=\"list-divider\">".$category."</li>";
            for($i=0; $i<sizeof($getOrderJobStausByDueDate); $i++)
            {
                 $categoryFound = in_array($category,$getOrderJobStausByDueDate[$i]);
                 if($categoryFound)
                 {
                      $ul  .= "<li class=\"orderIDCompanyNameLi\" id="."\"".$getOrderJobStausByDueDate[$i]['kp_OrderID'].
                              ","."pageRequestFromjobDueDate"."\""."><a  class=\"orderIDCompanyNameAnchor\" href=\"\"><h4>".
                              $getOrderJobStausByDueDate[$i]['kp_OrderID']." ".$getOrderJobStausByDueDate[$i]['t_CustCompany'].
                              "</h4><p><strong>".$getOrderJobStausByDueDate[$i]['t_JobName']."</strong></p><p>O:".
                              round($getOrderJobStausByDueDate[$i]['n_OrderItemCount'])."   M:".$getOrderJobStausByDueDate[$i]['t_MachineAb']
                              ."   AB:".$getOrderJobStausByDueDate[$i]['t_OrderItemAb']."   S:".$getOrderJobStausByDueDate[$i]['t_OrdShip'].
                              "</p><p class=\"ui-li-aside\"><strong>".$getOrderJobStausByDueDate[$i]['ti_JobDue']."</strong></p></a></li>";
                      //$ul  .= "<li data-role=\"list-divider\">".$getOrderJobStausByDueDate[$i]['t_JobStatus']."</li>";
                                  //<li><a href=\"\">".$getOrderJobStausByDueDate[$i]['kp_OrderID']." ".$getOrderJobStausByDueDate[$i]['t_CustCompany']."</a></li>"; 
                 }    
            }
        }
        //$ul  .= "</ul>";
        echo $ul;
        
        //print_r($getOrderJobStausByDueDate);
    }        
    public function updateOrderJobStatus($jobStatus,$orderID)
    {
        $this->ordermodel->updateOrderStatus($jobStatus,$orderID);
        
    }
    public function duplicateOrder($oldOrderID,$newOrderID)
    {
        //0. get the orderItems of the old OrderID 
        $getOldOrderItemArrFromOrderID           = Modules::run('orderItems/orderitemcontroller/getOrderItemsFromOrderID',$oldOrderID);
        //echo "<br/><br/>";
        //print_r($getOldOrderItemArrFromOrderID);
        //echo "<br/><br/>";
        
        
        
        //1. get the orderItemID's of the old OrderID and put them in an array
        $getOldOrderItemIDFromOrderItemArray     = Modules::run('orderItems/orderitemcontroller/getOrderItemIDFromOrderItemArray',$getOldOrderItemArrFromOrderID);
        //echo "<br/><br/>";
        //print_r($getOldOrderItemIDFromOrderItemArray);
        //echo "<br/><br/>";
        
        
        //2. Duplicate OrderItemID and Insert the duplicated OrderItem array in the OrderItem table.
        Modules::run('orderItems/orderitemcontroller/duplicateOrderItemsFromOrderID',$getOldOrderItemArrFromOrderID,$newOrderID);
        
        
        
        //3. get the newly inserted OrderItemID's from the new OrderID and put them in an array
        $getNewOrderItemsFromOrderID             = Modules::run('orderItems/orderitemcontroller/getOrderItemsFromOrderID',$newOrderID);
        
        //4. get the orderItemID's of the new OrderID and put them in an array
        $getNewOrderItemIDFromOrderItemArray     = Modules::run('orderItems/orderitemcontroller/getOrderItemIDFromOrderItemArray',$getNewOrderItemsFromOrderID);
        //echo "<br/><br/>";
        //print_r($getNewOrderItemIDFromOrderItemArray);
        //echo "<br/><br/>";
        
        
        //5. get the oic fields from the old orderID value
        $oicArrayFromOldOrderID                  = Modules::run('orderItemComponents/orderitemcomponentcontroller/getOrderItemComponentArrayFromOrderID',$oldOrderID);
        
        //6. replace old orderID with new orderID and old OrderItemID with new OrderItemID to duplicate the OIC array
        for($x=0; $x<sizeof($oicArrayFromOldOrderID); $x++)
        {
            for($i=0; $i<sizeof($getOldOrderItemIDFromOrderItemArray); $i++)
            {
                $orderItemIDFound = in_array($getOldOrderItemIDFromOrderItemArray[$i],$oicArrayFromOldOrderID[$x]);
                if($orderItemIDFound)
                {
                    $oicArrayFromOldOrderID[$x]['kp_OrderItemComponentID'] = "";
                    $oicArrayFromOldOrderID[$x]['kf_OrderItemID']          = $getNewOrderItemIDFromOrderItemArray[$i];
                    $oicArrayFromOldOrderID[$x]['kf_OrderID']              = $newOrderID;
                    //echo "  <br/>New One: ".$newlyInsertedOrderItemIDArray[$i]."  OldOne: ".$oldOrderItemIDArray[$i]." <br/>";
                }
            }    
        }
        //7. Insert the duplicated OIC array in the OIC able.
        Modules::run('orderItemComponents/orderitemcomponentcontroller/submitOrderItemComponentTable',$oicArrayFromOldOrderID);
        
        
    }        
    
}

?>
