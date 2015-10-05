<?php

class OrderShipModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = "OrderShip";

        //Server Shipping path
        $this->barcodeImageOldPath = realpath(APPPATH . '../../../images/shipping');
        $this->barcodeImagePath    = realpath(APPPATH . '../../../images/Orders');
    }

    public function getOrderShipInvoiceEmailTrackDataFromOrderID($orderID) {
        $query = $this->db->query("SELECT OrderShip.kp_OrderShipID AS ID,
                                'F0000-1073860483' AS ItemRef_ListID,
                                Shippers.t_Company,
                                ShipperService.t_ShipperService,
                                OrderShipTracking.t_TrackingID,
                                Concat(Shippers.t_Company, ' - ' , ShipperService.t_ShipperService, IFNULL(CONCAT(' - Tracking: ', OrderShipTracking.t_TrackingID), '')) AS Description,
                                1 AS Quantity,
                                Round(OrderShipTracking.n_ShippingCharge, 2)AS Rate,
                                Round(OrderShipTracking.n_ShippingCharge, 2) AS Amount,
                                'Tax' as Taxable
                            FROM OrderShip
                                 LEFT OUTER JOIN OrderShipTracking ON OrderShipTracking.kf_OrderShipID = OrderShip.kp_OrderShipID
                                 LEFT OUTER JOIN Shippers ON OrderShip.kf_ShipperID = Shippers.kp_ShipperID
                                 LEFT OUTER JOIN ShipperService ON OrderShip.kf_ShipperServiceID = ShipperService.kp_ShipperServiceID
                            WHERE OrderShip.kf_OrderID = '$orderID' and (nb_HideOnWorkOrder = '0' or isnull(nb_HideOnWorkOrder))");

        return $query->result_array();


    }

    public function getOrderShipEmailTrackingDataFromOrderID($orderID) {
        $where = "kf_OrderID = '$orderID' and (nb_HideOnWorkOrder = '0' or isnull(nb_HideOnWorkOrder))";
        $query = $this->db->get_where('OrderShip',$where);

        return $query->result_array();
    }

    public function getDefaultBlindAddress($orderID) {
      $query = $this->db->query("SELECT Addresses.kp_AddressID, Orders.kf_CustomerID, Addresses.t_CompanyName, Addresses.t_ContactNameFull,
                                        Addresses.t_Address1, Addresses.t_Address2, Addresses.t_Phone, Addresses.t_City, Addresses.t_StateOrProvince,
                                        Addresses.t_PostalCode, Addresses.t_Country
                                  FROM Addresses
                                  JOIN Orders ON Orders.kp_OrderID = $orderID
                                  JOIN Customers ON Addresses.kp_AddressID = Customers.kf_AddrDefBlindShipper
                                  WHERE Customers.kp_CustomerID = Orders.kf_CustomerID AND Customers.nb_UseDefBlindShipper = '1'");

      return $query->row_array();
    }

    public function getOrderShipByOrderID($orderID) {
      $query = $this->db->query("SELECT OrderShip.kp_OrderShipID, OrderShip.t_RecipientCompany, OrderShip.t_RecipientCity
                                  FROM OrderShip
                                  WHERE OrderShip.kf_OrderID = " . $orderID);

      return $query->result_array();
    }

    public function getOrderShipInvoiceDataFromOrderID($orderID)
    {
        $query = $this->db->query("SELECT OrderShip.kp_OrderShipID AS ID,
                                'F0000-1073860483' AS ItemRef_ListID,
                                Concat(Shippers.t_Company, ' - ' , ShipperService.t_ShipperService, IFNULL(CONCAT(' - Tracking: ', OrderShipTracking.t_TrackingID), '')) AS Description,
                                1 AS Quantity,
                                ship.t_Company as originalShipper,
                                shipserv.t_ShipperService as originalShipperService,
                                Round(OrderShipTracking.n_ShippingCharge, 2)AS Rate,
                                Round(OrderShipTracking.n_ShippingCharge, 2) AS Amount,
                                'Tax' as Taxable,
                                ShipperService.nb_ShipPricedManually,
                                OrderShip.kf_ShipperID,
                                OrderShip.kf_ShipperServiceID,
                                OrderShipTracking.t_TrackingID,
                                OrderShip.nb_ShippingException,
                                OrderShip.kf_ShippingExceptionOriginalShipperID,
                                OrderShip.kf_ShippingExceptionOriginalShipperServiceID
                            FROM OrderShip
								                LEFT OUTER JOIN OrderShipTracking ON OrderShipTracking.kf_OrderShipID = OrderShip.kp_OrderShipID
                                LEFT OUTER JOIN Shippers ON OrderShip.kf_ShipperID = Shippers.kp_ShipperID
                                LEFT OUTER JOIN ShipperService ON OrderShip.kf_ShipperServiceID = ShipperService.kp_ShipperServiceID
                                LEFT OUTER JOIN Shippers ship ON OrderShip.kf_ShippingExceptionOriginalShipperID = ship.kp_ShipperID
                                LEFT OUTER JOIN ShipperService shipserv ON OrderShip.kf_ShippingExceptionOriginalShipperServiceID = shipserv.kp_ShipperServiceID
                            WHERE OrderShip.kf_OrderID = '$orderID'");

        return $query->result_array();


    }
    public function getOrderShipDataFromOrderID($orderID)
    {
        $query = $this->db->get_where('OrderShip',array('kf_OrderID'=>$orderID));

        return $query->result_array();

    }
    public function getOrderShipDataFromOrderShipID($orderShipID)
    {

      $query = $this->db->query("SELECT os.*, Shippers.t_Company AS OriginalShipper, ShipperService.t_ShipperService as OriginalShipperService
                                  FROM OrderShip os
                                  LEFT JOIN Shippers ON os.kf_ShippingExceptionOriginalShipperID = Shippers.kp_ShipperID
                                  LEFT JOIN ShipperService ON os.kf_ShippingExceptionOriginalShipperServiceID = ShipperService.kp_ShipperServiceID
                                  WHERE kp_OrderShipID = $orderShipID");

      return $query->row_array();
        //  $query = $this->db->get_where('OrderShip',array('kp_OrderShipID'=>$orderShipID));
         //
        //  return $query->row_array();

    }
    public function blindIndicator($orderID)
    {
      $this->db->select('Addresses.t_CompanyName,Addresses.t_ContactNameFull,
                         Addresses.t_Address1,Addresses.t_City,
                         Addresses.t_StateOrProvince,Addresses.t_PostalCode,
                         Addresses.t_Phone,Addresses.t_Address2,
                         Addresses.t_Country,Addresses.t_Email,Addresses.t_Fax,
                         Addresses.t_Mobile,Addresses.kf_OtherID,Addresses.kp_AddressID',false)
               ->from('Orders')
               ->join('Customers',' Orders.kf_CustomerID = Customers.kp_CustomerID')
               ->join('Addresses','Customers.kf_AddrDefBlindShipper = Addresses.kp_AddressID', 'right')
               ->where('Orders.kp_OrderID',$orderID,'Customers.nb_UseDefBlindShipper',1);
      $query = $this->db->get();

//      $query = $this->db->query("SELECT ad.t_CompanyName, ad.t_ContactNameFull, ad.t_Address1,
//                        ad.t_City,ad.t_StateOrProvince,ad.t_PostalCode,ad.t_Phone,
//                        ad.t_Address2,ad.t_Country,ad.t_Email,ad.t_Fax,ad.t_Mobile,ad.kf_OtherID,ad.kp_AddressID
//                        FROM basetables2.Orders as o
//                        JOIN Customers as c
//                        on kf_CustomerID = c.kp_CustomerID
//                        RIGHT JOIN Addresses as ad
//                        ON c.kf_AddrDefBlindShipper = ad.kp_AddressID
//                        WHERE o.kp_OrderID = '$orderID' && c.nb_UseDefBlindShipper = \"1\" ");

        return $query->row();
    }
    public function billQueryOnCreate($orderID)
    {
        $this->db->select('Orders.kp_OrderID,Orders.kf_CustomerID,
                           Customers.nb_ShipAlwaysFedXAcct,Customers.nb_UseDefBlindShipper,
                           Customers.t_CustFedexAcct,Customers.t_CustUPSAcct,t_AlwaysShipService')
                   ->from('Orders')
                   ->join('Customers',' Orders.kf_CustomerID = Customers.kp_CustomerID')
                  ->where('Orders.kp_OrderID',$orderID);
        $query = $this->db->get();
        return $query->row();


//        $query = $this->db->query("SELECT ord.kp_OrderID,ord.kf_CustomerID,
//                                    cs.nb_ShipAlwaysFedXAcct,
//                                    cs.nb_UseDefBlindShipper,
//                                    cs.t_CustFedexAcct
//                                    FROM basetables2.Orders as ord
//                                    INNER JOIN basetables2.Customers as cs
//                                    on ord.kf_CustomerID = cs.Kp_CustomerID
//                                    WHERE kp_OrderID = '$orderID' ");
//
//        return $query->row();


    }
    public function deleteOrderShipTableRow($orderShipID)
    {
          $result = $this->db->delete('OrderShip', array('kp_OrderShipID' => $orderShipID));

          return $result;
    }
    public function insertOrderShipTblData($data=null)
    {
         $this->db->insert('OrderShip', $data);
         return $this->db->insert_id();

    }
    public function createAction($action=null,$data=null)
    {
        if($action != "duplicateOrderShipFromOrderID")
        {
             $data = array(
            'kf_CustomerShipToID'=> $this->input->post('receipeintCustomerShipToIDHidden',true),
            'kf_CustomerID'=> $this->input->post('receipeintCustomerIDHidden',true),
            'kf_OrderID'=> $this->input->post('orderIDHidden',true),
            't_RecipientCompany'=> $this->input->post('receipeintCompanyNameHidden',true),
            't_RecipientContact'=> $this->input->post('receipeintContactNameHidden',true),
            't_RecipientAddress1'=> $this->input->post('receipeintAddressNameHidden',true),
            't_RecipientAddress2'=> $this->input->post('receipeintAddressNameHidden2',true),
            't_RecipientCountry'=> $this->input->post('receipeintCountryNameHidden',true),
            't_RecipientCity'=> $this->input->post('receipeintCityNameHidden',true),
            't_RecipientState'=> $this->input->post('receipeintStateNameHidden',true),
            't_RecipientPostalCode'=> $this->input->post('receipeintZipCodeNameHidden',true),
            't_RecipientEmail'=> $this->input->post('receipeintEmailNameHidden',true),
            't_RecipientFax'=> $this->input->post('receipeintFaxNameHidden',true),
            't_RecipientMobile'=> $this->input->post('receipeintMobileNameHidden',true),
            't_RecipientPhone'=> $this->input->post('receipeintPhoneNameHidden',true),
            'kf_ShipperID'=> $this->input->post('shipperInfo',true),
            'kf_ShipperServiceID'=> $this->input->post('ShipperServiceID',true),
            't_BillTransportToAccount'=> $this->input->post('billTo',true),
            't_AccountNumber'=> $this->input->post('billAcountNumber',true),
            't_ShipWithOrderID'=> $this->input->post('shipWithOrderID',true),
            'nb_HideOnWorkOrder'=> $this->input->post('hideOnWorkOrder',true),
            't_SenderCompany'=> $this->input->post('blindCompanyNameHidden',true),
            't_SenderContact'=> $this->input->post('blindContactNameHidden',true),
            't_SenderAddress1'=> $this->input->post('blindAddressNameHidden',true),
            't_SenderAddress2'=> $this->input->post('blindAddressNameHidden2',true),
            't_SenderCountry'=> $this->input->post('blindCountryNameHidden',true),
            't_SenderCity'=> $this->input->post('blindCityNameHidden',true),
            't_SenderState'=> $this->input->post('blindStateNameHidden',true),
            't_SenderPostalCode'=> $this->input->post('blindZipCodeNameHidden',true),
            't_SenderEmail'=> $this->input->post('blindEmailNameHidden',true),
            't_SenderFax'=> $this->input->post('blindFaxNameHidden',true),
            't_SenderMobile'=> $this->input->post('blindMobileNameHidden',true),
            't_SenderPhone'=> $this->input->post('blindPhoneNameHidden',true),
            'kf_CustomerShipBlindFromID'=> $this->input->post('blindCustomerShipToIDHidden',true),
            't_Notes'=>  $this->input->post('notes',true),
            'nb_ShippingTBD'=>$this->input->post('nb_ShippingTBD',true),
            't_SenderBlindIndicator'=> ""
            );
            if($data['t_SenderContact'] == "" && ($data['t_SenderAddress1'] == "" ||   $data['t_SenderAddress1'] == ""))
            {
                 $data['t_SenderBlindIndicator']   = null;
            }
            else
            {
                 $data['t_SenderBlindIndicator']   = "Yes";
            }
             $this->db->insert('OrderShip', $data);
             return $this->db->insert_id();
        }
        if($action == "duplicateOrderShipFromOrderID")
        {
            $lenArr                  = sizeof($data);
            for($i = 0; $i<$lenArr; $i++)
            {
                $this->db->insert('OrderShip', $data[$i]);

            }
            return $this->db->insert_id();
        }

    }
    public function deleteModalAction($orderShipID)
    {
        /*
        * Any non-digit character will be excluded after passing $id
        * from intval function. This is done for security reason.
        */
        $kp_OrderShipID = intval( $orderShipID );

        // below delete operation generates this query DELETE FROM users WHERE id = $id
        $this->db->delete( 'OrderShip', array( 'kp_OrderShipID' => $kp_OrderShipID ) );

    }
    public function deleteNewOldBarCodeImage($orderID,$orderShipID,$newOld='old')
    {
        if($newOld == "old")
        {
            $targetDir = $this->barcodeImageOldPath.'/'.$orderID;

            if(file_exists($targetDir))
            {
                $files = scandir($targetDir);
                //var_dump($files);
                $files = array_diff($files,array('.','..','.DS_Store'));
                //var_dump($files);
                foreach($files as $file)
                {
                    unlink($targetDir.'/'.$file); // remove any image files.
//                    if($file == $orderShipID.".gif")
//                    {
//                        $removeImageFileMsg = unlink($targetDir.'/'.$file); // remove any image files.
//                    }

                }
                //echo "file exists";
                //$removeImageFileMsg =   unlink($targetDir.'/'.$orderShipID.".gif");
                $msg                = rmdir($targetDir); // remove any Shipping BarCode Img files.
                //echo "<br/>".$removeImageFileMsg."<br/>".$msg."<br/>";
                if(!$msg)
                {
                     die("Failed to create Year and Month Folders");
                }
                return $msg;
            }
            else
            {
                echo "file Doesn't Exit";
            }


        }
        else if($newOld = "new") // an extension to delete shipping Barcode Images from the new location
        {
            $dateReceived               = Modules::run('orderItems/orderitemcontroller/getDateReceived',$orderID);

            $dateOrderReceivedArr       = explode("-", $dateReceived);

            $yearOrder                  = $dateOrderReceivedArr[0];

            $monthOrder                 = $dateOrderReceivedArr[1];

            $path                       = $this->barcodeImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID;

            $targetDir                 = $path.'/'.'Shipping';

            if(file_exists($targetDir))
            {
                $files = scandir($targetDir);

                $files = array_diff($files,array('.','..','.DS_Store'));

                foreach($files as $file)
                {
                    unlink($targetDir.'/'.$file); // remove any image files.
//                    if($file == $orderShipID.".gif")
//                    {
//                        echo $targetDir.'/'.$file;
//                        //$removeImageFileMsg = unlink($targetDir.'/'.$file); // remove any image files.
//                        //$removeImageFileMsg = unlink($targetDir.'/'.$file); // remove any image files.
//
//                    }

                }

                $msg                = rmdir($targetDir); // remove any Shipping BarCode Img files.

                if(!$msg)
                {
                     die("Failed to delete the shipping folder");
                }

                return $msg;

            }




        }


    }
    public function getBarCode($orderIDBarCode,$orderID,$orderShipID)
    {
        //echo "This is the barcode: ".$this->barcodeImagePath."<br/>";
        //$targetDir  = 'images'.DIRECTORY_SEPARATOR.'shipping'.DIRECTORY_SEPARATOR.$orderID;
        // get Date Received from OrderID
        $dateReceived               = Modules::run('orderItems/orderitemcontroller/getDateReceived',$orderID);

        $dateOrderReceivedArr       = explode("-", $dateReceived);

        $yearOrder                  = $dateOrderReceivedArr[0];

        $monthOrder                 = $dateOrderReceivedArr[1];

        // checks and creates Year and Month Folder
        if(!is_dir($this->barcodeImagePath.'/'.$yearOrder.'/'.$monthOrder))
        {
            if(!mkdir($this->barcodeImagePath.'/'.$yearOrder.'/'.$monthOrder,0777,TRUE))
            {
                die("Failed to create Year and Month Folders");
            }

        }

        $path                       = $this->barcodeImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID;
        if(!is_dir($path))
        {
            if(!mkdir($path,0777,TRUE))
            {
                die("Failed to create Order Folder");
            }

        }

        $targetPath                 = $path.'/'.'Shipping';

        if(!is_dir($targetPath))
        {
            if(!mkdir($targetPath,0777,TRUE))
            {
                die("Failed to create Shipping Folder");
            }

        }
        //$targetDir                  = $path.'/'.$orderID;
        //$targetDir2 = 'images'.DIRECTORY_SEPARATOR.'shipping'.DIRECTORY_SEPARATOR.'test';
//         if(!file_exists($targetDir))
//         {
//              if(!mkdir($targetDir,0777))
//              {
//                  die("Failed to create Folder");
//              }
//              else
//              {
//                  chmod($this->barcodeImagePath.'/'.$orderID, 0777);
//              }
//              //@mkdir($targetDir2, 0777);
//         }
        $bc   = new Barcode39($orderIDBarCode);
        // set text size
        $bc->barcode_text_size = 5;

        // set barcode bar thickness (thick bars)
        $bc->barcode_bar_thick = 4;

        // set barcode bar thickness (thin bars)
        $bc->barcode_bar_thin = 2;
        //$data =    $bc->draw("images/shipping/$orderID/$orderShipID.gif");
        $data =    $bc->draw($targetPath.'/'.$orderShipID.".gif");
        if($data)
        {
            chmod($targetPath.'/'.$orderShipID.".gif", 0777);
        }
        //print_r($data);
        //echo "<br/>";
    }
    public function barCodeGeneration($orderID)
    {
         $query = $this->db->query("SELECT shos.kp_OrderShipID as ID,
                                    concat(
                                    cast(ifnull(shos.kp_OrderShipID,'') as CHAR),'<br>',
                                    cast(ifnull(shos.t_Company,'') as CHAR),'<br>',
                                    cast(ifnull(ss.t_ShipperService,'')as CHAR),'<br>',
                                    'shipped: ','_____','<br>',
                                    'date: ','_____'
                                    ) as ShippingID,

                                    if(shos.nb_ShippingTBD = '1', 'To be Determined',concat(
                                    cast(ifnull(shos.t_RecipientCompany,'')as CHAR),'<br>',
                                    cast(ifnull(shos.t_RecipientContact,'')as CHAR),'<br>',
                                    cast(ifnull(shos.t_RecipientAddress1,'')as CHAR),' ',
                                    cast(ifnull(shos.t_RecipientAddress2,'')as CHAR),'<br> ',
                                    cast(ifnull(shos.t_RecipientCountry,'')as CHAR),' ',
                                    cast(ifnull(shos.t_RecipientCity,'')as CHAR),'<br> ',
                                    cast(ifnull(shos.t_RecipientState,'')as CHAR),' ',
                                    cast(ifnull(shos.t_RecipientPostalCode,'')as CHAR),'<br> ',
                                    cast(ifnull(shos.t_RecipientPhone,'')as CHAR)
                                    ))as Receipeint,

                                    concat(
                                    cast(ifnull(shos.t_SenderCompany,'')as CHAR),'<br>',
                                    cast(ifnull(shos.t_SenderContact,'')as CHAR),'<br>',
                                    cast(ifnull(shos.t_SenderAddress1,'')as CHAR),' ',
                                    cast(ifnull(shos.t_SenderAddress2,'')as CHAR),'<br>',
                                    cast(ifnull(shos.t_SenderCountry,'')as CHAR),' ',
                                    cast(ifnull(shos.t_SenderCity,'')as CHAR),'<br>',
                                    cast(ifnull(shos.t_SenderState,'')as CHAR),' ',
                                    cast(ifnull(shos.t_SenderPostalCode,'')as CHAR),'<br>',
                                    cast(ifnull(shos.t_SenderPhone,'')as CHAR)
                                    ) as Blind,
                                    concat(
                                    cast(ifnull(shos.t_BillTransportToAccount,'') as CHAR),'<br>',
                                    cast(ifnull(shos.t_AccountNumber,'') as CHAR),'<br>',
                                    cast(ifnull(shos.t_Notes,'') as CHAR)
                                    ) as notes,
                                    concat(
                                    cast(ifnull(shos.kp_OrderShipID,'') as CHAR)
                                    ) as barID

                                    FROM basetables2.ShipperService as ss

                                    left join
                                    (

                                            SELECT shipp.t_Company,osh.kp_OrderShipID,osh.kf_ShipperID,osh.kf_ShipperServiceID,
                                            osh.t_BillTransportToAccount,osh.t_AccountNumber,osh.t_ShipWithOrderID,
                                            osh.t_RecipientCompany,osh.t_RecipientContact,
                                            osh.t_RecipientAddress1,osh.t_RecipientAddress2,
                                            osh.t_RecipientCountry,osh.t_RecipientCity,
                                            osh.t_RecipientState,osh.t_RecipientPostalCode,osh.t_RecipientPhone,
                                            osh.t_SenderCompany,osh.t_SenderContact,osh.t_SenderAddress1,osh.t_SenderAddress2,
                                            osh.t_SenderCountry,osh.t_SenderCity,osh.t_SenderState,osh.t_SenderPostalCode,
                                            osh.t_SenderPhone,osh.nb_HideOnWorkOrder,
                                            osh.t_RecipientEmail,osh.t_RecipientFax,osh.t_RecipientMobile,
					    osh.t_SenderEmail,osh.t_SenderFax,osh.t_SenderMobile,
					    osh.kf_CustomerShipBlindFromID,osh.kf_CustomerShipToID,
					    osh.kf_OrderID,osh.kf_CustomerID,osh.nb_ShippingTBD,osh.t_Notes
                                            FROM basetables2.Shippers as shipp

                                            left join
                                            (
                                            SELECT kp_OrderShipID,kf_ShipperID,kf_ShipperServiceID,
                                            t_BillTransportToAccount,t_AccountNumber,t_ShipWithOrderID,
                                            t_RecipientCompany,t_RecipientContact,
                                            t_RecipientAddress1,t_RecipientAddress2,t_RecipientCountry,t_RecipientCity,
                                            t_RecipientState,t_RecipientPostalCode,t_RecipientPhone,
                                            t_SenderCompany,t_SenderContact,t_SenderAddress1,t_SenderAddress2,
                                            t_SenderCountry,t_SenderCity,t_SenderState,t_SenderPostalCode,t_SenderPhone,nb_HideOnWorkOrder,
                                            t_RecipientEmail,t_RecipientFax,t_RecipientMobile,t_SenderEmail,t_SenderFax,t_SenderMobile,
					    kf_CustomerShipBlindFromID,kf_CustomerShipToID,kf_OrderID,kf_CustomerID,nb_ShippingTBD,t_Notes
                                            FROM basetables2.OrderShip
                                            WHERE kf_OrderID = '$orderID'
                                            ) as osh
                                            on osh.kf_ShipperID = shipp.kp_ShipperID
                                            WHERE shipp.kp_ShipperID=osh.kf_ShipperID and nb_Inactive is null and kp_ShipperID is not null

                                    ) as shos
                                     on shos.kf_ShipperServiceID = ss.kp_ShipperServiceID

                                    LEFT JOIN basetables2.OrderShipTracking as oshtr
                                    on shos.kp_OrderShipID = oshtr.kf_OrderShipID
                                    WHERE ss.kf_ShipperID = shos.kf_ShipperID
                                    GROUP BY shos.kp_OrderShipID");


                    return $query->result();

    }
    public function orderShipSelectTbl($orderID)
    {
        $query = $this->db->query("SELECT kp_OrderShipID as ID,
					concat(
                                                cast(ifnull(t_Company,'') as CHAR),'<br>',
                                                cast(ifnull(t_ShipperService,'')as CHAR),'<br>',
                                                cast(ifnull(t_BillTransportToAccount,'')as CHAR),'<br>',
                                                cast(ifnull(t_AccountNumber,'')as CHAR),'<br>',
                                                cast(ifnull(t_ShipWithOrderID,'')as CHAR),'<br>',
                                                cast(ifnull(OrderShip.t_Notes,'')as CHAR)
                                                ) as Details,
                                        if(nb_ShippingTBD = '1', 'To be Determined',concat(
                                                cast(ifnull(t_RecipientCompany,'')as CHAR),'<br>',
                                                cast(ifnull(t_RecipientContact,'')as CHAR),'<br>',
                                                cast(ifnull(t_RecipientAddress1,'')as CHAR),' ',
                                                cast(ifnull(t_RecipientAddress2,'')as CHAR),'<br> ',
                                                cast(ifnull(t_RecipientCountry,'')as CHAR),' ',
                                                cast(ifnull(t_RecipientCity,'')as CHAR),'<br> ',
                                                cast(ifnull(t_RecipientState,'')as CHAR),' ',
                                                cast(ifnull(t_RecipientPostalCode,'')as CHAR),'<br> ',
                                                cast(ifnull(t_RecipientPhone,'')as CHAR)
                                                ))as Receipeint,
                                        concat(
                                                cast(ifnull(t_SenderCompany,'')as CHAR),'<br>',
                                                cast(ifnull(t_SenderContact,'')as CHAR),'<br>',
                                                cast(ifnull(t_SenderAddress1,'')as CHAR),' ',
                                                cast(ifnull(t_SenderAddress2,'')as CHAR),'<br>',
                                                cast(ifnull(t_SenderCountry,'')as CHAR),' ',
                                                cast(ifnull(t_SenderCity,'')as CHAR),'<br>',
                                                cast(ifnull(t_SenderState,'')as CHAR),' ',
                                                cast(ifnull(t_SenderPostalCode,'')as CHAR),'<br>',
                                                cast(ifnull(t_SenderPhone,'')as CHAR)
                                                ) as Blind,
                                        GROUP_CONCAT(
                                                cast(ifnull(t_TrackingID,'') as CHAR) SEPARATOR '<br>' ) as Tracking
                                   FROM basetables2.OrderShip
                                   left join Shippers
                                   on OrderShip.kf_ShipperID = Shippers.kp_ShipperID
                                   left join ShipperService
                                   on OrderShip.kf_ShipperServiceID = ShipperService.kp_ShipperServiceID
                                   LEFT JOIN basetables2.OrderShipTracking
                                   on OrderShip.kp_OrderShipID = OrderShipTracking.kf_OrderShipID
                                   WHERE OrderShip.kf_OrderID = '$orderID'
                                GROUP BY OrderShip.kp_OrderShipID"
                                 );


                    return $query->result();

//        $query = $this->db->query("SELECT kf_OrderShipID as ID,
//                                    concat(
//                                            cast(ifnull(t_Company,'') as CHAR),'<br>',
//                                            cast(ifnull(t_ShipperService,'')as CHAR),'<br>',
//                                            cast(ifnull(t_BillTransportToAccount,'')as CHAR),'<br>',
//                                            cast(ifnull(t_AccountNumber,'')as CHAR),'<br>',
//                                            cast(ifnull(t_ShipWithOrderID,'')as CHAR),'<br>',
//                                            cast(ifnull(OrderShip.t_Notes,'')as CHAR)
//                                          ) as Details,
//                                    if(nb_ShippingTBD = '1', 'To be Determined',concat(
//                                            cast(ifnull(t_RecipientCompany,'')as CHAR),'<br>',
//                                            cast(ifnull(t_RecipientContact,'')as CHAR),'<br>',
//                                            cast(ifnull(t_RecipientAddress1,'')as CHAR),' ',
//                                            cast(ifnull(t_RecipientAddress2,'')as CHAR),'<br> ',
//                                            cast(ifnull(t_RecipientCountry,'')as CHAR),' ',
//                                            cast(ifnull(t_RecipientCity,'')as CHAR),'<br> ',
//                                            cast(ifnull(t_RecipientState,'')as CHAR),' ',
//                                            cast(ifnull(t_RecipientPostalCode,'')as CHAR),'<br> ',
//                                            cast(ifnull(t_RecipientPhone,'')as CHAR)
//                                      ))as Receipeint,
//                                    concat(
//                                            cast(ifnull(t_SenderCompany,'')as CHAR),'<br>',
//                                            cast(ifnull(t_SenderContact,'')as CHAR),'<br>',
//                                            cast(ifnull(t_SenderAddress1,'')as CHAR),' ',
//                                            cast(ifnull(t_SenderAddress2,'')as CHAR),'<br>',
//                                            cast(ifnull(t_SenderCountry,'')as CHAR),' ',
//                                            cast(ifnull(t_SenderCity,'')as CHAR),'<br>',
//                                            cast(ifnull(t_SenderState,'')as CHAR),' ',
//                                            cast(ifnull(t_SenderPostalCode,'')as CHAR),'<br>',
//                                            cast(ifnull(t_SenderPhone,'')as CHAR)
//                                          ) as Blind,
//                                    GROUP_CONCAT(
//                                            cast(ifnull(t_TrackingID,'') as CHAR) SEPARATOR '<br>' ) as Tracking,
//                                            kp_OrderShipTrackingID as OrderShipTrackingID
//
//                                     FROM basetables2.OrderShipTracking
//                                          LEFT JOIN basetables2.OrderShip
//                                          on OrderShip.kp_OrderShipID = OrderShipTracking.kf_OrderShipID
//                                          left join Shippers
//                                          on OrderShip.kf_ShipperID = Shippers.kp_ShipperID
//                                          left join ShipperService
//                                          on OrderShip.kf_ShipperServiceID = ShipperService.kp_ShipperServiceID
//                                     WHERE OrderShipTracking.kf_OrderID = '$orderID'
//                                     GROUP BY OrderShipTracking.kf_OrderShipID");
//
//
//        return $query->result();
//
    }

    public function shipDChaining($orderID=null,$orderShipID=null)
    {
        //$where = "OrderShip.kf_OrderID     = '$orderID'";
        //echo "starting".$orderShipID;
        if($orderShipID !== '')
        {
             //echo "inside model ".$orderShipID;
             $where = "OrderShip.kp_OrderShipID = '$orderShipID'";
             $this->db
                  ->select('kp_OrderShipID as ID,
                  CONCAT(   CAST(IFNULL(t_Company,\'\') AS CHAR),\'<br>\',
                            CAST(IFNULL(t_ShipperService,\'\') AS CHAR),\'<br>\',
                            CAST(IFNULL(t_BillTransportToAccount,\'\') AS CHAR),\'<br>\',
                            CAST(IFNULL(t_AccountNumber,\'\') AS CHAR),\'<br>\',
                            CAST(IFNULL(t_ShipWithOrderID,\'\') AS CHAR),\'<br>\',
                            CAST(IFNULL(OrderShip.t_Notes,\'\') as CHAR)


                        ) as Details,
                  if(nb_ShippingTBD = \'1\', \'To be Determined\',
                  CONCAT(   cast(ifnull(t_RecipientCompany,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientContact,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientAddress1,\'\')as CHAR),\' \',
                            cast(ifnull(t_RecipientAddress2,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientCountry,\'\')as CHAR),\' \',
                            cast(ifnull(t_RecipientCity,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientState,\'\')as CHAR),\' \',
                            cast(ifnull(t_RecipientPostalCode,\'\')as CHAR),\'<br> \',
                            cast(ifnull(t_RecipientPhone,\'\')as CHAR)
                        )
                    )as Receipeint,
                  concat(    cast(ifnull(t_SenderCompany,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderContact,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderAddress1,\'\')as CHAR),\' \',
                            cast(ifnull(t_SenderAddress2,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderCountry,\'\')as CHAR),\' \',
                            cast(ifnull(t_SenderCity,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderState,\'\')as CHAR),\' \',
                            cast(ifnull(t_SenderPostalCode,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderPhone,\'\')as CHAR)
                       ) as Blind,
                  GROUP_CONCAT( cast(ifnull(t_TrackingID,\'\') as CHAR) SEPARATOR \'<br>\')
                        as Tracking,
                  concat(    cast(OrderShip.kf_ShipperID as CHAR),\'<br>\',
                            cast(kf_ShipperServiceID as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientCompany,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientContact,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientAddress1,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientAddress2,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientCountry,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientCity,\'\')as CHAR),\'<br> \',
                            cast(ifnull(t_RecipientState,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientPostalCode,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientPhone,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientEmail,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientFax,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientMobile,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderCompany,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderContact,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderAddress1,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderAddress2,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderCountry,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderCity,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderState,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderPostalCode,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderPhone,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderEmail,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderFax,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderMobile,\'\')as CHAR),\'<br>\',
                            cast(ifnull(kf_CustomerShipBlindFromID,\'\')as CHAR),\'<br>\',
                            cast(ifnull(kf_CustomerShipToID,\'\')as CHAR),\'<br>\',
                            cast(ifnull(OrderShip.kf_OrderID,\'\')as CHAR),\'<br>\',
                            cast(ifnull(kf_CustomerID,\'\')as CHAR),\'<br>\',
                            cast(ifnull(kp_OrderShipID,\'\') as CHAR),\'<br>\',
                            cast(ifnull(nb_ShippingTBD,\'\') as CHAR),\'<br>\',
                            cast(ifnull(nb_HideOnWorkOrder,\'\') as CHAR),\'<br>\',
                            cast(ifnull(OrderShip.t_Notes,\'\')as CHAR)
                          ) as shipperIDShipperServiceID',false)
                  ->from('OrderShip')
                  ->join('Shippers', 'OrderShip.kf_ShipperID = Shippers.kp_ShipperID', 'left')
                  ->join('ShipperService', 'OrderShip.kf_ShipperServiceID = ShipperService.kp_ShipperServiceID', 'left')
                  ->join('OrderShipTracking', 'OrderShip.kp_OrderShipID = OrderShipTracking.kf_OrderShipID', 'left')
                  ->where($where);

                  $query = $this->db->get();

                  //return $query->result();
                  return $query->row();


        }
        else
        {
            //echo "inside order ".$orderID;
            $where = "OrderShip.kf_OrderID = '$orderID'";
            //echo $where;
             $this->db
                  ->select('kp_OrderShipID as ID,
                  CONCAT(   CAST(IFNULL(t_Company,\'\') AS CHAR),\'<br>\',
                            CAST(IFNULL(t_ShipperService,\'\') AS CHAR),\'<br>\',
                            CAST(IFNULL(t_BillTransportToAccount,\'\') AS CHAR),\'<br>\',
                            CAST(IFNULL(t_AccountNumber,\'\') AS CHAR),\'<br>\',
                            CAST(IFNULL(t_ShipWithOrderID,\'\') AS CHAR),\'<br>\',
                            CAST(IFNULL(OrderShip.t_Notes,\'\') as CHAR)
                        ) as Details,
                  if(nb_ShippingTBD = \'1\', \'To be Determined\',
                  CONCAT(   cast(ifnull(t_RecipientCompany,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientContact,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientAddress1,\'\')as CHAR),\' \',
                            cast(ifnull(t_RecipientAddress2,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientCountry,\'\')as CHAR),\' \',
                            cast(ifnull(t_RecipientCity,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientState,\'\')as CHAR),\' \',
                            cast(ifnull(t_RecipientPostalCode,\'\')as CHAR),\'<br> \',
                            cast(ifnull(t_RecipientPhone,\'\')as CHAR)
                        )
                    )as Receipeint,
                  concat(    cast(ifnull(t_SenderCompany,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderContact,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderAddress1,\'\')as CHAR),\' \',
                            cast(ifnull(t_SenderAddress2,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderCountry,\'\')as CHAR),\' \',
                            cast(ifnull(t_SenderCity,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderState,\'\')as CHAR),\' \',
                            cast(ifnull(t_SenderPostalCode,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderPhone,\'\')as CHAR)
                       ) as Blind,
                  GROUP_CONCAT( cast(ifnull(t_TrackingID,\'\') as CHAR) SEPARATOR \'<br>\')
                        as Tracking,
                  concat(    cast(OrderShip.kf_ShipperID as CHAR),\'<br>\',
                            cast(kf_ShipperServiceID as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientCompany,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientContact,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientAddress1,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientAddress2,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientCountry,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientCity,\'\')as CHAR),\'<br> \',
                            cast(ifnull(t_RecipientState,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientPostalCode,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientPhone,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientEmail,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientFax,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_RecipientMobile,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderCompany,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderContact,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderAddress1,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderAddress2,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderCountry,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderCity,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderState,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderPostalCode,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderPhone,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderEmail,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderFax,\'\')as CHAR),\'<br>\',
                            cast(ifnull(t_SenderMobile,\'\')as CHAR),\'<br>\',
                            cast(ifnull(kf_CustomerShipBlindFromID,\'\')as CHAR),\'<br>\',
                            cast(ifnull(kf_CustomerShipToID,\'\')as CHAR),\'<br>\',
                            cast(ifnull(OrderShip.kf_OrderID,\'\')as CHAR),\'<br>\',
                            cast(ifnull(kf_CustomerID,\'\')as CHAR),\'<br>\',
                            cast(ifnull(kp_OrderShipID,\'\') as CHAR),\'<br>\',
                            cast(ifnull(nb_ShippingTBD,\'\') as CHAR),\'<br>\',
                            cast(ifnull(nb_HideOnWorkOrder,\'\') as CHAR),\'<br>\',
                            cast(ifnull(OrderShip.t_Notes,\'\')as CHAR)
                          ) as shipperIDShipperServiceID',false)
                  ->from('OrderShip')
                  ->join('Shippers', 'OrderShip.kf_ShipperID = Shippers.kp_ShipperID', 'left')
                  ->join('ShipperService', 'OrderShip.kf_ShipperServiceID = ShipperService.kp_ShipperServiceID', 'left')
                  ->select('t_ShipperService,kp_ShipperID,kp_ShipperServiceID')
                  ->join('OrderShipTracking', 'OrderShip.kp_OrderShipID = OrderShipTracking.kf_OrderShipID', 'left')
                  ->where($where)
                  ->group_by('OrderShip.kp_OrderShipID');

                  $query = $this->db->get();

                  return $query->result();
                  //return $query->row();

        }



    }

    public function updateOrderShipTbl($data,$orderShipID)
    {
        $result = $this->db->update('OrderShip', $data, array('kp_OrderShipID'=>  $orderShipID));

        if(!$result)
        {
            return $this->db->_error_message();
        }
        else
        {
            return $this->db->affected_rows();

        }

    }
    public function updateAction()
    {
        $data = array(
            'kf_CustomerShipToID'=> $this->input->post('receipeintCustomerShipToIDHidden',true),
            'kf_CustomerID'=> $this->input->post('receipeintCustomerIDHidden',true),
            'kf_OrderID'=> $this->input->post('orderIDHidden',true),
            't_RecipientCompany'=> $this->input->post('receipeintCompanyNameHidden',true),
            't_RecipientContact'=> $this->input->post('receipeintContactNameHidden',true),
            't_RecipientAddress1'=> $this->input->post('receipeintAddressNameHidden',true),
            't_RecipientAddress2'=> $this->input->post('receipeintAddressNameHidden2',true),
            't_RecipientCountry'=> $this->input->post('receipeintCountryNameHidden',true),
            't_RecipientCity'=> $this->input->post('receipeintCityNameHidden',true),
            't_RecipientState'=> $this->input->post('receipeintStateNameHidden',true),
            't_RecipientPostalCode'=> $this->input->post('receipeintZipCodeNameHidden',true),
            't_RecipientEmail'=> $this->input->post('receipeintEmailNameHidden',true),
            't_RecipientFax'=> $this->input->post('receipeintFaxNameHidden',true),
            't_RecipientMobile'=> $this->input->post('receipeintMobileNameHidden',true),
            't_RecipientPhone'=> $this->input->post('receipeintPhoneNameHidden',true),
            'kf_ShipperID'=> $this->input->post('shipperInfo',true),
            'kf_ShipperServiceID'=> $this->input->post('ShipperServiceID',true),
            't_BillTransportToAccount'=> $this->input->post('billTo',true),
            't_AccountNumber'=> $this->input->post('billAcountNumber',true),
            't_ShipWithOrderID'=> $this->input->post('shipWithOrderID',true),
            'nb_HideOnWorkOrder'=> $this->input->post('hideOnWorkOrder',true),
            't_SenderCompany'=> $this->input->post('blindCompanyNameHidden',true),
            't_SenderContact'=> $this->input->post('blindContactNameHidden',true),
            't_SenderAddress1'=> $this->input->post('blindAddressNameHidden',true),
            't_SenderAddress2'=> $this->input->post('blindAddressNameHidden2',true),
            't_SenderCountry'=> $this->input->post('blindCountryNameHidden',true),
            't_SenderCity'=> $this->input->post('blindCityNameHidden',true),
            't_SenderState'=> $this->input->post('blindStateNameHidden',true),
            't_SenderPostalCode'=> $this->input->post('blindZipCodeNameHidden',true),
            't_SenderEmail'=> $this->input->post('blindEmailNameHidden',true),
            't_SenderFax'=> $this->input->post('blindFaxNameHidden',true),
            't_SenderMobile'=> $this->input->post('blindMobileNameHidden',true),
            't_SenderPhone'=> $this->input->post('blindPhoneNameHidden',true),
            'kf_CustomerShipBlindFromID'=> $this->input->post('blindCustomerShipToIDHidden',true),
            't_Notes'=>  $this->input->post('notes',true),
            'nb_ShippingTBD'=>$this->input->post('nb_ShippingTBD',true)
        );

        //print_r($data)."<br/>";

        $this->db->update('OrderShip', $data, array('kp_OrderShipID'=>  $this->input->post('orderShipIDHidden',true)));
        //$this->db->update('users', $data, array('id'=>  $this->input->post('id',true)));


    }

// not used as shipDChaining does both jobs.
//    public function shipD($orderID)
//    {
//        $query = $this->db->query("SELECT kp_OrderShipID as ID,
//					concat(
//                                                cast(ifnull(t_Company,'') as CHAR),'<br>',
//                                                cast(ifnull(t_ShipperService,'')as CHAR),'<br>',
//                                                cast(ifnull(t_BillTransportToAccount,'')as CHAR),'<br>',
//                                                cast(ifnull(t_AccountNumber,'')as CHAR),'<br>',
//                                                cast(ifnull(t_ShipWithOrderID,'')as CHAR),'<br>',
//                                                cast(ifnull(OrderShip.t_Notes,'')as CHAR)
//                                                ) as Details,
//                                        if(nb_ShippingTBD = '1', 'To be Determined',concat(
//                                                cast(ifnull(t_RecipientCompany,'')as CHAR),'<br>',
//                                                cast(ifnull(t_RecipientContact,'')as CHAR),'<br>',
//                                                cast(ifnull(t_RecipientAddress1,'')as CHAR),' ',
//                                                cast(ifnull(t_RecipientAddress2,'')as CHAR),'<br> ',
//                                                cast(ifnull(t_RecipientCountry,'')as CHAR),' ',
//                                                cast(ifnull(t_RecipientCity,'')as CHAR),'<br> ',
//                                                cast(ifnull(t_RecipientState,'')as CHAR),' ',
//                                                cast(ifnull(t_RecipientPostalCode,'')as CHAR),'<br> ',
//                                                cast(ifnull(t_RecipientPhone,'')as CHAR)
//                                                ))as Receipeint,
//                                        concat(
//                                                cast(ifnull(t_SenderCompany,'')as CHAR),'<br>',
//                                                cast(ifnull(t_SenderContact,'')as CHAR),'<br>',
//                                                cast(ifnull(t_SenderAddress1,'')as CHAR),' ',
//                                                cast(ifnull(t_SenderAddress2,'')as CHAR),'<br>',
//                                                cast(ifnull(t_SenderCountry,'')as CHAR),' ',
//                                                cast(ifnull(t_SenderCity,'')as CHAR),'<br>',
//                                                cast(ifnull(t_SenderState,'')as CHAR),' ',
//                                                cast(ifnull(t_SenderPostalCode,'')as CHAR),'<br>',
//                                                cast(ifnull(t_SenderPhone,'')as CHAR)
//                                                ) as Blind,
//                                        GROUP_CONCAT(
//                                                cast(ifnull(t_TrackingID,'') as CHAR) SEPARATOR '<br>' ) as Tracking,
//                                        concat(
//                                                cast(OrderShip.kf_ShipperID as CHAR),'<br>',
//                                                cast(kf_ShipperServiceID as CHAR),'<br>',
//                                                cast(ifnull(t_RecipientCompany,'')as CHAR),'<br>',
//                                                cast(ifnull(t_RecipientContact,'')as CHAR),'<br>',
//                                                cast(ifnull(t_RecipientAddress1,'')as CHAR),'<br>',
//                                                cast(ifnull(t_RecipientAddress2,'')as CHAR),'<br>',
//                                                cast(ifnull(t_RecipientCountry,'')as CHAR),'<br>',
//                                                cast(ifnull(t_RecipientCity,'')as CHAR),'<br> ',
//                                                cast(ifnull(t_RecipientState,'')as CHAR),'<br>',
//                                                cast(ifnull(t_RecipientPostalCode,'')as CHAR),'<br>',
//                                                cast(ifnull(t_RecipientPhone,'')as CHAR),'<br>',
//                                                cast(ifnull(t_RecipientEmail,'')as CHAR),'<br>',
//                                                cast(ifnull(t_RecipientFax,'')as CHAR),'<br>',
//                                                cast(ifnull(t_RecipientMobile,'')as CHAR),'<br>',
//                                                cast(ifnull(t_SenderCompany,'')as CHAR),'<br>',
//                                                cast(ifnull(t_SenderContact,'')as CHAR),'<br>',
//                                                cast(ifnull(t_SenderAddress1,'')as CHAR),'<br>',
//                                                cast(ifnull(t_SenderAddress2,'')as CHAR),'<br>',
//                                                cast(ifnull(t_SenderCountry,'')as CHAR),'<br>',
//                                                cast(ifnull(t_SenderCity,'')as CHAR),'<br>',
//                                                cast(ifnull(t_SenderState,'')as CHAR),'<br>',
//                                                cast(ifnull(t_SenderPostalCode,'')as CHAR),'<br>',
//                                                cast(ifnull(t_SenderPhone,'')as CHAR),'<br>',
//                                                cast(ifnull(t_SenderEmail,'')as CHAR),'<br>',
//                                                cast(ifnull(t_SenderFax,'')as CHAR),'<br>',
//                                                cast(ifnull(t_SenderMobile,'')as CHAR),'<br>',
//                                                cast(ifnull(kf_CustomerShipBlindFromID,'')as CHAR),'<br>',
//                                                cast(ifnull(kf_CustomerShipToID,'')as CHAR),'<br>',
//                                                cast(ifnull(OrderShip.kf_OrderID,'')as CHAR),'<br>',
//                                                cast(ifnull(kf_CustomerID,'')as CHAR),'<br>',
//                                                cast(ifnull(kp_OrderShipID,'') as CHAR),'<br>',
//                                                cast(ifnull(nb_ShippingTBD,'') as CHAR),'<br>',
//                                                cast(ifnull(nb_HideOnWorkOrder,'') as CHAR),'<br>',
//                                                cast(ifnull(OrderShip.t_Notes,'')as CHAR)
//                                              ) as shipperIDShipperServiceID
//
//                                   FROM basetables2.OrderShip
//                                   left join Shippers
//                                   on OrderShip.kf_ShipperID = Shippers.kp_ShipperID
//                                   left join ShipperService
//                                   on OrderShip.kf_ShipperServiceID = ShipperService.kp_ShipperServiceID
//                                   LEFT JOIN basetables2.OrderShipTracking
//                                   on OrderShip.kp_OrderShipID = OrderShipTracking.kf_OrderShipID
//                                   WHERE OrderShip.kf_OrderID = '$orderID'
//                                GROUP BY OrderShip.kp_OrderShipID"
//                                 );
//
//
//                    return $query->result();
//
//    }


}
?>
