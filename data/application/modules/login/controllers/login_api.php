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

class Login_Api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('loginmodel');
    }
    public function user_get()
    {
         if(!$this->get('id'))
        {
        	$this->response(NULL, 400);
        }

        // $user = $this->some_model->getSomething( $this->get('id') );
    	$users = array(
			1 => array('id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com', 'fact' => 'Loves swimming'),
			2 => array('id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com', 'fact' => 'Has a huge face'),
			3 => array('id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com', 'fact' => 'Is a Scott!', array('hobbies' => array('fartings', 'bikes'))),
		);

    	$user = @$users[$this->get('id')];

        if($user) {
            $this->response($user, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'User could not be found'), 404);
        }

    }
    // Commented out until we need to use it, should not be normally available
    // public function loginEmployeePermanent_post() {
    //     $output           = array();
    //     $output['status'] = false;
    //
    //     if(!$this->post('id') || !$this->post('password')) {
    //         $this->response(array('error' => 'Email or password is not set'), 400);
    //     }
    //
    //     $id             = $this->post('id');
    //     $password       = $this->post('password');
    //     $data           = $this->loginmodel->loginEmployeeData($id,$password);
    //
    //     if($data['nb_Locked'] == "1") {
    //       $output['errors'] = 'This account is no longer accessible.';
    //       $this->response(array('output' => $output), 200);
    //     }
    //
    //     if($data['kp_EmployeeID'] != false) {
    //         $token                       = array();
    //         $token['id']                 = $data['kp_EmployeeID'];
    //         $token['privilegeSet']       = $data['t_PrivilegeSet'];
    //         $token['time']               = "Permanent";
    //
    //         $output['status']            = true;
    //         $output['email']             = $data['t_EmployeeEmail'];
    //         $output['userName']          = $data['t_UserName'];
    //         $output['id']                = $data['kp_EmployeeID'];
    //         $output['t_Department']      = $data['t_Department'];
    //         $output['nb_DeleteInvoice'] = $data['nb_DeleteInvoice'];
    //         $output['nb_DeleteInvoice45Days'] = $data['nb_DeleteInvoice45Days'];
    //         $output['t_PrivilegeSet']    = $data['t_PrivilegeSet'];
    //
    //         $output['token']             = JWT::encode($token, $this->config->item('jwt_key'));
    //     } else {
    //         $output['errors'] = 'The email/password combination entered is invalid.';
    //     }
    //
    //     if($output['status']) {
    //         $this->response(array('output' => $output), 200); // 200 being the HTTP response code
    //     } else {
    //         $this->response(array('output' => $output), 404);
    //     }
    // }
    public function loginEmployee_post() {
        $output           = array();
        $output['status'] = false;

        if(!$this->post('id') || !$this->post('password')) {
            $this->response(array('error' => 'Email or password is not set'), 400);
        }

        $id          = $this->post('id');
        $password       = $this->post('password');
        $data           = $this->loginmodel->loginEmployeeData($id,$password);

        if($data['nb_Locked'] == "1") {
          $output['errors'] = 'This account is no longer accessible.';
          $this->response(array('output' => $output), 200);
        }

        if($data['kp_EmployeeID'] != false) {
            $token                       = array();
            $token['id']                 = $data['kp_EmployeeID'];
            $token['privilegeSet']       = $data['t_PrivilegeSet'];
            $token['time']               = date("Y-m-d H:i:s", time());
            // $token['time']               = "Permanent";

            $output['status']            = true;
            $output['email']             = $data['t_EmployeeEmail'];
            $output['userName']          = $data['t_UserName'];
            $output['id']                = $data['kp_EmployeeID'];
            $output['t_Department']      = $data['t_Department'];
            $output['nb_DeleteInvoice'] = $data['nb_DeleteInvoice'];
            $output['nb_DeleteInvoice45Days'] = $data['nb_DeleteInvoice45Days'];
            $output['t_PrivilegeSet']    = $data['t_PrivilegeSet'];
            $output['n_Extension']       = $data['n_Extension'];
            $output['nb_SalesDoNotFilterHome'] = $data['nb_SalesDoNotFilterHome'];
            $output['nb_CanViewEmployeeDocs'] = $data['nb_CanViewEmployeeDocs'];
            $output['nb_CanDeleteEmployeeDocs'] = $data['nb_CanDeleteEmployeeDocs'];

            $output['token']             = JWT::encode($token, JWT_KEY);
        } else {
            $output['errors'] = 'The email/password combination entered is invalid.';
        }

        if($output['status']) {
            $this->response(array('output' => $output), 200); // 200 being the HTTP response code
        } else {
            $this->response(array('output' => $output), 404);
        }
    }
  //   public function updateEmplPasswd_put()
  //   {
  //       if(!$this->put('id'))
  //       {
  //           $this->response(array('error' => 'ID could not be found'), 400);
  //       }
  //       if(!$this->put('password'))
  //       {
  //           $this->response(array('error' => 'Password could not be found'), 400);
  //       }
  //       $output           = array();
	// $output['passUpdateStatus'] = false;
  //
  //       // get the id and password
  //       $id            = $this->put('id');
  //       $password         = $this->put('password');
  //
  //       $employeeInfo     = $this->loginmodel->getEmployeeFromEmployeeEmail($id);
  //
  //       if(!$employeeInfo) {
  //         $employeeInfo     = $this->loginmodel->getEmployeeFromEmployeeUserName($id);
  //       }
  //
  //       $employeeID       = $employeeInfo['kp_EmployeeID'];
  //
  //       $emplPasswordChg  = $this->loginmodel->updateEmployeePassword($password,$employeeID);
  //       // $output['time'] = $emplPasswordChg;
  //
  //       if($emplPasswordChg)
  //       {
  //           $output['passUpdateStatus'] = true;
  //           $this->response(array('output' => $output), 200); // 200 being the HTTP response code
  //       }
  //       else
  //       {
  //           $output['passUpdateStatus'] = false;
  //           $this->response(array('output' => $output), 404);
  //       }
  //
  //
  //
  //   }
    public function resetEmplPasswd_put() {
      if(!$this->put('kp_EmployeeID')) {
        $this->response(array('error' => 'Employee ID could not be found'), 400);
      }
      $id = $this->put('kp_EmployeeID');


      $employeeInfo     = $this->loginmodel->getEmployeeFromEmployeeID($id);

      if(!$employeeInfo) {
        $output['message'] = 'Employee Not Found';
        $this->response(array('output' => $output), 200);
      }

      //Generate a random password
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$^*()-_=+`~[]{}?|';
      $randomString = '';
      for ($i = 0; $i < 10; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
      }

      //save it to the database
      $emplPasswordChg  = $this->loginmodel->updateEmployeePassword($randomString,$employeeInfo['kp_EmployeeID']);
      $this->loginmodel->resetEmployeePassword($employeeInfo['kp_EmployeeID']);

        if($employeeInfo['t_EmployeeEmail'] && strrpos($employeeInfo['t_EmployeeEmail'], "@indyimaging.com") !== false) {
          //Send the user an email
          $body = "Your password has been reset.  Please login using this temporary password: " . $randomString;
          $subject = "Password Reset";
          Modules::run('rest/customemailcontroller/genericEmail',$employeeInfo['t_EmployeeEmail'], $body, $subject);

          $output['message'] = "Password emailed.";
          } else {
            $output['message'] = 'Password: ' . $randomString;
          }


      $this->loginmodel->resetEmployeePassword($id);

      $this->response(array('output' => $output), 200); // 200 being the HTTP response code
    }
    public function changePassword_put() {
      $output = array();
      if($this->input->get_request_header('Authorization')) {
        $tokenHeader      = $this->input->get_request_header('Authorization', TRUE);

        try {
          $token            = JWT::decode($tokenHeader, JWT_KEY);
        } catch (Exception $e) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
      } else {
        $output['message'] = 'You do not have permission to change this password.';
        $this->response(array('output' => $output), 200);
      }

      if ($token !== null && $token !== false) {
        if($token->privilegeSet !== "Reset") {
          $output['message'] = 'You do not have permission to change this password.';
          $this->response(array('output' => $output), 200);
        }
        $id = $token->id;
        if(!$this->put('password')) {
          $this->response(array('error' => 'New password was not found'), 400);
        }
        $password = $this->put('password');

        $this->loginmodel->updateEmployeePassword($password, $id);

        $output['message'] = "Password changed.  Logging you in now.";
        $this->response(array('output' => $output), 200);
      }
    }
    public function resetPasswordRequest_get() {
      $output           = array();

      if(!$this->get('id')) {
        $this->response(array('error' => 'ID was not found'), 400);
      }

      $id          = $this->get('id');

      $employeeInfo     = $this->loginmodel->getEmployeeFromEmployeeEmail($id);

      if(!$employeeInfo) {
        $employeeInfo     = $this->loginmodel->getEmployeeFromEmployeeUserName($id);
      }

      if(!$employeeInfo) {
        $output['message'] = 'Employee Not Found';
        $this->response(array('output' => $output), 200);
      }

      if($employeeInfo['kp_EmployeeID']) {
        if($employeeInfo['t_EmployeeEmail'] && strrpos($employeeInfo['t_EmployeeEmail'], "@indyimaging.com") !== false) {
          //Generate a random password
          $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$^*()-_=+`~[]{}?|';
          $randomString = '';
          for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
          }

          //save it to the database
          $emplPasswordChg  = $this->loginmodel->updateEmployeePassword($randomString,$employeeInfo['kp_EmployeeID']);
          $this->loginmodel->resetEmployeePassword($employeeInfo['kp_EmployeeID']);

          if($emplPasswordChg) {
            //Send the user an email
            $body = "Your password has been reset.  Please login using this temporary password: " . $randomString;
            $subject = "Password Reset";

            $this->loginmodel->genericEmail($employeeInfo['t_EmployeeEmail'], $body, $subject);

            $output['message'] = "Your temporary password has been emailed to you.";
          } else {
            $output['message'] = "Something went wrong with creating a temporary password.  Please contact IT.";
          }


        } else {
          $output['message'] = 'You do not have a Indy Imaging email.  Talk with IT or a Manager to reset your password.';
        }
      }
      else
      {
        $output['message'] = 'Employee Not Found';
      }

      $this->response(array('output' => $output), 200); // 200 being the HTTP response code

    }
    public function employeeInfoByToken_get() {
        if($this->input->get_request_header('Authorization')) {
            $tokenHeader      = $this->input->get_request_header('Authorization', TRUE);

            // decode the token
            try {
              $token            = JWT::decode($tokenHeader, JWT_KEY);
            } catch (Exception $e) {
              $this->response(array('error' => 'Invalid or missing token.'), 401);
            }
        } else {
            $token      = null;
        }

        if ($token !== null && $token !== false) {
          if(!$token->time) {
            $this->response(array('output' => "No login time found,"), 400);
          }

            $employeeID       = $token->id;

            $output['status'] = true;

            $employeeInfo     = $this->loginmodel->getEmployeeDataByID($employeeID);

            if($employeeInfo) {
                $output['kp_EmployeeID']        = $employeeInfo['kp_EmployeeID'];
                $output['t_UserName']           = $employeeInfo['t_UserName'];
                $output['t_EmployeeEmail']      = $employeeInfo['t_EmployeeEmail'];
                $output['t_Department']      = $employeeInfo['t_Department'];
                $output['nb_DeleteInvoice'] = $employeeInfo['nb_DeleteInvoice'];
                $output['nb_DeleteInvoice45Days'] = $employeeInfo['nb_DeleteInvoice45Days'];
                $output['n_Extension']          = $employeeInfo['n_Extension'];
                $output['nb_SalesDoNotFilterHome'] = $employeeInfo['nb_SalesDoNotFilterHome'];
                $output['nb_CanViewEmployeeDocs'] = $employeeInfo['nb_CanViewEmployeeDocs'];
                $output['nb_CanDeleteEmployeeDocs'] = $employeeInfo['nb_CanDeleteEmployeeDocs'];

                if($employeeInfo['nb_ResetPassword'] === "1") {
                  $output['t_PrivilegeSet']     = "Reset";
                } else {
                  $output['t_PrivilegeSet']     = $employeeInfo['t_PrivilegeSet'];
                }




                if($output['status']) {
                    $this->response(array('output' => $output), 200); // 200 being the HTTP response code
                }
            } else {
                $this->response(array('output' => 'No employee Data found for '.$id), 404);
            }
        } else {
            $output['errors'] = 'missing authorization header.';

            $this->response(array('output' => $output), 400);
        }
    }
}
