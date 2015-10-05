<?php

class AddressModel extends CI_Model
{
    //put your code here
    public function __construct()
    {
        parent::__construct();

        //Server image path
        $this->loadImagePath   = 'https://'.$_SERVER['SERVER_NAME'].'/images/Addresses/';

        $this->imageUploadPath = realpath(APPPATH . '../../../images');

        $this->load->library('image_lib');
    }

    public function getAddressesFieldsFromAddressID($addressID) {
        $query = $this->db->get_where('Addresses', array('kp_AddressID' => $addressID));

        return $query->row_array();

    }

    public function getRecipientShippingTableData($OrderID) {
      $query = $this->db->query("SELECT *
        FROM basetables2.Addresses
        INNER JOIN Customers on Addresses.kf_OtherID = Customers.kp_CustomerID
        WHERE Addresses.kf_TypeMain = \"Customer\" and Addresses.kf_TypeSub = \"ShipTo\" and
        (
          Addresses.t_ContactNameFull is not null or Addresses.t_Address1 is not null or
          Addresses.t_City is not null or Addresses.t_StateOrProvince is not null or
          Addresses.t_Phone is not null
        ) and (Addresses.nb_Inactive is null || Addresses.nb_Inactive = 0)and
        Customers.kp_CustomerID = (SELECT kf_CustomerID FROM basetables2.Orders
          WHERE Orders.kp_OrderID=\"$OrderID\") ");

          return $query->result_array();
    }

    public function getBlindShippingTableData($OrderID) {
      $query = $this->db->query("SELECT *
        FROM basetables2.Addresses
        INNER JOIN Customers on Addresses.kf_OtherID = Customers.kp_CustomerID
        WHERE Addresses.kf_TypeMain = \"Customer\" and Addresses.kf_TypeSub = \"ShipBlindFrom\" and
        (Addresses.t_ContactNameFull is not null or Addresses.t_Address1 is not null or
          Addresses.t_City is not null or Addresses.t_StateOrProvince is not null or
          Addresses.t_Phone is not null
        ) and (Addresses.nb_Inactive is null || Addresses.nb_Inactive = 0) and
        Customers.kp_CustomerID = (SELECT kf_CustomerID FROM basetables2.Orders
          WHERE Orders.kp_OrderID=".$OrderID.") ");

          return $query->result_array();
    }


    public function getCustomerBillToAddressesBySalesPersonID($employeeID) {
      $query = $this->db->query("SELECT Customers.kp_CustomerID, Customers.t_CustCompany, Addresses.kp_AddressID, Addresses.n_Lat, Addresses.n_Lng FROM Addresses
      left join Customers on Customers.kp_CustomerID = Addresses.kf_OtherID
      WHERE Addresses.kf_TypeSub = 'BillTo' and Addresses.kf_TypeMain = 'Customer' and Customers.kf_EmployeeID_Sales=".$employeeID." and Addresses.n_Lat is not null and Addresses.n_Lat != 0");

      return $query ->result_array();
    }
    public function getCustomerBillToAddresses() {
      $query = $this->db->query("SELECT Customers.kp_CustomerID, Customers.t_CustCompany, Customers.kf_EmployeeID_Sales, Addresses.kp_AddressID, Addresses.n_Lat, Addresses.n_Lng FROM Addresses
        left join Customers on Customers.kp_CustomerID = Addresses.kf_OtherID
        WHERE Addresses.kf_TypeSub = 'BillTo' and Addresses.kf_TypeMain = 'Customer' and Addresses.n_Lat is not null and Addresses.n_Lat != 0");

        return $query ->result_array();
    }
    public function blindShipDataWithoutDataTables($OrderID)
    {
        $query = $this->db->query("SELECT t_CompanyName, t_ContactNameFull, t_Address1,
                                   t_City,t_StateOrProvince, t_PostalCode,t_Phone,
                                   t_Address2,t_Country,t_Email,t_Fax,t_Mobile,kf_OtherID,kp_AddressID
                                   FROM basetables2.Addresses
                                   INNER JOIN Customers on Addresses.kf_OtherID = Customers.kp_CustomerID
                                   WHERE Addresses.kf_TypeMain = \"Customer\" and Addresses.kf_TypeSub = \"ShipBlindFrom\" and
                                     (Addresses.t_ContactNameFull is not null or Addresses.t_Address1 is not null or
                                     Addresses.t_City is not null or Addresses.t_StateOrProvince is not null or
                                     Addresses.t_Phone is not null
                                     ) and (Addresses.nb_Inactive is null || Addresses.nb_Inactive = 0) and
                                    Customers.kp_CustomerID = (SELECT kf_CustomerID FROM basetables2.Orders
                                    WHERE Orders.kp_OrderID=".$OrderID.") ");
        return $query->result_array();
    }
    public function blindShipData($OrderID)
    {

        $staticWhere =  "Addresses.kf_TypeMain = \"Customer\" and Addresses.kf_TypeSub = \"ShipBlindFrom\" and
                           (Addresses.t_ContactNameFull is not null or Addresses.t_Address1 is not null or
                            Addresses.t_City is not null or Addresses.t_StateOrProvince is not null or
                            Addresses.t_Phone is not null
                            ) and (Addresses.nb_Inactive is null || Addresses.nb_Inactive = 0) and
                           Customers.kp_CustomerID = (SELECT kf_CustomerID FROM basetables2.Orders
                           WHERE Orders.kp_OrderID=".$OrderID.") ";
        $this->datatables
        ->select('t_CompanyName, t_ContactNameFull, t_Address1,
                            t_City,t_StateOrProvince, t_PostalCode,t_Phone,
                            t_Address2,t_Country,t_Email,t_Fax,t_Mobile,kf_OtherID,kp_AddressID')
        ->add_column('Edit', '<a class="editBtn" href="$1/$2/$3/$4" >Edit</a>','kp_AddressID,kf_OtherID,Customer,ShipBlindFrom')
        ->from('Addresses')
        ->join('Customers', 'Addresses.kf_OtherID = Customers.kp_CustomerID')
        ->where($staticWhere);

        return $this->datatables->generate();

        //$data['result'] = $this->datatables->generate();
        //$this->load->view('ajax', $data);
    }
    public function blindIndicator($orderID)
    {
        $query = $this->db->query("SELECT ad.t_CompanyName, ad.t_ContactNameFull, ad.t_Address1,
                        ad.t_City,ad.t_StateOrProvince,ad.t_PostalCode,ad.t_Phone,
                        ad.t_Address2,ad.t_Country,ad.t_Email,ad.t_Fax,ad.t_Mobile,ad.kf_OtherID,ad.kp_AddressID
                        FROM basetables2.Orders as o
                        JOIN Customers as c
                        on kf_CustomerID = c.kp_CustomerID
                        RIGHT JOIN Addresses as ad
                        ON c.kf_AddrDefBlindShipper = ad.kp_AddressID
                        WHERE o.kp_OrderID = '$orderID' && c.nb_UseDefBlindShipper = \"1\" ");

        return $query->row();
    }
    public function blindModalUpdateAddress()
    {
        $data = array(
            't_CompanyName'=> $this->input->post('blindCompanyNameModal',true),
            't_ContactNameFull'=> $this->input->post('blindContactNameModal',true),
            't_ContactTitle'=> $this->input->post('blindTitleModal',true),
            't_Phone'=> $this->input->post('blindPhoneModal',true),
            't_Fax'=> $this->input->post('blindFaxModal',true),
            't_Mobile'=> $this->input->post('blindMobileModal',true),
            't_Email'=>  $this->input->post('blindEmailModal',true),
            't_Address1'=> $this->input->post('blindAddress1Modal',true),
            't_Address2'=> $this->input->post('blindAddress2Modal',true),
            't_City'=> $this->input->post('blindCityModal',true),
            't_StateOrProvince'=> $this->input->post('blindStateModal',true),
            't_PostalCode'=> $this->input->post('blindZipCodeModal',true),
            't_Country'=> $this->input->post('blindCountryModal',true),
            't_Notes'=> $this->input->post('blindNotesModal',true),
            'nb_Inactive'=> $this->input->post('blindInActiveModal',true),
            'kf_OtherID'=> $this->input->post('blindModalCustomerID',true),
            'kf_TypeMain'=> $this->input->post('blindModalTypeMain',true),
            'kf_TypeSub'=> $this->input->post('blindModalTypeSub',true)


        );

        $this->db->update('Addresses', $data, array('kp_AddressID'=> $this->input->post('blindModalAddressID',true)));
        //$this->db->insert('Addresses', $data);
        //return $this->db->insert_id();
    }



    public function modalAddressData($addressID)
    {
         $query = $this->db->query("Select t_CompanyName, t_ContactNameFull,t_ContactTitle, t_Address1, t_City,t_StateOrProvince,
                                    t_PostalCode,t_Phone,t_Address2,t_Country,t_Notes,
                                    t_Email,t_Fax,t_Mobile,kf_OtherID,kp_AddressID
                                    FROM basetables2.Addresses
                                    WHERE kp_AddressID = \"$addressID\" ");
         return  $query->row();
    }

    public function modalUpdateAddress()
    {
        $data = array(
            't_CompanyName'=> $this->input->post('companyNameModal',true),
            't_ContactNameFull'=> $this->input->post('contactNameModal',true),
            't_ContactTitle'=> $this->input->post('titleModal',true),
            't_Phone'=> $this->input->post('phoneModal',true),
            't_Fax'=> $this->input->post('faxModal',true),
            't_Mobile'=> $this->input->post('mobileModal',true),
            't_Email'=>  $this->input->post('emailModal',true),
            't_Address1'=> $this->input->post('Address1Modal',true),
            't_Address2'=> $this->input->post('Address2Modal',true),
            't_City'=> $this->input->post('cityModal',true),
            't_StateOrProvince'=> $this->input->post('stateModal',true),
            't_PostalCode'=> $this->input->post('zipCodeModal',true),
            't_Country'=> $this->input->post('countryModal',true),
            't_Notes'=> $this->input->post('notesModal',true),
            'nb_Inactive'=> $this->input->post('inActiveModal',true),
            'kf_OtherID'=> $this->input->post('modalCustomerID',true),
            'kf_TypeMain'=> $this->input->post('modalTypeMain',true),
            'kf_TypeSub'=> $this->input->post('modalTypeSub',true)


        );

        $this->db->update('Addresses', $data, array('kp_AddressID'=> $this->input->post('modalAddressID',true)));
        //$this->db->insert('Addresses', $data);
        //return $this->db->insert_id();
    }
    public function recipientShipDataWithoutDataTables($OrderID)
    {
        $query = $this->db->query("SELECT t_CompanyName, t_ContactNameFull, t_Address1,t_City,
                                   t_StateOrProvince,t_PostalCode,t_Phone,t_Address2,t_Country,t_Email,
                                   t_Fax,t_Mobile,kf_OtherID,kp_AddressID,Addresses.t_Notes,Addresses.t_ContactTitle
                                   FROM basetables2.Addresses
                                   INNER JOIN Customers on Addresses.kf_OtherID = Customers.kp_CustomerID
                                   WHERE Addresses.kf_TypeMain = \"Customer\" and Addresses.kf_TypeSub = \"ShipTo\" and
                                   (
                                    Addresses.t_ContactNameFull is not null or Addresses.t_Address1 is not null or
                                    Addresses.t_City is not null or Addresses.t_StateOrProvince is not null or
                                    Addresses.t_Phone is not null
                                   ) and (Addresses.nb_Inactive is null || Addresses.nb_Inactive = 0)and
                                   Customers.kp_CustomerID = (SELECT kf_CustomerID FROM basetables2.Orders
                                   WHERE Orders.kp_OrderID=\"$OrderID\") ");
        return $query->result_array();
    }
    public function recipientShipData($OrderID)
    {
        //$editLink = "<a id=\"addReceipient\" target=\"_blank\" >Add Receipient</a>";

        $staticWhere =  "Addresses.kf_TypeMain = \"Customer\" and Addresses.kf_TypeSub = \"ShipTo\" and
                           (Addresses.t_ContactNameFull is not null or Addresses.t_Address1 is not null or
                            Addresses.t_City is not null or Addresses.t_StateOrProvince is not null or
                            Addresses.t_Phone is not null
                            ) and (Addresses.nb_Inactive is null || Addresses.nb_Inactive = 0)and
                           Customers.kp_CustomerID = (SELECT kf_CustomerID FROM basetables2.Orders
                           WHERE Orders.kp_OrderID=".$OrderID.") ";
        $this->datatables
        ->select('t_CompanyName, t_ContactNameFull, t_Address1,
                            t_City,t_StateOrProvince, t_PostalCode,t_Phone,
                            t_Address2,t_Country,t_Email,t_Fax,t_Mobile,kf_OtherID,kp_AddressID')
        ->add_column('Edit', '<a class="editBtn" href="$1/$2/$3/$4" >Edit</a>','kp_AddressID,kf_OtherID,Customer,ShipTo')
        ->from('Addresses')
        ->join('Customers', 'Addresses.kf_OtherID = Customers.kp_CustomerID')
        ->where($staticWhere);

        return $this->datatables->generate();

        //$data['result'] = $this->datatables->generate();
        //$this->load->view('ajax', $data);
    }

    public function submitAddressInfo()
    {
        $action = $this->input->post('actionStringHidden',true);
        $data = array(
            't_Address1'=> $this->input->post('Address1',true),
            't_Address2'=> $this->input->post('Address2',true),
            't_City'=> $this->input->post('city',true),
            't_CompanyName'=> $this->input->post('companyName',true),
            't_ContactNameFull'=> $this->input->post('contactName',true),
            't_Country'=> $this->input->post('countryNameStateTable',true),
            'kf_OtherID'=> $this->input->post('customerID',true),
            't_Email'=>  $this->input->post('email',true),
            't_Fax'=> $this->input->post('fax',true),
            't_Mobile'=> $this->input->post('mobile',true),
            't_Notes'=> $this->input->post('notes',true),
            't_Phone'=> $this->input->post('phone',true),
            't_StateOrProvince'=> $this->input->post('stateNameStateTable',true),
            't_ContactTitle'=> $this->input->post('title',true),
            'kf_TypeMain'=> $this->input->post('typeMain',true),
            'kf_TypeSub'=> $this->input->post('typeSub',true),
            't_PostalCode'=> $this->input->post('zipCode',true),
            'nb_Inactive'=> $this->input->post('inActive',true)
        );

        if($action == "update")
        {
            $addressID = $this->input->post('addressIDHidden',true);
            $this->updateAddressTbl($data, $addressID);

        }
        else
        {
            $this->db->insert('Addresses', $data);
            return $this->db->insert_id();

        }

    }

    public function getContactNameFull($customerID)
    {
        $where = "kf_TypeMain = \"Customer\" and kf_TypeSub = \"Contact\"
                  and kf_OtherID=$customerID and ((isnull(nb_Inactive)) || (nb_Inactive = 0))  ";

        $this->db
                 ->select('kp_AddressID,t_ContactNameFull')
                 ->from('Addresses')
                 ->where($where);
         $query = $this->db->get();

         return $query->result_array();

    }
    public function getCustomerContactData($customerID)
    {

        $staticWhere =  "Addresses.kf_OtherID =".$customerID." and Addresses.kf_TypeMain = \"Customer\" and
                           Addresses.kf_TypeSub = \"Contact\" and (Addresses.nb_Inactive is null || Addresses.nb_Inactive = 0)";
        $this->datatables
        ->select('Addresses.kp_AddressID,
                  Addresses.t_ContactNameFull,
                  Addresses.t_ContactTitle,
                  Addresses.t_Email')
        ->add_column('Edit', '<a class="editBtn" href="$1">Edit</a>','Addresses.kp_AddressID')
        ->from('Addresses')
        ->where($staticWhere);

        return $this->datatables->generate();
    }

    public function updateAddressTbl($data,$addressID)
    {
        $result = $this->db->update('Addresses', $data, array('kp_AddressID'=>  $addressID));

        if(!$result)
        {
            return $this->db->_error_message();
        }
        else
        {
            return $this->db->affected_rows();

        }

    }
    public function angularRecipientShipData($orderID)
    {
        //$editLink = "<a id=\"addReceipient\" target=\"_blank\" >Add Receipient</a>";

        $staticWhere =  "Addresses.kf_TypeMain = \"Customer\" and Addresses.kf_TypeSub =\"ShipTo\" and
                           (Addresses.t_ContactNameFull is not null or Addresses.t_Address1 is not null or
                            Addresses.t_City is not null or Addresses.t_StateOrProvince is not null or
                            Addresses.t_Phone is not null
                            ) and (Addresses.nb_Inactive is null || Addresses.nb_Inactive = 0)and
                           Customers.kp_CustomerID = (SELECT kf_CustomerID FROM basetables2.Orders
                           WHERE Orders.kp_OrderID=".$orderID.") ";
        $this->db
             ->select('t_CompanyName, t_ContactNameFull, t_Address1,
                                t_City,t_StateOrProvince, t_PostalCode,t_Phone,
                                t_Address2,t_Country,t_Email,t_Fax,t_Mobile,kf_OtherID,kp_AddressID')
             ->from('Addresses')
             ->join('Customers', 'Addresses.kf_OtherID = Customers.kp_CustomerID')
             ->where($staticWhere);

        $query = $this->db->get();

        return $query->result_array();

        //$data['result'] = $this->datatables->generate();
        //$this->load->view('ajax', $data);
    }
    public function deleteAddressDataFromAddressID($addressID)
    {
        $result = $this->db->delete('Addresses', array('kp_AddressID' => $addressID));

        if(!$result)
        {
            return $this->db->_error_message();
        }
        else
        {
            return $this->db->affected_rows();

        }

    }
    public function getDistinctTypeSubData()
    {
        $query = $this->db->query("SELECT distinct(kf_TypeSub) FROM (Addresses) WHERE kf_TypeSub != \"\"");

        return $query->result_array();

    }
    public function getTypeOfSubDataByTypeOfMain($typeMain)
    {
        $query = $this->db->query("SELECT distinct(kf_TypeSub) FROM (Addresses) WHERE Addresses.kf_TypeMain='".$typeMain."' and Addresses.kf_TypeSub != \"\"");

        return $query->result_array();

    }
    public function doAddressImgCustomUpload($addressID)
    {
        $allowed = array('jpeg','jpg','svg','png');
        $msg     = "";

        if(file_exists($this->imageUploadPath.DIRECTORY_SEPARATOR.'.am_i_mounted'))
        {
            if(isset($_FILES['file']) && $_FILES['file']['error'] == 0)
            {
                $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

                if(!in_array(strtolower($extension), $allowed))
                {
                     echo '{"status":"error file extension"}';
                     exit;
                }

                // checks and creates Inventory and Zones Folder
                if(!is_dir($this->imageUploadPath.DIRECTORY_SEPARATOR.'Addresses'))
                {
                    if(!mkdir($this->imageUploadPath.DIRECTORY_SEPARATOR.'Addresses',0777,TRUE))
                    {
                        die("Failed to create Employee Folder");
                    }

                }
                $path = $this->imageUploadPath.DIRECTORY_SEPARATOR.'Addresses';

                //CHECK and create zone ID Folder
                if(!is_dir($path.DIRECTORY_SEPARATOR.$addressID))
                {
                    if(!mkdir($path.DIRECTORY_SEPARATOR.$addressID,0777,TRUE))
                    {
                        die("Failed to create Employee ID Folder");
                    }

                }

                $fullPath = $path.DIRECTORY_SEPARATOR.$addressID;



                $tempPath   = $_FILES[ 'file' ][ 'tmp_name' ];

                $fileName   = str_replace(" ", "_", $_FILES['file']['name']);

                $uploadPath = $fullPath.DIRECTORY_SEPARATOR.$fileName;


                if(move_uploaded_file($tempPath, $uploadPath))
                {
                  //crop image
                  if($extension == 'jpeg' || $extension == 'jpg') {
                    $im = imagecreatefromjpeg($uploadPath);
                  } else if($extension == 'png') {
                    $im = imagecreatefrompng($uploadPath);
                  }

                  $ini_filename = $uploadPath;

                  $ini_x_size = getimagesize($ini_filename )[0];
                  $ini_y_size = getimagesize($ini_filename )[1];

                  //the minimum of xlength and ylength to crop.
                  $crop_measure = min($ini_x_size, $ini_y_size);


                  $to_crop_array = array('x' =>0 , 'y' => 0, 'width' => $crop_measure, 'height'=> $crop_measure);
                  $thumb_im = imagecrop($im, $to_crop_array);

                  if($extension == 'jpeg' || $extension == 'jpg') {
                    imagejpeg($thumb_im, $uploadPath, 100);
                  } else if($extension == 'png') {
                    imagepng($thumb_im, $uploadPath, 100);
                  }


                    //update invtZoneData
                    $data['t_AddressImage']  = $fileName;

                    //print_r($data);

                    //echo "<br/>".$fileName."<br/>";
                    $result              = $this->updateAddressTbl($data, $addressID);

                    //echo "result ".$result."<br/>";

                    $msg = array( 'answer' => 'File transfer completed and Update Done '.$data['t_AddressImage'] );

                    //echo $msg;
                    return $msg;
                     //$json = json_encode( $answer );
                }
                else
                {
                    $msg = "upload failed";
                    return $msg;
                }
            }

        }
        else
        {
            $errorCause             = array();

            $errorCause['msg']      = ".am_i_mounted file not found.";

            $errorCause['location'] = "occured when uploading address Image";

            //$this->angUploadErrorEmail($errorCause);

            $orderItemArry    = Modules::run('rest/customemailcontroller/customAngUploadError',$errorCause);
            $msg = "am i mounted file not found";
            return $msg;
        }
    }

}

?>
