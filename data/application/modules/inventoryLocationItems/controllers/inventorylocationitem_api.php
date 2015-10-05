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

class InventoryLocationItem_Api extends REST_Controller
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
        $this->load->model('inventorylocationitemmodel');

    }
    public function invtLocationItemsFromInvtItemID_get()
    {
        if(!$this->get('kp_InventoryItemID'))
        {
        	$this->response(array('error' => 'InventoryItem could not be found'), 400);
        }
        $inventoryItemID = $this->get('kp_InventoryItemID');
        //echo $inventoryItemID;
        $result = $this->inventorylocationitemmodel->getInvtLocationItemsFromInvtItemID($inventoryItemID);
        //print_r($result);
        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'No result InventoryItem could not be found'), 404);
        }
    }
    public function invtLocationItems_put()
    {
        if($this->put('kp_InventoryLocationItemID')) // if there is an value for kp_addressID throw an error
        {
            $this->response(array('error' => 'somethign went wrong with \'put\'.kp_InventoryLocationItemID ID cannot have a value for insert'), 400);
        }
        $data                       = $this->put('formData',false);
        $data['zCreated']           = date("Y-m-d H:i:s", time());
        $insertData[0]              = $data;
        //print_r($data);
        $result = $this->inventorylocationitemmodel->insertInvLocationItemTable($insertData);

        if($result)
        {
            $this->response(array('id'=>$result), 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'somethign went wrong invtLocationItem Insert API could not be found'), 404);
        }
    }

}
