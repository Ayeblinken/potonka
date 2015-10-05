<?php

class PrepressModel extends CI_Model {
    public function __construct() {
        parent::__construct();

        $this->imageUploadPath = realpath(APPPATH . '../../../images/Orders');
        $this->customerArtImageUploadPath = realpath(APPPATH . '../../../images/CustomerArt');
    }

    public function updatePrintAll($data) {
      $updateData = array('nb_NeedsPrinted' => $data['nb_NeedsPrinted']);
      $id = $data['kp_TicketID'];
      $this->db->where('kp_TicketID', $id);
      $this->db->update('PrepressTicket', $updateData);
    }

    public function getOrderItemFromOrderDashNum($orderID, $dashNum) {
      $query = $this->db->query("SELECT kp_OrderItemID, n_Quantity FROM OrderItems WHERE kf_OrderID = $orderID AND n_DashNum = $dashNum");

      return $query->row_array();
    }

    public function getEmployeeIDFromUserName($name) {
      $query = $this->db->query("SELECT kp_EmployeeID FROM Employees WHERE t_UserName = '$name'");

      return $query->row_array();
    }

    public function getCustomerArtTableData($date) {
      $query = $this->db->query("SELECT PrepressTicket.*, Employees.t_UserName, SalesEmployee.t_UserName as t_SalesPerson
                                  FROM basetables2.PrepressTicket
                                  LEFT JOIN Employees ON basetables2.PrepressTicket.kf_PrepressID = Employees.kp_EmployeeID
                                  LEFT JOIN Employees as SalesEmployee ON basetables2.PrepressTicket.kf_SalesID = SalesEmployee.kp_EmployeeID
                                  WHERE PrepressTicket.t_TicketType = 'Customer Art' AND DATE(ts_TimeCreate) = '$date'");




      return $query->result_array();
    }

    public function prepressCustomerArtTicketImageUpload($ticketID, $year, $monthDay) {
      $allowed = array('jpeg','jpg','svg','png');
      $msg     = "";

      if(file_exists(APPPATH . '../../../images/.am_i_mounted')) {
          if(isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
              $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

              if(!in_array(strtolower($extension), $allowed)) {
                echo '{"status":"error file extension"}';
                exit;
              }

              // checks and creates CustomerArt Folder
              if(!is_dir($this->customerArtImageUploadPath)) {
                if(!mkdir($this->customerArtImageUploadPath,0777,TRUE)) {
                  die("Failed to create CustomerArt Folder");
                }
              }

              //checks and creates year and month-day folders
              if(!is_dir($this->customerArtImageUploadPath.'/'.$year.'/'.$monthDay)) {
                if(!mkdir($this->customerArtImageUploadPath.'/'.$year.'/'.$monthDay,0777,TRUE)) {
                  die("Failed to create Year and Month-Day Folders");
                }

              }



              $tempPath   = $_FILES[ 'file' ][ 'tmp_name' ];

              $fileName   = str_replace(" ", "_", $_FILES['file']['name']);

              $path_parts = pathinfo($fileName);

              $fileName = $ticketID . '.' . $path_parts['extension'];



              $uploadPath = $this->customerArtImageUploadPath.'/'.$year.'/'.$monthDay.'/'.$fileName;


              if(move_uploaded_file($tempPath, $uploadPath)) {
                  chmod($uploadPath, 0777);

                  $data = array(
                     't_ImageName'=> $fileName
                  );

                  $this->db->where('kp_TicketID', $ticketID);
                  $this->db->update('PrepressTicket',$data);

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

    public function prepressTicketImageUpload($ticketID,$orderID,$yearOrder,$monthOrder) {
      // return array('error' => 'Feature is incomplete');

      $path = $this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID;

      $allowed = array('jpeg','jpg','svg','png');
      $msg     = "";

      if(file_exists(APPPATH . '../../../images/.am_i_mounted')) {
          if(isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
              $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

              if(!in_array(strtolower($extension), $allowed)) {
                echo '{"status":"error file extension"}';
                exit;
              }

              // checks and creates Month and Year Folder
              if(!is_dir($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder)) {
                if(!mkdir($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder,0777,TRUE)) {
                  die("Failed to create Year and Month Folders");
                }

              }


              // checks and creates the Order Folder
              if(!is_dir($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID)) {
                if(!mkdir($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID,0777,TRUE)) {
                  die('Failed to create Order and other folders...');
                } else {
                  chmod($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID, 0777);
                }
              }

              // checks and creates the Prepress Folder
              if(!is_dir($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/prepress')) {
                if(!mkdir($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/prepress',0777,TRUE)) {
                  die('Failed to create Prepress and other folders...');
                } else {
                  chmod($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/prepress', 0777);
                }
              }


              $fullPath = $path.'/prepress';

              $tempPath   = $_FILES[ 'file' ][ 'tmp_name' ];

              $fileName   = str_replace(" ", "_", $_FILES['file']['name']);

              $path_parts = pathinfo($fileName);
              // $fileNameNoExt = $path_parts['filename'];
              // $extension = ".".$path_parts['extension'];

              $fileName = $ticketID . '.' . $path_parts['extension'];



              $uploadPath = $fullPath.'/'.$fileName;


              if(move_uploaded_file($tempPath, $uploadPath)) {
                  chmod($uploadPath, 0777);

                  $data = array(
                     't_ImageName'=> $fileName
                  );

                  $this->db->where('kp_TicketID', $ticketID);
                  $this->db->update('PrepressTicket',$data);

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

    public function insertPrepressTicketData($data) {
      $this->db->insert("PrepressTicket", $data);
      return $this->db->insert_id();
    }

    public function getPrepressTicketData($id) {
      $query = $this->db->query("SELECT *, DATE(PrepressTicket.ts_TimeCreate) AS DateCreated
                                  FROM basetables2.PrepressTicket
                                  WHERE PrepressTicket.kp_TicketID = $id");

      return $query->row_array();
    }

    public function getPrepressTicketDataForDuplicate($id) {
      $query = $this->db->query("SELECT *
                                  FROM basetables2.PrepressTicket
                                  WHERE PrepressTicket.kp_TicketID = $id");

      return $query->row_array();
    }

    public function getPrepressTableData($id) {
      $query = $this->db->query("SELECT PrepressTicket.*, OrderItems.n_DashNum, Employees.t_UserName
                                  FROM basetables2.PrepressTicket
                                  LEFT JOIN OrderItems ON PrepressTicket.kf_OrderItemID = OrderItems.kp_OrderItemID
                                  LEFT JOIN Employees ON basetables2.PrepressTicket.kf_PrepressID = Employees.kp_EmployeeID
                                  WHERE PrepressTicket.kf_OrderID = $id
                                  ORDER BY PrepressTicket.kp_TicketID asc");

      return $query->result_array();
    }

    public function prepressOrderItemComponentInfoFromOrderID($orderItemInsertedID)
    {
        $where = "OrderItemComponents.kf_OrderItemID =". $orderItemInsertedID." and BuildItems.kf_ManCatID = \"2\"";

        $this->db->select(' CONCAT(OrderItemComponents.kf_OrderItemID, "-", OrderItems.n_DashNum) as OrderID,
                            OrderItemComponents.n_Quantity,
                            OrderItemComponents.n_HeightInInches,
                            OrderItemComponents.n_WidthInInches,
                            OrderItemComponents.n_BleedTop,
                            OrderItemComponents.n_BleedBottom,
                            OrderItemComponents.n_BleedLeft,
                            OrderItemComponents.n_BleedRight,
                            OrderItemComponents.n_WhiteTop,
                            OrderItemComponents.n_WhiteBottom,
                            OrderItemComponents.n_WhiteLeft,
                            OrderItemComponents.n_WhiteRight,
                            OrderItemComponents.n_PocketTop,
                            OrderItemComponents.n_PocketBottom,
                            OrderItemComponents.n_PocketLeft,
                            OrderItemComponents.n_PocketRight,
                            InventoryItems.n_AttribHeight,
                            InventoryItems.t_AttribHeightUOM,
                            InventoryItems.n_AttribWidth,
                            InventoryItems.t_AttribWidthUOM',false)
                ->from('OrderItemComponents')
                ->join('BuildItems', 'OrderItemComponents.kf_BuildItemID = BuildItems.kp_BuildItemID','left outer')
                ->join('InventoryItems', 'OrderItemComponents.kf_InventoryItemID = InventoryItems.kp_InventoryItemID','left outer')
                ->join('OrderItems', 'OrderItemComponents.kf_OrderItemID = OrderItems.kp_OrderItemID','inner')
                ->where($where);

        $query  = $this->db->get();

        return $query->row_array();
    }
}
