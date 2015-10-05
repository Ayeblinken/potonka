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

class InventoryZone_Api extends REST_Controller
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
        $this->load->model('inventoryzonemodel');
    }
    public function invtZoneData_get()
    {

        $result = $this->inventoryzonemodel->getInventoryZoneData();
        //print_r($result); JSON_NUMERIC_CHECK
//        for($x=0;$x<sizeof($result);$x++)
//        {
//            $result[$x]['n_SortOrder'] = (int)$result[$x]['n_SortOrder'];
//        }
        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'InventoryItem could not be found'), 404);
        }
    }
    public function invtZoneTblData_get()
    {
        $result = $this->inventoryzonemodel->getInventoryZoneTblData();

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'InventoryItem could not be found'), 404);
        }

    }
    public function invtZoneTblData_post()
    {
        if(!$this->post('kp_InventoryZoneID'))
        {
            $this->response(array('kp_InventoryZoneID' => $this->post('kp_InventoryZoneID'),'error' => 'somethign went wrong hellow inventoryZoneID could not be found'), 400);
        }
        else
        {
            $data                             = $this->post();

            $inventoryZoneID                  = $this->post('kp_InventoryZoneID');

            $data['t_ImageMap']               =  str_replace(" ", "_",$data['t_ImageMap']);

            //print_r($data);

            //echo $inventoryZoneID;

             $result              = $this->inventoryzonemodel->updateInvZoneData($data,$inventoryZoneID);
        }
        if($result == "0" || $result == "1")
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'somethign went wrong gogoog Address could not be found'. $result), 404);
        }


    }
    public function invtZone_post()
    {
            //print_r($this->post());
            $data                = $this->post();

            if(array_key_exists('formData',$data))// submitting form without image -- just form data
            {
                //echo "submitting form without image <br/>";
                //print_r($data);
                $invZoneinsertID    = $this->inventoryzonemodel->insertInvZoneData($data['formData']);

                if($invZoneinsertID)
                {
                    $this->response(array('ID'=>$invZoneinsertID), 200); // 200 being the HTTP response code
                }
                else
                {
                    $this->response(array(), 404);
                }
            }
            else // submitting form with Image data --- insert and then update file name
            {
                //$data['t_ImageMap']   =  str_replace(" ", "_",$data['t_ImageMap']);
                //echo "submitting form with Image data --- insert and then update file name<br/>";
                //print_r($data);
                $invZoneinsertID      = $this->inventoryzonemodel->insertInvZoneData($data);
                //echo "<br/>invZoneinsertID : ".$invZoneinsertID."<br/>";
                if(!empty($_FILES['file']['name']))
                {
                    //echo "<br/>fileName : ".$_FILES['file']['name']."<br/>";
                    $msg            = $this->inventoryzonemodel->doInvtZoneCustomUpload($invZoneinsertID);
                    //echo "<br/> Msg:- ".$msg."<br/>";
                    if($msg)
                    {
                        $this->response($msg, 200); // 200 being the HTTP response code
                    }
                    else
                    {
                        $this->response($msg, 404);
                    }

                }
            }

            //print_r($_FILES);
            //$result                     = array('InvItemID'=>$invItemInsertID);





    }
    public function invtZoneImgUplaod_post()
    {
        //submitting  Image data ---  update the table with file name
        $data            = $this->post();
        //print_r($data);
        //echo $data['kp_InventoryZoneID'];
        $inventoryZoneID = $data['kp_InventoryZoneID'];

        if(!empty($_FILES['file']['name']))
        {
            //echo "not empty";
            $msg          = $this->inventoryzonemodel->doInvtZoneCustomUpload($inventoryZoneID);

            if($msg)
            {
                $this->response($msg, 200); // 200 being the HTTP response code
            }
            else
            {
                $this->response($msg, 404);
            }

        }



    }
}
