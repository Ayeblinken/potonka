<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OrderItemController extends MX_Controller
{
    //put your code here
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        $this->load->model('orderitemmodel');

    }
    public function reportParam()
    {
        $param[] = array(
                   'orderItemID' => 112234);
        foreach($param as $name=>$value)
        {
            echo "name:". $name."<br/>";
            echo "value:". $value."<br/>";
        }
    }
    public function deleteProofInfo($orderItemID,$orderID)
    {
        $proofBy        = null;
        $proofNotes     = null;
        $orderItemProof = null;

        $data = array(
        't_ProofBy'=> $proofBy,
        't_ProofNote'=> $proofNotes,
        't_OrderItemProof'  =>$orderItemProof
        );

        $this->orderitemmodel->updateOrderItemTable($orderItemID,$data);

        // get the date Received from OrderID
        $dateReceived = $this->getDateReceived($orderID);

        // delete the image from the images folder.
        $msg =  $this->orderitemmodel->deleteProofImage($orderItemID,$orderID,$dateReceived);

        return $msg;


    }
    public function readConvertedImageFiles($year=null,$month=null)
    {
        $data['year'] = '2009';

        $this->load->view('imageResizingSummary',$data);
    }
    public function checkAndConvertImageFiles($year=null,$month=null)
    {
        //$this->load->library('image_lib');
        // if no month is provided
        // get all OrderID's ---> from  the where condition YEAR(d_Received)
        // loop through each OrderID
        // check OrderID exists and is a folder on the server --> (file_exists)
        // if exists get all OrderItems that belong to that OrderID
        // check Each OrderItemID for a folder --->(file_exists)
        // if exists process orderItemImage, proofImage,DeckSheetImage
        if(!empty($month))
        {
            $monthAllowed = array('01','02','03','04','05','06','07','08','09','10','11','12');
            if(!in_array($month, $monthAllowed))
            {
                echo '{"status":"error"}';
                exit;
            }

        }

        $maxWidth=1500; //Default Value

        $maxHeight=1200;//Default Value

        $orderIDArryOnly    = Modules::run('orders/ordercontroller/getOrderIDFromMonthYearDateReceived',$year,$month);

        //var_dump($orderIDArryOnly);
        $orderOrderItemImageArry= $this->getOrderImageArry($orderIDArryOnly);

        if(!empty($orderOrderItemImageArry))
        {
            $imageResizeSummary=$this->processOrderImageArry($orderOrderItemImageArry);
        }
        //echo json_encode($imageResizeSummary);
        //var_dump($imageResizeSummary);
        $imageResizeSummaryFlatten = $this->flatten($imageResizeSummary);

        //$imageResizeSummaryFlatten = $this->array_flatten($imageResizeSummary);

        print_r($imageResizeSummaryFlatten);
        //$output = iterator_to_array(new RecursiveIteratorIterator(
        //new RecursiveArrayIterator($imageResizeSummary)), FALSE);
        //var_dump($output);
        //echo json_encode($imageResizeSummary);

        //echo "<br/>";

//        $list =(array (
//        array('aaa', 'bbb', 'ccc', 'dddd'),
//        array('123', '456', '789'),
//        array('"aaa"', '"bbb"')));

//        foreach ($list as $fields) {
//            //var_dump($fields);
//        }
        //echo "<br/>";
        //echo "<br/>";

        //$filename = realpath(APPPATH . '../../../images/Orders');


        //$f = fopen('php://memory', 'w');
//        foreach($imageResizeSummaryFlatten as $key=>$value)
//        {
//            $keyArry = array($key);
//            $valArry = array($value);
//
//        }
        //var_dump($keyArry);
        //var_dump($valArry);
        $keyCol = array_keys($imageResizeSummaryFlatten);
        //echo "<br/>";
        //var_dump($keyCol);

        $valCol = array_values($imageResizeSummaryFlatten);
        //echo "<br/>";
        //var_dump($valCol);
        $f      = fopen(realpath(APPPATH . '../../../images/Orders').'/export.csv', 'w') or die("can't open file");

        for($i =0;$i<sizeof($keyCol);$i++)
        {
            fputcsv($f, array($keyCol[$i],$valCol[$i])) ;
        }
        //fputcsv($f,array_keys($imageResizeSummaryFlatten),",");
        //fputcsv($f,$imageResizeSummaryFlatten,",");
//        foreach($imageResizeSummary as $key=>$imageResize)
//        {
//            $y=0;
//            //echo "<br/>";
//            //echo "Size of : ".sizeof($imageResize)."<br/>";
//            //echo "Key: ".$key."<br/>";
//            //var_dump($imageResize)."<br/>";
//            //array_keys($imageResize);
//            foreach($imageResize as $resizeKey=>$resizeArry)
//            {
//                //echo "Size of : ".sizeof($resizeArry)."<br/>";
//                //echo "Key: ".$resizeKey."<br/>";
//                // write out the headers
//
//                if($y==0)
//                {
//                    // write out the headers
//                    $headerOrderID= array($resizeKey);
//
//                    fputcsv($f,$headerOrderID,"\n");
//
//                    fputcsv($f, array_keys($resizeArry),",");
//                    fputcsv($f, $resizeArry,",");
//                    //$this->array_to_csv_download($resizeArry);
//                    //var_dump($resizeArry)."<br/>";
//
//                }
//
//                if($y>0)
//                {
//                    $headerOrderItemID = array($resizeKey);
//                    fputcsv($f,$headerOrderItemID,"\n");
//
//                    //fputcsv($f, array_keys($resizeArry),"\n");
//
//                }
//
//                //echo "Y value:  ".$y." <br/>";
//                $y++;
//
//
//                if(is_array($resizeArry))
//                {
//                     foreach($resizeArry as $k=>$innerArray)
//                     {
//                         if(is_array($innerArray))
//                         {
//                             $headerForOrderItemID = array($k);
//                             fputcsv($f, $headerForOrderItemID,"\n");
//                             //echo " <br/>InneryArry: <br/>";
//                             //var_dump($innerArray);
//
//
//                             fputcsv($f, array_keys($innerArray),",");
//                             fputcsv($f, $innerArray,",");
//    //                         foreach ($innerArray as $value)
//    //                         {
//    //                              var_dump($value)."<br/>";
//    //
//    //                         }
//
//                         }
//
//                     }
//
//                }
//
//
//            }
//            for($u=0;$u<sizeof($imageResize);$u++)
//            {
//                if($u==0)
//                {
//                    fputcsv($f, $imageResize, ";");
//                }
////                else
////                {
////                    if(!empty($imageResize['OrderItemImage_ProofImage_Summary']))
////                    {
////                        fputcsv($f, $imageResize['OrderItemImage_ProofImage_Summary'], ";");
////
////                    }
////                }
//
//            }


            //echo "<br/>";

        //}

        //$this->array_to_csv_download($imageResizeSummary);
        //print_r($imageResizeSummary);


        //$filename = "export.csv";
        //fseek($f, 0);


        // tell the browser it's going to be a csv file


        //header('Content-Type: application/csv');


        // tell the browser we want to save it instead of displaying it

        //header('Content-Disposition: attachement; filename="'.$filename.'"');

        // make php send the generated csv lines to the browser

        //fpassthru($f);



    }
    public function flatten($array, $prefix = '')
    {
        $result = array();
        foreach($array as $key=>$value) {
            if(is_array($value))
            {
                $result = $result + $this->flatten($value, $prefix . $key . '.');
            }
            else
            {
                $result[$prefix . $key] = $value;
            }
        }
        return $result;
    }
    public function array_flatten($array)
    {
        if (!is_array($array))
        {
            return false;
        }
        $result = array();
        foreach ($array as $key => $value)
        {
          if (is_array($value))
          {
            $result = array_merge($result, $this->array_flatten($value));
          }
          else
          {
              $result[$key] = $value;
          }
        }
        return $result;
    }
    public function array_to_csv_download($array, $filename = "export.csv", $delimiter=";")
    {
        // open raw memory as file so no temp files needed, you might run out of memory though
        $f = fopen('php://memory', 'w');
        // loop over the input array
        foreach ($array as $line) {
            // generate csv lines from the inner arrays
            fputcsv($f, $line, $delimiter);


        }
        // rewrind the "file" with the csv lines
        fseek($f, 0);
        // tell the browser it's going to be a csv file
        header('Content-Type: application/csv');
        // tell the browser we want to save it instead of displaying it
        header('Content-Disposition: attachement; filename="'.$filename.'"');
        // make php send the generated csv lines to the browser
        fpassthru($f);
    }
    public function getOrderImageArry($orderIDArryOnly)
    {
        $orderItemArry = array();
        $processOrderImageArry = array();
        for($i=0;$i<sizeof($orderIDArryOnly);$i++)
        {
            $orderItemArry = $this->getOrderItemsFromOrderID($orderIDArryOnly[$i]['kp_OrderID']);

            //var_dump($orderItemArry);
            for($x=0;$x<sizeof($orderItemArry);$x++)
            {
                //$processOrderImageArry['OrderID']         = $orderItemArry[$x]['kf_OrderID'];
                $processOrderImageArry[$orderIDArryOnly[$i]['kp_OrderID']][$x]['OrderItemID']     = $orderItemArry[$x]['kp_OrderItemID'];
                $processOrderImageArry[$orderIDArryOnly[$i]['kp_OrderID']][$x]['OrderItemImage']  = $orderItemArry[$x]['t_OrderItemImage'];
                $processOrderImageArry[$orderIDArryOnly[$i]['kp_OrderID']][$x]['DeckSheet']       = $orderItemArry[$x]['t_DeckSheet'];
                $processOrderImageArry[$orderIDArryOnly[$i]['kp_OrderID']][$x]['OrderItemProof']  = $orderItemArry[$x]['t_OrderItemProof'];
                //$processOrderImageArry$orderIDArryOnly[$i]['d_Received']][$x]['OrderDa'] = $orderIDArryOnly[$i]['d_Received'];


            }
            //echo $orderItemArry['kp_OrderItemID']."<br/>";
        }

        return $processOrderImageArry;

    }
    public function processOrderImageArry($orderOrderItemImageArry)
    {
       $imageResizeSummary = "";
       $t=0;
       foreach($orderOrderItemImageArry as $orderID=>$orderItemArry)
       {
           $dateReceived             = $this->getDateReceived($orderID);
           $imageResizeSummary[$t]   = $this->orderitemmodel->processOrderImgArryData($dateReceived,$orderID,$orderItemArry);
           $t++;


       }
       return $imageResizeSummary;

    }
    public function getProofImage($orderItemID=null,$orderID=null)
    {
        $pic = "NO PIC";
        if(!is_null($orderID) || !empty($orderID))
        {
            $dateReceived            = $this->getDateReceived($orderID);
            $img                     = $this->orderitemmodel->getProofImageContent($orderItemID,$orderID,$dateReceived);

            $imageName = $img[0]['imageName'];
            if(!is_null($img))
            {
                //$li    = '<img style="max-width: 580px; max-height: 100px; line-height: 20px;" src="'.$img[0]['imageUrl'].'" alt="Image 01" />';
                //$li     = '<img style="max-width: 280px; max-height: 150px; line-height: 150px;" src="'.$img[0]['imageUrl'].'" alt="Image 01" />';

                //$li    = '<a href="'.$img[0]['imageUrl'].'"><img style="max-width: 280px; max-height: 150px; line-height: 150px;" src="'.$img[0]['imageUrl'].'" alt="Image 01" /></a>';
                //$li    = '<a href="'."orderItems/orderitemcontroller/imageBackBtn/".$orderItemID."/".$orderID."/".$dateReceived.'/'.$imageName.'"><img id="proofImageUploadPreviewInfo" style="max-width: 280px; max-height: 150px; line-height: 150px;" src="'.$img[0]['imageUrl'].'" alt="Image 01" /></a>';
                $li    = '<a href="'."orderItems/orderitemcontroller/imageBackBtn/".$orderItemID."/".$orderID."/".$dateReceived.'/'.$imageName.'"><img id="proofImageUploadPreviewInfo" src="'.$img[0]['imageUrl'].'" alt="Image 01" /></a>';
                echo $li;

             }
             else
             {
                  echo $pic;

             }
        }
        else
        {
            echo $pic;

        }




    }
    public function submitProofModalUpload()
    {
        $orderItemID  = $this->input->post('proofOrderItemIDHidden');
        $orderID      = $this->input->post('proofOrderIDHidden');

        $dateReceived = $this->getDateReceived($orderID);
        if(!empty($_FILES['proofUploadImage']['name']))
        {
            //echo "not empty";
            $msg          = $this->orderitemmodel->doProofCustomUpload($orderItemID,$orderID,$dateReceived);
            if($msg['msg'] == "success")
            {
                echo $msg['msg'];
                // update the values in the database
                //redirect("orderItemUpSideFrm/read/".$orderItemID,'refresh');
            }
        }

    }
    public function submitProofModalData()
    {
        $orderItemID  = $this->input->post('proofOrderItemIDHidden');
        //$orderID      = $this->input->post('proofOrderIDHidden');

        $proofBy      = $this->input->post('proofCreatedBy');
        $proofNotes   = $this->input->post('proofNotes');
        //echo $proofBy;

        if(!empty($proofBy))
        {
            if(empty($proofNotes))
            {
                $proofNotes = null;

            }

            $data = array(
            't_ProofBy'=> $proofBy,
            't_ProofNote'=> $proofNotes,
            );

            $this->orderitemmodel->updateOrderItemTable($orderItemID,$data);
            echo "dataOnly";
        }
//        if(isset($proofBy) || isset($proofNotes))
//        {
//            echo "proof By: ".$proofBy;
//            echo "<br/>";
//            echo "proof Notes: ".$proofNotes;
//            echo "<br/>";
//            $this->orderitemmodel->updateOrderItemTable($orderItemID,$data);
//        }


          //$dateReceived = $this->getDateReceived($orderID);
//        if(!empty($_FILES['proofUploadImage']['name']))
//        {
//            //echo "not empty";
//            $msg          = $this->orderitemmodel->doProofCustomUpload($orderItemID,$orderID,$dateReceived);
//            if($msg['msg'] == "success")
//            {
//                echo $msg['msg'];
//                // update the values in the database
//                //redirect("orderItemUpSideFrm/read/".$orderItemID,'refresh');
//            }
//        }
//        else
//        {
//            echo "dataOnly";
//
//            //redirect("orderItemUpSideFrm/read/".$orderItemID,'refresh');
//        }



    }
    public function uploadPrepressImage()
    {
         $sportUploadImage = $this->input->post('sportUploadImage');

        if($sportUploadImage == "1")
        {
            //echo "hi1";
            $orderItemID      = $this->input->post('sportUploadOrderItemIDHidden');
            $orderID          = $this->input->post('sportUploadOrderIDHidden');
            $inputFile        = "sportUploadFile";

        }
        else
        {
            $orderItemID      = $this->input->post('uploadOrderItemIDHidden');
            $orderID          = $this->input->post('uploadOrderIDHidden');
            $inputFile        = "upl";

        }
        //echo "hi2: ".$orderItemID."<br/>".$orderID."<br/>";

        $dateReceived = $this->getDateReceived($orderID);
        //echo $dateReceived."<br/>";
        $msg          = $this->orderitemmodel->doPrepressCustomUpload($orderItemID,$orderID,$dateReceived,$inputFile);



        if($msg['msg'] == "success")
        {
            echo $msg['msg'];
            //var_dump($msg);
            //redirect("orderItemUpSideFrm/read/".$orderItemID,'refresh');
        }


        //echo "working";
    }
