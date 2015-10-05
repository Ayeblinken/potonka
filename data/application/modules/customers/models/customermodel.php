<?php

class CustomerModel extends CI_Model
{
    //put your code here
    public function __construct()
    {
        parent::__construct();
        $this->termsApplicationUploadPath = realpath(APPPATH . '../../../../shopbot/Customers');
    }

    public function updateCustomerDocumentName($id, $oldName, $newName) {
      if(file_exists($this->termsApplicationUploadPath.'/'.$id.'/docs/'.$oldName)) {

        return rename($this->termsApplicationUploadPath.'/'.$id.'/docs/'.$oldName, $this->termsApplicationUploadPath.'/'.$id.'/docs/'.$newName);
      } else {
        return "File not Found";
      }
    }

    public function deleteCustomerDocument($id, $name) {
      if(file_exists($this->termsApplicationUploadPath.'/'.$id.'/docs/'.$name)) {

        return unlink($this->termsApplicationUploadPath.'/'.$id.'/docs/'.$name);
      } else {
        return "File not Found";
      }
    }

    public function customerDocumentUpload($id) {
      if(file_exists(APPPATH . '../../../../shopbot/Customers/.am_i_mounted')) {
          if(isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
              $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

              //checks and creates customer id folder
              if(!is_dir($this->termsApplicationUploadPath.'/'.$id)) {
                if(!mkdir($this->termsApplicationUploadPath.'/'.$id,0777,TRUE)) {
                  die("Failed to create customer id folder");
                }
              }

              //checks and creates customer doc folder
              if(!is_dir($this->termsApplicationUploadPath.'/'.$id.'/docs')) {
                if(!mkdir($this->termsApplicationUploadPath.'/'.$id.'/docs',0777,TRUE)) {
                  die("Failed to create customer docs folder");
                }
              }



              $tempPath   = $_FILES[ 'file' ][ 'tmp_name' ];

              $fileName   = str_replace(" ", "_", $_FILES['file']['name']);

              $path_parts = pathinfo($fileName);

              $uploadPath = $this->termsApplicationUploadPath.'/'.$id.'/docs/'.$fileName;


              if(move_uploaded_file($tempPath, $uploadPath)) {
                  chmod($uploadPath, 0777);

                  return $fileName;
              } else {
                  $msg = "upload failed";
                  return $msg;
              }
          }

      } else {
          $msg = "am i mounted file not found";
          return $msg;
      }
    }

    public function termsAuthUpload($id) {
      $allowed = array('pdf');
      $msg     = "";

      if(file_exists(APPPATH . '../../../../shopbot/Customers/.am_i_mounted')) {
          if(isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
              $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

              if(!in_array(strtolower($extension), $allowed)) {
                echo '{"status":"error file extension"}';
                exit;
              }

              //checks and creates year and month-day folders
              if(!is_dir($this->termsApplicationUploadPath.'/'.$id)) {
                if(!mkdir($this->termsApplicationUploadPath.'/'.$id,0777,TRUE)) {
                  die("Failed to create customer id folder");
                }

              }



              $tempPath   = $_FILES[ 'file' ][ 'tmp_name' ];

              $fileName   = str_replace(" ", "_", $_FILES['file']['name']);

              $path_parts = pathinfo($fileName);

              $fileName = $id . '_auth' . '.' . $path_parts['extension'];



              $uploadPath = $this->termsApplicationUploadPath.'/'.$id.'/'.$fileName;


              if(move_uploaded_file($tempPath, $uploadPath)) {
                  chmod($uploadPath, 0777);

                  $data = array(
                     't_TermsCardAuthFileName'=> $fileName
                  );

                  $this->db->where('kp_CustomerID', $id);
                  $this->db->update('Customers',$data);

                  $quality                          = 90;
                  $res                              = '300x300';
                  $exportPath                       = $this->termsApplicationUploadPath.'/'.$id.'/'.$id.'_auth.jpg';
                  exec("'gs' '-dNOPAUSE' '-sDEVICE=jpeg' '-dUseCIEColor' '-dTextAlphaBits=4' '-dGraphicsAlphaBits=4' '-o$exportPath' '-g$res' '-dPDFFitPage' '-dJPEGQ=$quality' '$uploadPath' 2>&1",$output);

                  return $output;
              } else {
                  $msg = "upload failed";
                  return $msg;
              }
          }

      } else {
          $msg = "am i mounted file not found";
          return $msg;
      }
    }

