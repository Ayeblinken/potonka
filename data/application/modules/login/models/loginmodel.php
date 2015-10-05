<?php

class LoginModel extends CI_Model
{
    //put your code here
    public function __construct()
    {
        parent::__construct();
    }

    public function genericEmail($to, $body, $subject) {
      $config = Array(
      'protocol' => 'smtp',
      'smtp_host' => 'ssl://smtp.gmail.com',
      'smtp_port' => 465,
      'smtp_user' => 'noreply@indyimaging.com',
      'smtp_pass' => 'n0rEp1y',
      'mailtype'  => 'html',
      'charset'   => 'iso-8859-1',
      'wordwrap' => TRUE
    );

    $from                      = 'noreply<noreply@indyimaging.com>';


    $this->load->library('email',$config);
    $this->email->set_newline("\r\n");
    $this->email->from($from);
    $this->email->to($to);

    $this->email->subject($subject);

    $this->email->message($body);
    $this->email->send();
  }

    public function loginEmployeeData($email, $password)
    {
        $this->db->select('Employees.kp_EmployeeID, Employees.t_Password, Employees.t_PrivilegeSet, Employees.nb_ResetPassword, Employees.t_EmployeeEmail, Employees.t_UserName, nb_Locked, Employees.t_Department, Employees.nb_DeleteInvoice45Days, Employees.nb_DeleteInvoice, Employees.n_Extension, Employees.nb_SalesDoNotFilterHome, Employees.nb_CanViewEmployeeDocs, Employees.nb_CanDeleteEmployeeDocs');
        $this->db->from('Employees');
        $this->db->where('t_EmployeeEmail', $email);
        $this->db->where('nb_Inactive IS NULL', null, false);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1)
        {
            $result = $query->result();
            //print_r($result);
            if (validate_password($password, $result[0]->t_Password))
            {
              $data['kp_EmployeeID']  =   $result[0]->kp_EmployeeID;
              $data['t_EmployeeEmail']  =   $result[0]->t_EmployeeEmail;
              $data['t_UserName']  =   $result[0]->t_UserName;
              $data['nb_Locked'] = $result[0]->nb_Locked;
              $data['t_Department'] = $result[0]->t_Department;
              $data['nb_DeleteInvoice'] = $result[0]->nb_DeleteInvoice;
              $data['nb_DeleteInvoice45Days'] = $result[0]->nb_DeleteInvoice45Days;
              $data['n_Extension'] = $result[0]->n_Extension;
              $data['nb_SalesDoNotFilterHome'] = $result[0]->nb_SalesDoNotFilterHome;
              $data['nb_CanViewEmployeeDocs'] = $result[0]->nb_CanViewEmployeeDocs;
              $data['nb_CanDeleteEmployeeDocs'] = $result[0]->nb_CanDeleteEmployeeDocs;

                if($result[0]->nb_ResetPassword == "1") {
                  $data['t_PrivilegeSet'] = "Reset";
                } else {
                  $data['t_PrivilegeSet'] =   $result[0]->t_PrivilegeSet;
                }

                //return $result[0]->kp_EmployeeID;
                return $data;
            }

            return false;
        } else {
          $this->db->select('Employees.kp_EmployeeID, Employees.t_Password, Employees.t_PrivilegeSet, Employees.nb_ResetPassword, Employees.t_EmployeeEmail, Employees.t_UserName, nb_Locked, Employees.t_Department, Employees.nb_DeleteInvoice45Days, Employees.nb_DeleteInvoice, Employees.n_Extension, Employees.nb_SalesDoNotFilterHome, Employees.nb_CanViewEmployeeDocs, Employees.nb_CanDeleteEmployeeDocs');
              $this->db->from('Employees');
              $this->db->where('t_UserName', $email);
              $this->db->where('nb_Inactive IS NULL', null, false);
              $this->db->limit(1);
              $query = $this->db->get();
              if ($query->num_rows() == 1)
              {
                  $result = $query->result();
                  //print_r($result);
                  if (validate_password($password, $result[0]->t_Password))
                  {
                      $data['kp_EmployeeID']  =   $result[0]->kp_EmployeeID;
                      $data['t_EmployeeEmail']  =   $result[0]->t_EmployeeEmail;
                      $data['t_UserName']  =   $result[0]->t_UserName;
                      $data['nb_Locked'] = $result[0]->nb_Locked;
                      $data['t_Department'] = $result[0]->t_Department;
                      $data['nb_DeleteInvoice'] = $result[0]->nb_DeleteInvoice;
                      $data['nb_DeleteInvoice45Days'] = $result[0]->nb_DeleteInvoice45Days;
                      $data['n_Extension'] = $result[0]->n_Extension;
                      $data['nb_SalesDoNotFilterHome'] = $result[0]->nb_SalesDoNotFilterHome;
                      $data['nb_CanViewEmployeeDocs'] = $result[0]->nb_CanViewEmployeeDocs;
                      $data['nb_CanDeleteEmployeeDocs'] = $result[0]->nb_CanDeleteEmployeeDocs;


                      if($result[0]->nb_ResetPassword == "1") {
                        $data['t_PrivilegeSet'] = "Reset";
                      } else {
                        $data['t_PrivilegeSet'] =   $result[0]->t_PrivilegeSet;
                      }

                      //return $result[0]->kp_EmployeeID;
                      return $data;
                  }

                  return false;
              }
        }
        return false;

    }
     public function getEmployeeDataByID($id)
    {
        $this->db->select('Employees.kp_EmployeeID, Employees.t_Password, Employees.t_PrivilegeSet, Employees.nb_ResetPassword, Employees.t_EmployeeEmail, Employees.t_UserName, nb_Locked, Employees.t_Department, Employees.nb_DeleteInvoice45Days, Employees.nb_DeleteInvoice, Employees.n_Extension, Employees.nb_SalesDoNotFilterHome, Employees.nb_CanViewEmployeeDocs, Employees.nb_CanDeleteEmployeeDocs')
                 ->from('Employees')
                 ->where('Employees.kp_EmployeeID',$id)
                 ->where('nb_Inactive IS NULL', null, false);

        $query  = $this->db->get();

        return $query->row_array();
    }
    public function getEmployeeFromEmployeeEmail($email)
    {
        $this->db->select('Employees.kp_EmployeeID, Employees.t_Password, Employees.t_PrivilegeSet, Employees.nb_ResetPassword, Employees.t_EmployeeEmail, Employees.t_UserName, nb_Locked, Employees.t_Department, Employees.nb_DeleteInvoice45Days, Employees.nb_DeleteInvoice, Employees.nb_SalesDoNotFilterHome, Employees.nb_CanViewEmployeeDocs, Employees.nb_CanDeleteEmployeeDocs')
                 ->from('Employees')
                 ->where('Employees.t_EmployeeEmail',$email)
                 ->where('nb_Inactive IS NULL', null, false);

        $query  = $this->db->get();

        return $query->row_array();
    }

    public function getEmployeeFromEmployeeUserName($userName)
    {
        $this->db->select('Employees.kp_EmployeeID, Employees.t_Password, Employees.t_PrivilegeSet, Employees.nb_ResetPassword, Employees.t_EmployeeEmail, Employees.t_UserName, nb_Locked, Employees.t_Department, Employees.nb_DeleteInvoice45Days, Employees.nb_DeleteInvoice, Employees.nb_SalesDoNotFilterHome, Employees.nb_CanViewEmployeeDocs, Employees.nb_CanDeleteEmployeeDocs')
                 ->from('Employees')
                 ->where('Employees.t_UserName',$userName);

        $query  = $this->db->get();

        return $query->row_array();
    }
    public function getEmployeeFromEmployeeID($id) {
      $this->db->select('Employees.kp_EmployeeID, Employees.t_Password, Employees.t_PrivilegeSet, Employees.nb_ResetPassword, Employees.t_EmployeeEmail, Employees.t_UserName, nb_Locked, Employees.t_Department, Employees.nb_DeleteInvoice45Days, Employees.nb_DeleteInvoice, Employees.nb_SalesDoNotFilterHome, Employees.nb_CanViewEmployeeDocs, Employees.nb_CanDeleteEmployeeDocs')
      ->from('Employees')
      ->where('Employees.kp_EmployeeID',$id);

      $query  = $this->db->get();

      return $query->row_array();
    }

    public function updateEmployeePassword($password,$employeeID)
    {
      $flag = array();
      $flag['nb_ResetPassword'] = null;

      $this->db->update('Employees', $flag, array('kp_EmployeeID' => $employeeID));

        $data                    = array();
        // $time = -microtime(true);
        $data['t_Password']      = create_hash($password);
        // $time += microtime(true);

        $result = $this->db->update('Employees', $data, array('kp_EmployeeID'=>  $employeeID));

        if(!$result)
        {
            return $this->db->_error_message();
        }
        else
        {
            return $this->db->affected_rows();
            // return $time;

        }

    }
    public function resetEmployeePassword($id) {
      $data = array();
      $data['nb_ResetPassword'] = "1";

      $result = $this->db->update('Employees', $data, array('kp_EmployeeID' => $id));

      if(!$result) {
        return $this->db->_error_message();
      } else {
        return $this->db->affected_rows();
      }
    }
}

?>
