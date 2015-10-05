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

class PurchaseRequestItem_Api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        if(!checkPermissionsByModule($privSet, 'purchase')) {
          $this->response(array('error' => 'You\'re privilege set doesn\'t allow access to this content'), 400);
        }
        $this->load->model('purchaserequestitemmodel');

    }
    public function purchaseRequestItemFromInvtItemID_get()
    {
        if(!$this->get('kp_InventoryItemID'))
        {
        	$this->response(array('error' => 'InventoryItem could not be found'), 400);
        }
        $inventoryItemID = $this->get('kp_InventoryItemID');
        //echo $inventoryItemID;
        $result = $this->purchaserequestitemmodel->getPurchaseRequestItemPurchaseRequestFromInvtItemID($inventoryItemID);
        //print_r($result);
        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'InventoryItem could not be found'), 404);
        }
    }
    public function purchaseOrderGraphData_get()
    {
        if(!$this->get('kp_InventoryItemID'))
        {
        	$this->response(array('error' => 'InventoryItem could not be found to produce the graph'), 400);
        }
        $inventoryItemID = $this->get('kp_InventoryItemID');
        //echo $inventoryItemID;
        $result          = $this->purchaserequestitemmodel->getPurchaseOrderGraph($inventoryItemID);

        //print_r($result);
        //print_r($result);
        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'InventoryItem could not be found to produce the graph'), 404);
        }

    }

}
