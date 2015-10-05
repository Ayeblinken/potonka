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

class InventoryLocation_Api extends REST_Controller
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
        $this->load->model('inventorylocationmodel');
    }

    public function invtLocationDataByLocationName_get()
    {
        if(!$this->get('t_Location'))
        {
            $this->response(NULL, 400);
        }

        $locationName = $this->get('t_Location');

        //echo $locationName."<br/>";
        $result       = $this->inventorylocationmodel->getInvtLocationDataByLocationName($locationName);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array(), 200);
        }

    }

    public function locationItemTblData_get() {
      if(!$this->get('kp_InventoryLocationID')) {
        $this->response(NULL, 400);
      }

      $inventoryLocationID = $this->get('kp_InventoryLocationID');

      $result = $this->inventorylocationmodel->getLocationItemTblData($inventoryLocationID);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(), 200);
      }
    }
    public function invtLocationIDLocation_get()
    {

        $result = $this->inventorylocationmodel->getInventoryLocationIDLocation();
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
    public function invtLocationZoneTbl_get()
    {
        $result = $this->inventorylocationmodel->getinvtLocationZoneTblData();
        //print_r($result);
        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'Invt Location Zone could not be found'), 404);
        }

    }
    public function invtLocationZoneTbl_post()
    {
        if(!$this->post('kp_InventoryLocationID'))
        {
            $this->response(array('kp_InventoryLocationID' => $this->post('kp_InventoryLocationID'),'error' => 'somethign went wrong kp_InventoryLocationID not found'), 400);
        }
        else
        {
            $updateData                       = $this->post();
            $updateData['zModified']          = date("Y-m-d H:i:s", time());
            $inventoryLocationID              = $this->post('kp_InventoryLocationID');

            if($updateData['nb_Inactive'] != "1")
            {
                $updateData['nb_Inactive'] = null;
            }
            $data['zModified']                = $updateData['zModified'];
            $data['nb_Inactive']              = $updateData['nb_Inactive'];

            //echo $inventoryLocationID."<br/><br/>"; print_r($data);

            $result    = $this->inventorylocationmodel->updateInvtLocationTbl($data,$inventoryLocationID);
        }
        if($result == "0" || $result == "1")
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'somethign went wrong orderShip Tbl cannot be updated'), 404);
        }
    }
}
