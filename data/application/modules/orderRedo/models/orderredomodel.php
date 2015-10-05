<?php

class OrderRedoModel extends CI_Model
{
    public function __construct()
    {
        //Server image path
        $this->imageUploadPath = realpath(APPPATH . '../../../images/Orders');

        $this->loadImagePath   = 'https://'.$_SERVER['SERVER_NAME'].'/images/Orders';
    }

    public function getRedoTableData() {
      $this->db->select('OrderRedo.kp_OrderRedoID,
                        OrderRedo.t_Status,
                        OrderRedo.t_ItemsRedo,
                        OrderRedo.kf_OrderIDRedo,
                        OrderRedo.kf_OrderID,
                        OrderRedo.t_Department,
                        OrderRedo.t_CustomerIssue,
                        OrderRedo.t_SalesViewIssue,
                        OrderRedo.t_ResearchedProblem,
                        OrderRedo.t_Solution,
                        Orders.kf_RedoOriginalJobID,
                        OrderRedo.ts_DateRequested,
                        OrderRedo.ts_DateApproved')
              ->from('OrderRedo')
              ->join('Orders', 'Orders.kp_OrderID = OrderRedo.kf_OrderID')
              ->where("OrderRedo.t_Status = 'Pending' OR OrderRedo.t_Status = 'Need More Info'");
      $query = $this->db->get();
      return $query->result();
    }

    public function getSalesEmailFromID($empID) {
      $this->db->select('Employees.t_EmployeeEmail')
              ->from('Employees')
              ->where('Employees.kp_EmployeeID',$empID);
      $query = $this->db->get();
      return $query->result();
    }

    public function doRedoImageUpload($orderRedoID,$orderID,$yearOrder,$monthOrder) {
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

              // checks and creates the Redo Folder
              if(!is_dir($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/Redo')) {
                if(!mkdir($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/Redo',0777,TRUE)) {
                  die('Failed to create Redo and other folders...');
                } else {
                  chmod($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/Redo', 0777);
                }
              }

              // checks and creates the RedoID Folder
              if(!is_dir($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/Redo/'.$orderRedoID)) {
                if(!mkdir($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/Redo/'.$orderRedoID,0777,TRUE)) {
                  die('Failed to create Redo ID folder');
                } else {
                  chmod($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/Redo/'.$orderRedoID, 0777);
                }
              }


              $fullPath = $path.'/Redo/'.$orderRedoID;

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

              $uploadPath = $fullPath.'/'.$fileName;


              if(move_uploaded_file($tempPath, $uploadPath)) {
                  chmod($uploadPath, 0777);

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

    public function insertOrderRedoItem($data) {
      $this->db->insert('OrderRedoItems', $data);

      return $this->db->insert_id();
    }

    public function deleteOrderRedoItem($id) {
      $result = $this->db->delete('OrderRedoItems', array('kp_OrderRedoItemID' => $id));

      return $result;
    }

    public function getRedoList($orderID) {
         $this->db->select('OrderRedo.kp_OrderRedoID,
                           OrderRedo.t_Status,
                           OrderRedo.t_ItemsRedo,
                           OrderRedo.kf_OrderIDRedo,
                           OrderRedo.kf_OrderID,
                           OrderRedo.t_Department,
                           OrderRedo.t_CustomerIssue,
                           OrderRedo.t_SalesViewIssue,
                           OrderRedo.t_ResearchedProblem,
                           OrderRedo.t_Solution')
                 ->from('OrderRedo')
                 ->where('OrderRedo.kf_OrderID',$orderID);
         $query = $this->db->get();
         return $query->result();
    }
    public function getRedoListData($orderID) {
         $this->db->select('OrderRedo.kp_OrderRedoID as OrderRedoID,
                           OrderRedo.t_Status as Status,
                           OrderRedo.t_ItemsRedo as ItemsRedo,
                           OrderRedo.kf_OrderIDRedo as kf_OrderIDRedo,
                           OrderRedo.kf_OrderID as OrderID,
                           OrderRedo.t_Department as Department,
                           OrderRedo.t_CustomerIssue as CustomerIssue,
                           OrderRedo.t_SalesViewIssue as SalesViewIssue,
                           OrderRedo.t_ResearchedProblem as Problem,
                           OrderRedo.t_Solution as solution')
                 ->from('OrderRedo')
                 ->where('OrderRedo.kf_OrderID',$orderID);
         $query = $this->db->get();
         return $query->result();
    }
    public function insertOrderRedoTable($orderRedoArray)
    {
         $lenArr                  = sizeof($orderRedoArray);

         for($i = 0; $i<$lenArr; $i++)
         {
             $this->db->insert('OrderRedo', $orderRedoArray[$i]);

         }

         return $this->db->insert_id();


    }
    public function getAllOrderRedoDataFromOrderID($orderID)
    {
        $query = $this->db->get_where('OrderRedo',array('kf_OrderID'=>$orderID));

        return $query->result_array();

    }
    public function getAllOrderRedoData($orderRedoID)
    {
        $query = $this->db->get_where('OrderRedo',array('kp_OrderRedoID'=>$orderRedoID));

        return $query->row_array();

    }
    public function updateOrderRedoTable($orderRedoID,$data)
    {
        $this->db->where('kp_OrderRedoID', $orderRedoID);
        $this->db->update('OrderRedo',$data);

    }
    public function getOrderRedoImageContent($orderRedoID,$orderID,$dateReceived)
    {
        $dateOrderReceivedArr       = explode("-", $dateReceived);

        $yearOrder                  = $dateOrderReceivedArr[0];

        $monthOrder                 = $dateOrderReceivedArr[1];

        $path                       = $this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID;

        $fullPath                   = $path.'/'.'Redo'.'/'.$orderRedoID;

        $image = array();

        if(file_exists($fullPath))
        {
            $files = scandir( $fullPath);

            $filesArry = array_diff($files,array('.','..','.DS_Store'));
            //var_dump($filesArry);
            foreach($filesArry as $file)
            {
                $imagePath           = '../images/Orders/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.'Redo'.'/'.$orderRedoID.'/'.$file;
                $imageHref           = '../images/Orders/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.'Redo'.'/'.$orderRedoID.'/'.$file;

                $actualImageName     = $file;

                $image[] = array(
                   'imageUrl' => $imagePath,
                   'imagehref' => $imageHref,
                   'imageName' => $actualImageName);

               //return $image;

            }


        }
        //var_dump($image);
        if(!is_null($image))
        {
            return $image;
        }
        else
        {
            return null;
        }

    }
}

?>
