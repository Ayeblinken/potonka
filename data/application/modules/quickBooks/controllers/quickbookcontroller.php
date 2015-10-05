<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class QuickBookController extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        $this->load->model('quickbookmodel');
    }
    public function getQuickBooksCustomerTableInfo($qb_CustID)
    {
        $result                          = $this->quickbookmodel->getQuickBooksCustomerTableData($qb_CustID);

        return $result;

    }
    public function insertQuickBooksItemNonInventoryTbl($data)
    {
        $result =$this->quickbookmodel->insertItemNonInventoryData($data);

        return $result;
    }
    public function insertSalesOrPurchaseDetailTbl($data)
    {
        $result =$this->quickbookmodel->insertSalesOrPurchaseDetailData($data);

        return $result;
    }
    public function updateQuickBooksCustomerTableInfo($qb_CustID,$data)
    {
        $this->quickbookmodel->updateQuickBooksCustomerTableData($qb_CustID,$data);
    }
    public function getQuickBooksCustomerTableDataInJsonFormat($qb_CustID)
    {
        $result                          = $this->getQuickBooksCustomerTableInfo($qb_CustID);

        echo json_encode($result);

    }
    public function getQBInvoicePaid($qb_CustID)
    {
        $result = $this->quickbookmodel->getQBInvoicePaidData($qb_CustID);

        return $result;

    }
    public function getQuickBooksTermsData($qb_CustID)
    {
        $qbIsPaidArry                    = $this-> getQBInvoicePaid($qb_CustID);
        //print_r($qbIsPaidArry);
        if(empty($qbIsPaidArry))
        {
           $result                       = $this->quickbookmodel->quickBookTermIsPaid($qb_CustID);
           //print_r($result);
        }
        else
        {
          $result                        = $this->quickbookmodel->quickBookTerm($qb_CustID);
        }

        return $result;

    }
    public function showQBTermsData($qb_CustID)
    {
        $result                          = $this->getQuickBooksTermsData($qb_CustID);

        $quickBooksTermDataArry          = $this->modifyQuickBookTermsData($result,$qb_CustID);

        var_dump($quickBooksTermDataArry);

    }
    public function modifyQuickBookTermsData($result,$qb_CustID)
    {
        $vData['current']                = $result[0]['Current'];

        $vData['balance_1_15']           = $result[0]['1-15'];
        $vData['balance_16_30']          = $result[0]['16-30'];
        $vData['balance_31_45']          = $result[0]['31-45'];
        $vData['balance_46_60']          = $result[0]['46-60'];

        $vData['balance_1_30']           = $result[0]['1-30'];
	$vData['balance_31_60']          = $result[0]['31-60'];
	$vData['balance_61_90']          = $result[0]['61-90'];
	$vData['balance_greater_90']     = $result[0]['Greater90'];

        $vData['TotalUnusedPayment']     = $result[0]['TotalUnusedPayment'] ;
        $vData['CreditRemaining']        = $result[0]['CreditRemaining'];
        $vData['WorkInHouseTotalAmount'] = $result[0]['WorkInHouseTotalAmount'];

        $vData['totalInvoicesPastDue']   = ($vData['balance_1_30'] +$vData['balance_31_60'] +$vData['balance_61_90'] +$vData['balance_greater_90']);



        $vData['grandTotalInvoiced']     = ($vData['current']+$vData['totalInvoicesPastDue']);

        $vData['Credit Limit']           = $result[0]['Credit Limit'];



        $vData['current']                = $result[0]['Current'];

        $vData['totalOnAccount']         = ($vData['grandTotalInvoiced']-$vData['TotalUnusedPayment']-$vData['CreditRemaining']+$vData['WorkInHouseTotalAmount']);

        //-----------------start: set the credit availability in the basetables2.Customers table ---------------//

        // have changed the data type of credit available from int(100) to DECIMAL(11,2)

        //Need to calculate from the formulae. Credit Available = creditLimit - TotalOnAccount
	$vData['creditAvailable']        = (($vData['Credit Limit']) - ( $vData['totalOnAccount'] ) );


        $data                            = array(
                                                  'n_CreditAvailable' => $vData['creditAvailable']
                                            );

        $updateCreditAvailable = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);

        if(!is_numeric($updateCreditAvailable))
        {
            echo $updateCreditAvailable;
        }
        $customerDataArr       = Modules::run('customers/customercontroller/getCustomerDataFromQuickBookCustID',$qb_CustID);
        //var_dump($vData);
        //$pastDueNoOrder	       = $customerDataArr['n_PastDueNoOrders'];
        $overLimitCondition    = ($customerDataArr['n_CustCreditLimit']*$customerDataArr['n_PerAllowedOverCredit'])+$vData['creditAvailable'];

        if($vData['totalInvoicesPastDue'] >0)
        {
            //echo "<br/>we are here inside<br/>";
            $dataForUpdate['nb_CreditHold'] = "1";
            //echo "<br/>we are here inside<br/>";
        }
        else
        {
            $dataForUpdate['nb_CreditHold'] =null;


        }
        if($overLimitCondition <=0)
        {
           $dataForUpdate['nb_Overlimit'] = "1";

        }
        else
        {
             $dataForUpdate['nb_Overlimit'] = null;
        }

        $updateCreditHoldReason   = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$dataForUpdate);
        echo $updateCreditHoldReason;
        var_dump($dataForUpdate);
        return $vData;
    }
    public function modifyQuickBookTermsData_old($result,$qb_CustID)
    {
        $vData['current']                = $result[0]['Current'];

        $vData['balance_1_30']           = $result[0]['1-30'];
	$vData['balance_31_60']          = $result[0]['31-60'];
	$vData['balance_61_90']          = $result[0]['61-90'];
	$vData['balance_greater_90']     = $result[0]['Greater90'];

        $vData['TotalUnusedPayment']     = $result[0]['TotalUnusedPayment'] ;
        $vData['CreditRemaining']        = $result[0]['CreditRemaining'];
        $vData['WorkInHouseTotalAmount'] = $result[0]['WorkInHouseTotalAmount'];

        $vData['totalInvoicesPastDue']   = ($vData['balance_1_30'] +$vData['balance_31_60'] +$vData['balance_61_90'] +$vData['balance_greater_90']);



        $vData['grandTotalInvoiced']     = ($vData['current']+$vData['totalInvoicesPastDue']);

        $vData['Credit Limit']           = $result[0]['Credit Limit'];


        //$balance_1_30                    = $result[0]['1-30'];
	//$balance_31_60                   = $result[0]['31-60'];
	//$balance_61_90                   = $result[0]['61-90'];
	//$balance_greater_90              = $result[0]['Greater90'];


        $vData['current']                = $result[0]['Current'];

        $vData['totalOnAccount']         = ($vData['grandTotalInvoiced']-$vData['TotalUnusedPayment']-$vData['CreditRemaining']+$vData['WorkInHouseTotalAmount']);

        //-----------------start: set the credit availability in the basetables2.Customers table ---------------//

        // have changed the data type of credit available from int(100) to DECIMAL(11,2)

        //Need to calculate from the formulae. Credit Available = creditLimit - TotalOnAccount
	$vData['creditAvailable']        = (($vData['Credit Limit']) - ( $vData['totalOnAccount'] ) );
        //echo "<br/>".$creditAvailable." <br/>";

        $data                            = array(
                                                  'n_CreditAvailable' => $vData['creditAvailable']
                                            );
        //echo "<br/>".$qb_CustID."<br/>";
        //print_r($data);
        $updateCreditAvailable = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);

        if(!is_numeric($updateCreditAvailable))
        {
            echo $updateCreditAvailable;
        }

        //-----------------End: set the credit availability in the basetables2.Customers table.----------------//

        //----------- start: set credit Hold--------------
            /*
                    past due no orders values (

                    30

                    60

                    90

                    greater than 90 (i.e, the field can contain any value grater than 90, example 120 or 240)
            */
        $customerDataArr       = Modules::run('customers/customercontroller/getCustomerDataFromQuickBookCustID',$qb_CustID);

        $pastDueNoOrder	       = $customerDataArr['n_PastDueNoOrders'];


        if($pastDueNoOrder >=1 && $pastDueNoOrder<=30)
        {

            // check to see if there is any balance in ($balance_31_60) and   ($balance_61_90) ($balance_greater_90)
            if ($vData['balance_31_60'] > 0 || $vData['balance_61_90'] > 0 || $vData['balance_greater_90'] > 0 )
            {

                //if there is a balance set credit hold to 1
                $data                = array(
                                                'nb_CreditHold' => "1"
                                             );
                $updateCreditHold    = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
            }
            //  very rare condition.....no past due and credit available is 0
            if($vData['creditAvailable'] <=0)
            {
                //set the credit hold to 1
                $data                = array(
                                                'nb_CreditHold' => "1"
                                        );
                $updateCreditHold    = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);

                if($vData['totalInvoicesPastDue'] > 0)
                {
                    //record the reason for credit hold as Overlimit and passdue
                    $data            = array(
                                                't_CreditHoldReason' => "Overlimit and Passdue"
                                        );
                     $updateCreditHoldReason   = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
                }
                else
                {
                    //record the reason for credit hold as Overlimit only
                    $data            = array(
                                                't_CreditHoldReason' => "Overlimit"
                                        );
                    $updateCreditHoldReason   = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
                }
             }
             else
             {
                $data                = array(
                                                'nb_CreditHold'      => "0",
                                                't_CreditHoldReason' => ""
                                        );
                $updateC_HoldReason  = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
             }
         }
         else if($pastDueNoOrder >=31 && $pastDueNoOrder<=60)
         {
             // check to see if there is any balance in ($balance_61_90) ($balance_greater_90)
             if ($vData['balance_61_90'] >0 || $vData['balance_greater_90'] >0)
             {
                 //if there is a balance set credit hold to 1
                 $data                = array(
                                                'nb_CreditHold' => "1"
                                             );
                 $updateCreditHold    = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
             }
             ///  very rare condition.....no past due and credit available is 0
             if($vData['creditAvailable'] <=0)
             {
                 //set the credit hold to 1
                 $data                = array(
                                                'nb_CreditHold' => "1"
                                        );
                 $updateCreditHold    = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);

                 if($vData['totalInvoicesPastDue'] > 0)
                 {
                     //record the reason for credit hold as Overlimit and passdue
                     $data            = array(
                                                't_CreditHoldReason' => "Overlimit and Passdue"
                                        );
                     $updateCreditHoldReason   = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
                 }
                 else
                 {
                     //record the reason for credit hold as Overlimit only
                     $data            = array(
                                                't_CreditHoldReason' => "Overlimit"
                                        );
                     $updateCreditHoldReason   = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
                 }

             }
             else
             {
                 $data                = array(
                                                'nb_CreditHold'      => "0",
                                                't_CreditHoldReason' => ""
                                        );
                 $updateC_HoldReason  = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);

             }
         }
         else if($pastDueNoOrder >=61 && $pastDueNoOrder<=90)
         {
             // check to see if there is any balance in ($balance_greater_90)
             if ($vData['balance_greater_90'] >0)
             {
                 //if there is a balance set credit hold to 1
                 $data                = array(
                                                'nb_CreditHold' => "1"
                                             );
                 $updateCreditHold    = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
             }
             ///  very rare condition.....no past due and credit available is 0
             if($vData['creditAvailable'] <=0)
             {
                 //if there is a balance set credit hold to 1
                 $data                = array(
                                                'nb_CreditHold' => "1"
                                             );
                 $updateCreditHold    = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);

                 if($vData['totalInvoicesPastDue'] > 0)
                 {
                      //record the reason for credit hold as Overlimit and passdue
                      $data                     = array(
                                                            't_CreditHoldReason' => "Overlimit and Passdue"
                                                        );
                      $updateCreditHoldReason   = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
                 }
                 else
                 {
                     //record the reason for credit hold as Overlimit only
                     $data            = array(
                                                't_CreditHoldReason' => "Overlimit"
                                        );
                     $updateCreditHoldReason   = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);

                 }
             }
             else
             {
                 $data                = array(
                                                'nb_CreditHold'      => "0",
                                                't_CreditHoldReason' => ""
                                        );
                 $updateC_HoldReason  = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);

             }
         }
         if($vData['creditAvailable']  <=0)
         {
            // if the credit Available is less than zero set credit hold to one
            $data                        = array(
                                                    'nb_CreditHold' => "1"
                                                );
            $updateCreditHold            = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);

            if($vData['totalInvoicesPastDue']> 0)
            {
                //record the reason for credit hold as Overlimit and passdue
                $data                    = array(
                                                    't_CreditHoldReason' => "Overlimit and Passdue"
                                                );
                $updateCreditHoldReason  = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);

            }
            else
            {
                //record the reason for credit hold as Overlimit only
                $data                    = array(
                                                    't_CreditHoldReason' => "Overlimit"
                                                );
                $updateCreditHoldReason  = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);

            }



         }
         else if($vData['creditAvailable'] >0)
         {
            //  Total InvoicePastdue > 0 || TotalonAccount > 0
            // set credit hold to 1
            // else set credit hold to 0
              if($vData['totalInvoicesPastDue']>0)
              {
                  //record the reason for credit hold as PassDue only
                  $data                    = array(
                                                    'nb_CreditHold' => "1",
                                                    't_CreditHoldReason' => "Passdue"
                                             );
                  $updateCreditHoldReason  = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
              }
              else
              {
                  $data                     = array(
                                                    'nb_CreditHold' => "0",
                                                    't_CreditHoldReason' => ""
                                             );
                  $updateCreditHoldReason   = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
              }
         }
         return $vData;

    }

    public function getQuickBookTermsView($qb_CustID=null)
    {
        //$qb_CustID            = "2560000-1107544760" ;  //TGI
        //$qb_CustID                       = "40E0000-1141667074"; //sport Graphics
        $result = $this->getQuickBooksTermsData($qb_CustID);

        $vData  = $this->modifyQuickBookTermsData($result,$qb_CustID);

//        $result                          = $this->quickbookmodel->quickBookTerm($qb_CustID);
//
//
//        $vData['current']                = $result[0]['Current'];
//
//        $vData['balance_1_30']           = $result[0]['1-30'];
//	$vData['balance_31_60']          = $result[0]['31-60'];
//	$vData['balance_61_90']          = $result[0]['61-90'];
//	$vData['balance_greater_90']     = $result[0]['Greater90'];
//
//        $vData['TotalUnusedPayment']     = $result[0]['TotalUnusedPayment'] ;
//        $vData['CreditRemaining']        = $result[0]['CreditRemaining'];
//        $vData['WorkInHouseTotalAmount'] = $result[0]['WorkInHouseTotalAmount'];
//
//        $vData['totalInvoicesPastDue']   = ($vData['balance_1_30'] +$vData['balance_31_60'] +$vData['balance_61_90'] +$vData['balance_greater_90']);
//
//
//
//        $vData['grandTotalInvoiced']     = ($vData['current']+$vData['totalInvoicesPastDue']);
//
//        $vData['Credit Limit']           = $result[0]['Credit Limit'];
//
//
//        //$balance_1_30                    = $result[0]['1-30'];
//	//$balance_31_60                   = $result[0]['31-60'];
//	//$balance_61_90                   = $result[0]['61-90'];
//	//$balance_greater_90              = $result[0]['Greater90'];
//
//
//        $vData['current']                = $result[0]['Current'];
//
//        $vData['totalOnAccount']         = ($vData['grandTotalInvoiced']-$vData['TotalUnusedPayment']-$vData['CreditRemaining']+$vData['WorkInHouseTotalAmount']);
//
//        //-----------------start: set the credit availability in the basetables2.Customers table ---------------//
//
//        // have changed the data type of credit available from int(100) to DECIMAL(11,2)
//
//        //Need to calculate from the formulae. Credit Available = creditLimit - TotalOnAccount
//	$vData['creditAvailable']        = (($vData['Credit Limit']) - ( $vData['totalOnAccount'] ) );
//        //echo "<br/>".$creditAvailable." <br/>";
//
//        $data                            = array(
//                                                  'n_CreditAvailable' => $vData['creditAvailable']
//                                            );
//        //echo "<br/>".$qb_CustID."<br/>";
//        //print_r($data);
//        $updateCreditAvailable = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
//
//        if(!is_numeric($updateCreditAvailable))
//        {
//            echo $updateCreditAvailable;
//        }
//
//        //-----------------End: set the credit availability in the basetables2.Customers table.----------------//
//
//        //----------- start: set credit Hold--------------
//            /*
//                    past due no orders values (
//
//                    30
//
//                    60
//
//                    90
//
//                    greater than 90 (i.e, the field can contain any value grater than 90, example 120 or 240)
//            */
//        $customerDataArr       = Modules::run('customers/customercontroller/getCustomerDataFromQuickBookCustID',$qb_CustID);
//
//        $pastDueNoOrder	       = $customerDataArr['n_PastDueNoOrders'];
//
//        if($vData['creditAvailable']  <=0)
//        {
//            // if the credit Available is less than zero set credit hold to one
//            $data                        = array(
//                                                    'nb_CreditHold' => "1"
//                                                );
//            $updateCreditHold            = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
//
//            if($vData['totalInvoicesPastDue']> 0)
//            {
//                //record the reason for credit hold as Overlimit and passdue
//                $data                    = array(
//                                                    't_CreditHoldReason' => "Overlimit and Passdue"
//                                                );
//                $updateCreditHoldReason  = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
//
//            }
//            else
//            {
//                //record the reason for credit hold as Overlimit only
//                $data                    = array(
//                                                    't_CreditHoldReason' => "Overlimit"
//                                                );
//                $updateCreditHoldReason  = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
//
//            }
//
//
//
//        }
//        if($vData['creditAvailable']  >0)
//        {
//            //  Total InvoicePastdue > 0 || TotalonAccount > 0
//            // set credit hold to 1
//            // else set credit hold to 0
//              if($vData['totalInvoicesPastDue']>0)
//              {
//                  //record the reason for credit hold as PassDue only
//                  $data                    = array(
//                                                    'nb_CreditHold' => "1",
//                                                    't_CreditHoldReason' => "Passdue"
//                                             );
//                  $updateCreditHoldReason  = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
//              }
//              else
//              {
//                  $data                     = array(
//                                                    'nb_CreditHold' => "0",
//                                                    't_CreditHoldReason' => ""
//                                             );
//                  $updateCreditHoldReason   = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
//              }
//        }
//        if($pastDueNoOrder >=1 && $pastDueNoOrder<=30)
//        {
//
//            // check to see if there is any balance in ($balance_31_60) and   ($balance_61_90) ($balance_greater_90)
//            if ($vData['balance_31_60'] > 0 || $vData['balance_61_90'] > 0 || $vData['balance_greater_90'] > 0 )
//            {
//
//                //if there is a balance set credit hold to 1
//                $data                = array(
//                                                'nb_CreditHold' => "1"
//                                             );
//                $updateCreditHold    = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
//            }
//            //  very rare condition.....no past due and credit available is 0
//            if($vData['creditAvailable'] <=0)
//            {
//                //set the credit hold to 1
//                $data                = array(
//                                                'nb_CreditHold' => "1"
//                                        );
//                $updateCreditHold    = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
//
//                if($vData['totalInvoicesPastDue'] > 0)
//                {
//                    //record the reason for credit hold as Overlimit and passdue
//                    $data            = array(
//                                                't_CreditHoldReason' => "Overlimit and Passdue"
//                                        );
//                     $updateCreditHoldReason   = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
//                }
//                else
//                {
//                    //record the reason for credit hold as Overlimit only
//                    $data            = array(
//                                                't_CreditHoldReason' => "Overlimit"
//                                        );
//                    $updateCreditHoldReason   = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
//                }
//             }
//             else
//             {
//                $data                = array(
//                                                'nb_CreditHold'      => "0",
//                                                't_CreditHoldReason' => ""
//                                        );
//                $updateC_HoldReason  = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
//             }
//         }
//         else if($pastDueNoOrder >=31 && $pastDueNoOrder<=60)
//         {
//             // check to see if there is any balance in ($balance_61_90) ($balance_greater_90)
//             if ($vData['balance_61_90'] >0 || $vData['balance_greater_90'] >0)
//             {
//                 //if there is a balance set credit hold to 1
//                 $data                = array(
//                                                'nb_CreditHold' => "1"
//                                             );
//                 $updateCreditHold    = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
//             }
//             ///  very rare condition.....no past due and credit available is 0
//             if($vData['creditAvailable'] <=0)
//             {
//                 //set the credit hold to 1
//                 $data                = array(
//                                                'nb_CreditHold' => "1"
//                                        );
//                 $updateCreditHold    = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
//
//                 if($vData['totalInvoicesPastDue'] > 0)
//                 {
//                     //record the reason for credit hold as Overlimit and passdue
//                     $data            = array(
//                                                't_CreditHoldReason' => "Overlimit and Passdue"
//                                        );
//                     $updateCreditHoldReason   = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
//                 }
//                 else
//                 {
//                     //record the reason for credit hold as Overlimit only
//                     $data            = array(
//                                                't_CreditHoldReason' => "Overlimit"
//                                        );
//                     $updateCreditHoldReason   = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
//                 }
//
//             }
//             else
//             {
//                 $data                = array(
//                                                'nb_CreditHold'      => "0",
//                                                't_CreditHoldReason' => ""
//                                        );
//                 $updateC_HoldReason  = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
//
//             }
//         }
//         else if($pastDueNoOrder >=61 && $pastDueNoOrder<=90)
//         {
//             // check to see if there is any balance in ($balance_greater_90)
//             if ($vData['balance_greater_90'] >0)
//             {
//                 //if there is a balance set credit hold to 1
//                 $data                = array(
//                                                'nb_CreditHold' => "1"
//                                             );
//                 $updateCreditHold    = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
//             }
//             ///  very rare condition.....no past due and credit available is 0
//             if($vData['creditAvailable'] <=0)
//             {
//                 //if there is a balance set credit hold to 1
//                 $data                = array(
//                                                'nb_CreditHold' => "1"
//                                             );
//                 $updateCreditHold    = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
//
//                 if($vData['totalInvoicesPastDue'] > 0)
//                 {
//                      //record the reason for credit hold as Overlimit and passdue
//                      $data                     = array(
//                                                            't_CreditHoldReason' => "Overlimit and Passdue"
//                                                        );
//                      $updateCreditHoldReason   = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
//                 }
//                 else
//                 {
//                     //record the reason for credit hold as Overlimit only
//                     $data            = array(
//                                                't_CreditHoldReason' => "Overlimit"
//                                        );
//                     $updateCreditHoldReason   = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
//
//                 }
//             }
//             else
//             {
//                 $data                = array(
//                                                'nb_CreditHold'      => "0",
//                                                't_CreditHoldReason' => ""
//                                        );
//                 $updateC_HoldReason  = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);
//
//             }
//         }
        //var_dump($result);
        //----------- End: set credit Hold--------------
         $this->load->view('qbTermsView',$vData);
    }

}

?>
