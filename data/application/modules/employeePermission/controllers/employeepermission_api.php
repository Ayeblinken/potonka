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

class EmployeePermission_Api extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('employeepermissionmodel');
  }

  // public function employeePermissions_get() {
  //   $result = $this->employeepermissionmodel->getEmployeePermissions();
  //
  //   if($result) {
  //     $this->response($result, 200); // 200 being the HTTP response code
  //   } else {
  //     $this->response(array('error' => 'Couldn\'t find any results!'), 404);
  //   }
  // }

  public function employeePermissions_get() {
    $permissions = getPermissions();

    $result = array();
    foreach ($permissions as &$value) {
      array_push($result, (object) $value);
    }

    if($result) {
      $this->response($result, 200); // 200 being the HTTP response code
    } else {
      $this->response(array('error' => 'Couldn\'t find any results!'), 404);
    }
  }

}

?>
