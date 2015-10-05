<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class OrderController extends MX_Controller 
{
    //put your code here
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('ordermodel');
        // this is testing 
    }
    public function getOrderIDFromMonthYearDateReceived($year=null,$month=null)
    {
        $orderIDInfo = $this->ordermodel->getOrderFromMonthYearDateRecived($year,$month);
        //echo sizeof($orderIDInfo);
        //var_dump($orderIDInfo);
        
        return $orderIDInfo;
        
    }        
    public function getOrderPricingQuickBook($orderID)
    {
           echo json_encode($this->ordermodel->orderPricingQuickBook($orderID));
        
    }     
    public function reCreateQRCodeFromYearAndMonth($year=2013,$month=11)
    {
        $orderInfo = $this->getOrderIDFromMonthYearDateReceived($year, $month);
        for($i=0;$i<sizeof($orderInfo);$i++)
        {
            //echo $orderInfo[$i]['kp_OrderID'];
            $this->ordermodel->reCreateQRCode($orderInfo[$i]['kp_OrderID']);
            
            //$orderArry         = $this->getOrderFieldsFromOrderID($orderInfo[$i]['kp_OrderID']);
            
            //$orderDateReceived = $orderArry['d_Received'];
            
            //send to the model where it will delete any qrcodes and re create them 
            
            
        }
        //print_r($orderInfo);
        
    } 
    public function createQRCodeFromOrderID($orderID)
    {
        $orderArry        = $this->getOrderFieldsFromOrderID($orderID);
        $qrCodeGenerated  = $orderArry['nb_QrcodeGeneratedOI'];
        $dateReceived     = $orderArry['d_Received'];
        //check if QRCode already exists
        if($qrCodeGenerated == 1)
        {
            // check the physical path of the image
            $this->ordermodel->checkPhysicalPathOFQrCodeImage($orderID,$dateReceived);
            $createQRCode['msg'] = "yesQRcode";
            
        }
        else
        {
            $this->ordermodel->createQRCodeFromOrderID($orderID,$dateReceived);
            $qrCodeGenerated              = 1;
            $data['nb_QrcodeGeneratedOI'] = $qrCodeGenerated;
            $result = $this->updateOrderTbl($data, $orderID);
            $createQRCode['msg']          = "yesQRcode";
        }    
        
        
    }        
    public function loadCreditReleaseFrm($orderID)
    {
        $data['orderID']           = $orderID;
        
        //$orderIDArry               = $this->getOrderFieldsFromOrderID($orderID);
        
        //$customerDataFromOrderID   = Modules::run('customers/customercontroller/getCustomerFieldsFromCustomerID',$orderIDArry['kf_CustomerID']);
        
        //$data['t_CustCompany']     = $customerDataFromOrderID['t_CustCompany']; 
        //$data['t_ContactNameFull'] = $customerDataFromOrderID['t_ContactNameFull']; 
        $this->load->view('creditRelease',$data);
        
    }
    public function orderNotes($orderID=null,$typeOfView=null)
    {
        $data['notesOrderID']   = $orderID;
        $data['typeOfView']     = $typeOfView;
        $this->load->view('orderNotes',$data);
    }  
    public function getOrderNotesData($orderID)
    {
        $orderDataArry                   = $this->getOrderFieldsFromOrderID($orderID);
        $dataArry['t_NotesHTML']         = $orderDataArry['t_NotesHTML'];
        $dataArry['t_Notes']             = $orderDataArry['t_Notes'];
        
        $customerID                      = $orderDataArry['kf_CustomerID'];
        $dataArry['notesCustomerID']     = $customerID;
        
        $customerDataArry                = Modules::run('Customers/customercontroller/getCustomerFieldsFromCustomerID',$dataArry['notesCustomerID']);
        //print_r($customerDataArry);
        //echo "<br/>".$customerDataArry['t_Notes']."<br/>";
        $customerNotes                   = $customerDataArry['t_Notes'];
        //echo "<br/>".$customerNotes."<br/>";
        
        $dataArry['customerNotes']       = $customerNotes;
        
        //var_dump($dataArry);
        echo json_encode($dataArry);
        
        
    }
    public function submitOrderNotesData()
    {
        $notesTypeOfView   = $this->input->post('notesTypeOfView');
        $orderID           = $this->input->post('notesOrderIDHidden');
        
        $customerID        = $this->input->post('notesCustomerIDHidden');
        $breaks            = array("<br />","<br>","<br/>"); 
        
        if($notesTypeOfView == "customerOrderView")
        {
            
            $t_NotesHTML                = $this->input->post('orderNotesHiddenVal');
        
            $data['t_NotesHTML']        =  $t_NotesHTML;
        
            $t_NotesCrude               = strip_tags($data['t_NotesHTML'],"<br>");
            //echo $t_NotesCrude."<br/>";
            $t_NotesCrudeTxt            = str_replace($breaks, "\n",$t_NotesCrude);
            //echo $t_NotesCrude."<br/>";
            $t_Notes                    = ltrim($t_NotesCrudeTxt);
            //$t_Notes                  = ltrim($t_NotesCrude);
        
            $data['t_Notes']            = $t_Notes;
        
            //$t_Notes                  = str_replace("\r", '', $t_NotesCrude);
            //$data['t_Notes']          = str_replace("\r", '', $t_NotesCrude);
        
            //$data['t_Notes']         = str_replace("\r", '', $t_NotesCrude); // remove carriage returns
            //echo "<br/><br/>";
            //print_r($data);
        
            $result                     = $this->updateOrderTbl($data, $orderID);
        
        
            $customerNotesHTML          = $this->input->post('customerNotesHiddenVal');
        
            $customerNotesHTMLCrude     = strip_tags($customerNotesHTML,"<br>");
       
            $customerNotesHTMLCrudeTxt  = str_replace($breaks, "\n",$customerNotesHTMLCrude);
        
            $customerNotes              = ltrim($customerNotesHTMLCrude);
      
        
            $custData['t_Notes']        = $customerNotes;
      
        
            $customerDataArry           = Modules::run('Customers/customercontroller/updateCustomerInfo',$customerID,$custData);
        
            
        }
        else if($notesTypeOfView == "customerView")
        {
            $customerNotesHTML          = $this->input->post('customerNotesHiddenVal');
        
            $customerNotesHTMLCrude     = strip_tags($customerNotesHTML,"<br>");
       
            $customerNotesHTMLCrudeTxt  = str_replace($breaks, "\n",$customerNotesHTMLCrude);
        
            $customerNotes              = ltrim($customerNotesHTMLCrude);
      
        
            $custData['t_Notes']        = $customerNotes;
      
        
            $customerDataArry           = Modules::run('Customers/customercontroller/updateCustomerInfo',$customerID,$custData);
            
        }
        else if($notesTypeOfView == "orderView")
        {
            $t_NotesHTML                = $this->input->post('orderNotesHiddenVal');
        
            $data['t_NotesHTML']        =  $t_NotesHTML;
        
            $t_NotesCrude               = strip_tags($data['t_NotesHTML'],"<br>");
            //echo $t_NotesCrude."<br/>";
            $t_NotesCrudeTxt            = str_replace($breaks, "\n",$t_NotesCrude);
            //echo $t_NotesCrude."<br/>";
            $t_Notes                    = ltrim($t_NotesCrudeTxt);
            //$t_Notes                  = ltrim($t_NotesCrude);
        
            $data['t_Notes']            = $t_Notes;
        
            //$t_Notes                  = str_replace("\r", '', $t_NotesCrude);
            //$data['t_Notes']          = str_replace("\r", '', $t_NotesCrude);
        
            //$data['t_Notes']         = str_replace("\r", '', $t_NotesCrude); // remove carriage returns
            //echo "<br/><br/>";
            //print_r($data);
        
            $result                     = $this->updateOrderTbl($data, $orderID);
            
        }    
        //echo $t_Notes;
        echo json_encode("done");
        //echo json_encode($t_Notes);
        //echo json_encode($data['t_Notes']);
        
    }        

    public function getCreditReleaseFrmData($orderID)
    {
         $creditReleaseFrmData = $this->ordermodel->getCreditReleaseData($orderID);
         
         //return $creditReleaseFrmData;
         echo json_encode($creditReleaseFrmData);
        
    }
    public function submitCreditReleaseFrmData()
    {
        date_default_timezone_set('America/Indianapolis');
        
        $data['nb_CreditHoldOveride']      = $this->input->post('overRideCreditHoldCheckBox');
        
        $data['t_CreditHoldTypeOveride']   = $this->input->post('creditHoldTypeOverRideSelect');
        $data['t_CreditHoldReleasedBy']    = $this->input->post('releasedBySelect');
        
        $data['t_CreditHoldOverideNote']   = $this->input->post('overRideNotes');
        
        $data['ts_CreditHoldReleased']     = date('Y-m-d H:i:s');
        
        $orderID                           = $this->input->post('orderIDHidden');
        
        //$this->updateOrderTbl($data, $orderID);
        
        //echo json_encode("done");
        $result = $this->updateOrderTbl($data, $orderID);
        
        if($result == "1")
        {
            echo json_encode("done");
            
        }
        else
        {
             echo json_encode("Error");
            
        }    
        //echo $result;
//        if($this->ordermodel->updateOrderTbl($data, $orderID))
//        {
//            echo json_encode("done");
//        }        
        
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
                              ","."pageRequestFromjobDueDate"."\""."><a  class=\"orderIDCompanyNameAnchor\" href=\"\">".
                              $getOrderJobStausByDueDate[$i]['kp_OrderID']." ".$getOrderJobStausByDueDate[$i]['t_CustCompany'].
                              "<h2>".$getOrderJobStausByDueDate[$i]['t_JobName']."</h2><h2>".$getOrderJobStausByDueDate[$i]['t_MachineAb']
                              ."<h2/><span class=\"ui-li-count\">".round($getOrderJobStausByDueDate[$i]['n_OrderItemCount'])."</span>".
                              "<p class=\"ui-li-aside\"><strong>".$getOrderJobStausByDueDate[$i]['ti_JobDue']."</strong></p></a></li>";
                      
                      
                      
                      
//                       $ul  .= "<li class=\"orderIDCompanyNameLi\" id="."\"".$getOrderJobStausByDueDate[$i]['kp_OrderID'].
//                              ","."pageRequestFromjobDueDate"."\""."><a  class=\"orderIDCompanyNameAnchor\" href=\"\"><h4>".
//                              $getOrderJobStausByDueDate[$i]['kp_OrderID']." ".$getOrderJobStausByDueDate[$i]['t_CustCompany'].
//                              "</h4><p><strong>".$getOrderJobStausByDueDate[$i]['t_JobName']."</strong></p><p>O:".
//                              round($getOrderJobStausByDueDate[$i]['n_OrderItemCount'])."   M:".$getOrderJobStausByDueDate[$i]['t_MachineAb']
//                              ."   AB:".$getOrderJobStausByDueDate[$i]['t_OrderItemAb']."   S:".$getOrderJobStausByDueDate[$i]['t_OrdShip'].
//                              "</p><p class=\"ui-li-aside\"><strong>".$getOrderJobStausByDueDate[$i]['ti_JobDue']."</strong></p></a></li>";
                      
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
    public function updateOrderTbl($data,$orderID)
    {
        //print_r($data);
        
        //echo "<br/>".$orderID;
        $msg = $this->ordermodel->updateOrderTbl($data,$orderID);
        return $msg;
        
    }
    public function dupOrderItemOrderItemComponents($oldOrderID,$newOrderID)
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
    public function getOrderFieldsFromOrderID($orderID)
    {
        $row = $this->ordermodel->getOrderByID($orderID);
        
        return $row;
        
    }
    public function getOrderFieldsFromOrderIDInJsonFormat($orderID)
    {
        $row = $this->getOrderFieldsFromOrderID($orderID);
        echo json_encode($row);
    }
    
    public function duplicateOrder($oldOrderID,$typeOfJobTicket,$typeOfOrder=null,$kf_RedoOriginalJobID=null,$kf_OrderRedoID=null)
    {
        date_default_timezone_set('America/Indianapolis');
        
        $row                                        =  $this->getOrderFieldsFromOrderID($oldOrderID);
        
        if($typeOfOrder == "Redo")
        {
            $row['t_TypeOfOrder']                   = "Redo";
            $row['kf_RedoOriginalJobID']            = $kf_RedoOriginalJobID;
            $row['kf_OrderRedoID']                  = $kf_OrderRedoID;
            
            $row['nb_UseTotalOrderPricing']         = 1;
            $row['n_TotalOrderPrice']               = "0.00";
            
        }    
        //$row                                        =  $this->ordermodel->getOrderByID($oldOrderID);
        $row['nb_QrcodeGeneratedOI']                =null;
        $row['t_PressProofSize']                    =null;
        $row['t_PressProofNotes']                   =null;
        $row['t_PressProofType']                    =null;
        
        $row['kp_OrderID']                          =null;
        
        # Clean out Date fields					
        $row['t_ServiceLevel']                      =null;
        $row['d_Received']                          =date("Y-m-d", time());
        #-
        $row['d_JobDue']                            =null;
        $row['d_PrintJobDue']                       =null;
        $row['d_ProofDue']                          =null;
        $row['d_SchedDay']                          =null;
        $row['d_TentativeApproval']                 =null;
        $row['d_TentativeJobDue']                   =null;
        $row['nb_OrderLoggedIntoSystem']            =null;
        $row['nb_JobFinished']                      =null;

        # Clean out Time and TimeStamp fields 
        $row['ti_JobDue']                           =null;
        $row['ti_PrintJobDue']                      =null;
        $row['ti_ProofDue']                         =null;
        $row['ti_TentativeJobDue']                  =null;
        #-
        $row['ts_JobMarkedAsFinished']              =null;
        $row['ts_OverrideAfterCutOff']              =null;
        $row['ts_PostedToQuickbooks']               =null;
        $row['ts_ProofApproved']                    =null;				
        $row['ts_ProofsApproved']                   =null;
        $row['ts_ProofSent']                        =null;
        $row['ts_ScheduleApproval']                 =null;
        $row['ts_ServiceLevelApproved']             =null;
        $row['ts_TrackNumberEmailSent']             =null;

        # Clean out Order Level Proof Approved fields					
        $row['t_ProofsApprovedBy']                  =null;

        //EmailSentFlags #
        $row['nb_EmailSentOrderConfirmation']       =null;
        $row['nb_EmailSentTrackNumReadyToPickUp']   =null;

        # Clean out QB fields and accounting			
        $row['nb_PostedToQuickBooks']               =null;
        $row['t_QBInvoiceNumber']                   =null;
        $row['t_QBEditSequence']                    =null;
        $row['t_QBTxnID']                           =null; 
        $row['t_CreditCardTransaction']             =null;
        $row['t_OverideCreditHold']                 =null;

        $row['t_JobStatus']                         =null;
        
        //type of job ticket
        if($typeOfJobTicket == "Estimate")
        {
            $row['t_TypeOfJobTicket']               = "Estimate";
        } 
        if($typeOfJobTicket == "InternalOrderForm")
        {
            $row['t_TypeOfJobTicket']               = "InternalOrderForm";   
        }
        if($typeOfJobTicket == "JobWithProductBuilds")
        {
            $row['t_TypeOfJobTicket']               = "JobWithProductBuilds";
        }
        
        $row['zCreated']   = date("Y-m-d H:i:s", time());
        
        
        // credit hold
        $row['t_CreditHoldReleasedBy']              = null;
        $row['t_CreditHoldOverideNote']             = null;
          
        //insert the updated Order as new Order and get the new OrderID
        $newOrderID        = $this->ordermodel->insertOrderData($row);
        
        //$result            = array($oldOrderID,$newOrderID);
        
        return $newOrderID;
        
    }        
    public function dupOtherChargesOrderShipOrderOrderItemOrderItemComponents($oldOrderID,$typeOfJobTicket,$viewAction=null)
    {
        //0. duplicates Order from OrderID  //$result_array = $this->duplicateOrder($oldOrderID);  //$oldOrderID = $result_array[0];
        $newOrderID                           = $this->duplicateOrder($oldOrderID,$typeOfJobTicket);
        
        //1. duplicates OrderItems and OrderItemComponents from OrderID
        $this->dupOrderItemOrderItemComponents($oldOrderID, $newOrderID);
        
        //2. get the orderShipArry from the old OrderID 
        $getOrderShipArrFromOrderID           = Modules::run('orderShip/ordershipcontroller/getOrderShipTblFromOrderID',$oldOrderID);
        
        //print_r($getOrderShipArrFromOrderID);
        
        if(!empty($getOrderShipArrFromOrderID))
        {
            //3. Duplicate orderShipArr with the new OrderID and Insert the duplicated orderShipArr array in the orderShip table.
            Modules::run('orderShip/ordershipcontroller/duplicateOrderShipDataFromOrderID',$getOrderShipArrFromOrderID,$newOrderID);
        }  
        
        //3.1 get the orderShipArry from the new OrderID
        $getOrderShipArrFromNewOrderID          = Modules::run('orderShip/ordershipcontroller/getOrderShipTblFromOrderID',$newOrderID);
        
        //3.2 Create the BarCode for the newly Creted OrderShipArry
        
        if(!empty($getOrderShipArrFromNewOrderID))
        {
             Modules::run('orderShip/ordershipcontroller/createBarCodeForduplicateOrderShipData',$getOrderShipArrFromNewOrderID,$newOrderID);
        }     
        
        //4. get the otherChargesArry from the old OrderID 
        $getOtherChargesArrFromOrderID           = Modules::run('otherCharges/otherchargecontroller/getOtherChargeTblFromOrderID',$oldOrderID);
        
        //print_r($getOtherChargesArrFromOrderID);
        //echo empty($getOtherChargesArrFromOrderID)."<br/>";
        //echo sizeof($getOtherChargesArrFromOrderID)."<br/>";
        
        if(!empty($getOtherChargesArrFromOrderID))
        {
            //5. Duplicate otherChargesArry with the new OrderID and Insert the duplicated otherChargesArry array in the otherCharges table.
            Modules::run('otherCharges/otherchargecontroller/duplicateOtherChargesDataFromOrderID',$getOtherChargesArrFromOrderID,$newOrderID);        
        }
        if($viewAction == "steps")
        {
             redirect("salesOrderRequest/".$newOrderID,'refresh');
            
        }
        else
        {
            echo $newOrderID;
            
        }    
        
    } 
    public function getOrderandOrderItemsByOrderID($OrderID)	  
    {	
        $data['result'] = $this->ordermodel->getOrderRelatedInfoForOrderView($OrderID);
        //$data['result1'] = $this->ordermodel->getorderitem($id);

        //$data['result1'] = Modules::run('orderItems/orderitemcontroller/getOrderItemRowsByOrderIDResultObject',$OrderID);

        $data['page_title'] = "Orders";  

        $data['orderID'] = $OrderID;

        $this->load->view('orderview',$data);
        //print_r($data);


    }
    public function internalOrderFrmView($orderID=null)
    {
        $data = "";
        if(isset($orderID) && !is_null($orderID))
        {
            $data['orderID'] = $orderID;
            
           
        }
        else
        {
             $data['orderID'] = "";
            
        }    
       
        $this->load->view('internalOrderFrm',$data);
       
       
    }
    public function readInternalOrderFrmView($orderID=null)
    {
        date_default_timezone_set('America/Indianapolis');
        
        $orderDataArry                     = $this->getOrderFieldsFromOrderID($orderID);
        
        //var_dump($orderDataArry);
        //$customerID                      = $orderDataArry['kf_CustomerID'];
        
        $customerDataArry                  = Modules::run('customers/customercontroller/getCustomerFieldsFromCustomerID',$orderDataArry['kf_CustomerID']);
        //print_r($customerDataArry);
        $orderDataArry['custCompany']      = $customerDataArry['t_CustCompany'];
        
        //echo "<br/>".$orderDataArry['custCompany']."<br/>";
        $addressDataArry                   = Modules::run('addresses/addresscontroller/getAddressesDataFromAddressID',$orderDataArry['kf_ContactID']);
        
        $orderDataArry['contactNameFull']  = $addressDataArry['t_ContactNameFull'];
        
        if(!empty($orderDataArry['ti_ProofDue']) && !is_null($orderDataArry['ti_ProofDue']))
        {
            $orderDataArry['timeProofFormat']  = strftime('%I:%M %p', strtotime($orderDataArry['ti_ProofDue']));
            
        }
        else
        {
            $orderDataArry['timeProofFormat']  = null;
            
        }
        if(!empty($orderDataArry['ti_JobDue']) && !is_null($orderDataArry['ti_JobDue']))
        {
            $orderDataArry['timeJobDueFormat']  = strftime('%I:%M %p', strtotime($orderDataArry['ti_JobDue']));
            
        }
        else
        {
            $orderDataArry['timeJobDueFormat']  = null;
            
        }
        if(!empty($orderDataArry['ti_PrintJobDue']) && !is_null($orderDataArry['ti_PrintJobDue']))
        {
            $orderDataArry['timePrintFormat']  = strftime('%I:%M %p', strtotime($orderDataArry['ti_PrintJobDue']));
            
        }
        else
        {
            $orderDataArry['timePrintFormat']  = null;
            
        }
        //$orderDataArry['timeProofFormat']  = strftime('%I:%M %p', strtotime($orderDataArry['ti_ProofDue']));
        
        //$orderDataArry['timeJobDueFormat'] = strftime('%I:%M %p', strtotime($orderDataArry['ti_JobDue']));
        
        //$orderDataArry['timePrintFormat']  = strftime('%I:%M %p', strtotime($orderDataArry['ti_PrintJobDue']));
        
        //print_r($orderDataArry);
        
        echo json_encode($orderDataArry);
       
    }
    public function submitInternalOrderRequestFrm()
    {
        date_default_timezone_set('America/Indianapolis');
        
        $data['kf_CustomerID']               = $this->input->post('customerIDHidden');
        
        $data['kf_ContactID']                = $this->input->post('addressIDHidden');
        $data['t_TypeOfOrder']               = $this->input->post('orderType');
        
        $data['t_JobName']                   = $this->input->post('jobName');
        
        $data['t_ArtLocation']               = $this->input->post('artLocation');
        
        $data['t_CustomerPO']                = $this->input->post('customerPurChaseOrderNumber');
        
        $data['nb_CustomerPOToBeDetermined'] = $this->input->post('customerPOToBeDetermined');
        
        $data['nb_UseTotalOrderPricing']     = $this->input->post('totalOrderPricingCheckBox');
        $data['n_TotalOrderPrice']           = str_replace("$", '',$this->input->post('orderPricingInput'));
        
        $data['nb_IncompletePricing']        = $this->input->post('incompletePricingCheckBox');
        
        $data['kf_EmployeeIDSales']         = $this->input->post('orderEmployeeIDSalesHidden');
        
        $proofDueDate                        = $this->input->post('proofDueDate');
        
        if(!empty($proofDueDate))
        {
            $proofDueDateArry                = explode("/", $proofDueDate);
            $data['d_ProofDue']              = $proofDueDateArry[2]."-".$proofDueDateArry[0]."-".$proofDueDateArry[1];
        }
        else
        {
            $data['d_ProofDue']              = null;
            
        }    
        
       
        $ti_ProofDue                         = $this->input->post('proofDueTime');
        
        if(!empty($ti_ProofDue))
        {
            $data['ti_ProofDue']             = strftime('%H:%M:%S', strtotime($ti_ProofDue));//strtotime($ti_ProofDue, '%h:%i %p');
            
        }
        else
        {
            $data['ti_ProofDue']             = null;
            
        }    
        
        $data['d_Received']                  = date('Y-m-d');;
        $data['t_ServiceLevel']              = $this->input->post('serviceLevel');
        
        $jobDueDate                          = explode("/",$this->input->post('jobDueDate'));
        $data['d_JobDue']                    = $jobDueDate[2]."-".$jobDueDate[0]."-".$jobDueDate[1];
        
        
        $ti_JobDue                           = $this->input->post('jobDueTime');
        
        if(!empty($ti_JobDue))
        {
            $data['ti_JobDue']               = strftime('%H:%M:%S',strtotime($ti_JobDue));
            
        }
        else
        {
           $data['ti_JobDue']                = null;
            
        }    
        
        $onPressDate                         = explode("/",$this->input->post('onPressDate'));
        $data['d_PrintJobDue']               = $onPressDate[2]."-".$onPressDate[0]."-".$onPressDate[1];
        
        $ti_PrintJobDue                      = $this->input->post('onPressTime');
        
        if(!empty($ti_PrintJobDue))
        {
            $data['ti_PrintJobDue']          = strftime('%H:%M:%S',strtotime($ti_PrintJobDue));
            
        }
        else
        {
           $data['ti_PrintJobDue']           = null;
            
        }    
        
        
        $data['t_TypeOfJobTicket']           = "InternalOrderForm";
        $postOrderID                         = $this->input->post('newOrderIDHidden');
        
        if(!empty($postOrderID))
        {
            //print_r($data);
            $msg =  $this->updateOrderTbl($data,$postOrderID);
           
            $updateOperationDone = "updateDone";
            
            echo $updateOperationDone;
        }
        else
        {
            //insert operation
             $newOrderID        = $this->ordermodel->insertOrderData($data);
             echo $newOrderID;
            
        }    
        //print_r($data);
        //insert the updated Order as new Order and get the new OrderID
       
        
        
    } 
    public function loadSalesOrderRequestSteps($orderID=null)
    {
        $data = "";
        
        if(isset($orderID) && !is_null($orderID))
        {
            $data['orderID'] = $orderID;
            $rowArry         = $this->getOrderFieldsFromOrderID($orderID);
            
            if(!empty($rowArry))
            {
                $data['orderStepsComplete'] = $rowArry['nb_OrderStepsComplete'];
                $data['customerID']         = $rowArry['kf_CustomerID'];   
            }   
            //print_r($data);
        }
        else
        {
            $data['orderID'] = "";
        } 
        $this->load->view('salesOrderFrm',$data);
    } 
    public function submitDynamicModalSalesOrderRequest()
    {
         date_default_timezone_set('America/Indianapolis');
         
         print_r($_POST);
         
         
         
         
         
         $dynamicModalTypeOfSubmit   =  $this->input->post('dymanicModalTypeOfSubmitHidden');
         
         if($dynamicModalTypeOfSubmit == "1")
         {
             $addressID                   =  $this->input->post('dymanicModalAddressIDHidden');
             $data['t_ContactNameFull']   =  $this->input->post('addressTblContactName');
             $data['t_ContactTitle']      =  $this->input->post('addressTblContactTitle');
             $data['t_Email']             =  $this->input->post('addressTblContactEmail');
             //update to teh address table
             $uodateAddressInfo           = Modules::run('addresses/addresscontroller/updateAddressTbl',$data,$addressID);
         }
         
         echo $uodateAddressInfo;
        
    }
    public function stepsFinalFinish()
    {
        $orderID                          = $this->input->post('orderID');
        $rowArry['nb_OrderStepsComplete'] = $this->input->post('orderStepsComplete');
        
        $this->updateOrderTbl($rowArry, $orderID);
        
    }        

    
}

?>
