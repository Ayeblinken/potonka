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

class InventoryItem_Api extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        if(!checkPermissionsByModule($privSet, 'inventory')) {
          $this->response(array('error' => 'You\'re privilege set doesn\'t allow access to this content'), 400);
        }
        date_default_timezone_set('America/Indianapolis');
        $this->load->model('inventoryitemmodel');
        $this->load->helper('download');
        $this->load->helper('file');

        $this->orderRedoUploadPath = realpath(APPPATH . '../../../images/');

    }

    public function invReceiptInProcessList_get() {
      $result = $this->inventoryitemmodel->getInvReceiptInProcessList();

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No inventory receipts found')), 200);
      }
    }

    public function invItemInProcessList_get() {
      $result = $this->inventoryitemmodel->getInvItemInProcessList();

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No inventory items found')), 200);
      }
    }

    public function inventoryOnHandMinMax_get() {
      if(!$this->get('id')) {
        $this->response(NULL, 400);
      }

      $inventoryItemID = $this->get('id');

      $result = $this->inventoryitemmodel->getInventoryOnHandMinMax($inventoryItemID);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array('error' => 'Inventory Item not found.'), 404);
      }
    }

    public function inventoryItemCustomInsert_put()
    {

        if($this->put('kp_InventoryItemID')) // if there is an value for kp_addressID throw an error
        {
            $this->response(array('error' => 'somethign went wrong with \'put\'.InventoryItemID ID cannot have a value for insert  could not be found'), 400);
        }
        else
        {
             $data                       = $this->put('formData',false);
             $data['zCreated']           = date("Y-m-d H:i:s", time());
             //print_r($data);

             // get itemNonInvData
             $itemNonInvData             = $this->put('itemNonInv',false);
             //print_r($itemNonInvData);

             // get salesOrPurchaseDetailData
             $salesOrPurchaseDetailData  = $this->put('salesOrPurchaseDetail',false);
             //print_r($salesOrPurchaseDetailData);

             $invItemInsertID            = $this->inventoryitemmodel->insertInvItemData($data);

             $invItemInsertedData        = $this->inventoryitemmodel->getInventoryItemByID($invItemInsertID);

             //print_r($invItemInsertedData);

             // complete salesOrPurchaseDetailData
             $salesOrPurchaseDetailData['IDKEY']       = $invItemInsertID;
             $salesOrPurchaseDetailData['Description'] = htmlspecialchars($invItemInsertedData['t_description']);

             // insert salesOrPurchaseDetailData
             $salesOrPurchaseDetailID    = Modules::run('quickBooks/quickbookcontroller/insertSalesOrPurchaseDetailTbl',$salesOrPurchaseDetailData);

             // complete itemNonInvData
             $itemNonInvData['ListID']   = $invItemInsertID;

             // insert itemNonInvData
             $itemNonInvInsertedID       = Modules::run('quickBooks/quickbookcontroller/insertQuickBooksItemNonInventoryTbl',$itemNonInvData);


             $result                     = array('InvItemID'=>$invItemInsertID);

             //print_r($result);
        }
        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'somethign went wrong Address could not be found'), 404);
        }
    }
    public function invtInactiveVendorLocByInvtItemID_get()
    {
        if(!$this->get('kp_InventoryItemID')) // if there is an value for kp_addressID throw an error
        {
            $this->response(array('error' => 'somethign went wrong with \'get\'.InventoryItemID ID cannot be null'), 400);
        }

        $inventoryItemID = $this->get('kp_InventoryItemID');

        $result = $this->inventoryitemmodel->getInvtInactiveVendorLocByInvtItemID($inventoryItemID);


        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Invt Item could not be found'), 404);
        }

    }
    public function invtVendorLocByInvtItemID_get()
    {
        if(!$this->get('kp_InventoryItemID')) // if there is an value for kp_addressID throw an error
        {
            $this->response(array('error' => 'somethign went wrong with \'get\'.InventoryItemID ID cannot be null'), 400);
        }

        $inventoryItemID = $this->get('kp_InventoryItemID');

        $result = $this->inventoryitemmodel->getInvItemLocVenByInvtItemID($inventoryItemID);


        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Invt Item could not be found'), 404);
        }
    }
    public function inventoryVendorLocation_get()
    {
        $result = $this->inventoryitemmodel->getInvItemLocationVendor();


        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Invt Item could not be found'), 404);
        }
    }
    public function inventoryVendorLocationInActive_get()
    {
        $result = $this->inventoryitemmodel->getInvItemLocationVendorInActive();


        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Invt Item could not be found'), 404);
        }

    }
    public function inventoryVendorLocationByVendorID_get()
    {
        if(!$this->get('kf_VendorID')) // if there is an value for kp_addressID throw an error
        {
            $this->response(array('error' => 'somethign went wrong with \'get\'.VendorID ID cannot be null'), 400);
        }

        $vendorID = $this->get('kf_VendorID');

        $result = $this->inventoryitemmodel->getInvItemLocationVendorByVendorID($vendorID);


        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Invt Item could not be found'), 404);
        }
    }
    public function processInvtLabelData_put()
    {
        //echo $data;
        $textData          = $this->put('textData',false);
        $customString      = "";

        for($x=0;$x<sizeof($textData);$x++)
        {
           $customString .= implode(",",$textData[$x])."\n";
        }
        $printMeTextData = $customString;

        // save this to a text file and send the filename as the result
        $fileNamePath = $this->orderRedoUploadPath.'/printme.txt';
        if (!write_file($fileNamePath, $printMeTextData))
        {
            $result = false;
            $msg    = 'Unable to write the file';
        }
        else
        {
            $result =  true;
            $msg    = 'File written!';
        }

//        header("Cache-Control: public");
//        header("Content-Description: File Transfer");
//        header("Content-Disposition: attachment; filename= file.txt");
//        header("Content-Transfer-Encoding: binary");
//
        if($result)
        {
            $this->response(array('fileNamePath' => $fileNamePath), 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response($msg, 404);
        }


    }
    public function printInvtLabel_get()
    {

        $string                 = read_file($this->orderRedoUploadPath.'/printme.txt');
        //$data                   = date("Y-m-d:H:i:s", time());
        $name = date("Y-m-d_H_i_s", time()).'.printme.txt';

        force_download($name,$string);
    }

}
