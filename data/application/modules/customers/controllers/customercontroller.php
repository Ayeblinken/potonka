<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CustomerController extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        $this->load->model('customermodel');

    }
    public function index()
    {
        echo "This is a customer HMVC model";

    }
    public function setupDefaultArtDirectory()
    {
        $data['t_Directoryname'] = $this->input->post('setUpartLocation');
        $customerID              = $this->input->post('dymanicModalAddressOtherIDHidden');

        $this->updateCustomerInfo($customerID, $data);

    }
    public function customerTermView($customerID,$orderID=null)
    {
        // check if the customer exists in the customer table
        $row = $this->customermodel->getCustomerDataByID($customerID);

        //print_r($row);
        if(!empty($row))
        {
           $qbTermsResult                            = Modules::run('quickBooks/quickbookcontroller/getQuickBooksTermsData',$row['t_QBCustID']);

           $qbCustTermsArry                          = Modules::run('quickBooks/quickbookcontroller/modifyQuickBookTermsData',$qbTermsResult,$row['t_QBCustID']);

           $qbCustTermsArry['donePullingQBCusTerms'] = "yes";

           $qbCustTermsArry['customerID']            = $row['kp_CustomerID'];


           $custAcctLockedOrNotArry = $this->getCustAcctLockedOrNot($row['t_QBCustID']);

           if($custAcctLockedOrNotArry['acctLock'] == "accountLocked")
           {
               $dataToUpdate['nb_AccountLocked']= "1";

           }
           else
           {
               $dataToUpdate['nb_AccountLocked']= null;
           }
           if($custAcctLockedOrNotArry['nb_CreditHold'] == "1" && $custAcctLockedOrNotArry['nb_Overlimit'] =="1" && $custAcctLockedOrNotArry['acctLock'] == "accountLocked")
           {
               //$dataToUpdate['t_CreditHoldReason']       = "Past Due Account Locked";
               $dataToUpdate['t_CreditHoldReason']= "Account Locked Pastdue and Overlimit";
           }
           else if($custAcctLockedOrNotArry['nb_CreditHold'] == "1" && $custAcctLockedOrNotArry['nb_Overlimit'] !="1" && $custAcctLockedOrNotArry['acctLock'] == "accountLocked")
           {
               $dataToUpdate['t_CreditHoldReason']= "Account Locked Pastdue";
           }
           else if($custAcctLockedOrNotArry['nb_CreditHold'] == "1" && $custAcctLockedOrNotArry['nb_Overlimit'] !="1" && $custAcctLockedOrNotArry['acctLock'] != "accountLocked")
           {
               $dataToUpdate['t_CreditHoldReason']= "Pastdue";
           }
           else if($custAcctLockedOrNotArry['nb_CreditHold'] != "1" && $custAcctLockedOrNotArry['nb_Overlimit'] =="1" && $custAcctLockedOrNotArry['acctLock'] != "accountLocked")
           {
               $dataToUpdate['t_CreditHoldReason']= "Overlimit";
           }
           else if($custAcctLockedOrNotArry['nb_CreditHold'] == "1" && $custAcctLockedOrNotArry['nb_Overlimit'] =="1" && $custAcctLockedOrNotArry['acctLock'] != "accountLocked")
           {
               $dataToUpdate['t_CreditHoldReason']= "Pastdue and Overlimit";
           }
           else if($custAcctLockedOrNotArry['nb_CreditHold'] != "1" && $custAcctLockedOrNotArry['nb_Overlimit'] !="1" && $custAcctLockedOrNotArry['acctLock'] != "accountLocked")
           {
               $dataToUpdate['t_CreditHoldReason']= "";
           }
           //$this->updateCustomerInfo($row['kp_CustomerID'], array('t_CreditHoldReason' =>"Past Due Account Locked"));
           //If the Run CC Upfront is checked:
           //Then the order is on Credit Hold and the Reason is: Run Credit Card Up Front
           // update - we are not setting nb_CreditHold  when nb_CreditCardHold is 1
           if($row['nb_CreditCardHold'] == "1")
           {
               // do the update operation
               $customerID                          = $row['kp_CustomerID'];
               //$dataToUpdate['nb_CreditHold']       = 1;
               $dataToUpdate['t_CreditHoldReason']  = "Run CC Up Front";

               //$this->updateCustomerInfo($customerID, $dataToUpdate);

           }

           $updateCustomerDataMsg = $this->updateCustomerInfo($customerID, $dataToUpdate);

           if($orderID !== null)
           {
               //echo " we are inside ";
               $updateOrderDataMsg = $this->updateOrderFieldsFromCustomerID($orderID,$customerID);

           }

           $this->load->view('customerTermView',$qbCustTermsArry);

        }
        else
        {
            $data['error'] = "No Customer Found";
            $this->load->view('customerErrorView',$data);

        }
    }
    public function updateOrderFieldsFromCustomerID($orderID,$customerID)
    {
        $orderDataArry                                  = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$orderID);

        $updateOrderDataMsg                             = "";

        if($orderDataArry)
        {
            $custDataArry                               = $this->getCustomerFieldsFromCustomerID($customerID);
            //var_dump($custDataArry);
            $orderDataArry['nb_CreditCardHoldTimeOrder']= $custDataArry['nb_CreditCardHold'];
            $orderDataArry['nb_CreditHoldTimeOrder']    = $custDataArry['nb_CreditHold'];
            $orderDataArry['nb_OverlimitTimeOrder']     = $custDataArry['nb_Overlimit'];
            $orderDataArry['nb_AccountLockedTimeOrder'] = $custDataArry['nb_AccountLocked'];
            $orderDataArry['t_CreditHoldType']          = $custDataArry['t_CreditHoldReason'];

            $updateOrderDataMsg                         = Modules::run('orders/ordercontroller/updateOrderTbl',$orderDataArry,$orderID);
            //echo $updateOrderDataMsg."<br/>";
        }

        return $updateOrderDataMsg;

    }
    public function getCustAcctLockedOrNot($qb_CustID)
    {
         $result = $this->customermodel->getCustAcctInfo($qb_CustID);

         if($result)
         {
             return $result;

         }
    }
    public function getCustomerFromOrderID($orderID)
    {
        echo json_encode($this->customermodel->getCustomerID($orderID));

    }
    public function getCustomerFieldsFromCustomerID($customerID)
    {
        $row = $this->customermodel->getCustomerDataByID($customerID);

        return $row;
    }
    public function getCustomerDataFromQuickBookCustID($qb_CustID)
    {
        $row = $this->customermodel->getCustomerDataFromQuickBookCustID($qb_CustID);

        return $row;
    }
    public function updateCustomerInfo($customerID,$data)
    {
        $this->customermodel->updateCustomerTable($customerID,$data);
    }
    public function updateCustomerFromQuickBookCusID($qb_CustID,$data)
    {

        $updateStatus = $this->customermodel->updateCustomerTableFromQuickBookCustID($qb_CustID,$data);

        return $updateStatus;
    }
    public function getCustomerDataInJsonFormat($customerID)
    {
        $customerDataArry = $this->getCustomerFieldsFromCustomerID($customerID);

        echo json_encode($customerDataArry);

    }
    public function getQBCustomerData($customerID)
    {
        $customerDataArry = $this->getCustomerFieldsFromCustomerID($customerID);

        $qbListID         = $customerDataArry['t_QBCustID'];

        $qbCustDataArry   = Modules::run('quickBooks/quickbookcontroller/getQuickBooksCustomerTableInfo',$qbListID);

        return $qbCustDataArry;


    }
    public function getQBCustomerDataInJsonFormat($customerID)
    {
        $qbCustDataArry   = $this->getQBCustomerData($customerID);

        echo json_encode($qbCustDataArry);


    }
    public function submitCustomerTermsData()
    {
        date_default_timezone_set('America/Indianapolis');
        //-------Basetables 2 customer table Data --------------------//
        $cusID                             = $this->input->post('customerNumberIDHidden');
        $n_DownPaymentReqOver              = str_replace("$","",$this->input->post('customerDownPaymentReqOver'));
        $cusData['n_DownPaymentReqOver']   = str_replace(",","",$n_DownPaymentReqOver);

        $cusData['n_PerAllowedOverCredit'] = $this->input->post('customerPerAllowedOverCredit');
        $cusData['n_PastDueNoOrders']      = $this->input->post('customerPastDueNoOrders');
        $cusData['zModified']              = date('Y-m-d H:i:s');
        $cusData['nb_CreditCardHold']      = $this->input->post('customerRunCCUpFront');

        $this->updateCustomerInfo($cusID,$cusData);

        //-------Quick Books customer table Data --------------------//
        $qb_CustID                         = $this->input->post('customerQBCustIDHidden');

        $qbData['TermsRef_FullName']       = $this->input->post('qbTermsRef_FullName');
        $creditLimit                       = str_replace("$","",$this->input->post('qbCreditLimit'));
        $qbData['CreditLimit']             = str_replace(",","",$creditLimit);
        $qbData['Status']                  = "update";

        $qbCustomerDataUpdate             = Modules::run('quickBooks/quickbookcontroller/updateQuickBooksCustomerTableInfo',$qb_CustID,$qbData);

        echo json_encode("done");

    }
    public function getCustomerIDCompanyQBCityQBState()
    {

        $getCustomerIDCompanyQBCityQBStateArry = $this->customermodel->getCustomerIDCompanyQBCityQBStateData();

        return $getCustomerIDCompanyQBCityQBStateArry;
    }

    public function getCustomerIDCompanyQBCityQBStateDataTableInfo()
    {
        $customerDataTable = $this->customermodel->getCustomerIDCompanyQBCityQBStateDataTable();

        echo $customerDataTable;

    }
    public function getCustomerIDCompanyQBCityQBStateMagicSuggest()
    {

        $result = $this->customermodel->getCustomerIDCompanyQBCityQBStateData();



        $searchArry = array();

        foreach($result as $key=>$value)
        {
            $searchArry[$key]['customerID']             = $value['kp_CustomerID'];
            $searchArry[$key]['companyName']            = $value['t_CustCompany'];
            $searchArry[$key]['cityName']               = $value['t_QBCity'];
            $searchArry[$key]['stateName']              = $value['t_QBState'];
            $searchArry[$key]['companyCityStateInfo']   = $value['t_CustCompany']." ".$value['t_QBCity']." ".$value['t_QBState'];
//            $searchArry[$key]['tokens'][0] = $value['t_CustCompany'];
//            $searchArry[$key]['tokens'][1]  = $value['t_QBCity'];
//            $searchArry[$key]['tokens'][2]  = $value['t_QBCity'];

        }

        return $searchArry;

        //var_dump($searchArry);
    }
    public function getCustomerIDCompanyQBCityQBStateInJsonFormat()
    {
        if(isset($_POST['init']))
        {
            echo json_encode($this->getCustomerIDCompanyQBCityQBStateByName($_POST['query']));
            //echo json_encode($this->getColorsByName($_POST['query']));
            //echo json_encode($this->getColorsbyId($_POST['cols']));
        }
        else
        {
            //echo json_encode($this->getColorsByName($_POST['query']));
        }


    }
    public function getCustomerIDCompanyQBCityQBStateByName($query)
    {
        //global $colors;
        $custCompanyInfo = $this->getCustomerIDCompanyQBCityQBStateMagicSuggest();

        if(empty($query))
        {
            return $custCompanyInfo;

        }

        $matches = array();

        for($i = 0; $i < count($custCompanyInfo); $i++)
        {
            if(strpos(strtolower($custCompanyInfo[$i]['companyCityStateInfo']), strtolower($query)) > -1)
            {
                $matches[] = $custCompanyInfo[$i];
            }
        }
        //var_dump($matches);
        return $matches;
    }
    public function getExampleData()
    {
         $colors = array(
            array('id' => 0, 'companyName' => 'red','companyCityStateInfo' =>'hello world'),
            array('id' => 1, 'companyName' => 'blue','companyCityStateInfo' =>'supre man'),
            array('id' => 2, 'companyName' => 'green','companyCityStateInfo' =>'spider man')
        );
        //var_dump($colors);
        return $colors;

    }
    /**
    * Retrieve colors according to given name query
    * $query - string : user query
    */

    public function getColorsByName($query)
    {
        //global $colors;
        $colors= $this->getExampleData();

        if(empty($query))
        {
            //echo "query: ";
            return $colors;
        }
        $matches = array();
        for($i = 0; $i<count($colors);$i++)
        {
            if(strpos($colors[$i]['companyCityStateInfo'], $query) > -1)
            {
                $matches[] = $colors[$i];
            }
        }
        return $matches;
    }

    /**
    * Retrieve colors according to given ids
    * $arrIds - array : array of color ids.
    */
    public function getColorsbyId($arrIds)
    {
        //global $colors;
        $colors= $this->getExampleData();
        $matches = array();
        for($i = 0; $i < count($colors); $i++)
        {
            if(array_search($colors[$i]['id'], $arrIds) !== false)
            {
                $matches[] = $colors[$i];
            }
        }
        return $matches;
    }


    public function saveCustomerIDCompanyQBCityQBStateInJsonFormat()
    {
        $result = $this->getCustomerIDCompanyQBCityQBState();

        $searchArry = array();


        //echo "<br/>".sizeof($result)."<br/>";
        //var_dump($result);
        //$f      = fopen(realpath(APPPATH . '../../images/Orders').'/customerInfo.json', 'w') or die("can't open file");
        foreach($result as $key=>$value)
        {
            $searchArry[$key]['customerID'] = $value['kp_CustomerID'];
            $searchArry[$key]['value']      = $value['t_CustCompany']." ".$value['t_QBCity']." ".$value['t_QBState'];
            $searchArry[$key]['tokens'][0] = $value['t_CustCompany'];
            $searchArry[$key]['tokens'][1]  = $value['t_QBCity'];
            $searchArry[$key]['tokens'][2]  = $value['t_QBCity'];

        }
//        for($x=0;$x<sizeof($result);$x++)
//        {
//            $searchArry[$x]['customerID'] = $result[$x]['kp_CustomerID'];
//            $searchArry[$x]['value']      = $result[$x]['t_CustCompany']." ".$result[$x]['t_QBCity']." ".$result[$x]['t_QBState'];
//            $searchArry[$x]['tokens'][0]  = $result[$x]['t_CustCompany'];
//            $searchArry[$x]['tokens'][1]  = $result[$x]['t_QBCity'];
//            $searchArry[$x]['tokens'][2]  = $result[$x]['t_QBCity'];
//            //echo sizeof()
////            for($y=0;$y<3;$y++)
////            {
////
////
////            }
//
//        }
       $fp      = fopen(realpath(APPPATH .'../../images/CustomerJsonData').'/customerInfo.json', 'w') or die("can't open file");

       //$fp = fopen('customerInfo.json', 'w');
       fwrite($fp, json_encode($searchArry));
       fclose($fp);
        //echo json_encode($searchArry);
//        foreach($result as $key=>$value)
//        {
//            for($x=0;$x>sizeof($value);$x++)
//            {
//                $value[];
//            }
//        }
        //var_dump($result);

        //echo json_encode($result);
    }

}
?>
