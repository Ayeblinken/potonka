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

class BuildItem_Api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        if(!checkPermissionsByModule($privSet, 'builditem')) {
          $this->response(array('error' => 'You\'re privilege set doesn\'t allow access to this content'), 400);
        }
        $this->load->model('builditemmodel');

    }

    public function buildItemsByManCatID_get() {
      if(!$this->get('kf_ManCatID')) {
        $this->response(NULL, 400);
      }

      $manCatID = $this->get('kf_ManCatID');

      $result = $this->builditemmodel->getBuildItemsByManCatID($manCatID);

      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(), 200);
      }
    }

    public function buildItemNameFromManCatID_get()
    {
        if(!$this->get('kf_ManCatID'))
        {
            $this->response(NULL, 400);
        }
        $mancatID= $this->get('kf_ManCatID');

        $result = $this->builditemmodel->getBuildItemNameFromManCatID($mancatID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array(array('error' => 'No Build Items Found')), 200);
        }
    }
    public function budilItemsManCatsManCatSubCats_get()
    {
        $result = $this->builditemmodel->getBudilItems_Join_ManCats_InnerJoin_ManCatSubCats();

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'BuilItemID and BuildItem Name could not be found'), 404);
        }
    }
    public function budilItemsManCatsManCatSubCatsByBuilItemID_get()
    {
        if(!$this->get('kp_BuildItemID'))
        {
            $this->response(NULL, 400);
        }
        $buildItemID= $this->get('kp_BuildItemID');

        $result = $this->builditemmodel->getBudilItems_Join_ManCats_InnerJoin_ManCatSubCatsByBuildItemID($buildItemID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'BuilItemID and BuildItem Name could not be found'), 404);
        }
    }
}
