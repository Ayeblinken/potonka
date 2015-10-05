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

class InventoryItemsToBuildItemsLink_Api extends REST_Controller
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
        $this->load->model('inventoryitemstobuilditemslinkmodel');

    }
    public function categoryLinkedBuildItemsTbl_get()
    {
        if(!$this->get('kp_InventoryItemID'))
        {
        	$this->response(NULL, 400);
        }
        $inventoryItemID = $this->get('kp_InventoryItemID');

        $result = $this->inventoryitemstobuilditemslinkmodel->categoryLinkedBuildItemsTblData($inventoryItemID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'InventoryItem could not be found'), 404);
        }


    }
    public function buildItemTabInventoryToBuildItemLink_get()
    {
        if(!$this->get('kf_BuildItemID'))
        {
        	$this->response(NULL, 400);
        }
        $buildItemID = $this->get('kf_BuildItemID');

        $result = $this->inventoryitemstobuilditemslinkmodel-> inventoryItemsToBuildItemsLink_Vendor_InventoryItems_InventoryLocationItems($buildItemID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'BuildItemID could not be found'), 404);
        }

    }


}