    public function termsTaxUpload($id) {
      $allowed = array('pdf');
      $msg     = "";

      if(file_exists(APPPATH . '../../../../shopbot/Customers/.am_i_mounted')) {
          if(isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
              $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

              if(!in_array(strtolower($extension), $allowed)) {
                echo '{"status":"error file extension"}';
                exit;
              }

              //checks and creates year and month-day folders
              if(!is_dir($this->termsApplicationUploadPath.'/'.$id)) {
                if(!mkdir($this->termsApplicationUploadPath.'/'.$id,0777,TRUE)) {
                  die("Failed to create customer id folder");
                }

              }



              $tempPath   = $_FILES[ 'file' ][ 'tmp_name' ];

              $fileName   = str_replace(" ", "_", $_FILES['file']['name']);

              $path_parts = pathinfo($fileName);

              $fileName = $id . '_tax' . '.' . $path_parts['extension'];



              $uploadPath = $this->termsApplicationUploadPath.'/'.$id.'/'.$fileName;


              if(move_uploaded_file($tempPath, $uploadPath)) {
                  chmod($uploadPath, 0777);

                  $data = array(
                     't_CustResellerTaxExemptFilename'=> $fileName
                  );

                  $this->db->where('kp_CustomerID', $id);
                  $this->db->update('Customers',$data);

                  $quality                          = 90;
                  $res                              = '300x300';
                  $exportPath                       = $this->termsApplicationUploadPath.'/'.$id.'/'.$id.'_tax.jpg';
                  exec("'gs' '-dNOPAUSE' '-sDEVICE=jpeg' '-dUseCIEColor' '-dTextAlphaBits=4' '-dGraphicsAlphaBits=4' '-o$exportPath' '-g$res' '-dPDFFitPage' '-dJPEGQ=$quality' '$uploadPath'",$output);

                  return $uploadPath;
              } else {
                  $msg = "upload failed";
                  return $msg;
              }
          }

      } else {
          $msg = "am i mounted file not found";
          return $msg;
      }
    }

    public function termsReferencesUpload($id) {
      $allowed = array('pdf');
      $msg     = "";

      if(file_exists(APPPATH . '../../../../shopbot/Customers/.am_i_mounted')) {
          if(isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
              $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

              if(!in_array(strtolower($extension), $allowed)) {
                echo '{"status":"error file extension"}';
                exit;
              }

              //checks and creates year and month-day folders
              if(!is_dir($this->termsApplicationUploadPath.'/'.$id)) {
                if(!mkdir($this->termsApplicationUploadPath.'/'.$id,0777,TRUE)) {
                  die("Failed to create customer id folder");
                }

              }



              $tempPath   = $_FILES[ 'file' ][ 'tmp_name' ];

              $fileName   = str_replace(" ", "_", $_FILES['file']['name']);

              $path_parts = pathinfo($fileName);

              $fileName = $id . '_ref' . '.' . $path_parts['extension'];



              $uploadPath = $this->termsApplicationUploadPath.'/'.$id.'/'.$fileName;


              if(move_uploaded_file($tempPath, $uploadPath)) {
                  chmod($uploadPath, 0777);

                  $data = array(
                     't_TermsCreditReportFileName'=> $fileName
                  );

                  $this->db->where('kp_CustomerID', $id);
                  $this->db->update('Customers',$data);

                  $quality                          = 90;
                  $res                              = '300x300';
                  $exportPath                       = $this->termsApplicationUploadPath.'/'.$id.'/'.$id.'_ref.jpg';
                  exec("'gs' '-dNOPAUSE' '-sDEVICE=jpeg' '-dUseCIEColor' '-dTextAlphaBits=4' '-dGraphicsAlphaBits=4' '-o$exportPath' '-g$res' '-dPDFFitPage' '-dJPEGQ=$quality' '$uploadPath'",$output);

                  return $uploadPath;
              } else {
                  $msg = "upload failed";
                  return $msg;
              }
          }

      } else {
          $msg = "am i mounted file not found";
          return $msg;
      }
    }

