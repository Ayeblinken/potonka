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
        $this->load->model('quickbookmodel');
        
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
    public function itemNonInventory_put()
    {
        $data               = $this->put('formData',false);
        $data['zCreated']   = date("Y-m-d H:i:s", time());
        print_r($data);
//        $invItemInsertID    = $this->quickbookmodel->insertInvItemData($data);
//        $result             = array('InvItemID'=>$invItemInsertID);
//        
//      
//        if($result)
//        {
//            $this->response($result, 200); // 200 being the HTTP response code
//        }
//        else
//        {
//            $this->response(array('error' => 'somethign went wrong Address could not be found'), 404);
//        }
    }
    public function salesOrPurchaseDetail_put()
    {
        $data               = $this->put('formData',false);
        $data['zCreated']   = date("Y-m-d H:i:s", time());
        print_r($data);
        
        //$invItemInsertID    = $this->quickbookmodel->insertInvItemData($data);
        //$result             = array('InvItemID'=>$invItemInsertID);
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
}