//    public function uploadPrepressImage()
//    {
//        $orderItemID  = $this->input->post('uploadOrderItemIDHidden');
//        $orderID      = $this->input->post('uploadOrderIDHidden');
//        //echo "hi: ".$orderItemID."<br/>".$orderID."<br/>";
//
//        $dateReceived = $this->getDateReceived($orderID);
//        //$orderArry    = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$orderID);
//        //$dateReceived = $orderArry['d_Received'];
//
//        $msg          = $this->orderitemmodel->doPrepressCustomUpload($orderItemID,$orderID,$dateReceived);
//        if($msg == "success")
//        {
//            redirect("orderItemUpSideFrm/read/".$orderItemID,'refresh');
//        }
//
//
//        //echo "working";
//    }
    public function getDateReceived($orderID)
    {
        $orderArry    = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$orderID);
        $dateReceived = $orderArry['d_Received'];

        return $dateReceived;

    }
    public function getPrepressImage($orderItemID,$orderID)
    {

        //header("Cache-Control: no-cache, must-revalidate");
        //header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        //header("Content-Type: application/xml; charset=utf-8");
        $imgOutput = array();
        //$this->output->cache('150');
//         $this->output->clear_path_cache('https://localhost/apps/orderItems/orderitemcontroller/getPrepressImage/'.$orderItemID.'/'.$orderID);
//        $imgOutput['CachePresent'] = $this->output->path_cached('https://localhost/apps/orderItems/orderitemcontroller/getPrepressImage/'.$orderItemID.'/'.$orderID);
//        if($this->output->path_cached('https://localhost/apps/orderItems/orderitemcontroller/getPrepressImage/'.$orderItemID.'/'.$orderID))
//        {
//            $cache_expires = $this->output->get_path_cache_expiration('https://localhost/apps/orderItems/orderitemcontroller/getPrepressImage/'.$orderItemID.'/'.$orderID);
//            if ($cache_expires > 0)
//            {
//                $imgOutput['cacheTime']    = $cache_expires;
//                $imgOutput['CacheCleared'] = $this->output->clear_path_cache('https://localhost/apps/orderItems/orderitemcontroller/getPrepressImage/'.$orderItemID.'/'.$orderID);
//                //The cache for 'path/to/check' will expire on the unix timestanp $cache_expires
//            }
//
//        }
        $dateReceived = $this->getDateReceived($orderID);

        $img       = $this->orderitemmodel->getPrepressImageContent($orderItemID,$orderID,$dateReceived);
        //var_dump($img);
        //echo ""

        $li        = "";
        $pic       = "NO PIC";
        //echo $img['sportActualImageName'];


        if(!is_null($img)&& !empty($img))
        {
            $imgOutput['dateReceived'] = $dateReceived;
            $imgOutput['orderID'] = $orderID;
            $imgOutput['orderItemID'] = $orderItemID;

            //var_dump($img);

            //$li    = '<img style="max-width: 580px; max-height: 100px; line-height: 20px;" src="'.$img[0]['imageUrl'].'" alt="Image 01" />';
            //$li     = '<img style="max-width: 280px; max-height: 150px; line-height: 150px;" src="'.$img[0]['imageUrl'].'" alt="Image 01" />';

            //$li    = '<a href="'.$img[0]['imageUrl'].'"><img style="max-width: 280px; max-height: 150px; line-height: 150px;" src="'.$img[0]['imageUrl'].'" alt="Image 01" /></a>';
            //$li    = '<a href="'."orderItems/orderitemcontroller/imageBackBtn/".$orderItemID."/".$orderID."/".$dateReceived.'/'.$imageName.'"><img style="max-width: 280px; max-height: 150px; line-height: 150px;" src="'.$img['imageUrl'].'" alt="Image 01" /></a>';

            if(isset($img['imageName']))
            {
                 //$imageName      = $img['imageName'];
                 //$li             = '<a href="'.'orderItems/orderitemcontroller/imageBackBtn/'.$orderItemID.'/'.$orderID.'/'.$dateReceived.'/'.$imageName.'"><img style="max-width: 280px; max-height: 150px; line-height: 150px;" src="'.$img['imageUrl'].'" alt="Image 01" /></a>';

                 $imgOutput['prepressImageName']  = $img['imageName'];;
                 $imgOutput['prepressImageURL']   = $img['imageUrl'];
                 $imgOutput['prepressImageFound'] = "yes";


                 //$imgOutput['prepressOutput'] = $li;
                 //echo $imgOutput['prepressOutput'];
                 //echo json_encode($imgOutput['prepressOutput']);
            }
            if(isset($img['sportActualImageName']))
            {
                //$sportImageName = $img['sportActualImageName'];
                $imgOutput['sportImageName']     = $img['sportActualImageName'];
                $imgOutput['sportImageURL']      = $img['sportImagePath'];
                $imgOutput['sportImageFound']    = "yes";

                $imgOutput['deckSheetExtension'] = $img['$deckSheetExtension'];

                //$si    = '<a href="'."orderItems/orderitemcontroller/imageBackBtn/".$orderItemID."/".$orderID."/".$dateReceived.'/'.$sportImageName.'"><img style="max-width: 280px; max-height: 150px; line-height: 150px;" src="'.$img['sportImagePath'].'" alt="Image 01" /></a>';

                //$imgOutput['sportOutput'] = $si;
                //echo $imgOutput['sportOutput'];
                //echo json_encode($imgOutput['prepressOutput']);
            }
            //var_dump($imgOutput);

            //echo $imgOutput['prepressOutput'];





            echo json_encode($imgOutput);
            //var_dump($imgOutput);
            //echo $li;

         }
         else
         {
              $imgOutput['imageNotFound'] = $pic;
              //echo $imgOutput['imageNotFound'];
              echo json_encode($imgOutput);
              //echo $pic;

         }
    }
    public function imageBackBtn($orderItemID,$orderID,$dateReceived,$imageName)
    {

        $data['orderItemID']   = $orderItemID;

        $data['orderID']       = $orderID;

        $data['dateReceived']  = $dateReceived;

        $dateOrderReceivedArr  = explode("-", $dateReceived);

        $data['yearOrder']     = $dateOrderReceivedArr[0];

        $data['monthOrder']    = $dateOrderReceivedArr[1];

        $data['imageName']     = $imageName;

        $data['imgExtension']  = $this->orderitemmodel->getDeckSheetFileExtension($imageName);

        $this->load->view('fullImageView',$data);
    }
    public function getImageNameExtension($imageName)
    {
        $imgExtension = $this->orderitemmodel->getDeckSheetFileExtension($imageName);

        return $imgExtension;

    }
    public function deleteOrderItemRowFromOrderItemID($orderItemID)
    {
        $this->orderitemmodel->deleteOrderItemDataFromOrderItemID($orderItemID);
    }
    public function getOrderItemUpSideFrmPagination($orderID,$dashNum,$operation)
    {
        //echo "hi";
        $orderItemArry = $this->orderitemmodel->getOrderItemIDFromOrderIDDashNum($orderID,$dashNum);
        while(empty($orderItemArry))
        {
            if($operation == "addition")
            {
                $dashNum++;

            }
            if($operation == "minus")
            {
                $dashNum--;

            }
            $orderItemArry = $this->orderitemmodel->getOrderItemIDFromOrderIDDashNum($orderID,$dashNum);
        }

        //var_dump($orderItemArry);
        echo json_encode($orderItemArry);
        //redirect("orderItemUpSideFrm/read/".$orderItemArry['kp_OrderItemID'],'refresh');

    }
    public function orderItemUpSideFrm($request,$id)
    {
        if(isset($id))
        {
            $data['changeID']       = $id;
            $data['requestCalled']  = $request;

            // check if it is a read or create
            // if 'read' load data from OrderItem Table.
            if($request == "read")
            {
                // if read send kp_OrderItemID , kf_ProductBuildID and kf_OrderID from OrderItem Table
                // if create no data is send.
                $orderItemArry             = $this->getOrderItemFieldsFromOrderItemID($id);
                $data['productBuildID']    = $orderItemArry[0]['kf_ProductBuildID'];
                $data['orderID']           = $orderItemArry[0]['kf_OrderID'];
                $data['dashNum']           = $orderItemArry[0]['n_DashNum'];

                // get the orderItem Dash Num
                //$getDashNumArr             = $this->orderitemmodel->orderItemDashNumber($orderItemArry[0]['kf_OrderID']);

                $getDashNumArr             = $this->orderitemmodel->getOrderItemByOrderID($orderItemArry[0]['kf_OrderID']);

                //echo sizeof($getDashNumArr);
                $getDashNumArr             = Modules::run('orderRedo/orderredocontroller/subval_sort',$getDashNumArr,'n_DashNum');
                $lastDashNum               = $getDashNumArr[sizeof($getDashNumArr)-1]['n_DashNum'];

                $data['getDashNum']        = $lastDashNum;

                //$data['getDashNum']        = $getDashNumArr['dashNum'];

                // get customer data
                $customerDataFromOrderID   = Modules::run('customers/customercontroller/getCustomerFieldsFromCustomerID',$orderItemArry[0]['kf_CustomerID']);

                $data['companyName']       = $customerDataFromOrderID['t_CustCompany'];
                //print_r($data);
            }
            if($request == "create")
            {
                $data['orderID'] = $id;
                $orderArr                  = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$id);
                // get customer data
                $customerDataFromOrderID   = Modules::run('customers/customercontroller/getCustomerFieldsFromCustomerID',$orderArr['kf_CustomerID']);

                $data['companyName']       = $customerDataFromOrderID['t_CustCompany'];

            }
            if($request == "template")
            {
                $orderItemArry             = $this->getOrderItemFieldsFromOrderItemID($id);

                $data['productBuildID']    = $orderItemArry[0]['kf_ProductBuildID'];

                $data['orderID']           = null;

                $data['templateName']      = $orderItemArry[0]['t_TemplateName'];

                //$data['dashNum']           = $orderItemArry[0]['n_DashNum'];

                // get customer data
                $customerDataFromOrderID   = Modules::run('customers/customercontroller/getCustomerFieldsFromCustomerID',$orderItemArry[0]['kf_CustomerID']);

                $data['companyName']       = $customerDataFromOrderID['t_CustCompany'];

                $data['dashNum']           = null;
                $data['getDashNum']       = null;

            }
            //print_r($data);
            $this->load->view('orderItemUpSideFrm',$data);
        }
        else
        {
            $this->load->view('testing');
        }
    }
    public function updateOrderItemTemplateName()
    {

        $orderItemID               = $this->input->post('templateOrderItemIDHidden');

        $data['t_TemplateName']    = $this->input->post('templateNameInput');

       $this->orderitemmodel->updateOrderItemTable($orderItemID,$data);

       echo "updateSuccess";




    }
    public function loadTemplateView($orderID=null)
    {
        $data['orderID']     = $orderID;

        $this->load->view('templateView',$data);


    }
    public function getCustomerTemplateList($orderID=null,$templateCust=null)
    {
        //DataTable for template view
        $orderIDArry                                =  Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$orderID);

        if($templateCust !== "guide")
        {
            $customerID                             = $orderIDArry['kf_CustomerID'];
        }
        else
        {
            $customerID                             = "2877";

        }

        $result = $this->orderitemmodel->getCustomerTemplateListData($customerID);
        echo $result;
        //$resultArry = json_decode($result);
        //print_r($resultArry);
        //echo $result;
        //echo json_encode($result);
        //var_dump($result);

    }
    public function assignTemplateInfoToOrderID($orderID=null,$orderItemID=null,$templateChoice=null)
    {
        //echo $orderID."<br/>";
        //echo $orderItemID."<br/>";
        //echo $templateChoice."<br/>";

        // get the customerID and Job Status from OrderID
        $orderIDArry                                =  Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$orderID);

        $orderJobStatus                             = $orderIDArry['t_JobStatus'];

        $orderCustomerID                            = $orderIDArry['kf_CustomerID'];


        $countDashNumArry                           = $this->orderitemmodel->getOrderItemByOrderID($orderID);

        //$countDashNumArrySortByDashNum              = Modules::run('orderRedo/orderredocontroller/subval_sort',$countDashNumArry,'n_DashNum');

        //$countDashNum                               = $countDashNumArrySortByDashNum[sizeof($countDashNumArrySortByDashNum)-1]['n_DashNum'];

        $lastInsertedOrderItemID                    = $this->createTemplateDupOrderItemOrderItemComponents($orderItemID,$templateChoice);

        //echo "lastInsertedOrderItemID: ".$lastInsertedOrderItemID."<br/>";

        //assign the OrderID to the OrderItemID
        $currentOrderItemArray                      = $this->getOrderItemFieldsFromOrderItemID($lastInsertedOrderItemID);

        //var_dump($currentOrderItemArray);

        $currentOrderItemArray[0]['kf_OrderID']     = $orderID;

        $currentOrderItemArray[0]['nb_Template']    = null;

        $currentOrderItemArray[0]['t_TemplateName'] = null;

        //$dashNum                                    = $currentOrderItemArray[0]['n_DashNum'];
        if($orderCustomerID == "1467")
        {
            $currentOrderItemArray[0]['t_OiStatus']      = "Hold - Waiting on Art";


        }
        else
        {

            $currentOrderItemArray[0]['t_OiStatus']      = null;

        }
        if(!is_null($countDashNumArry) && !empty($countDashNumArry))//        if(!is_null($countDashNum) && !empty($countDashNum))
        {
            $countDashNumArrySortByDashNum              = Modules::run('orderRedo/orderredocontroller/subval_sort',$countDashNumArry,'n_DashNum');

            $countDashNum                               = $countDashNumArrySortByDashNum[sizeof($countDashNumArrySortByDashNum)-1]['n_DashNum'];
            $countDashNum++;

        }
        else
        {
            $countDashNum = 1;
        }
        $currentOrderItemArray[0]['kf_CustomerID']  = $orderCustomerID;
        $currentOrderItemArray[0]['n_DashNum']      = $countDashNum;




        $currentOrderItemComponentArry              = Modules::run('orderItemComponents/orderitemcomponentcontroller/getOrderItemComponentArrayFromOrderItemID',$lastInsertedOrderItemID);

        $lenArr                                     = sizeof($currentOrderItemComponentArry);

        for($i = 0; $i<$lenArr; $i++)
        {
            $currentOrderItemComponentArry[$i]['kf_OrderID']  = $orderID;
            //update OrderItemComponent Table
            $updateOrderItemComponentArry                     = Modules::run('orderItemComponents/orderitemcomponentcontroller/updateOIC',$currentOrderItemComponentArry[$i]['kp_OrderItemComponentID'],$currentOrderItemComponentArry[$i]);
        }

        //update OrderItem Table
        $this->orderitemmodel->updateOrderItemTable($lastInsertedOrderItemID,$currentOrderItemArray[0]);

        echo $lastInsertedOrderItemID;

    }
    public function createTemplateFromOrderItemID($orderItemID=null,$templateChoice=null)
    {
        //get the orderItemID Arry
        $lastInsertedOrderItemID = $this->createTemplateDupOrderItemOrderItemComponents($orderItemID,$templateChoice);

        echo $lastInsertedOrderItemID;


    }
    public function createTemplateDupOrderItemOrderItemComponents($orderItemID=null,$templateChoice=null) {
        $oldOrderItemArray                      = $this->getOrderItemFieldsFromOrderItemID($orderItemID);

        if(!is_null($oldOrderItemArray[0]['kf_OrderID']) && !empty($oldOrderItemArray[0]['kf_OrderID'])) {
            $oldOrderIDArry                         = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$oldOrderItemArray[0]['kf_OrderID']);
            $customerArryForOldOrderID              = Modules::run('customers/customercontroller/getCustomerFieldsFromCustomerID',$oldOrderIDArry['kf_CustomerID']);
        }

        $oldOrderItemArray[0]['kp_OrderItemID'] = "";
        $oldOrderItemArray[0]['kf_OrderID']     = null;
        $oldOrderItemArray[0]['nb_Template']    = 1;

        $oldOrderItemComponentArry              = Modules::run('orderItemComponents/orderitemcomponentcontroller/getOrderItemComponentArrayFromOrderItemID',$orderItemID);

        if($templateChoice == "guide") {
            $oldOrderItemArray[0]['kf_CustomerID'] = "2877";

        } else if($templateChoice == "customer") {
            $oldOrderItemArray[0]['kf_CustomerID'] = $customerArryForOldOrderID['kp_CustomerID'];
        }

        $lastInsertedOrderItemID                = $this->orderitemmodel->insertOrderItemTable($oldOrderItemArray);

        $lenArr                                 = sizeof($oldOrderItemComponentArry);

        for($i = 0; $i<$lenArr; $i++) {
            $oldOrderItemComponentArry[$i]['kp_OrderItemComponentID']   = '';
            $oldOrderItemComponentArry[$i]['kf_OrderItemID']            = $lastInsertedOrderItemID;
            $oldOrderItemComponentArry[$i]['kf_OrderID']                = null;
        }

        echo Modules::run('orderItemComponents/orderitemcomponentcontroller/submitOrderItemComponentTable',$oldOrderItemComponentArry);

        return $lastInsertedOrderItemID;


    }
    public function getOrderItemFieldsFromOrderItemID($orderItemID)
    {
        $row = $this->orderitemmodel->getOrderItemByID($orderItemID);

        return $row;

    }
    public function getHeighWidthInFeet($heightInInches,$widthInInches)
    {
        $heightWidthValues = array();

        //---Height Values
        $heightFullFeet                    = ($heightInInches)/12;
        $heightJustFeet                    = floor($heightFullFeet);
        $heightDecimalFeetInInches         = round(($heightFullFeet - $heightJustFeet) * 12);

        //---Width Values
        $widthFullFeet                     = ($widthInInches)/12;
        $widthJustFeet                     = floor($widthFullFeet);
        $widthDecimalFeetInInches          = round(($widthFullFeet - $widthJustFeet) * 12);

        //final Height and Width Values
        $heightWidthValues['heightInFeet'] = $heightJustFeet.'\''.'   '.$heightDecimalFeetInInches."\"";
        $heightWidthValues['widthInFeet']  = $widthJustFeet.'\''.'   '.$widthDecimalFeetInInches."\"";

        return $heightWidthValues;

    }
    public function getOrderItemRowsByOrderIDResultObject($orderID)
    {
        //$rows = $this->orderitemmodel->getOrderItemRowsByOrderIDResultObject($orderID);
        //$aaData = array();
        $dateReceived = $this->getDateReceived($orderID);

        $resultSet = $this->orderitemmodel->getOrderItemRowsByOrderIDResultObject($orderID,$dateReceived);
        //print_r(json_decode($resultSet));
        $res = json_decode($resultSet);


        //$aaData =$res->aaData;
        //echo sizeof($res->aaData)."<br/>";
        //find the size of aaData property
        //$aaDataArrySize = sizeof($res->aaData);
        if(sizeof($res->aaData) > 1)
        {
            for($i=0;$i<sizeof($res->aaData);$i++)
            {
                if(is_null($res->aaData[$i][10]) || $res->aaData[$i][10] == "")
                {
                    $res->aaData[$i][13] =  '<img  src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" height="150" width="150" />';
                }
                $heightWidthValues       = $this->getHeighWidthInFeet($res->aaData[$i][11], $res->aaData[$i][12]);
                $res->aaData[$i][2]      = $heightWidthValues['heightInFeet']." H"." x ".$heightWidthValues['widthInFeet']." W";



            }


        }
        else
        {
            if(is_null($res->aaData[0][10]) || $res->aaData[0][10] == "")
            {
                $res->aaData[0][13] =  '<img  src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" height="150" width="150" />';
            }
            $heightWidthValues       = $this->getHeighWidthInFeet($res->aaData[0][11], $res->aaData[0][12]);
            $res->aaData[0][2]      = $heightWidthValues['heightInFeet']." H"." x ".$heightWidthValues['widthInFeet']." W";


        }
        //var_dump($res);

        $result = json_encode($res);
        echo $result;
        //print_r($resultSet['sEcho']);
        //var_export($resultSet);
        //$data['aaData'] = $resultSet['aaData'];

        //print_r($data);
        //echo $resultSet->sEcho;
        //echo $resultSet;

        //return $rows;
        //print_r($rows);

    }
    public function readOrderItemUpSideFrmData($orderItemID)
    {
        $orderItemArry                         = $this->getOrderItemFieldsFromOrderItemID($orderItemID);
        //$orderItemArry             = $this->orderitemmodel->getOrderItemByID($orderItemID);

        $orderArr                              = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$orderItemArry[0]['kf_OrderID']);

        if(!empty($orderArr))
        {
            $orderJobStatus                    = $orderArr['t_JobStatus'];
            $orderPricing                      = $orderArr['nb_UseTotalOrderPricing'];
        }
        else
        {
            $orderJobStatus                    = null;
            $orderPricing                      = null;
        }

        $productBuildArr                       = Modules::run('productBuilds/productbuildcontroller/getProductBuildItemData',$orderItemArry[0]['kf_ProductBuildID']);


        $productBuildName                      = $productBuildArr['t_Name'];

        $orderItemArry[0]['orderPricing']      = $orderPricing;

        $orderItemArry[0]['productBuildName']  = $productBuildName;

        $orderItemArry[0]['orderJobStatus']    = $orderJobStatus;



        //var_dump($orderItemArry);
        echo json_encode($orderItemArry);

    }
    public function readCreateOrderItemUpSideFrmData($orderID)
    {
        $orderArr                  = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$orderID);

        //print_r($orderArr);
        //echo "<br/><br/>";
        echo json_encode($orderArr);

    }
    public function updateInspectionDataOrderItemTbl($orderItemID = null,$data=null)
    {
         $data = array(
        'nb_InspectReadOrder'=> $this->input->post('instruction'),
        'nb_InspectQty'=> $this->input->post('qty'),
        'nb_InspectSize'=> $this->input->post('size'),
        'nb_InspectColor'=> $this->input->post('color'),
        'nb_InspectFinishing'=> $this->input->post('finishing'),
        't_InspectNote'=> $this->input->post('inspectionNotes'),
        't_InspectName'=> $this->input->post('inspector'),
        'n_NumLabelsPrint'=> $this->input->post('n_NumLabelsPrint'),
        'nb_PrintLabel'=> $this->input->post('nb_PrintLabel')
        );

        $orderItemID = $this->input->post('inspectionOrderItemID');

        $this->orderitemmodel->updateOrderItemTable($orderItemID,$data);

    }
    public function submitOrderItemUpSideFrmData()
    {
        date_default_timezone_set('America/Indianapolis');

        $data = array(
        'n_Quantity'=> $this->input->post('n_Qty'),
        'n_WidthInInches'=> $this->input->post('widthHidden'),
        'n_HeightInInches'=> $this->input->post('heightHidden'),
        't_Description'=> $this->input->post('t_Description')
        );

        // for sport customer
        if($this->input->post('customerIDHidden') == "1467")
        {
            $data['t_SportJobNumber']           = $this->input->post('t_SportJobNumber');
            $data['t_SportItemNumber']          = $this->input->post('t_SportItemNumber');
            $data['t_SportLocationNumber']      = $this->input->post('t_SportLocationNumber');

            $data['nb_ArtReceivedProduction']   = $this->input->post('nb_ArtReceivedProduction');
            $data['d_ArtReceived']              = date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('d_ArtReceived'))));
            $data['t_ArtReceivedBy']            = $this->input->post('t_ArtReceivedBy');

            $data['t_ArtContact']               = $this->input->post('t_ArtContact');
            $data['ti_UploadComplete']          = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $this->input->post('ti_UploadComplete'))));
            $data['t_Structure']                = $this->input->post('t_SportItemNumber')." ".$this->input->post('t_SportLocationNumber');
        }
        else
        {
            $data['t_Structure']                = $this->input->post('t_Structure');
        }

        // for Pricing Type
        if($this->input->post('nb_UseTotalOrderPricingHidden') == "1")
        {
             $data['t_Pricing']                 = null;
             $data['n_Price']                   = null;


        }
        else
        {
              $data['t_Pricing']                = $this->input->post('pricingtype');
              $data['n_Price']                  = str_replace("$", '',$this->input->post('priceEach'));

        }
        $data['nb_DoNotInvoice']                = $this->input->post('doNotInvoice');

        // type of submit
        $typeOfSubmit = $this->input->post('requestCalledHidden');
        if($typeOfSubmit == "read" || $typeOfSubmit == "template")
        {
            // Get the OrderItemComponentID to update the Height and Width and Quantity and Description
            $getOrderItemComponentID   = Modules::run('orderItemComponents/orderitemcomponentcontroller/checkOrderItemComponentLinkedKeysFromOrderItemID',$this->input->post('orderItemIDHidden'));

            //Check whether the OrderItemComponentID is set or not
            if(isset($getOrderItemComponentID))
            {
                $updateOICData = array(
                'n_Quantity'=> $data['n_Quantity'],
                'n_WidthInInches'=> $data['n_WidthInInches'],
                'n_HeightInInches'=> $data['n_HeightInInches'],
                't_Description'=> $data['t_Description']
                );
                // update OIC table only when custom print check is not selected
                echo Modules::run('orderItemComponents/orderitemcomponentcontroller/updateOIC',$getOrderItemComponentID,$updateOICData);
            }
            //update the data
            //echo $this->input->post('orderItemIDHidden');

            // Dash Num for Read coming from Adjust Text box
            $data['n_DashNum']          = $this->input->post('dashNum');

            if($this->input->post('currentProductBuildID') != "none")
            {
                $data['t_ProductType']      = $this->input->post('productBuildCategory');

                $data['kf_ProductBuildID']  = $this->input->post('productBuildName');

                $this->orderitemmodel->updateOrderItemTable($this->input->post('orderItemIDHidden'),$data);
                echo $typeOfSubmit;

            }
            else
            {
                $this->orderitemmodel->updateOrderItemTable($this->input->post('orderItemIDHidden'),$data);
                echo $typeOfSubmit;

            }
            //redirect("orderItemUpSideFrm/read/".$this->input->post('orderItemIDHidden'),'refresh');
            //print_r($data);
            //echo "Succesfully updated the OrderItem record";

        }
        if($typeOfSubmit == "create")
        {
            $data['kf_CustomerID']      = $this->input->post('customerIDHidden');
            $data['zCreated']           = date("Y-m-d H:i:s", time());

            $data['d_ArtReceived']      = null;

            $data['t_ProductType']      = $this->input->post('productBuildCategory');

            $data['kf_ProductBuildID']  = $this->input->post('productBuildName');

            $data['kf_OrderID']         = $this->input->post('orderIDHidden');

            $getDashNumArr              = $this->orderitemmodel->orderItemDashNumber($this->input->post('orderIDHidden'));

            $getDashNum                 = $getDashNumArr['dashNum'];

            $getDashNum++;

            $data['n_DashNum']          = $getDashNum;

            //$orderItemArry = array();
            $data['kp_OrderItemID'] = "";
            //print_r($data);
            $orderItemArr               = array();
            $orderItemArr[0]            = $data;
            //echo "<br/><br/>";
            //print_r($orderItemArr);
            $newInsertedOrderItemID     = $this->orderitemmodel->insertOrderItemTable($orderItemArr);

            echo $newInsertedOrderItemID;
            //redirect("orderItemUpSideFrm/read/".$newInsertedOrderItemID,'refresh');

            //echo "Succesfully inserted the OrderItem record";

        }




    }
    public function index($orderID=null)
    {
        if(isset($orderID))
        {
            $data['orderID'] = $orderID;
            $this->load->view('invoiceDetails',$data);
        }
        else
        {
            $this->load->view('testing');
        }

    }
    public function getOrderItemJobStatus($orderItemID)
    {
        echo json_encode($this->orderitemmodel->orderItemJobStatus($orderItemID));

    }
    public function updateAllOrderItemJobStatus($jobStatus,$orderID)
    {
        $this->orderitemmodel->updateAllOrderItemStatus($jobStatus,$orderID);

    }
    public function updateOrderItemJobStatus($jobStatus,$orderItemID)
    {
        $this->orderitemmodel->updateOrderItemStatus($jobStatus,$orderItemID);

    }
    public function getOrderItemInvoiceFromOrderTable($orderID)
    {
        echo Modules::run('orders/ordercontroller/getOrderPricingQuickBook',$orderID);

    }

    public function getInvoiceDetails($orderID)
    {
        //$invoiceArray = $this->orderitemmodel->orderItemInvoiceDetails($orderID);
        //print_r($invoiceArray);
        echo json_encode($this->orderitemmodel->orderItemInvoiceDetails($orderID));

    }
    public function calculateGrandTotal($orderID)
    {
        $invoiceCalculation= array('orderItemTotal'=>"",'otherChargeTotal'=>"",'orderShipTrackingTotal'=>"",'grandTotal'=>"");
        //print_r($invoiceCalculation);
        $orderItemTotal = "";






        $invoiceArray = $this->orderitemmodel->orderItemInvoiceDetails($orderID);
        //print_r($invoiceArray);
        //$teettt = $this->getOtherChargeTotal($orderID);
        //echo "jyjyjyj: ".$teettt;
        //find the length of the array
        $lenArr = sizeof($invoiceArray);
        //echo "<br/>".$lenArr."<br/>";
        for($i = 0; $i<$lenArr; $i++)
        {
            $orderItemTotal=$orderItemTotal+$invoiceArray[$i]['total'];

        }
        // get the order value
        $orderInfo = json_decode(Modules::run('orders/ordercontroller/getOrderPricingQuickBook',$orderID),true);
        //echo $orderInfo['useTotalOrderPricing'];
        //print_r($orderInfo);
        if(!empty($orderInfo))
        {
            //echo $orderInfo['totalOrderPrice'];
            if($orderInfo['useTotalOrderPricing'] == "1")
            {
                $invoiceCalculation['orderItemTotal']  = round(floatval($orderInfo['totalOrderPrice']),2);

            }
            else
            {
                // we are calculating the orderItem total value.
                $invoiceCalculation['orderItemTotal']  = $orderItemTotal;

            }

        }
        else
        {
            // we are calculating the orderItem total value.
            $invoiceCalculation['orderItemTotal']         = $orderItemTotal;

        }
       //$invoiceCalculation['orderItemTotal']         = $orderItemTotal;



        //echo $orderItemTotal."<br/>";
        $invoiceCalculation['otherChargeTotal']       = $this->getOtherChargeTotal($orderID);
        $invoiceCalculation['orderShipTrackingTotal'] = $this->getOrderTrackingTotal($orderID);
        $invoiceCalculation['grandTotal']             = $invoiceCalculation['orderItemTotal'] +$invoiceCalculation['otherChargeTotal']+$invoiceCalculation['orderShipTrackingTotal'];
        //$invoiceCalculation['grandTotal']             = $orderItemTotal +$invoiceCalculation['otherChargeTotal']+$invoiceCalculation['orderShipTrackingTotal'];
        //print_r($invoiceCalculation);
        echo json_encode($invoiceCalculation);
        //print_r($invoiceCalculation);


    }

    public function getOtherChargeTotal($orderID)
    {
        $otherChargeTotal = 0.00;
        //$otherChargeInfo = Modules::run('otherCharges/otherchargecontroller/otherChargeTable',$orderID);
        //print_r($otherChargeInfo);
        $otherChargeInfo = json_decode(Modules::run('otherCharges/otherchargecontroller/otherChargeTable',$orderID),true);
        //print_r($otherChargeInfo);
        if(empty($otherChargeInfo))
        {
            //echo "0.00";
            $otherChargeTotal = 0.00;
            return $otherChargeTotal;

        }
        else
        {
            for($xy=0;$xy<sizeof($otherChargeInfo);$xy++)
            {
                $otherChargeTotal += str_replace("$", '',$otherChargeInfo[$xy]['Total']);
            }
            //$otherChargeTotal = str_replace("$", '',$otherChargeInfo[0]['Total']);
            //floatval($otherChargeTotal);
            return floatval($otherChargeTotal);
            //echo $otherChargeTotal;

        }


    }

    public function getOrderTrackingTotal($orderID)
    {
        $orderShipTrackingTotal = 0.00;
        //$otherChargeInfo = Modules::run('otherCharges/otherchargecontroller/otherChargeTable',$orderID);
        //print_r($otherChargeInfo);
        $orderShipTrackingInfo = json_decode(Modules::run('orderShipTracking/ordershiptrackingcontroller/orderShipTrackingTable',$orderID),true);
        //print_r($otherChargeInfo);
        if(empty($orderShipTrackingInfo))
        {
            $orderShipTrackingTotal = 0.00;
            //echo "0.00";
            return $orderShipTrackingTotal;

        }
        else
        {
            for($xy=0;$xy<sizeof($orderShipTrackingInfo);$xy++)
            {
                $orderShipTrackingTotal += str_replace("$", '',$orderShipTrackingInfo[$xy]['shippingCharge']);
            }
            //$orderShipTrackingTotal = str_replace("$", '',$orderShipTrackingInfo[0]['shippingCharge']);
            return floatval($orderShipTrackingTotal);
            //echo $orderShipTrackingTotal;

        }


    }


    public function dup($orderItemID=null,$typeOfRequest=null)
    {
        //echo "hi";
        if(isset($orderItemID))
        {
            $data['orderItemID']        = $orderItemID;
            $data['typeOfRequest']      = $typeOfRequest;

            //Get the required fields from OrderItems Table using OrderItemID
            //$orderItemTableData         = $this->orderitemmodel->orderItemTableData($orderItemID);

            //$dashNumber                 = $this->orderitemmodel->orderItemDashNumber($orderItemTableData->kf_OrderID);
            //$data['orderItemTableData'] = $orderItemTableData;
            //$data['dashNumber']         = $dashNumber;


            $this->load->view('orderitemduplicate',$data);

        }
        else
        {
            $this->load->view('testing');
        }

    }


    public function getDisplayOrderItemFields($orderItemID)
    {
        //echo "wass";
        echo json_encode($this->orderitemmodel->orderItemTableData($orderItemID));


    }
    public function getorderItemDashNumber($orderItemID)
    {
        $orderItemTableData = $this->orderitemmodel->orderItemTableData($orderItemID);
        //$dashNumArray       = $this->orderitemmodel->orderItemDashNumber($orderItemTableData['kf_OrderID']);
        //echo $dashNumArray['dashNum'];

        //$dashNumber         = $this->orderitemmodel->orderItemDashNumber($orderItemTableData->kf_OrderID);

        echo json_encode ($this->orderitemmodel->orderItemDashNumber($orderItemTableData['kf_OrderID']));
        //print_r($orderItemTableData);
        //$this->orderitemmodel->orderItemTableData($orderItemID);


    }
    public function getOrderIDWithDashNumber($orderID)
    {
        echo json_encode ($this->orderitemmodel->orderIDWithDashNumber($orderID));
    }
    public function getOrderItemIDFromOrderIDDashNumPrepress($orderID=null,$dashNum=null)
    {
         // check if the orderID exists or not
        $errorMsg   = array();

        $orderArry  = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$orderID);

        if($orderArry)
        {
            if($dashNum)
            {
                $orderItemArry = $this->orderitemmodel->getOrderItemIDFromOrderIDDashNum($orderID,$dashNum);

                // check if the dashNum exists also check if the orderID exists
                if($orderItemArry)
                {
                    return $orderItemArry;
                }
                else
                {
                    $errorMsg['errorMsg'] = 'No_OrderItemID_for_".$orderID."_with_".$dashNum."_as_DashNum';
                    return $errorMsg;
                }
            }
            else
            {
                $errorMsg['errorMsg'] = "No_DashNum_provided";
                return $errorMsg;
            }
        }
        else
        {
            $errorMsg['errorMsg'] = "No_OrderID_provided";
            return $errorMsg;
        }
    }
    public function getOrderItemIDFromOrderIDDashNumCommandLine($orderID=null,$dashNum=null)
    {
         // check if the orderID exists or not
         $orderArry = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$orderID);

         if($orderArry)
         {
             if($dashNum)
             {
                 $orderItemArry = $this->orderitemmodel->getOrderItemIDFromOrderIDDashNum($orderID,$dashNum);

                  // check if the dashNum exists also check if the orderID exists
                  if($orderItemArry)
                  {
                      echo $orderItemArry['kp_OrderItemID'];
                  }
                  else
                  {
                      echo "No_OrderItemID_for_".$orderID."_with_".$dashNum."_as_DashNum";
                  }
             }
             else
             {
                 echo "No_DashNum_provided";
             }
         }
         else
         {
             echo "No_OrderID_provided";
         }
    }

    public function getOrderItemQtyHeightWidth($orderItemID)
    {
        $row            = $this->orderitemmodel->getOrderItemByID($orderItemID);
        //print_r($row);
        // added Description also
        $qtyHeightWidthDescription = $row[0]['n_Quantity'].",".$row[0]['n_HeightInInches'].",".$row[0]['n_WidthInInches'].",".$row[0]['t_Description'];
        return  $qtyHeightWidthDescription;

    }

    public function submitOrderItemDuplicateForm()
    {

        // this is where all the magic goes ...(:P <-->. (:P ... <--> (:P

        //------- orderItem Select,update and Insert  ----------
        // get the OrderItem from the submitted OrderItemID
        // update the OrderItem with the fields that was submitted
        // insert the udpated orderItem as a new row in OrderItem Table
        //echo "oi".$this->input->post('orderItemID',true)."<br>";
        $orderItemArray=$this->orderitemmodel->getOrderItemByID($this->input->post('orderItemID',true));

        //print_r($orderItemArray);

        $orderItemArray[0]['kp_OrderItemID']            = '';

        $orderItemArray[0]['t_Description']             = $this->input->post('orderItemDescription',true);



        $orderItemArray[0]['n_Quantity']                = $this->input->post('orderItemQuantity',true);

        $orderItemArray[0]['n_HeightInInches']          = $this->input->post('orderItemHeight',true);

        $orderItemArray[0]['n_WidthInInches']           = $this->input->post('orderItemWidth',true);

        $orderItemArray[0]['n_Price']                   = $this->input->post('orderItemPrice',true);

        $orderItemArray[0]['n_DashNum']                 = $this->input->post('orderItemTotalDashNum',true);

        $orderItemArray[0]['nb_ArtReceivedProduction']  = '';

        $orderItemArray[0]['d_ArtReceived']             = '';

        $orderItemArray[0]['t_ArtReceivedBy']           = '';

        $orderItemArray[0]['t_ArtContact']              = '';

        // request came from robbie to add them for the image url
        $orderItemArray[0]['t_OrderItemImage']          = null;
	$orderItemArray[0]['t_OrderItemProof']          = null;




        if($this->input->post('orderItemCustomerID',true) == "1467")
        {
            $orderItemArray[0]['t_Structure']           = $this->input->post('orderItemSportItemNumber',true)." ".$this->input->post('orderItemSportLocationNumber',true);

            $orderItemArray[0]['t_SportJobNumber']      = $this->input->post('orderItemSportJobNumber',true);

            $orderItemArray[0]['t_SportItemNumber']     = $this->input->post('orderItemSportItemNumber',true);

            $orderItemArray[0]['t_SportLocationNumber'] = $this->input->post('orderItemSportLocationNumber',true);

            $orderItemArray[0]['t_OiStatus']            = 'Hold - Waiting on Art';



        }
        else
        {
            $orderItemArray[0]['t_Structure']           = $this->input->post('orderItemStructure',true);

            $orderItemArray[0]['t_OiStatus']            = '';

        }
        //print_r($orderItemArray);

        $orderItemInsertedID = $this->orderitemmodel->insertOrderItemTable($orderItemArray);

        //echo "oiInsertedID".$orderItemInsertedID."<br>";

        //echo $orderItemInsertedID;


         //print_r($orderItemArray);





        //----------orderItemComponent Select,update and Insert -----------
        // get the OrderItemComponent from the submitted OrderItemID
        // update the OrderItemComponent with the last Inserted OrderItemID and OrderItemDescription
        // insert the udpated OrderItemComponent as a new row in OrderItemComponent Table
        echo Modules::run('orderItemComponents/orderitemcomponentcontroller/getOrderItemComponentArray',$this->input->post('orderItemID',true),$this->input->post('orderItemDescription',true),$orderItemInsertedID);
        //orderItemComponent Select


        // get the Information for the print Material
         $message =Modules::run('orderItemComponents/orderitemcomponentcontroller/getPrintMaterialInfo',$orderItemInsertedID,
                            $this->input->post('orderItemQuantity',true),$this->input->post('orderItemHeight',true),$this->input->post('orderItemWidth',true));

         echo $orderItemInsertedID;
//         if($this->input->post('typeOfRequestCalledHidden') == "orderItemUpSideFrm")
//         {
//             //echo "hello";
//             redirect("orderItemUpSideFrm/read/".$orderItemInsertedID,'refresh');
//
//         }
//         else
//         {
//             echo $message;
//         }


    }
    public function getOrderItemsFromOrderID($oldOrderID)
    {
        $row      = $this->orderitemmodel->getOrderItemByOrderID($oldOrderID);
        return $row;
    }
    public function getOrderItemsFromOrderIDInJsonFormat($oldOrderID)
    {
        $row      = $this->getOrderItemsFromOrderID($oldOrderID);
        $response = array();
        if(!empty($row))
        {
            //$row['resMsg'] = "yesLineItems";
            echo json_encode($row);
        }
        else
        {
            $response['errorMsg'] = "noLineItems";
            echo json_encode($response);
        }


    }
    public function getOrderItemIDFromOrderItemArray($orderItemArray)
    {
        $orderItemArr            = array();
        for($i=0; $i<sizeof($orderItemArray); $i++)
        {
           $orderItemArr[$i] = $orderItemArray[$i]['kp_OrderItemID'];
        }
        return $orderItemArr;
    }
    public function duplicateOrderItemsFromOrderID($orderItemsArray,$newOrderID)
    {
         //Duplicate Old OrderItems fields for new OrderID
        for($i=0; $i<sizeof($orderItemsArray); $i++)
        {
            $orderItemsArray[$i]['kp_OrderItemID']            = "";
            $orderItemsArray[$i]['kf_OrderID']                = $newOrderID;

            $orderItemsArray[$i]['t_OiStatus']                = null;
            $orderItemsArray[$i]['t_SportJobNumber']          = null;
            $orderItemsArray[$i]['t_SportItemNumber']         = null;
            $orderItemsArray[$i]['t_SportLocationNumber']     = null;
            $orderItemsArray[$i]['d_ArtReceived']             = null;
            $orderItemsArray[$i]['nb_ArtReceivedProduction']  = null;
            $orderItemsArray[$i]['t_ArtReceivedBy']           = null;
            $orderItemsArray[$i]['t_ArtContact']              = null;
            $orderItemsArray[$i]['n_PackingListQuantity']     = null;
            $orderItemsArray[$i]['nb_PackingList']            = null;
            $orderItemsArray[$i]['t_OrderItemImage']          = null;
            $orderItemsArray[$i]['t_ProofNote']               = null;
            $orderItemsArray[$i]['t_ProofBy']                 = null;
	          $orderItemsArray[$i]['t_OrderItemProof']          = null;
        }


        //Insert the modified OrderItemArray into the table
        $insertedOrderItemID = $this->orderitemmodel->insertOrderItemTable($orderItemsArray);
    }
    public function insertIntoOrderItemTable($orderItemArry)
    {
        $insertedOrderItemID = $this->orderitemmodel->insertOrderItemTable($orderItemArry);

        return $insertedOrderItemID;

    }
    public function deleteOrderItemOrderItemComponents($deleteOrderItemID)
    {
        // get all orderItemComponents for that OrderItemID
        $orderItemComponentArry = Modules::run('orderItemComponents/orderitemcomponentcontroller/getOrderItemComponentArrayFromOrderItemID',$deleteOrderItemID);
        //var_dump($orderItemComponentArry);
        for($i=0;$i<sizeof($orderItemComponentArry);$i++)
        {
            //echo "<br/>".$orderItemComponentArry[$i]['kp_OrderItemComponentID']."<br/>";
            $deleteOrderItemComponentID = $orderItemComponentArry[$i]['kp_OrderItemComponentID'];
            $deleteOicMsg = Modules::run('orderItemComponents/orderitemcomponentcontroller/deleteOrderItemComponentTableRow',$deleteOrderItemComponentID);
        }
        //Delete OrderItemID
        $this->deleteOrderItemRowFromOrderItemID($deleteOrderItemID);

    }


}

?>