    public function termsApplicationUpload($id) {
      $allowed = array('pdf');
      $msg     = "";

      if(file_exists(APPPATH . '../../../../shopbot/Customers/.am_i_mounted')) {
          if(isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
              $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

              if(!in_array(strtolower($extension), $allowed)) {
                echo '{"status":"error file extension"}';
                exit;
              }

              //checks and creates year and month-day folders
              if(!is_dir($this->termsApplicationUploadPath.'/'.$id)) {
                if(!mkdir($this->termsApplicationUploadPath.'/'.$id,0777,TRUE)) {
                  die("Failed to create customer id folder");
                }

              }



              $tempPath   = $_FILES[ 'file' ][ 'tmp_name' ];

              $fileName   = str_replace(" ", "_", $_FILES['file']['name']);

              $path_parts = pathinfo($fileName);

              $fileName = $id . '.' . $path_parts['extension'];



              $uploadPath = $this->termsApplicationUploadPath.'/'.$id.'/'.$fileName;


              if(move_uploaded_file($tempPath, $uploadPath)) {
                  chmod($uploadPath, 0777);

                  $data = array(
                     't_TermsAppFileName'=> $fileName
                  );

                  $this->db->where('kp_CustomerID', $id);
                  $this->db->update('Customers',$data);

                  $quality                          = 90;
                  $res                              = '300x300';
                  $exportPath                       = $this->termsApplicationUploadPath.'/'.$id.'/'.$id.'.jpg';
                  exec("'gs' '-dNOPAUSE' '-sDEVICE=jpeg' '-dUseCIEColor' '-dTextAlphaBits=4' '-dGraphicsAlphaBits=4' '-o$exportPath' '-g$res' '-dPDFFitPage' '-dJPEGQ=$quality' '$uploadPath'",$output);

                  return $uploadPath;
              } else {
                  $msg = "upload failed";
                  return $msg;
              }
          }

      } else {
          $msg = "am i mounted file not found";
          return $msg;
      }
    }

