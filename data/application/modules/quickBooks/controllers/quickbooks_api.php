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

class QuickBooks_Api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        date_default_timezone_set('America/Indianapolis');
        $this->load->model('quickbookmodel');

    }

    public function qbTermsData_get() {
      if(!$this->get('id')) {
        $this->response(NULL, 400);
      }

      $qb_CustID = $this->get('id');

        $qbIsPaidArry                    = $this->quickbookmodel->getQBInvoicePaidData($qb_CustID);

        if(empty($qbIsPaidArry)) {
           $result                       = $this->quickbookmodel->quickBookTermIsPaid($qb_CustID);
        } else {
          $result                        = $this->quickbookmodel->quickBookTerm($qb_CustID);
        }

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
        $vData['totalOnAccount']         = ($vData['grandTotalInvoiced']-$vData['TotalUnusedPayment']-$vData['CreditRemaining']+$vData['WorkInHouseTotalAmount']);
	      $vData['creditAvailable']        = (($vData['Credit Limit']) - ( $vData['totalOnAccount'] ) );


        $data                            = array('n_CreditAvailable' => $vData['creditAvailable']);

        $updateCreditAvailable = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$data);

        if(!is_numeric($updateCreditAvailable)) {
            echo $updateCreditAvailable;
        }

        $customerDataArr       = Modules::run('customers/customercontroller/getCustomerDataFromQuickBookCustID',$qb_CustID);
        $overLimitCondition    = ($customerDataArr['n_CustCreditLimit']*$customerDataArr['n_PerAllowedOverCredit'])+$vData['creditAvailable'];

        if($vData['totalInvoicesPastDue'] >0) {
            $dataForUpdate['nb_CreditHold'] = "1";
        } else {
            $dataForUpdate['nb_CreditHold'] =null;
        }

        if($overLimitCondition <=0) {
           $dataForUpdate['nb_Overlimit'] = "1";
        } else {
             $dataForUpdate['nb_Overlimit'] = null;
        }

        $updateCreditHoldReason   = Modules::run('customers/customercontroller/updateCustomerFromQuickBookCusID',$qb_CustID,$dataForUpdate);

        $this->response($result, 200);

    }

    public function orderInvoiceDuplicateData_get() {
      $result  = $this->quickbookmodel->getOrderInvoiceDuplicateData();

      $this->response($result, 200);
    }

    public function invoiceAmtDontMatchWithOrderAmt_get() {
        $result = $this->quickbookmodel->getInvoiceAmtDontMatchWithOrderAmt();

        $this->response($result, 200);
    }

    public function IDKEYFromRefNumber_get() {
      if(!$this->get('RefNumber')) {
        $this->response(NULL, 400);
      }

      $RefNumber = $this->get('RefNumber');

      $result = $this->quickbookmodel->getIDKEYFromRefNumber($RefNumber);

      if($result) {
          $this->response($result, 200); // 200 being the HTTP response code
      } else {
          $this->response(array(), 200);
      }
    }
    public function qbSalesRep_get()
    {
        $result = $this->quickbookmodel->getQBSalesRep();

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'QuickBook Sales Rep could not be found'), 404);
        }
    }
    public function itemNonInventoryByName_get()
    {
        if(!$this->get('Name'))
        {
          $this->response(NULL, 400);
        }

        $itemNonInvtName = $this->get('Name');


        $result = $this->quickbookmodel->getItemNonInventoryByName($itemNonInvtName);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array(), 200);
        }

    }
    public function qbEmployeesData_get()
    {
        $result = $this->quickbookmodel->getQBEmployeesData();

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'QuickBook Sales Rep could not be found'), 404);
        }

    }
    public function taxRate_get()
    {
        $result = $this->quickbookmodel->getItemSalesTaxDataFromListID();

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'QuickBook itemSales Tax -Tax Rate could not be found'), 404);
        }

    }
    public function quickBookAccountCOG_get()
    {
        $result = $this->quickbookmodel->getQuickBooksAccountCOG();

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'QuickBook Account could not be found'), 404);
        }

    }
    public function invoiceDataByRefNumber_get()
    {
        if(!$this->get('RefNumber'))
        {
            $this->response(NULL, 400);
        }

        $refNumber  = $this->get('RefNumber');

        $result     = $this->quickbookmodel->getInvoiceDataByRefNumber($refNumber);

        //$this->response($result, 200);
        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error'=>'No Invoice record found from RefNumber '.$refNumber), 200);
        }
    }
    public function invoiceInProcessData_get()
    {
        $result = $this->quickbookmodel->getInvoiceInProcessData();

        $this->response($result, 200);
    }
    public function invoice_put()
    {
        $data   = $this->put('formData',false);

        $result = $this->quickbookmodel->insertInvoiceData($data);

        $this->response($result, 200);

    }
    public function vendor_put()
    {
        $data   = $this->put('formData',false);

        $result =   $this->quickbookmodel->insertVendorData($data);

        $this->response($result, 200);

    }
    public function purchaseOrder_put()
    {
        $data   = $this->put('formData',false);

        $result =   $this->quickbookmodel->insertPurchaseOrderData($data);

        $this->response($result, 200);

    }
    public function purchaseOrderLineDetail_put()
    {
        $data   = $this->put('formData',false);

        $result =   $this->quickbookmodel->insertPurchaseOrderLineDetailData($data);

        $this->response($result, 200);

    }
    public function invoiceLineDetail_put()
    {
        $data               = $this->put('formData',false);


        for($x=0;$x<sizeof($data);$x++)
        {
            $result=$this->quickbookmodel->insertInvoicelineDetailData($data[$x]);
        }
        $this->response($result, 200);


    }
    public function getPurchaseOrderDataToReceive_get()
    {
        $result = $this->quickbookmodel->getPurchaseOrderToReceive();


        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Invt Item could not be found'), 404);
        }
    }
    public function getPurchaseOrderDataToReceiveByTxnID_get()
    {
      if(!$this->get('TxnID')) {
        $this->response(NULL, 400);
      }

      $TxnID = $this->get('TxnID');

        $result = $this->quickbookmodel->getPurchaseOrderToReceiveByTxnID($TxnID);


        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Could not be found'), 404);
        }
    }
    public function getPurchaseOrderDataToReceiveByDate_get()
    {
        if(!$this->get('selectedDate'))
        {
            $this->response(NULL, 400);

        }
        $selectedDate = $this->get('selectedDate');

        $result = $this->quickbookmodel->getPurchaseOrderToReceiveFromTimeCreatedDate($selectedDate);

        //print_r($result);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array(), 200);
        }

    }
    public function getPurchaseVendorListID_get()
    {
        if(!$this->get('listID'))
        {
            $this->response(NULL, 400);
        }

        $listID  = $this->get('listID');
        //echo $listID;

        $result  = $this->quickbookmodel->getPurhcaseOrderByVendorListID($listID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array(), 200);
        }
    }
    public function getPurchaseOrderLineDetailData_get()
    {
        if(!$this->get('IDKEY'))
        {
            $this->response(NULL, 400);
        }

        $IDKEY  = $this->get('IDKEY');

        $result = $this->quickbookmodel->getPurchaseOrderLineDetail($IDKEY);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array(), 200);
        }
    }
    public function purchaseOrderToReceiveCustomInsert_put()
    {
        $txnItemLineDetailData   = $this->put('txnItemLineDetailData',false);
        //$txnItemLineDetailData  = $this->put('txnitemlinedetailData',false);
        //echo "<br/>";
        //print_r($txnItemLineDetailData);
        //echo "<br/>";
        $itemReceiptData         = $this->put('itemreceiptData',false);

        //print_r($itemReceiptData);
        //echo "<br/>";
        $purchaseOrderData       = $this->put('purchaseOrderData',false);

        //print_r($purchaseOrderData);
        //echo "<br/>";
        for($x=0;$x<sizeof($txnItemLineDetailData);$x++)
        {
            $txnItemLineDetailInsertedID[$x]=$this->quickbookmodel->insertTxnItemLineDetail($txnItemLineDetailData[$x]);


        }
        $itemReceiptInsertedID     = $this->quickbookmodel->itemReceipt($itemReceiptData);

        $updatePurchaseOrderResult = $this->quickbookmodel->updatePurchaseOrder($purchaseOrderData,$purchaseOrderData['RefNumber']);

        $result['txnLineDetailResult']       = $txnItemLineDetailInsertedID;
        $result['itemReceiptResult']         = $itemReceiptInsertedID;
        $result['updatePurchaseOrderResult'] = $updatePurchaseOrderResult;

        //print_r($result);


        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'somethign went wrong Address could not be found'), 404);
        }

    }

    public function invoiceCustomDelete_post()
    {
        if(!$this->post('RefNumber'))
        {
            $this->response(array('RefNumber' => $this->post('RefNumber'),'error' => 'somethign went wrong with Invoice Table RefNumber could not be found'), 400);
        }
        else
        {
             // insert a new row into statusLog table
            $statusLogData                     = $this->post('formData'); // returns all POST items without XSS filter
            $statusLogData['zCreated']         = date("Y-m-d H:i:s", time());
            //print_r($statusLogData);

            //get the insertedID of statusLog table
            $statusLogID                       = Modules::run('statusLog/statuslogcontroller/insertStatusLogData',$statusLogData);

            if($statusLogID)
            {
                // update the Status field to "DELETE" of invoice table
                $data['Status'] = "DELETE";
                $refNumber      = $this->post('RefNumber');
                //print_r($data);
                $result = $this->quickbookmodel->updateInvoiceTblDataByRefNumber($data,$refNumber);

            }


        }
        if($result == "0" || $result == "1")
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => print_r($result).'somethign went wrong Invoice Table '.$refNumber.' could not be found'. $result), 404);
        }
    }
}
