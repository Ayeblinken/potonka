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

class Employee_Api extends REST_Controller {
  
    public function __construct() {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        if(!checkPermissionsByModule($privSet, 'employee')) {
          $this->response(array('error' => 'You\'re privilege set doesn\'t allow access to this content'), 400);
        }
        $this->load->model('employeemodel');
    }

    public function newEmployeeDocumentUpload_post() {
      $result       = $this->employeemodel->newEmployeeDocumentUpload();

      $this->response($result, 200);
    }

    public function deleteNewEmployeeDocument_delete($name) {
      if(!$name) {
        $this->response(array(array('error' => 'Name Not Found')), 400);
      }

      $nameDecode = rawurldecode($name);
      $nameDecode = str_replace('%2529', ')', str_replace('%2528', '(', $name));

      $result       = $this->employeemodel->deleteNewEmployeeDocument($nameDecode);

      $this->response($result, 200);
    }

    public function masterDocs_get() {
      $result = scandir("../../../shopbot/Employees/_masterdocs");

      $this->response($result, 200);
    }

    public function employeeDocumentName_put() {
      if(!$this->put('id') || !$this->put('newName') || !$this->put('oldName')) {
        $this->response(array(array('error' => 'ID and/or Names Not Found')), 400);
      }
      $id = $this->put('id');
      $newName = $this->put('newName');
      $oldName = $this->put('oldName');

      $result       = $this->employeemodel->updateEmployeeDocumentName($id, $oldName, $newName);

      $this->response($result, 200);
    }

    public function employeeDocumentUpload_post() {
      if(!$this->post('id')) {
        $this->response(array(array('error' => 'ID Not Found')), 400);
      }
      $id = $this->input->post('id');

      $result       = $this->employeemodel->employeeDocumentUpload($id);

      $this->response($result, 200);
    }

    public function employeeDocument_delete($id, $name) {
      if(!$id || !$name) {
        $this->response(array(array('error' => 'ID and/or Name Not Found')), 400);
      }

      $result       = $this->employeemodel->deleteEmployeeDocument($id, $name);

      $this->response($result, 200);
    }

    public function sendOvertime_post() {
      if(!$this->post('body') || !$this->post('to')) {
        $this->response(NULL, 400);
      }

      $body = $this->post('body');
      $to = $this->post('to');
      // $to = 'supervisors@indyimaging.com';
      // $to = 'matthew.sickler@indyimaging.com';
      $subject = "New Time Sheet Data Available";
      $bcc = '';

      Modules::run('rest/customemailcontroller/genericEmail', $to, $body, $subject);

      $this->response("Done", 200);
    }

    public function empInProcessList_get() {
      $result = $this->employeemodel->getEmpInProcessList();

      if($result) {
        $this->response($result, 200); // 200 being the HTTP response code
      } else {
        $this->response(array(array('error' => "No employees found.")), 200);
      }
    }

    public function employees_get() {
      $employeess = $this->employeemodel->getAllEmployees();

      if($employeess) {
        $this->response($employeess, 200); // 200 being the HTTP response code
      } else {
        $this->response(array('error' => 'Couldn\'t find any employeess!'), 404);
      }
    }

    public function getAllEmployeeRowsDatatablesRestApi_get() {
        $employeess = $this->employeemodel->getAllEmployeeRowsDatatablesRestFullApi();

        if($employeess) {
            $this->response($employeess, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'Couldn\'t find any employeess!'), 404);
        }
    }

    public function employeeCustomerServiceResult_get() {
        $employeeCustomerServiceResult    = $this->employeemodel->employeeCustomerService();

        if($employeeCustomerServiceResult) {
            $this->response($employeeCustomerServiceResult, 200); // 200 being the HTTP response code
        } else {
            $this->response(array(), 200);
        }
    }

    public function employeeImgUplaod_post() {
        $data                = $this->post();
        $employeeID          = $data['kp_EmployeeID'];

        if(!empty($_FILES['file']['name'])) {
            $msg            = $this->employeemodel->doEmployeeImgCustomUpload($employeeID);

            if($msg) {
                $this->response(array('path' => $msg), 200); // 200 being the HTTP response code
            } else {
                $this->response($msg, 404);
            }
        }

    }
}

?>