    public function getCustomerSalesApprovalEmailAddresses() {
      $query = $this->db->query("SELECT GROUP_CONCAT(t_EmployeeEmail SEPARATOR ', ') as emails
                                  FROM Employees
                                  WHERE nb_NewCustomerSalesApproval = 1 AND (nb_Inactive IS NULL OR nb_Inactive = 0)");

      return $query->row();
    }

    public function getCustomerAccountingApprovalEmailAddresses() {
      $query = $this->db->query("SELECT GROUP_CONCAT(t_EmployeeEmail SEPARATOR ', ') as emails
                                  FROM Employees
                                  WHERE nb_NewCustomerAccountingApproval = 1 AND (nb_Inactive IS NULL OR nb_Inactive = 0)");

      return $query->row();
    }

    public function getCustomerTermsApprovalEmailAddresses() {
      $query = $this->db->query("SELECT GROUP_CONCAT(t_EmployeeEmail SEPARATOR ', ') as emails
                                  FROM Employees
                                  WHERE nb_CustomerApproveTerms = 1 AND (nb_Inactive IS NULL OR nb_Inactive = 0)");

      return $query->row();
    }

    public function getSalesEmailFromCustomerID($id) {
      $query = $this->db->query("SELECT Employees.t_EmployeeEmail
                                  FROM Customers
                                  LEFT OUTER JOIN Employees ON Customers.kf_EmployeeID_Sales = Employees.kp_EmployeeID
                                  WHERE Customers.kp_CustomerID = $id");

      return $query->row();
    }

    public function insertCustomerData($data) {
      $this->db->insert("Customers", $data);
      return $this->db->insert_id();
    }

    public function getInProcessCustomerTableData() {
      $query = $this->db->query("SELECT Customers.kp_CustomerID,
                                      	Customers.t_CustCompany,
                                      	Customers.t_CustType,
                                      	Customers.t_Status,
                                      	Customers.t_CustTerms,
                                      	Customers.nb_Overlimit,
                                      	Customers.nb_AccountLocked,
                                      	Employees.t_UserName,
                                        DATE_FORMAT(Customers.d_LastOrder, '%c-%e-%Y') AS d_LastOrder,
                                        quickbooks_sql2.customer.Status,
                                        quickbooks_sql2.error_table.Error_Desc
                                FROM Customers LEFT OUTER JOIN Employees ON Customers.kf_EmployeeID_Sales = Employees.kp_EmployeeID
                                LEFT OUTER JOIN quickbooks_sql2.customer ON quickbooks_sql2.customer.ListID = Customers.t_QBCustID
                                LEFT JOIN quickbooks_sql2.error_table ON quickbooks_sql2.customer.ListID = quickbooks_sql2.error_table.IDKEY
                                WHERE (ISNULL(Customers.nb_Inactive) or Customers.nb_Inactive = 0) AND (quickbooks_sql2.customer.Status = 'Update' OR quickbooks_sql2.customer.Status = 'Delete')
                                UNION
                                SELECT Customers.kp_CustomerID,
                                      	Customers.t_CustCompany,
                                      	Customers.t_CustType,
                                      	Customers.t_Status,
                                      	Customers.t_CustTerms,
                                      	Customers.nb_Overlimit,
                                      	Customers.nb_AccountLocked,
                                      	Employees.t_UserName,
                                        DATE_FORMAT(Customers.d_LastOrder, '%c-%e-%Y') AS d_LastOrder,
                                        quickbooks_sql2.customer.Status,
                                        quickbooks_sql2.error_table.Error_Desc
                                FROM Customers LEFT OUTER JOIN Employees ON Customers.kf_EmployeeID_Sales = Employees.kp_EmployeeID
                                LEFT OUTER JOIN quickbooks_sql2.customer ON quickbooks_sql2.customer.ListID = Customers.kp_CustomerID
                                LEFT JOIN quickbooks_sql2.error_table ON quickbooks_sql2.customer.ListID = quickbooks_sql2.error_table.IDKEY
                                WHERE (ISNULL(Customers.nb_Inactive) or Customers.nb_Inactive = 0) AND quickbooks_sql2.customer.Status = 'Add'");

      return $query->result_array();
    }

    public function getTermsCustomerTableData() {
      $query = $this->db->query("SELECT Customers.kp_CustomerID,
                                      	Customers.t_CustCompany,
                                      	Customers.t_CustType,
                                      	Customers.t_Status,
                                      	Customers.t_CustTerms,
                                        Customers.t_TermsTypeApplyingFor,
                                        Customers.t_TermsApprovedStatus,
                                        Customers.ts_TermsCustomerApply,
                                      	Employees.t_UserName
                                FROM Customers LEFT OUTER JOIN Employees ON Customers.kf_EmployeeID_Sales = Employees.kp_EmployeeID
                                WHERE (ISNULL(Customers.nb_Inactive) or Customers.nb_Inactive = 0) AND nb_TermsCustomerApply = '1' && t_TermsApprovedStatus != 'Approved'");

      return $query->result_array();
    }

    public function getRequestedCustomerTableData() {
      $query = $this->db->query("SELECT Customers.kp_CustomerID,
                                      	Customers.t_CustCompany,
                                      	Customers.t_CustType,
                                      	Customers.t_Status,
                                      	Customers.t_CustTerms,
                                        Customers.t_NewCustomerSalesApproved,
                                        Customers.t_NewCustomerAccountingApproved,
                                      	Employees.t_UserName
                                FROM Customers LEFT OUTER JOIN Employees ON Customers.kf_EmployeeID_Sales = Employees.kp_EmployeeID
                                WHERE (ISNULL(Customers.nb_Inactive) or Customers.nb_Inactive = 0) AND t_NewCustomerStatus = 'Requested'");

      return $query->result_array();
    }

    public function getCustomerTableData() {
      $query = $this->db->query("SELECT Customers.kp_CustomerID,
                                      	Customers.t_CustCompany,
                                      	Customers.t_CustType,
                                      	Customers.t_Status,
                                      	Customers.t_CustTerms,
                                      	Customers.nb_Overlimit,
                                      	Customers.nb_AccountLocked,
                                      	Employees.t_UserName,
                                        DATE_FORMAT(Customers.d_LastOrder, '%c-%e-%Y') AS d_LastOrder
                                FROM Customers LEFT OUTER JOIN Employees ON Customers.kf_EmployeeID_Sales = Employees.kp_EmployeeID
                                WHERE (ISNULL(Customers.nb_Inactive) or Customers.nb_Inactive = 0) AND t_NewCustomerStatus = 'Approved'");

      return $query->result_array();
    }

    public function getCustomerByName($customerName)
    {
        $query = $this->db->query("SELECT Customers.t_CustCompany FROM Customers
                                   WHERE Customers.t_CustCompany =\"$customerName\"");

        return $query->result_array();

    }
    public function getActiveCustomerNames()
    {
        $query = $this->db->query("SELECT Customers.kp_CustomerID, Customers.t_CustCompany FROM Customers
                                   WHERE Customers.nb_Inactive is null or Customers.nb_Inactive = 0
                                   ORDER BY Customers.t_CustCompany");

        return $query->result_array();

    }
    public function getCustomerIDCompanyQBCityQBStateData()
    {
        $query = $this->db->query('SELECT Customers.kp_CustomerID,
                                   ifnull(Customers.t_CustCompany,"No Data") as t_CustCompany,
                                   ifnull(Customers.t_QBCity,"No Data") as t_QBCity,
                                   ifnull(Customers.t_QBState,"No Data") as t_QBState
                                   FROM Customers
                                   Where ISNULL(nb_Inactive) or nb_Inactive = 0
                                   ORDER BY Customers.t_CustCompany
                                   ');

        return  $query->result_array();

    }
    public function getCustomerIDCompanyQBCityQBStateDataTable()
    {
       $staticWhere =  "(Customers.nb_Inactive is null || Customers.nb_Inactive = 0) ";

        $this->datatables->select('Customers.kp_CustomerID,
                                   ifnull(Customers.t_CustCompany,"No Data") as t_CustCompany,
                                   ifnull(Customers.t_QBCity,"No Data") as t_QBCity,
                                   ifnull(Customers.t_QBState,"No Data") as t_QBState,
                                   ifnull(Customers.kf_EmployeeID_Sales,"No Data") as EmployeeID_Sales',false)

                         ->from('Customers')
                         ->where($staticWhere);

        return $this->datatables->generate();
    }
    public function getCustomerID($orderID)
    {
        $query = $this->db->query("Select kf_CustomerID
                        from basetables2.Orders
                        WHERE kp_OrderID = '$orderID'");

        return  $query->row();

    }
    public function getCustomerDataByID($customerID)
    {
        $query = $this->db->get_where('Customers', array('kp_CustomerID' => $customerID));

        return $query->row_array();
    }
    public function getCustomerDataFromQuickBookCustID($qb_CustID)
    {
        $query = $this->db->get_where('Customers', array('t_QBCustID' => $qb_CustID));

        return $query->row_array();

    }
    public function updateCustomerTable($customerID,$data)
    {
        $this->db->where('kp_CustomerID', $customerID);
        $this->db->update('Customers', $data);
    }
    public function updateCustomerTableFromQuickBookCustID($qb_CustID,$data)
    {

       $result = $this->db->update('Customers', $data, array('t_QBCustID'=>  $qb_CustID));
       if(!$result)
       {
           return $this->db->_error_message();
       }
       else
       {
           return $this->db->affected_rows();

       }

    }
    public function getCustAcctInfo($qb_CustID)
    {
        $query = $this->db->query("SELECT basetables2.Customers.t_CustTerms,basetables2.Customers.n_PastDueNoOrders,
                                    quickbooks_sql2.invoice.CustomerRef_ListID,
                                    basetables2.Customers.nb_CreditHold,
                                    basetables2.Customers.nb_Overlimit,
                                    quickbooks_sql2.invoice.DueDate,if(current_date > DATE_ADD(quickbooks_sql2.invoice.DueDate,INTERVAL if(basetables2.Customers.t_CustTerms=\"Net 30\",basetables2.Customers.n_PastDueNoOrders,if(basetables2.Customers.t_CustTerms=\"Net 45\",basetables2.Customers.n_PastDueNoOrders,0)) day),\"accountLocked\",\"noLock\" ) as acctLock
                                    FROM quickbooks_sql2.invoice
                                    left join basetables2.Customers
                                    on quickbooks_sql2.invoice.CustomerRef_ListID = basetables2.Customers.t_QBCustID
                                    WHERE CustomerRef_ListID = \"$qb_CustID\" AND IsPaid = \"false\"
                                    ORDER BY quickbooks_sql2.invoice.DueDate asc limit 1");
        return $query->row_array();

    }


}
?>
