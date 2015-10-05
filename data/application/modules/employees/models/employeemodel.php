<?php

class employeeModel extends CI_Model {

    public function __construct() {
        parent::__construct();

        //Server image path
        // $this->loadImagePath   = 'https://'.$_SERVER['SERVER_NAME'].'/images/Employees/';

        $this->imageUploadPath = realpath(APPPATH . '../../../../shopbot');

        $this->load->library('image_lib');
    }

    public function newEmployeeDocumentUpload() {
      if(isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        $fullPath = "../../../shopbot/Employees/_masterdocs";

        $tempPath   = $_FILES[ 'file' ][ 'tmp_name' ];

        $fileName   = str_replace(" ", "_", $_FILES['file']['name']);


        $path_parts = pathinfo($fileName);
        $fileNameNoExt = $path_parts['filename'];
        $extension = ".".$path_parts['extension'];

        if(file_exists($fullPath.'/'.$fileName)) {
          $looking = true;
          $n = 1;
          $s = "_01".$extension;
          while($looking && $n < 100) {
            if(!file_exists($fullPath.'/'.$fileNameNoExt.$s)) {
              $looking = false;
              $fileName = $fileNameNoExt.$s;
            } else {
              $n++;
              if($n < 10) {
                $s = "_0".$n.$extension;
              } else {
                $s = "_".$n.$extension;
              }
            }
          }

        }




        $uploadPath = $fullPath.DIRECTORY_SEPARATOR.$fileName;


        if(move_uploaded_file($tempPath, $uploadPath)) {

          chmod($uploadPath, 0777);

          $currentDateTime = date("Y-m-d H:i:s", time());

          $returnData = array(
            'path' => $uploadPath
          );

          return $returnData;

        } else {
          $msg = "upload failed";
          return $msg;
        }
      }
    }

    public function deleteNewEmployeeDocument($name) {
      $fullPath = "../../../shopbot/Employees/_masterdocs";
      if(file_exists($fullPath.'/'.$name)) {
        return unlink($fullPath.'/'.$name);
      } else {
        return "File Not Found: " . $fullPath.'/'.$name;
      }
    }

    public function updateEmployeeDocumentName($id, $oldName, $newName) {
      if(file_exists($this->imageUploadPath.'/Employees/'.$id.'/docs/'.$oldName)) {

        return rename($this->imageUploadPath.'/Employees/'.$id.'/docs/'.$oldName, $this->imageUploadPath.'/Employees/'.$id.'/docs/'.$newName);
      } else {
        return "File not Found";
      }
    }

    public function employeeDocumentUpload($employeeID) {
      if(isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        // checks and creates Employees Folder
        if(!is_dir($this->imageUploadPath.DIRECTORY_SEPARATOR.'Employees')) {

          if(!mkdir($this->imageUploadPath.DIRECTORY_SEPARATOR.'Employees',0777,TRUE)) {
            die("Failed to create Employee Folder");
          } else {
            chmod($this->imageUploadPath.DIRECTORY_SEPARATOR.'Employees', 0777);
          }

        }
        $path = $this->imageUploadPath.DIRECTORY_SEPARATOR.'Employees';

        //CHECK and create ID Folder
        if(!is_dir($path.DIRECTORY_SEPARATOR.$employeeID)) {

          if(!mkdir($path.DIRECTORY_SEPARATOR.$employeeID,0777,TRUE)) {
            die("Failed to create Employee ID Folder");
          } else {
            chmod($path.DIRECTORY_SEPARATOR.$employeeID, 0777);
          }

        }

        //CHECK and create docs Folder
        if(!is_dir($path.DIRECTORY_SEPARATOR.$employeeID.DIRECTORY_SEPARATOR.'docs')) {

          if(!mkdir($path.DIRECTORY_SEPARATOR.$employeeID.DIRECTORY_SEPARATOR.'docs',0777,TRUE)) {
            die("Failed to create Employee ID Folder");
          } else {
            chmod($path.DIRECTORY_SEPARATOR.$employeeID.DIRECTORY_SEPARATOR.'docs', 0777);
          }

        }

        $fullPath = $path.DIRECTORY_SEPARATOR.$employeeID.DIRECTORY_SEPARATOR.'docs';



        $tempPath   = $_FILES[ 'file' ][ 'tmp_name' ];

        $fileName   = str_replace(" ", "_", $_FILES['file']['name']);


        $path_parts = pathinfo($fileName);
        $fileNameNoExt = $path_parts['filename'];
        $extension = ".".$path_parts['extension'];

        if(file_exists($fullPath.'/'.$fileName)) {
          $looking = true;
          $n = 1;
          $s = "_01".$extension;
          while($looking && $n < 100) {
            if(!file_exists($fullPath.'/'.$fileNameNoExt.$s)) {
              $looking = false;
              $fileName = $fileNameNoExt.$s;
            } else {
              $n++;
              if($n < 10) {
                $s = "_0".$n.$extension;
              } else {
                $s = "_".$n.$extension;
              }
            }
          }

        }




        $uploadPath = $fullPath.DIRECTORY_SEPARATOR.$fileName;


        if(move_uploaded_file($tempPath, $uploadPath)) {

          chmod($uploadPath, 0777);

          $currentDateTime = date("Y-m-d H:i:s", time());

          $data = array(
            'kf_EmployeeID' => $employeeID,
            't_filename' => $fileName,
            'ts_DateCreated' => $currentDateTime,
            't_Ext' => $path_parts['extension']
          );

          $this->db->insert('Documents', $data);
          $newID = $this->db->insert_id();

          $returnData = array(
            'id' => $newID,
            'path' => $uploadPath
          );

          return $returnData;

        } else {
          $msg = "upload failed";
          return $msg;
        }
      }
    }

