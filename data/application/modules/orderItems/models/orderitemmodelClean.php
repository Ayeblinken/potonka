<?php

class OrderItemModel extends CI_Model
{
    var $imageUploadPath;
    //put your code here
    public function __construct()
    {
        //Server image path
        $this->imageUploadPath = realpath(APPPATH . '../../../images/Orders');

        $this->loadImagePath   = 'https://'.$_SERVER['SERVER_NAME'].'/images/Orders';

        $this->load->library('image_lib');
    }
    public function orderItemTableData($orderItemID)
    {
        $this->db
                 ->select('kp_OrderItemID,t_Description, t_Structure,
                     kf_OrderID,kf_CustomerID,n_Quantity,n_HeightInInches,n_WidthInInches,n_Price,
                     t_SportJobNumber,t_SportItemNumber,t_SportLocationNumber')
                 ->from('OrderItems')
                 ->where('kp_OrderItemID',$orderItemID);

         $query = $this->db->get();

         return $query->row_array();

    }
    public function orderItemInvoiceDetails($orderID)
    {
        $orderItemComponentShowInvoice = 1;

        $query = $this->db->query("SELECT  OrderItems.kp_OrderItemID AS ID, OrderItems.nb_DoNotInvoice AS doNotInvoice,
            concat(CAST(OrderItems.kf_OrderID AS char),'-',CAST(OrderItems.n_DashNum AS char)) as orderIDDashNum,
            OrderItems.n_Quantity AS Qty,
            Concat(ProductBuilds.t_Category,' ', '>',' ', ProductBuilds.t_Name,' ',' ', ifnull(newTable1.BuildItemName,'')) as product,
            OrderItems.t_Description AS description,
            OrderItems.t_Pricing AS pricingType,
			 if(OrderItems.t_Pricing = \"SQ.FT. Pricing\",
			  ROUND(((n_HeightInInches*n_WidthInInches)/144)*OrderItems.n_Price,2),
			  ROUND(OrderItems.n_Price,2)) AS price,
                          ROUND(OrderItems.n_Quantity * (if(OrderItems.t_Pricing = \"SQ.FT. Pricing\",
			  ROUND(
                                ((n_HeightInInches*n_WidthInInches)/144)*OrderItems.n_Price
                             ,2),
			  ROUND(
                                 OrderItems.n_Price,
                              2)
                               )),2) as total
        FROM basetables2.OrderItems
        INNER JOIN ProductBuilds ON ProductBuilds.kp_ProductBuildID = OrderItems.kf_ProductBuildID
        LEFT JOIN
        (
            SELECT OrderItemComponents.kf_OrderItemID,GROUP_CONCAT(DISTINCT BuildItems.t_Name) as BuildItemName
            FROM basetables2.OrderItemComponents
            INNER JOIN BuildItems
            ON OrderItemComponents.kf_BuildItemID = BuildItems.kp_BuildItemID
            WHERE OrderItemComponents.kf_OrderID = '$orderID' and  OrderItemComponents.nb_ShowOnInvoice = '$orderItemComponentShowInvoice'
            GROUP BY OrderItemComponents.kf_OrderItemID
        ) as newTable1
        ON OrderItems.kp_OrderItemID =newTable1.kf_OrderItemID
        WHERE OrderItems.kf_OrderID = '$orderID'");

//        $this->db
//             ->select('OrderItems.kp_OrderItemID as ID, ifnull(OrderItems.nb_DoNotInvoice,\'\') as doNotInvoice,
//                concat(OrderItems.kp_OrderItemID, \'-\', OrderItems.n_DashNum) as orderIDDashNum,
//                 OrderItems.n_Quantity as Qty,
//                 Concat(ProductBuilds.t_Category, \'>\', ProductBuilds.t_Name, \'(\',GROUP_CONCAT(BuildItems.t_Name), \')\') AS product,
//                        OrderItems.t_Description as description,
//                        OrderItems.t_Pricing as pricingType,
//                        OrderItems.n_Price as price,
//                        OrderItems.n_Quantity*OrderItems.n_Price as total',false)
//             ->from('OrderItemComponents')
//             ->join('BuildItems', 'OrderItemComponents.kf_BuildItemID = BuildItems.kp_BuildItemID', 'inner')
//             ->join('OrderItems', 'OrderItems.kp_OrderItemID = OrderItemComponents.kf_OrderItemID', 'inner')
//             ->join('ProductBuilds', 'ProductBuilds.kp_ProductBuildID = OrderItems.kf_ProductBuildID', 'inner')
//             ->where('OrderItemComponents.kf_OrderID',$orderID)
//             ->where('OrderItemComponents.nb_ShowOnInvoice',$orderItemComponentShowInvoice)
//             ->group_by('OrderItems.kp_OrderItemID');

        //$query = $this->db->get();

        return $query->result_array();

    }
    public function orderItemJobStatus($orderItemID)
    {
        $this->db
                ->select('kp_OrderItemID,kf_OrderID,ifnull(t_OiStatus,\'\') as orderItemJobStatus,n_DashNum',false)
                 ->from('OrderItems')
                 ->where('kp_OrderItemID',$orderItemID);

        $query = $this->db->get();

        return $query->row_array();


    }
    public function updateAllOrderItemStatus($jobStatus,$orderID)
    {
         $data = array(
            't_OiStatus'=> $jobStatus
             );
         $this->db->update('OrderItems', $data, array('kf_OrderID'=>  $orderID));

    }
    public function updateOrderItemStatus($jobStatus,$orderItemID)
    {
        $data = array(
            't_OiStatus'=> $jobStatus
             );
         $this->db->update('OrderItems', $data, array('kp_OrderItemID'=>  $orderItemID));

    }
    public function updateOrderItemTable($orderItemID,$data)
    {
        $this->db->where('kp_OrderItemID', $orderItemID);
        $this->db->update('OrderItems',$data);

    }
    public function getOrderItemByID($orderItemID)
    {
         $query = $this->db->get_where('OrderItems',array('kp_OrderItemID'=>$orderItemID));

         return $query->result_array();

    }
    public function getOrderItemByOrderID($orderID)
    {
         $query = $this->db->get_where('OrderItems',array('kf_OrderID'=>$orderID));

         return $query->result_array();

    }
    public function getOrderItemRowsByOrderIDResultObject($orderID,$dateReceived)
    {
        $dateOrderReceivedArr       = explode("-", $dateReceived);

        $yearOrder                  = $dateOrderReceivedArr[0];

        $monthOrder                 = $dateOrderReceivedArr[1];

        //$query = $this->db->query("SELECT * FROM OrderItems  WHERE kf_OrderID ='$orderID'");
//        $query = $this->db->query("SELECT Orders.d_Received,
//                                    OrderItems.kp_OrderItemID,
//                                    OrderItems.t_OrderItemImage,
//                                    OrderItems.t_OrderItemProof,
//                                    OrderItems.n_DashNum,
//                                    OrderItems.kf_OrderID,
//                                    OrderItems.n_Quantity,
//                                    OrderItems.n_HeightInInches,
//                                    OrderItems.n_WidthInInches,
//                                    OrderItems.t_ProductType,
//                                    OrderItems.t_Description,
//                                    OrderItems.t_Structure,
//                                    OrderItems.nb_ArtReceivedProduction,
//                                    OrderItems.t_ArtReceivedBy,
//                                    OrderItems.d_ArtReceived,
//                                    OrderItems.t_ArtContact,
//                                    OrderItems.t_OiStatus
//                                    FROM OrderItems INNER JOIN Orders ON OrderItems.kf_OrderID = Orders.kp_OrderID
//                                    WHERE kf_OrderID = '$orderID'");
//
//        return $query->result();


        $this->datatables->select('concat(OrderItems.kf_OrderID,"-",OrderItems.n_DashNum) as IDNum,
                                    OrderItems.n_Quantity as Qty,
                                    concat(TRIM(TRAILING "." FROM(ifnull(CAST(TRIM(TRAILING "0" FROM OrderItems.n_HeightInInches)as CHAR),"")))
                                        ," H x ",TRIM(TRAILING "." FROM(ifnull(CAST(TRIM(TRAILING "0" FROM OrderItems.n_WidthInInches)as CHAR),"")))," W") as size,
                                    OrderItems.t_ProductType as product,
                                    OrderItems.t_Description as des,
                                    OrderItems.t_Structure as ID,
                                    concat(ifnull(nullif(OrderItems.nb_ArtReceivedProduction,0),""),"<br/>",
                                    ifnull(nullif(OrderItems.t_ArtReceivedBy,""),"")," ",ifnull(if(OrderItems.d_ArtReceived = "0000-00-00","",OrderItems.d_ArtReceived),"")," ",
                                    ifnull(nullif(OrderItems.t_ArtContact,""),"")," ",
                                    ifnull(nullif(OrderItems.t_OiStatus,""),"")) as artInfo,
                                    OrderItems.n_DashNum as DashNum,
                                    OrderItems.kp_OrderItemID,
                                    OrderItems.kf_OrderID,
                                    OrderItems.t_OrderItemImage,
                                    OrderItems.n_HeightInInches,
                                    OrderItems.n_WidthInInches',false)
                ->edit_column('ID#','<a href="orderItemUpSideFrm/read/$1">$2</a>','OrderItems.kp_OrderItemID,IDNum')
                ->add_column('Picture','<img src="../../images/Orders/'.$yearOrder.'/'.$monthOrder.'/'.'$1/$2/$3 " height="200" width="250">','OrderItems.kf_OrderID,OrderItems.kp_OrderItemID,OrderItems.t_OrderItemImage')
                ->from('OrderItems')
                ->where('OrderItems.kf_OrderID',$orderID)
                ->join('Orders', 'OrderItems.kf_OrderID = Orders.kp_OrderID');

        return $this->datatables->generate();

    }
    public function deleteOrderItemDataFromOrderItemID($orderItemID)
    {
        $this->db->delete('OrderItems', array('kp_OrderItemID' => $orderItemID));

    }
    public function insertOrderItemTable($orderItemArray)
    {
         $lenArr                  = sizeof($orderItemArray);
         //echo $lenArr."<br/><br/>";
         //print_r($orderItemArray);
         //echo "<br/><br/>";
         for($i = 0; $i<$lenArr; $i++)
         {
             $this->db->insert('OrderItems', $orderItemArray[$i]);

         }
        //print_r($orderItemArray[0]);
        //$this->db->insert('OrderItems', $orderItemArray[0]);

        return $this->db->insert_id();


    }
    public function orderItemDashNumber($orderID)
    {
         $this->db
                 ->select('COUNT(kp_OrderItemID) as dashNum',false)
                 ->from('OrderItems')
                 ->where('kf_OrderID',$orderID);

         $query = $this->db->get();

         return $query->row_array();

    }
    public function orderIDWithDashNumber($orderID)
    {
        $query = $this->db->query("SELECT kp_OrderItemID,
                                    CONCAT(cast(kf_OrderID as CHAR),CAST('-' AS CHAR),CAST(n_DashNum as CHAR)) as orderIDDashNum
                                    FROM OrderItems
                                    WHERE kf_OrderID ='$orderID'");
//        $this->db
//                 ->select('kp_OrderItemID,CONCAT(cast(kf_OrderID as CHAR),CAST('-' AS CHAR),CAST(n_DashNum as CHAR)) as orderIDDashNum',false)
//                 ->from('OrderItems')
//                 ->where('kf_OrderID',$orderID);

         //$query = $this->db->get();

         return $query->result_array();

    }
    public function getOrderItemIDFromOrderIDDashNum($orderID,$dashNum)
    {
        $query = $this->db->get_where('OrderItems',array('kf_OrderID'=>$orderID,'n_DashNum' => $dashNum));

        return $query->row_array();

    }
    public function deleteProofImage($orderItemID,$orderID,$dateReceived)
    {
        $dateOrderReceivedArr = explode("-", $dateReceived);

        $yearOrder            = $dateOrderReceivedArr[0];

        $monthOrder           = $dateOrderReceivedArr[1];

        $path                 = $this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID;

        $msg                  = "";
        if(file_exists($path.'/'.$orderItemID.'/'."proof"))
        {
            $files = scandir( $path.'/'.$orderItemID.'/'."proof");
            //var_dump($files);
            $files = array_diff($files,array('.','..','.DS_Store'));
            //var_dump($files);
            foreach($files as $file)
            {
                $msg = unlink($path.'/'.$orderItemID.'/'."proof".'/'.$file); // remove any image files.
            }

            return $msg;
        }

    }
    public function getProofImageContent($orderItemID,$orderID,$dateReceived)
    {
        $dateOrderReceivedArr = explode("-", $dateReceived);

        $yearOrder               = $dateOrderReceivedArr[0];

        $monthOrder              = $dateOrderReceivedArr[1];
        $orderItemArry           = $this->getOrderItemByID($orderItemID);

        $orderItemProofImageName = $orderItemArry[0]['t_OrderItemProof'];



        $path                 = $this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID;
        $full_path            = "";
        if(!is_null($orderItemProofImageName))
        {
            $full_path=$path.'/'.$orderItemID.'/'.$orderItemProofImageName;

        }
        if(file_exists($full_path))
        {
            $files = scandir( $path.'/'.$orderItemID);
            //var_dump($files);
            $files = array_diff($files,array('.','..','.DS_Store'));

            $imagePath           = $this->loadImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID.'/'.$orderItemProofImageName;
            $actualImageName     = $orderItemProofImageName;

            $image[] = array(
            'imageUrl' => $imagePath,
            'imageName' => $actualImageName);

            return $image;

//            foreach($files as $file)
//            {
//                if($file == $orderItemProofImageName)
//                {
//                    echo $file;
//                    $imagePath           = $this->loadImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID.'/'.$file;
//                    $actualImageName     = $file;
//
//                    $image[] = array(
//                   'imageUrl' => $imagePath,
//                   'imageName' => $actualImageName);
//                }
//                return $image;
//
//            }
        }
        else
        {
            return null;
        }

    }
    public function doProofCustomUpload($orderItemID,$orderID,$dateReceived)
    {
        $msg = "";
        //$orderArry    = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$orderID);
        //$dateReceived = $orderArry['d_Received'];
        //check for the mount file if mount file exist do uploading or exit the uploading process.
        $allowed = array('jpeg','jpg');

        if(file_exists(realpath(APPPATH . '../../../images/.am_i_mounted'))&& !is_null($dateReceived))
        {
            $dateOrderReceivedArr  = explode("-", $dateReceived);

             $yearOrder            = $dateOrderReceivedArr[0];

            $monthOrder            = $dateOrderReceivedArr[1];


            if(isset($_FILES['proofUploadImage']) && $_FILES['proofUploadImage']['error'] == 0)
            {
                $extension = pathinfo($_FILES['proofUploadImage']['name'], PATHINFO_EXTENSION);
                if(!in_array(strtolower($extension), $allowed))
                {
                     echo '{"status":"error"}';
                     exit;
                }
                $tmpName          = $_FILES['proofUploadImage']['tmp_name'];

                $uploadedFileName = $_FILES['proofUploadImage']['name'];
                $uploadedFileName = "proof_".$uploadedFileName;
                // checks and creates Year and Month Folder
                if(!is_dir($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder))
                {
                    if(!mkdir($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder,0777,TRUE))
                    {
                        die("Failed to create Year and Month Folders");
                    }

                }
                $path              = $this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID;

                // checks and creates the Order Folder
                if(!is_dir($path)) // checks if the order# has a folder or not
                {
                    if(!mkdir($path.'/'.$orderItemID,0777,TRUE))
                    {
                        die('Failed to create Order and other folders...');
                    }
                    else
                    {
                        chmod($path, 0777);

                         //change the directory owner/group permission for OrderItemID folder
                        chmod($path.'/'.$orderItemID, 0777);
                    }
                }

                // checks and creates the OrderItem Folder
                if(!is_dir($path.'/'.$orderItemID)) // checks if the orderitemid# has a folder or not, if the order# already has a folder.
                {
                    if(!mkdir($path.'/'.$orderItemID,0777,TRUE))
                    {
                        die('Failed to create OrderItem and other folders...');
                    }
                    else
                    {
                        //change the directory owner/group permission for OrderItemID folder
                        chmod($path.'/'.$orderItemID, 0777);
                    }
                }
                $newFileName      = $path.'/'.$orderItemID.'/'.$uploadedFileName;
                if(move_uploaded_file($tmpName, $newFileName))
                {
                    //echo '{"status":"success"}';

                    $tmp = getimagesize($newFileName);

                    //set the maxWidth and maxHeight Values
                    $maxWidth  = 1500;
                    $maxHeight = 1200;
                    if($tmp[0] <= $maxWidth && $tmp[1] <= $maxHeight)
                    {

                         //[ THUMB IMAGE ]
                         $img_config_0['image_library']   = 'gd2';
                         $img_config_0['source_image']    = $newFileName;
                         $img_config_0['maintain_ratio']  = TRUE;
                         $img_config_0['width']           = 250;
                         $img_config_0['height']          = 200;
                         $img_config_0['create_thumb']    = TRUE;
                         $this->image_lib->initialize($img_config_0);
                         if($this->image_lib->resize())
                         {
                             $this->image_lib->clear();
                             $imageProofResize['thumbNailCreation'] = "yes";

                             $imageProofResize['msg']               = "success";

                         }
                         else
                         {
                             $imageProofResize['thumbNailCreation'] = "no";
                             $imageProofResize['thumbImageError']   = "Failed.". $this->image_lib->display_errors();

                         }


                    }
                    else
                    {
                         //[ THUMB IMAGE ]
                         $img_config_0['image_library']   = 'gd2';
                         $img_config_0['source_image']    = $newFileName;
                         $img_config_0['maintain_ratio']  = TRUE;
                         $img_config_0['width']           = 250;
                         $img_config_0['height']          = 200;
                         $img_config_0['create_thumb']    = TRUE;

                         //[ MAIN IMAGE ]
                         $img_config_1['image_library']   = 'gd2';
                         $img_config_1['source_image']    = $newFileName;
                         $img_config_1['maintain_ratio']  = TRUE;
                         $img_config_1['width']           = 1500;
                         $img_config_1['height']          = 1200;
                         $img_config_1['create_thumb']    = FALSE;

                         for($i=0;$i<2;$i++)
                         {
                             eval("\$this->image_lib->initialize(\$img_config_".$i.");");
                             if($this->image_lib->resize())
                             {
                                 $this->image_lib->clear();
                                 $imageProofResize['thumbNailCreation']   = "yes";
                                 $imageProofResize['originalImageReSize'] = "yes";
                                 $imageProofResize['msg']               = "success";

                             }
                            else
                            {
                                 $imageProofResize['thumbNailCreation']   = "no";
                                 $imageProofResize['originalImageReSize'] = "no";
                                 $imageProofResize['thumbImageError']     = "Failed." .$i . $this->image_lib->display_errors();
                            }
                         }

                       }

                        // do the update here
                       $data = array(
                          't_OrderItemProof'=> $uploadedFileName
                       );

                       $this->updateOrderItemTable($orderItemID, $data);

                       return $imageProofResize;
                }
            }
        }

    }
    public function doPrepressCustomUpload($orderItemID,$orderID,$dateReceived,$inputFile)
    {

        $allowed = array('jpeg','jpg','pdf');

        if(file_exists(realpath(APPPATH . '../../../images/.am_i_mounted'))&& !is_null($dateReceived))
        {
            $dateOrderReceivedArr = explode("-", $dateReceived);

            $yearOrder            = $dateOrderReceivedArr[0];

            $monthOrder           = $dateOrderReceivedArr[1];


            if(isset($_FILES[$inputFile]) && $_FILES[$inputFile]['error'] == 0)
            {
                $extension = pathinfo($_FILES[$inputFile]['name'], PATHINFO_EXTENSION);

                if(!in_array(strtolower($extension), $allowed))
                {
                     echo '{"status":"error"}';
                     exit;
                }
                $tmpName          = $_FILES[$inputFile]['tmp_name'];

                $uploadedFileName = $_FILES[$inputFile]['name'];

                if(!is_dir($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder))
                {
                    if(!mkdir($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder,0777,TRUE))
                    {
                        die("Failed to create Year and Month Folders");
                    }

                }
                $path              = $this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID;

                if(!is_dir($path))
                {
                    if(!mkdir($path.'/'.$orderItemID,0777,TRUE))
                    {
                        die('Failed to create Order and other folders...');
                    }
                    else
                    {
                        chmod($path, 0777);


                        chmod($path.'/'.$orderItemID, 0777);
                    }
                }

                // checks and creates the OrderItem Folder
                if(!is_dir($path.'/'.$orderItemID)) // checks if the orderitemid# has a folder or not, if the order# already has a folder.
                {
                    if(!mkdir($path.'/'.$orderItemID,0777,TRUE))
                    {
                        die('Failed to create OrderItem and other folders...');
                    }
                    else
                    {
                        //change the directory owner/group permission for OrderItemID folder
                        chmod($path.'/'.$orderItemID, 0777);
                    }
                }

                $newFileName      = $path.'/'.$orderItemID.'/'.$uploadedFileName;
                if(move_uploaded_file($tmpName, $newFileName))
                {


                    //get the image size
                    $tmp = getimagesize($newFileName);

                    //set the maxWidth and maxHeight Values
                    $maxWidth  = 1500;
                    $maxHeight = 1200;



                    if($inputFile == "upl")
                    {
                        $imageResize['customer']          = "nonSportCustomer";

                        if($tmp[0] <= $maxWidth && $tmp[1] <= $maxHeight)
                        {
                            //[ THUMB IMAGE ]
                            $img_config_0['image_library']   = 'gd2';
                            $img_config_0['source_image']    = $newFileName;
                            $img_config_0['maintain_ratio']  = TRUE;
                            $img_config_0['width']           = 250;
                            $img_config_0['height']          = 200;
                            $img_config_0['create_thumb']    = TRUE;
                            $this->image_lib->initialize($img_config_0);

                            if($this->image_lib->resize())
                            {
                                $this->image_lib->clear();
                                $imageResize['thumbNailCreation'] = "yes";

                                $imageResize['msg']               = "success";
                                //echo "Success";
                            }
                            else
                            {
                                $imageResize['thumbNailCreation'] = "no";
                                $imageResize['thumbImageError']   = "Failed.". $this->image_lib->display_errors();
                                //echo "Failed." .$i . $this->image_lib->display_errors();
                            }


                        }
                        //resize orignial Image and create the thumbnail
                        else
                        {

                            $img_config_0['image_library']   = 'gd2';
                            $img_config_0['source_image']    = $newFileName;
                            $img_config_0['maintain_ratio']  = TRUE;
                            $img_config_0['width']           = 250;
                            $img_config_0['height']          = 200;
                            $img_config_0['create_thumb']    = TRUE;


                            $img_config_1['image_library']   = 'gd2';
                            $img_config_1['source_image']    = $newFileName;
                            //$img_config_1['source_image']    = $newFileName;
                            $img_config_1['maintain_ratio']  = TRUE;
                            $img_config_1['width']           = 1500;
                            $img_config_1['height']          = 1200;
                            $img_config_1['create_thumb']    = FALSE;

                            for($i=0;$i<2;$i++)
                            {
                                eval("\$this->image_lib->initialize(\$img_config_".$i.");");
                                if($this->image_lib->resize())
                                {

                                    $this->image_lib->clear();
                                    $imageResize['thumbNailCreation']   = "yes";
                                    $imageResize['originalImageReSize'] = "yes";
                                    $imageResize['msg']               = "success";
                                    //echo "Success";
                                }
                                else
                                {
                                    $imageResize['thumbNailCreation']   = "no";
                                    $imageResize['originalImageReSize'] = "no";
                                    $imageResize['thumbImageError']     = "Failed." .$i . $this->image_lib->display_errors();
                                    //echo "Failed." .$i . $this->image_lib->display_errors();
                                }
                            }

                        }
                        $data = array(
                            't_OrderItemImage'=> $uploadedFileName
                                );
                        $this->updateOrderItemTable($orderItemID, $data);


                    }
                    if($inputFile == "sportUploadFile")
                    {
                        $imageResize['customer']          = "sportCustomer";
                        if(strtolower($extension) == "pdf")
                        {
                             $quality                          = 90;
                             $res                              = '300x300';
                             $uploadedFileNameWithoutExtesnion = basename($uploadedFileName,".pdf");
                             //echo "<br/> FileNameWithoutExtension: ".$uploadedFileNameWithoutExtesnion."<br/>";
                             $exportPath                       = $path.'/'.$orderItemID.'/'.$uploadedFileNameWithoutExtesnion.'.jpg';
                             echo "export PATH: ".$exportPath."<br/>";
                             echo "NewFileName: ".$newFileName."<br/>";
                             $path.'/'.$orderItemID.'/'.$uploadedFileName;
                             //$exportPath=$path."/".$exportName."/fullres/%03d.jpg";
                             //save the pdf as an jpeg image
                             exec("'gs' '-dNOPAUSE' '-sDEVICE=jpeg' '-dUseCIEColor' '-dTextAlphaBits=4' '-dGraphicsAlphaBits=4' '-o$exportPath' '-g$res' '-dPDFFitPage' '-dJPEGQ=$quality' '$newFileName'",$output);

                             for($i=0;$i<count($output);$i++)
                             {
                                  echo($output[$i] .'<br/>');

                             }
                            $img_config_sport_0['image_library']   = 'imagemagick';
                            $img_config_sport_0['source_image']    = $exportPath;
                            $img_config_sport_0['maintain_ratio']  = TRUE;
                            $img_config_sport_0['width']           = 250;
                            $img_config_sport_0['height']          = 200;
                            $img_config_sport_0['create_thumb']    = TRUE;

                            print_r($img_config_sport_0);
                            $this->image_lib->initialize($img_config_sport_0);

                            if($this->image_lib->resize())
                            {
                                $this->image_lib->clear();
                                $imageResize['sportThumbNailCreation'] = "yes";

                                $imageResize['msg']                    = "success";
                                 //echo "Success";
                            }
                            else
                            {
                                $imageResize['sportThumbNailCreation'] = "no";
                                $imageResize['sportThumbImageError']   = "Failed.". $this->image_lib->display_errors();
                                 //echo "Failed." .$i . $this->image_lib->display_errors();
                            }
                            unlink($path.'/'.$orderItemID.'/'.$uploadedFileNameWithoutExtesnion.'.jpg'); // remove any image files.

                        }
                        else
                        {
                            $sportTmp = getimagesize($newFileName);

                            if($sportTmp[0] <= $maxWidth && $sportTmp[1] <= $maxHeight)
                            {
                                 //create thumbnail creation
                                 $img_config_sport_0['library_path']    = "/opt/local/bin";
                                 $img_config_sport_0['image_library']   = 'imagemagick';
                                 $img_config_sport_0['source_image']    = $newFileName;
                                 $img_config_sport_0['maintain_ratio']  = TRUE;
                                 $img_config_sport_0['width']           = 250;
                                 $img_config_sport_0['height']          = 200;
                                 $img_config_sport_0['create_thumb']    = TRUE;

                                 $this->image_lib->initialize($img_config_sport_0);

                                 if($this->image_lib->resize())
                                 {
                                     $this->image_lib->clear();
                                     $imageResize['sportThumbNailCreation'] = "yes";

                                     $imageResize['msg']                    = "success";
                                     //echo "Success";
                                 }
                                 else
                                 {
                                     $imageResize['sportThumbNailCreation'] = "no";
                                     $imageResize['sportThumbImageError']   = "Failed.". $this->image_lib->display_errors();
                                     //echo "Failed." .$i . $this->image_lib->display_errors();
                                 }

                             }
                             else
                             {
                                 //create thumbnail creation
                                 $img_config_sport_0['library_path']    = "/opt/local/bin";
                                 $img_config_sport_0['image_library']   = 'imagemagick';
                                 $img_config_sport_0['source_image']    = $newFileName;
                                 $img_config_sport_0['maintain_ratio']  = TRUE;
                                 $img_config_sport_0['width']           = 250;
                                 $img_config_sport_0['height']          = 200;
                                 $img_config_sport_0['create_thumb']    = TRUE;

                                 //resize the image
                                 $img_config_sport_0['library_path']    = "/opt/local/bin";
                                 $img_config_sport_1['image_library']   = 'imagemagick';
                                 $img_config_sport_1['source_image']    = $newFileName;
                                 $img_config_sport_1['maintain_ratio']  = TRUE;
                                 $img_config_sport_1['width']           = 1500;
                                 $img_config_sport_1['height']          = 1200;
                                 $img_config_sport_1['create_thumb']    = FALSE;

                                 for($i=0;$i<2;$i++)
                                 {
                                     eval("\$this->image_lib->initialize(\$img_config_sport_".$i.");");
                                     if($this->image_lib->resize())
                                     {
                                         $this->image_lib->clear();
                                         $imageResize['sportThumbNailCreation']   = "yes";
                                         $imageResize['originalImageReSize']      = "yes";
                                         $imageResize['msg']                      = "success";
                                         //echo "Success";
                                     }
                                    else
                                    {
                                        $imageResize['sportThumbNailCreation']    = "no";
                                        $imageResize['originalImageReSize']       = "no";
                                        $imageResize['sportThumbImageError']      = "Failed." .$i . $this->image_lib->display_errors();
                                    }
                                 }

                             }

                        }
                        $data = array(
                        't_DeckSheet'=> $uploadedFileName
                        );
                        $this->updateOrderItemTable($orderItemID, $data);

                    }
                    print_r($imageResize);
                    //return $msg;
                    //return $imageResize;
                    //exit;

                }
            }
        }

    }
    public function getDeckSheetFileExtension($deckSheetImageFileName)
    {
        $extensionDeckSheet         = substr($deckSheetImageFileName,-4);

        $deckSheetFileNameExtension = "";

        if(substr(strtolower($extensionDeckSheet),0) == ".pdf")
        {
            $deckSheetFileNameExtension = "pdf";

        }
        else
        {
            $deckSheetFileNameExtension = substr(strtolower($extensionDeckSheet),1);

        }

        return $deckSheetFileNameExtension;

    }
    public function getPrepressImageContent($orderItemID,$orderID,$dateReceived)
    {
        $dateOrderReceivedArr          = explode("-", $dateReceived);

        $yearOrder                     = $dateOrderReceivedArr[0];

        $monthOrder                    = $dateOrderReceivedArr[1];

        $orderItemArry                 = $this->getOrderItemByID($orderItemID);

        $orderItemPrepressImageName    = $orderItemArry[0]['t_OrderItemImage'];

        $orderItemSportImageName       = $orderItemArry[0]['t_DeckSheet'];

        //check whether the deck sheet end with pdf extension
        //if it does end with pdf extension we need to show a link and open a new window to view the pdf.
        $sportActualImageNameExtension = $this->getDeckSheetFileExtension($orderItemSportImageName);


        $path                          = $this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID;
        //echo $path;
        $image = array();

        $fullPathPrepress              = "";

        $fullPathSport                 = "";

        if(!is_null($orderItemPrepressImageName))
        {
            $fullPathPrepress = $path.'/'.$orderItemID.'/'.$orderItemPrepressImageName;

        }
        if(!is_null($orderItemSportImageName))
        {
            $fullPathSport = $path.'/'.$orderItemID.'/'.$orderItemSportImageName;

        }
        if(file_exists($fullPathSport))
        {
            $files = scandir( $path.'/'.$orderItemID);
            //var_dump($files);
            $files = array_diff($files,array('.','..','proof','.DS_Store'));

            $sportImagePath           = $this->loadImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID.'/'.$orderItemSportImageName;
            if($sportActualImageNameExtension == "pdf")
            {
                $orderItemSportImageNameWithoutExtension   = basename($orderItemSportImageName,".pdf");
                $orderItemSportImageNameWithJpegExtension  = $orderItemSportImageNameWithoutExtension."_thumb.jpg";

                $sportImagePath                            = $this->loadImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID.'/'.$orderItemSportImageNameWithJpegExtension;

            }
            else
            {
                $sportImagePath           = $this->loadImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID.'/'.$orderItemSportImageName;

            }
            $sportActualImageName     = $orderItemSportImageName;

            $image['sportImagePath']       = $sportImagePath;
            $image['sportActualImageName'] = $sportActualImageName;
            $image['$deckSheetExtension']  = $sportActualImageNameExtension;



        }
        if(file_exists($fullPathPrepress))
        {

            $files = scandir( $path.'/'.$orderItemID);
            //var_dump($files);
            $files = array_diff($files,array('.','..','proof','.DS_Store'));
            //var_dump($files);

            $imagePath           = $this->loadImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID.'/'.$orderItemPrepressImageName;

            $actualImageName     = $orderItemPrepressImageName;

             $image['imageUrl']  = $imagePath;
             $image['imageName'] = $actualImageName;


//            foreach($files as $file)
//            {
//                //echo $file;
//                $imagePath           = $this->loadImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID.'/'.$file;
//
//                $actualImageName     = $file;
//                //echo "<br/>".$imagePath."<br/>";
//                //echo $this->imageUploadPath;
//                $image[] = array(
//                   'imageUrl' => $imagePath,
//                    'imageName' => $actualImageName);
//               //var_dump($image);
//               return $image;
//
//            }
//            foreach($files as $file)
//            {
//                //$thumbExtension = "_thumb";
//                $imagePath       = $this->loadImagePath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID.'/'.$file;
//                $thumbPath      = $imagePath;
//                //$thumbPath      = $imagePath.$thumbExtension;
//                $image[] = array(
//                    'imageUrl' => $imagePath,
//                    'thumbUrl' => $thumbPath
//                );
//
//            }
//            return $image;

        }
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