    public function deleteEmployeeDocument($id, $name) {
      if(file_exists($this->imageUploadPath.'/Employees'.'/'.$id.'/docs/'.$name)) {

        return unlink($this->imageUploadPath.'/Employees'.'/'.$id.'/docs/'.$name);
      } else {
        return "File not Found";
      }
    }

    public function getEmpInProcessList() {
      $query = $this->db->query("SELECT Employees.*, quickbooks_sql2.employee.Status, quickbooks_sql2.error_table.Error_Desc
                                  FROM Employees
                                  LEFT JOIN quickbooks_sql2.employee ON quickbooks_sql2.employee.ListID = Employees.t_QBEmployeeListID
                                  LEFT JOIN quickbooks_sql2.error_table ON quickbooks_sql2.employee.ListID = quickbooks_sql2.error_table.IDKEY
                                  WHERE (quickbooks_sql2.employee.Status = 'ADD' OR quickbooks_sql2.employee.Status = 'UPDATE' OR quickbooks_sql2.employee.Status = 'DELETE')");

      return $query->result_array();
    }

    public function getAllEmployees()
    {
      $query = $this->db->get('Employees');

      return $query->result_array();

    }

    public function verify_user($email, $password) {
        $query = $this
                ->db
                ->where('t_EmployeeEmail', $email)
                ->where('t_Password', sha1($password))
                ->limit(1)
                ->get('Employees');

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    public function employeeUserName() {
        $where = "nb_Inactive = '0' or isnull(nb_Inactive)";
        //SELECT t_UserName FROM Employees
        //WHERE nb_Inactive = 0 or isnull(nb_Inactive)
        $this->db
                ->select('t_UserName')
                ->from('Employees')
                ->where($where);

        $query = $this->db->get();

        return $query->result_array();
    }

    public function getEmployeeEmailAddress($username) {
        $where = $username;
        //SELECT t_UserName FROM Employees
        //WHERE nb_Inactive = 0 or isnull(nb_Inactive)
        $this->db
                ->select('t_EmployeeEmail')
                ->from('Employees')
                ->where('t_UserName', $where, null, false);

        $query = $this->db->get();

        return $query->row_array();
    }

    public function getAllEmployeeRowsDatatables() {
        $this->datatables->select('Employees.kp_EmployeeID,
                                    Employees.t_UserName,
                                    Employees.t_EmployeeEmail,
                                    Employees.n_Extension,
                                    Employees.t_Department,
                                    Employees.n_Shift,
                                    Employees.t_Title,
                                    Employees.t_PrivilegeSet,
                                    Employees.nb_OverrideCreditHold,
                                    Employees.t_QBSalesRepListID,
                                    Employees.nb_DontIncludeInReports,
                                    Employees.kf_CSRLinkedToSalesperson,
                                    Employees.nb_Inactive', false)
                //->edit_column('Name','<a href="#myModal" </a>')
                //->edit_column('Name','<a href="orderItemUpSideFrm/read/$1" target="_blank">$2</a>','OrderItems.kp_OrderItemID,IDNum')
                //->add_column('Picture','<img src="../../images/Orders/'.$yearOrder.'/'.$monthOrder.'/'.'$1/$2/$3 " height="200" width="250">','OrderItems.kf_OrderID,OrderItems.kp_OrderItemID,OrderItems.t_OrderItemImage')
                ->from('Employees')
                ->where('nb_Inactive', Null)
                ->or_where('nb_Inactive', 0);
        return $this->datatables->generate();
    }

    public function getAllEmployeeRowsDatatablesRestFullApi()
    {
        $where = "nb_Inactive is null or nb_Inactive = 0";

        $this->db
            ->select('Employees.kp_EmployeeID,
                                Employees.t_UserName,
                                Employees.t_EmployeeEmail,
                                Employees.n_Extension,
                                Employees.t_Department,
                                Employees.n_Shift,
                                Employees.t_Title,
                                Employees.t_PrivilegeSet,
                                Employees.nb_OverrideCreditHold,
                                Employees.t_QBSalesRepListID,
                                Employees.nb_DontIncludeInReports,
                                Employees.kf_CSRLinkedToSalesperson,
                                Employees.nb_Inactive',false)
            ->from('Employees')
            ->where($where);

        $query = $this->db->get();

        return $query->result_array();
    }
    public function getEmployeeUserNameEmployeeID()
    {
        $this->db->select('kp_EmployeeID,t_UserName')
                 ->from('Employees')
                 ->where('nb_Inactive', Null)
                 ->or_where('nb_Inactive', 0);

        $query = $this->db->get();

        return $query->result_array();
    }
    public function employeeCustomerService()
    {
        $where = "t_Department = 'Customer Service' and (nb_Inactive = '0' or isnull(nb_Inactive))";
        $this->db
                ->select('kp_EmployeeID,t_UserName')
                ->from('Employees')
                ->where($where);

        $query = $this->db->get();

        return $query->result_array();
    }
    public function updateEmployeeData($data,$employeeID)
    {
        $result = $this->db->update('Employees', $data, array('kp_EmployeeID'=>  $employeeID));

        if(!$result)
        {
            return $this->db->_error_message();
        }
        else
        {
            return $this->db->affected_rows();

        }

    }
    public function doEmployeeImgCustomUpload($employeeID) {
      $allowed = array('jpeg','jpg','svg','png');
      $msg     = "";

      $mask=umask(0);

      if(isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        if(!in_array(strtolower($extension), $allowed)) {
          echo '{"status":"error file extension"}';
          exit;
        }

        // checks and creates Employees Folder
        if(!is_dir($this->imageUploadPath.DIRECTORY_SEPARATOR.'Employees')) {

          if(!mkdir($this->imageUploadPath.DIRECTORY_SEPARATOR.'Employees',0777,TRUE)) {
            die("Failed to create Employee Folder");
          } else {
            chmod($this->imageUploadPath.DIRECTORY_SEPARATOR.'Employees', 0777);
          }

        }
        $path = $this->imageUploadPath.DIRECTORY_SEPARATOR.'Employees';

        //CHECK and create ID Folder
        if(!is_dir($path.DIRECTORY_SEPARATOR.$employeeID)) {

          if(!mkdir($path.DIRECTORY_SEPARATOR.$employeeID,0777,TRUE)) {
            die("Failed to create Employee ID Folder");
          } else {
            chmod($path.DIRECTORY_SEPARATOR.$employeeID, 0777);
          }

        }

        $fullPath = $path.DIRECTORY_SEPARATOR.$employeeID;

        $tempPath   = $_FILES[ 'file' ][ 'tmp_name' ];

        $fileName   = str_replace(" ", "_", $_FILES['file']['name']);

        $uploadPath = $fullPath.DIRECTORY_SEPARATOR.$fileName;


        if(move_uploaded_file($tempPath, $uploadPath)) {

          chmod($uploadPath, 0777);

          //update invtZoneData
          $data['t_EmployeeImage']  = $fileName;

          $result              = $this->updateEmployeeData($data, $employeeID);

          umask($mask);
          return $uploadPath;
        } else {
          $msg = "upload failed";
          umask($mask);
          return $msg;
        }
      }
    }


    public function insertEmployeeData($data)
    {
        $this->db->set($data,false);

        $this->db->insert('Employees');

        return $this->db->insert_id();
    }
}

?>
