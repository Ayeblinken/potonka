<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OrderRedoController  extends MX_Controller
{
    //put your code here
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        $this->load->model('orderredomodel');
        //Server image path
        $this->orderRedoUploadPath = realpath(APPPATH . '../../../images/Orders');
    }
    public function testRedo()
    {
        $this->load->view('redoSummerNote');

    }
    public function loadBootstrapTheme()
    {
        $this->load->view('bootstrapThemeTest');
    }
    public function orderRedoImageBackBtn($orderRedoID,$orderID,$imageName,$requestCalled)
    {
        $data['orderRedoID']   = $orderRedoID;

        $data['orderID']       = $orderID;

        $dateReceived          = Modules::run('orderItems/orderitemcontroller/getDateReceived',$orderID);

        $dateOrderReceivedArr  = explode("-", $dateReceived);

        $data['yearOrder']     = $dateOrderReceivedArr[0];

        $data['monthOrder']    = $dateOrderReceivedArr[1];
        $data['imageName']     = $imageName;

        $imageNameExtension    = Modules::run('orderItems/orderitemcontroller/getImageNameExtension',$imageName);

        $data['imgExtension']  = $imageNameExtension;

        $data['requestCalled'] = $requestCalled;
        //print_r($data);

        $this->load->view('orderRedoImageView',$data);

    }
    public function getCustomerInfoFromOrderRedoID($orderRedoID)
    {
        //echo "hi";
        $orderRedoDataArry = $this->orderredomodel->getAllOrderRedoData($orderRedoID);
        //var_dump($orderRedoDataArry);
        $orderArry         = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$orderRedoDataArry['kf_OrderID']);
        //var_dump($orderArry);
        $customerArry      = Modules::run('customers/customercontroller/getCustomerFieldsFromCustomerID',$orderArry['kf_CustomerID']);
        //var_dump($customerArry);
        echo json_encode($customerArry);
        //$custCompanyName   = $customerArry['t_CustCompany'];
    }
    public function getRedoImages($orderRedoID,$orderID)
    {
        $dateReceived = Modules::run('orderItems/orderitemcontroller/getDateReceived',$orderID);

        $img          = $this->orderredomodel->getOrderRedoImageContent($orderRedoID,$orderID,$dateReceived);

        echo json_encode($img);

    }

    public function orderRedoEmployeeEmailAddress($employeeUserName=null)
    {
        //echo "hi";
        //$employeeUserName = "Surya Raparla";
        $employeeEmailAddressArry = Modules::run('employees/employeecontroller/getEmployeeEmailAddressFromUserName',$employeeUserName);

        $errors = array_filter($employeeEmailAddressArry);

        if(!empty($errors))
        {
              $employeeEmailAddress     = $employeeEmailAddressArry['t_EmployeeEmail'];
        }
        else
        {
            $employeeEmailAddress  = "robbie@indyimaging.com";

        }
        //echo $employeeEmailAddress;
        return $employeeEmailAddress;

        //echo $employeeEmailAddressArry['t_EmployeeEmail'];
        //print_r($employeeEmailAddressArry);

    }
    public function submitOrderRedoRequest()
    {
        date_default_timezone_set('America/Indianapolis');
        $requestBy                            = $this->input->post('requestBy');
        $itemsRedo                            = $this->input->post('itemsRedo');
        $departResponsible                    = $this->input->post('deptRespNU');

        $customerIssue                        = $this->input->post('customerIssue');
        $salesIssue                           = $this->input->post('salesIssue');

        $picYN                                = $this->input->post('picYesNoHidden');

        $orderID                              = $this->input->post('ordNU');


        $partialOrderItemsWithRedo            = $this->input->post('partialOrderItemsWithRedoHidden');

        $partialOrderItemsWithRedoArry        = explode(",", $partialOrderItemsWithRedo);

        $orderUrgency                         = $this->input->post('orderUrgency');

        $shippingUrgency                      = $this->input->post('shippingUrgency');



        //array_pop($partialOrderItemsWithRedoArry);

        //$partialOrderItemsWithRedoOnly      = array_pop($partialOrderItemsWithRedoArry);

        //insert data into orderRedo table and get the orderRedo Inserted ID
        $orderRedoData['kf_OrderID']          =  $orderID;
        $orderRedoData['ts_DateRequested']    =  date('Y-m-d H:i:s');
        $orderRedoData['t_Status']            =  "Pending";
        $orderRedoData['t_ItemsRedo']         =  $itemsRedo;
        $orderRedoData['t_Department']        =  $departResponsible;
        $orderRedoData['t_CustomerIssue']     =  $customerIssue;
        $orderRedoData['t_SalesViewIssue']    =  $salesIssue;
        //$orderRedoData['t_HasPhotos']       =  $picYN;
        $orderRedoData['t_RequestedBy']       = $requestBy;

        $orderRedoData['t_OrderUrgency']      = $orderUrgency;

        $orderRedoData['t_ShippingUrgency']   = $shippingUrgency;

        $orderRedoDataArr                     = array();
        $orderRedoDataArr[0]                  = $orderRedoData;

        //print_r($orderRedoDataArr);

        // get the newly inserted orderItemID
        $newInsertedOrderRedoID               = $this->orderredomodel->insertOrderRedoTable($orderRedoDataArr);

        $moveMsg                              = $this->moveFilesOnceUploaded($newInsertedOrderRedoID,$picYN,$orderID);

        $orderRedoItemDataArry                = array();
        $orderRedoItemDataArry['orderItemID'] = $partialOrderItemsWithRedoArry;

        $orderRedoItemDataArry['orderRedoID'] = $newInsertedOrderRedoID;



        //print_r($orderRedoItemDataArry);

        $this->insertIntoOrderRedoItemTable($orderRedoItemDataArry);
//        if($itemsRedo == "Partial")
//        {
//            $orderRedoItemDataArry   = array();
//            $orderRedoItemDataArry['orderItemID']=$partialOrderItemsWithRedoArry;
//
//            $orderRedoItemDataArry['orderRedoID']=$newInsertedOrderRedoID;
//
//
//
//            print_r($orderRedoItemDataArry);
//
//            $this->insertIntoOrderRedoItemTable($orderRedoItemDataArry);
//
//        }
        //send email here
        // get orderIDArry
        $orderIDArry                          = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$orderID);

        // get customerID from orderIDArry
        $customerID                           = $orderIDArry['kf_CustomerID'];


        //get Company Name from CusomterID
        $customerIDArry                       = Modules::run('customers/customercontroller/getCustomerFieldsFromCustomerID',$customerID);

        $customerCompanyName                  = $customerIDArry['t_CustCompany'];
        $emailSubject                         = "Redo Request Sales";
        $emailData['companyName']             = "<strong>Company Name:</strong> ".$customerCompanyName;
        $emailData['requestedBy']             = "<strong>Request By</strong>: ".$requestBy;
        $emailData['oldOrderID']              = $orderID;
        $emailData['orderRedoID']             = $newInsertedOrderRedoID;


        $emailSubject                         = "Redo - ".$customerCompanyName." - ".$orderID;

        $emailData['emailSubject']            = $emailSubject;
        $emailData['redoStatus']              = "Pending";

        $emailData['redoRequestedBy']         = $this->orderRedoEmployeeEmailAddress($requestBy);

        $this->orderRedoSendEmail($emailData);

        echo $newInsertedOrderRedoID;

    }
    public function insertIntoOrderRedoItemTable($orderRedoItemDataArry)
    {

        //echo "<br/>inside of insert method<br/>";
        //print_r($orderRedoItemDataArry);
        $refinedOrderRedoItemDataArry    = array();

        $insertOrderRedoItemDataArry     = array();
//        $orderRedoItemDataArry                  = array();
//        $partialOrderItemsWithRedoArry = array();
//
//        $partialOrderItemsWithRedoArry[0]="306232";
//        $partialOrderItemsWithRedoArry[1]="306233";
//        $partialOrderItemsWithRedoArry[2]="306234";
//        $partialOrderItemsWithRedoArry[3]="306235";
//        $partialOrderItemsWithRedoArry[4]="306236";
//
//
//        $orderRedoItemDataArry['kf_OrderItemID'] =  $partialOrderItemsWithRedoArry;
//        $orderRedoItemDataArry['kf_OrderRedoID'] = "22";
        //var_dump($orderRedoItemDataArry);
        foreach($orderRedoItemDataArry as $key=>$value)
        {
            //echo "<br/>".$key."<br/>";
            if($key == "orderItemID")
            {
                for($i=0;$i<sizeof($value);$i++)
                {
                    $refinedOrderRedoItemDataArry['kf_OrderItemID']  = $value[$i];
                    $refinedOrderRedoItemDataArry['kf_OrderRedoID']  = $orderRedoItemDataArry['orderRedoID'];
                    //print_r($refinedOrderRedoItemDataArry);
                    $insertOrderRedoItemDataArry[0] = $refinedOrderRedoItemDataArry;
                    //insert into OrderRedoItems Table
                    $lastInsertedOrderRedoItemID = Modules::run('orderRedoItems/orderredoitemcontroller/insertOrderRedoItemDataArry',$insertOrderRedoItemDataArry);
                    //var_dump($refinedArry);
                }
            }
        }
    }
    public function moveFilesOnceUploaded($newInsertedOrderRedoID=null,$pic=null,$orderID=null)
    {
        //$orderRedoArry = $this->orderredomodel->getAllOrderRedoData($newInsertedOrderRedoID);

        //$hasPics       = $orderRedoArry['t_HasPhotos'];
        $hasPics       = $pic;

        //$orderID       = $orderRedoArry['kf_OrderID'];
        $orderID       = $orderID;

        //get the date received from OrderID
        $orderDateReceived = Modules::run('orderItems/orderitemcontroller/getDateReceived',$orderID);


        $dateReceivedArry  = explode("-", $orderDateReceived);

        $yearOrder         = $dateReceivedArry[0];

        $monthOrder        = $dateReceivedArry[1];
        $path              = $this->orderRedoUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID;
        $msg               = "";
        if($hasPics == "1")
        {
          if(file_exists($path.'/'."Redo"))
          {
              if(!file_exists($path.'/'."Redo".'/'.$newInsertedOrderRedoID))
              {
                   if(!mkdir($path.'/'."Redo".'/'.$newInsertedOrderRedoID,0777,TRUE))
                    {
                          die('Failed to create Order and other folders...');
                    }
                    else
                    {
                        chmod($path, 0777);
                        //change the directory owner/group permission for OrderItemID folder
                        chmod($path.'/'."Redo".'/'.$newInsertedOrderRedoID, 0777);
                    }

              }

              $source           = $path.'/'."Redo".'/';
              $destination      = $path.'/'."Redo".'/'.$newInsertedOrderRedoID.'/';
              $filesArry        = scandir($path.'/'."Redo");


              //var_dump($filesArry);

              $filesCleanArry   = array_diff($filesArry,array('.','..','.DS_Store',$newInsertedOrderRedoID));
              foreach ($filesCleanArry as $file)
              {
                  if(!(is_dir($source.$file)))
                  {
                      //echo "<br/>hello : ".$file."<br/>";
                      if (copy($source.$file, $destination.$file))
                      {
                          $delete[] = $source.$file;
                      }
                  }
              }
              // Delete all successfully-copied files
              foreach ($delete as $file)
              {
                  unlink($file);
              }
              $msg = "moved done";
          }
        }
        else
        {
             $msg = "no pic";

        }
        return $msg;
        //var_dump($orderRedoArry);

    }
    public function redoRequestFrmUpload()
    {

        //echo "hi";
        //echo $_SERVER['SERVER_PROTOCOL']."<br/>";
        //echo $_SERVER['SERVER_NAME'].'/images'."<br/>";
        //echo realpath(APPPATH.'modules/orderRedo/views/uploadLog.txt')."<br/>";


        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);// this is for initial commit
        header("Pragma: no-cache");

        //$updatefile = realpath(APPPATH.'modules/orderRedo/views');

        date_default_timezone_set('America/Indianapolis');

        //$fh = fopen($updatefile.'/uploadLog.txt', 'a') or die("can't open file");

        // Settings
        //$targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";


        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds

        // 5 minutes execution time
        @set_time_limit(2880 * 60);
        //fwrite($fh, $cleanupTargetDir."  <cleanupTargetDir>\n");
        //fwrite($fh, $maxFileAge."  <maxFileAge>\n");

        // Get parameters
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
        $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

        //$newInsertedOrderRedoID = isset($_REQUEST["orderRedoIDHidden"]) ? $_REQUEST["orderRedoIDHidden"] : '';

        // Clean the fileName for security reasons
        $fileName = preg_replace('/[^\w\._]+/', '_', $fileName);

        //fwrite($fh, $chunk."  <chunk>\n");

        //fwrite($fh, $chunks."  <chunk>\n");

        //fwrite($fh, $fileName."  <fileName>\n");



//        $itemsRedo                          = $this->input->post('itemsRedo');
//        $departResponsible                  = $this->input->post('departResponsible');
//
//        $customerIssue                      = $this->input->post('customerIssue');
//        $salesIssue                         = $this->input->post('salesIssue');
//
//        $picYN                              = $this->input->post('picturesYesNo');
//
        $orderID                            = $this->input->post('orderIDHidden');

        //fwrite($fh, $picYN."  <pic Yes and No>\n");

        //insert data into orderRedo table and get the orderRedo Inserted ID
//        $orderRedoData['kf_OrderID']        =  $orderID;
//        $orderRedoData['ts_DateRequested']  =  date('Y-m-d H:i:s');
//        $orderRedoData['t_Status']          =  "Pending";
//        $orderRedoData['t_ItemsRedo']       =  $itemsRedo;
//        $orderRedoData['t_Department']      =  $departResponsible;
//        $orderRedoData['t_CustomerIssue']   =  $customerIssue;
//        $orderRedoData['t_SalesViewIssue']  =  $salesIssue;
//        $orderRedoData['t_HasPhotos']       =  $picYN;
//
//        $orderRedoDataArr                   = array();
//        $orderRedoDataArr[0]                = $orderRedoData;

        // get the newly inserted orderItemID
        //$newInsertedOrderRedoID             = $this->orderredomodel->insertOrderRedoTable($orderRedoDataArr);
        //get the date received from OrderID
        $orderDateReceived = Modules::run('orderItems/orderitemcontroller/getDateReceived',$orderID);


        $dateReceivedArry  = explode("-", $orderDateReceived);

        $yearOrder         = $dateReceivedArry[0];
        //$yearOrder         = "2013";

        $monthOrder        = $dateReceivedArry[1];
        //$monthOrder        = "07";

        $targetDir         = realpath(APPPATH . '../../../images/Orders');
        //.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/Redo'.'/'.$newInsertedOrderRedoID);
        //$targetDir = "../../../lib/tomcat6/webapps/IndyUploader/resources/uploadData";
        //fwrite($fh, $targetDir."  <targetDir>\n");

        //fwrite($fh, $departResponsible."  <pic checkbox Array >\n");
        // Make sure the fileName is unique but only if chunking is disabled
        if ($chunks < 2 && file_exists($targetDir.DIRECTORY_SEPARATOR.$yearOrder.DIRECTORY_SEPARATOR.
                $monthOrder.DIRECTORY_SEPARATOR.$orderID.DIRECTORY_SEPARATOR.'Redo'.
                DIRECTORY_SEPARATOR.$fileName))
        {
            $ext = strrpos($fileName, '.');
            //fwrite($fh, $ext."  <position of the first occurence>\n");

            $fileName_a = substr($fileName, 0, $ext);
            //fwrite($fh, $fileName_a."  <starts at begining, length returned after position >\n");

            $fileName_b = substr($fileName, $ext);
            //fwrite($fh, $fileName_b."  <starts at position length returned >\n");

            $count = 1;

            $filenameingConvention =file_exists($targetDir.DIRECTORY_SEPARATOR.$yearOrder.DIRECTORY_SEPARATOR.
                $monthOrder.DIRECTORY_SEPARATOR.$orderID.DIRECTORY_SEPARATOR.'Redo'.
                DIRECTORY_SEPARATOR. $fileName_a . '_' . $count . $fileName_b);//i wrote this line
            //fwrite($fh, $filenameingConvention."  <filenameingConvention >\n");

            while (file_exists($targetDir .DIRECTORY_SEPARATOR.$yearOrder.DIRECTORY_SEPARATOR.
                $monthOrder.DIRECTORY_SEPARATOR.$orderID.DIRECTORY_SEPARATOR.'Redo'.
                DIRECTORY_SEPARATOR. $fileName_a . '_' . $count . $fileName_b))
            {
                $count++;
            }


            $fileName = $fileName_a . '_' . $count . $fileName_b;
            //fwrite($fh, $fileName."  <fileName after making sure that file name is unique>\n");
        }
        $filePath = $targetDir.DIRECTORY_SEPARATOR.$yearOrder.DIRECTORY_SEPARATOR.
                $monthOrder.DIRECTORY_SEPARATOR.$orderID.DIRECTORY_SEPARATOR.'Redo'.
                DIRECTORY_SEPARATOR.$fileName;

        //fwrite($fh, $filePath."  <filePath>\n");
        // Create target dir
        if (!file_exists($targetDir.DIRECTORY_SEPARATOR.$yearOrder.DIRECTORY_SEPARATOR.
            $monthOrder.DIRECTORY_SEPARATOR.$orderID.DIRECTORY_SEPARATOR.'Redo'))
        {
             //@mkdir($targetDir.DIRECTORY_SEPARATOR.$mainFolder);
             $rootFolder = $targetDir.DIRECTORY_SEPARATOR.$yearOrder.DIRECTORY_SEPARATOR.
             $monthOrder.DIRECTORY_SEPARATOR.$orderID.DIRECTORY_SEPARATOR.'Redo'.DIRECTORY_SEPARATOR;
             if(!mkdir($rootFolder,0777,true))
             {
                //fwrite($fh, $rootFolder."  <Failed to create Folders>\n");
                 die("Failed to create Folders");

             }
             //fwrite($fh, $targetDir.DIRECTORY_SEPARATOR.$yearOrder.DIRECTORY_SEPARATOR.
                    //$monthOrder.DIRECTORY_SEPARATOR.$orderID.DIRECTORY_SEPARATOR.'Redo'.DIRECTORY_SEPARATOR."  <inside file_exist targetDir>\n");
        }

        // Remove old temp files
        if ($cleanupTargetDir && is_dir($targetDir.DIRECTORY_SEPARATOR.$yearOrder.DIRECTORY_SEPARATOR.
                    $monthOrder.DIRECTORY_SEPARATOR.$orderID.DIRECTORY_SEPARATOR.'Redo') && ($dir = opendir($targetDir.DIRECTORY_SEPARATOR.$yearOrder.DIRECTORY_SEPARATOR.
                    $monthOrder.DIRECTORY_SEPARATOR.$orderID.DIRECTORY_SEPARATOR.'Redo')))
        {
                while (($file = readdir($dir)) !== false)
                    {
                        $tmpfilePath = $targetDir.DIRECTORY_SEPARATOR.$yearOrder.DIRECTORY_SEPARATOR.
                                       $monthOrder.DIRECTORY_SEPARATOR.$orderID.DIRECTORY_SEPARATOR.'Redo'
                                       .DIRECTORY_SEPARATOR . $file;
                        //fwrite($fh, $tmpfilePath."  <tempfilepath - name>\n");

                        // Remove temp file if it is older than the max age and is not the current file
                        if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part"))
                        {
                                @unlink($tmpfilePath);
                                //fwrite($fh, $tmpfilePath."  <remove old file inside if condition >\n");
                        }
                    }

                closedir($dir);
        }
        else
        {
            die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
        }

        // Look for the content type header
        if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
        {
            $contentType = $_SERVER["HTTP_CONTENT_TYPE"];
            //fwrite($fh, $contentType."  <http content type contentType>\n");
        }


        if (isset($_SERVER["CONTENT_TYPE"]))
        {
            $contentType = $_SERVER["CONTENT_TYPE"];
            //fwrite($fh, $contentType."  <just the content type>\n");
        }

        $whatisthis = $_FILES['file']['tmp_name'];
        //fwrite($fh, $whatisthis."  temporary file name>\n");

        $originalName = $_FILES['file']['name'];
        //fwrite($fh, $originalName."  <original file Name>\n");


        // Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
        if (strpos($contentType, "multipart") !== false)
        {
                if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name']))
                {
                        // Open temp file
                        $out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");

                        //fwrite($fh, "{$filePath}.part"."  <Handle non multipart uploads:FILEPATH.PARTS>\n");

                        //fwrite($fh, $out."  <Handle non multipart uploads: OUT>\n");

                        if ($out)
                        {
                                // Read binary input stream and append it to temp file
                                $in = fopen($_FILES['file']['tmp_name'], "rb");

                          //      fwrite($fh, $in."  <readbinary input stream>\n");

                                $whatisthisagain = $_FILES['file']['tmp_name'];
                            //    fwrite($fh, $whatisthisagain."  <again files array>\n");

                                if ($in)
                                {
                                        while ($buff = fread($in, 4096))
                                        {
                                            //fwrite($out, $buff);
                                            $whatisitwriting = fwrite($out, $buff);
                                //            fwrite($fh,  $whatisitwriting."  <writing something.>\n");
                                        }

                                }
                                else
                                {
                                        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                                }

                                fclose($in);
                                fclose($out);
                                @unlink($_FILES['file']['tmp_name']);
                        }
                        else
                        {
                            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
                        }

                }
                else
                {
                   die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
                }

        }
        else
        {
                // Open temp file
                $dontknow = "{$filePath}.part";

                //fwrite($fh, $dontknow."  <dontknow: filepath.parts>\n");
                $out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");


                //fwrite($fh, $out."  <Handle multipart uploads: OUT>\n");

                if ($out)
                {
                        // Read binary input stream and append it to temp file
                        $in = fopen("php://input", "rb");

                        if ($in)
                        {
                                while ($buff = fread($in, 4096))
                                        fwrite($out, $buff);
                        }
                        else
                        {
                            die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                        }


                        fclose($in);
                        fclose($out);
                }
                else
                {
                    die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');

                }

        }
        // Check if file has been uploaded
        if (!$chunks || $chunk == $chunks - 1)
        {
                // Strip the temp .part suffix off
                rename("{$filePath}.part", $filePath);

                //fwrite($fh, "{$filePath}.part"."  <oldname>\n");

                //fwrite($fh, $filePath."  <newname>\n");
        }


        // Return JSON-RPC response
        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');



    }
    public function getRedoFrmData($orderRedoID)
    {
        date_default_timezone_set('America/Indianapolis');
        $orderRedoTableData  = $this->orderredomodel->getAllOrderRedoData($orderRedoID);

        //var_dump($orderRedoTableData);
        $orderItemIDArry               = Modules::run('orderRedoItems/orderredoitemcontroller/getOrderItemsFromOrderRedoItemArry',$orderRedoID);
        //var_dump($orderItemIDArry);
        $dashNumArry                   = Modules::run('orderRedoItems/orderredoitemcontroller/getDashNumArry',$orderRedoTableData['kp_OrderRedoID']);
        $dashNumWithDashArry           = array();
        for($i=0;$i<sizeof($dashNumArry);$i++)
        {
            $dashNumWithDashArry[$i] = "-".$dashNumArry[$i];

        }
        $orderRedoTableData['orderItemID'] = implode(",",$orderItemIDArry);
        $orderRedoTableData['dashNum']     = implode(",",$dashNumWithDashArry);

        $orderRedoTableData['ts_DateRequested']= date('m/d/Y h:i a',strtotime($orderRedoTableData['ts_DateRequested']));
        //$dateTimeWhenRequested = explode(" ",$orderRedoTableData['ts_DateRequested']);
        //var_dump($dateTimeWhenRequested);
        //$dateArry              = explode("-",$dateTimeWhenRequested[0]);
        //$timeArry              = explode("-",$dateTimeWhenRequested[1]);
        //echo date($dateTimeWhenRequested[1],'g:i a');
        //$dateTimeFormat        = $dateArry[1]."/".$dateArry[2]."/".$dateArry[0];
        //echo $dateTimeFormat;

        //var_dump($orderRedoTableData);

        echo json_encode($orderRedoTableData);
    }
    public function submitRedoStatusFrm()
    {
        // A regular update with no creation of Order (a new Job Number)
        date_default_timezone_set('America/Indianapolis');
        $orderID                              = $this->input->post('orderIDHidden');
        $orderRedoID                          = $this->input->post('orderRedoIDHidden');

        $redoStatus                           = $this->input->post('redoStatus');
        $approvedBy                           = $this->input->post('approvedBy');

        //$dateTimeApproved                     = $this->input->post('dateTimeApproved');


        $requestedBy                          = $this->input->post('requestedBy');
        $dateTimeWhenRequested                = $this->input->post('dateTimeWhenRequested');

        $orderIDRedo                          = $this->input->post('orderIDRedo');

        $itemsRedo                            = $this->input->post('itemsRedo');

        $customerIssue                        = $this->input->post('customerConcern');
        $salesIssue                           = $this->input->post('saleConcern');


        $redoDepartResponsible                = $this->input->post('redoDeptRespHidden');

        $namePrepress                         = $this->input->post('namePrepress');

        $press                                = $this->input->post('press');
        $inspection                           = $this->input->post('inspection');



        $resProbHiddenVal                     = $this->input->post('resProbHiddenVal');

        $solutionHiddenVal                    = $this->input->post('solutionHiddenVal');

        $photosUploaded                       = $this->input->post('photosUploaded');


        $partialOrderItemsWithRedo            = $this->input->post('partialOrderItemsWithRedoHidden');

        $orderUrgency                         = $this->input->post('orderUrgency');

        $shippingUrgency                      = $this->input->post('shippingUrgency');

        $partialOrderItemsWithRedoArry        = explode(",", $partialOrderItemsWithRedo);

        // populate the data
        $orderRedoData['kf_OrderID']          =  $orderID;

        $orderRedoData['t_RequestedBy']       = $requestedBy;

        $orderRedoData['t_ApprovedBy']        =  $approvedBy;

//        $orderRedoData['ts_DateRequested']    =  $dateTimeWhenRequested;
        //$orderRedoData['ts_DateApproved']     =  $dateTimeApproved;
        $orderRedoData['ts_DateApproved']     =   date('Y-m-d H:i:s');

        $orderRedoData['t_Status']            =  $redoStatus;
        $orderRedoData['t_ItemsRedo']         =  $itemsRedo;

        $orderRedoData['t_Department']        =  $redoDepartResponsible;
        $orderRedoData['t_CustomerIssue']     =  $customerIssue;
        $orderRedoData['t_SalesViewIssue']    =  $salesIssue;

        $orderRedoData['t_NamePrepress']      =  $namePrepress;
        $orderRedoData['t_NamePress']         =  $press;
        $orderRedoData['t_NameInspection']    =  $inspection;

        $orderRedoData['t_ResearchedProblem'] =  $resProbHiddenVal;
        $orderRedoData['t_Solution']          =  $solutionHiddenVal;

        $orderRedoData['t_OrderUrgency']      = $orderUrgency;

        $orderRedoData['t_ShippingUrgency']   = $shippingUrgency;

        // get orderIDArry
        $orderIDArry                          = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$orderID);

        // get customerID from orderIDArry
        $customerID                           = $orderIDArry['kf_CustomerID'];


        //get Company Name from CusomterID
        $customerIDArry                       = Modules::run('customers/customercontroller/getCustomerFieldsFromCustomerID',$customerID);

        $customerCompanyName                  = $customerIDArry['t_CustCompany'];

        $emailData['companyName']             = "<strong>Company Name:</strong> ".$customerCompanyName;
        $emailData['requestedBy']             = "<strong>Request By</strong>: ".$requestedBy;
        $emailData['oldOrderID']              = $orderID;
        $emailData['orderRedoID']             = $orderRedoID;

        $emailSubject                         = "Redo - ".$customerCompanyName." - ".$orderID;

        $emailData['emailSubject']            = $emailSubject;
        $emailData['redoStatus']              = $redoStatus;

        //echo $requestedBy."<br/>";

        $emailData['redoRequestedBy']         = $this->orderRedoEmployeeEmailAddress($requestedBy);

        //echo $emailData['redoRequestedBy'] ;

        // A update that will also create a new Order, OrderItems and OrderItemComponents,orderShip
        if($redoStatus == "Approved" && $approvedBy !="" && $orderIDRedo =="")
        {
            //echo "hi1";

            //insert a new orderID
            //$orderIDArry                = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$orderID);

            $typeOfJobTicket            = $orderIDArry['t_TypeOfJobTicket'];

            $typeOfOrder                = "Redo";

            //1. duplicate Orders from old OrderID
            $newOrderID                 = Modules::run('orders/ordercontroller/duplicateOrder',$orderID,$typeOfJobTicket,$typeOfOrder,$orderID,$orderRedoID);

            //2. duplicate OrderItem and OrderItem Components - Based on Partial and All Items
            if($itemsRedo == "Partial")
            {
                  //echo "hi2";
                  $partialAllorderItemArry    = array();
                  $y                          = 0;
                  $oldOrderItemArry = Modules::run('orderItems/orderitemcontroller/getOrderItemsFromOrderID',$orderID);

                  $errors = array_filter($partialOrderItemsWithRedoArry);

                  if (!empty($errors))
                  {
                      foreach($partialOrderItemsWithRedoArry as $orderItemID)
                      {
                            for($i=0;$i<sizeof($oldOrderItemArry);$i++)
                            {
                               $orderItemIDFound = in_array($orderItemID, $oldOrderItemArry[$i]);
                               if($orderItemIDFound)
                               {
                                   $partialAllorderItemArry[$y]=$oldOrderItemArry[$i];
                                   $y++;
                               }
                            }
                      }
                      //print_r($partialAllorderItemArry);
                      for($i=0; $i<sizeof($partialAllorderItemArry); $i++)
                      {
                          $oldOrderItemID                                                = $partialAllorderItemArry[$i]['kp_OrderItemID'];
                          $partialAllorderItemArry[$i]['kp_OrderItemID']                 = "";
                          $partialAllorderItemArry[$i]['kf_OrderID']                     = $newOrderID;

                          $partialAllorderItemArry[$i]['t_OiStatus']                     = null;
                          $partialAllorderItemArry[$i]['t_SportJobNumber']               = null;
                          $partialAllorderItemArry[$i]['t_SportItemNumber']              = null;
                          $partialAllorderItemArry[$i]['t_SportLocationNumber']          = null;
                          $partialAllorderItemArry[$i]['d_ArtReceived']                  = null;
                          $partialAllorderItemArry[$i]['nb_ArtReceivedProduction']       = null;
                          $partialAllorderItemArry[$i]['t_ArtReceivedBy']                = null;
                          $partialAllorderItemArry[$i]['t_ArtContact']                   = null;

                          // request came from robbie to add them for the image url
                          $partialAllorderItemArry[$i]['t_OrderItemImage']               = null;
                          $partialAllorderItemArry[$i]['t_OrderItemProof']               = null;

                          //print_r($partialAllorderItemArry[$i]);

                          $orderItemArryResult                                           = array($partialAllorderItemArry[$i]);

                          //print_r($orderItemArryResult);

                          $newOrderItemID                                                = Modules::run('orderItems/orderitemcontroller/insertIntoOrderItemTable',$orderItemArryResult);

                          //echo $newOrderItemID." == ".$oldOrderItemID."<br/>";

                          $oldOrderItemComponentArry = Modules::run('orderItemComponents/orderitemcomponentcontroller/getOrderItemComponentArrayFromOrderItemID',$oldOrderItemID);

                          for($x=0; $x<sizeof($oldOrderItemComponentArry); $x++)
                          {
                              $oldOrderItemComponentArry[$x]['kp_OrderItemComponentID']  = "";
                              $oldOrderItemComponentArry[$x]['kf_OrderID']               = $newOrderID;
                              $oldOrderItemComponentArry[$x]['kf_OrderItemID']           = $newOrderItemID;

                          }
                          //print_r($oldOrderItemComponentArry);
                          //7. Insert the duplicated OIC array in the OIC able.
                          Modules::run('orderItemComponents/orderitemcomponentcontroller/submitOrderItemComponentTable',$oldOrderItemComponentArry);

                        }
                    }
            }
            if($itemsRedo == "All Items")
            {
                Modules::run('orders/ordercontroller/dupOrderItemOrderItemComponents',$orderID,$newOrderID);
                //echo "hi3";

            }

            //3. duplicate OrderShip
            //3.1 get the orderShipArry from the old OrderID
            $getOrderShipArrFromOrderID           = Modules::run('orderShip/ordershipcontroller/getOrderShipTblFromOrderID',$orderID);

            //print_r($getOrderShipArrFromOrderID);

             if(!empty($getOrderShipArrFromOrderID))
             {
                //echo "hi4";

                //3. Duplicate orderShipArr with the new OrderID and Insert the duplicated orderShipArr array in the orderShip table.
                Modules::run('orderShip/ordershipcontroller/duplicateOrderShipDataFromOrderID',$getOrderShipArrFromOrderID,$newOrderID);
             }
             $orderRedoData['kf_OrderIDRedo']          =  $newOrderID;

             $this->orderredomodel->updateOrderRedoTable($orderRedoID,$orderRedoData);




             $emailData['newOrderID']   = $newOrderID;

             $this->orderRedoSendEmail($emailData);

             echo json_encode($newOrderID);

        }
        else
        {
            // do a regular update
            $this->orderredomodel->updateOrderRedoTable($orderRedoID,$orderRedoData);

            //echo "hi5";

            //move uploaded photos if any
            if($photosUploaded == "1")
            {
                $this->moveFilesOnceUploaded($orderRedoID, $photosUploaded, $orderID);
            }

            $orderRedoItemDataArry                = array();
            $orderRedoItemDataArry['orderItemID'] = $partialOrderItemsWithRedoArry;
            $orderRedoItemDataArry['orderRedoID'] = $orderRedoID;

            $orderItemIDArry                      = Modules::run('orderRedoItems/orderredoitemcontroller/getOrderItemsFromOrderRedoItemArry',$orderRedoID);
            //$partialOrderItemsWithRedoArryFresh = array();

            //print_r($orderItemIDArry);

            //$ps = 0;

            //print_r($partialOrderItemsWithRedoArry);

            //insert new orderItemID
//            for($i=0;$i<sizeof($partialOrderItemsWithRedoArry);$i++)
//            {
//                $orderItemFound = in_array($partialOrderItemsWithRedoArry[$i], $orderItemIDArry);
//
//                 if(!$orderItemFound)
//                 {
//                      $partialOrderItemsWithRedoArryFresh[$ps] = $partialOrderItemsWithRedoArry[$i];
//                      $ps++;
//                 }
//            }
            //$orderRedoItemDataArry['orderItemID'] = $partialOrderItemsWithRedoArryFresh;

            Modules::run('orderRedoItems/orderredoitemcontroller/deleteOrderRedoItems',$orderRedoID);

            //print_r($orderRedoItemDataArry);
            $this->insertIntoOrderRedoItemTable($orderRedoItemDataArry);





           //send email to redoteam@indyimaging.com
           $this->orderRedoSendEmail($emailData);

           echo json_encode("done");
        }
    }
    public function orderRedoSendEmail($emailData=null)
    {
        date_default_timezone_set('America/Indianapolis');
        $emailSubject =  $emailData['emailSubject'];
        $empEmail     =  $emailData['redoRequestedBy'];

        //print_r($emailData);

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

        $this->load->library('email',$config);
        $this->email->set_newline("\r\n");
        $this->email->from('noreply@indyimaging.com');
        $this->email->to('robbie@indyimaging.com,redoteam@indyimaging.com,'.$emailData['salesEmail']);
        // $this->email->to('matthew.sickler@indyimaging.com');
        // $this->email->to($emailData['salesEmail']);
        $this->email->cc($empEmail);


        $this->email->subject($emailSubject);

        $data['generalMesg'] = "This has been updated on ".date('m/Y/d, g:i:s a').". Please review the below link";
        $data['companyName']    = $emailData['companyName'];
        $data['requestedBy']    = $emailData['requestedBy'];
        $data['oldOrderID']     = $emailData['oldOrderID'];
        $data['orderRedoID']    = $emailData['orderRedoID'];
        $data['redoStatus']     = $emailData['redoStatus'];
        $data['redoStatusMsg']  = "<strong>Status: </strong>".$emailData['redoStatus'];

        if($emailData['redoStatus'] == "Approved" && isset($emailData['newOrderID'])) {
            $data['newOrderID']  = $emailData['newOrderID'];
        }

        $msg = $this->load->view('emailTemplate3',$data, true);

        $this->email->message($msg);
        $this->email->send();
    }
    public function loadEmailTemplate()
    {
        $this->load->view('emailTemplate3');

    }
    public function redoListView($orderID=null)
    {
        if(isset($orderID))
        {
            $data['orderID'] = $orderID;

            $this->load->view('redolist',$data);
        }
        else
        {
            $this->load->view('error',$data);
        }
    }
    public function redoView($orderRedoID=null)
    {
        if(isset($orderRedoID))
        {
            //send orderID too
            $orderRedoTableData  = $this->orderredomodel->getAllOrderRedoData($orderRedoID);
            $data['orderID']     = $orderRedoTableData['kf_OrderID'];
            $data['orderRedoID'] = $orderRedoID;

            //get orderID from orderRedoID

            //$data['orderID'] = $orderID;
            $this->load->view('redo',$data);
        }
        else
        {
            $this->load->view('error',$data);
        }

    }
    public function redoReadOnly($orderID,$orderRedoID=null)
    {
        if(isset($orderRedoID) && isset($orderID))
        {
            //send original orderID too
            $orderRedoTableData  = $this->orderredomodel->getAllOrderRedoData($orderRedoID);

            //$data['orderRedoID'] = $orderRedoID;
            $data['originalOrderID']     = $orderRedoTableData['kf_OrderID'];
            $data['orderID']             = $orderID;
            $data['orderRedoID']         = $orderRedoID;

            //get orderID from orderRedoID

            //$data['orderID'] = $orderID;
            $this->load->view('redoSimple1',$data);
        }
        else
        {
            $this->load->view('error',$data);
        }

    }
    public function getRedoListInfo($orderID)
    {
        //var_dump($this->orderredomodel->getRedoListData($orderID));
        echo json_encode($this->orderredomodel->getRedoListData($orderID));
    }
    public function loadRedoRequestInfo($orderID=null)
    {
        if(isset($orderID))
        {
            $data['orderID'] = $orderID;

            $this->load->view('redorequest',$data);
        }
    }
    public function subval_sort($a,$subkey)
    {
	foreach($a as $k=>$v)
        {
            $b[$k] = strtolower($v[$subkey]);
	}
        //var_dump($b);
	asort($b);
        //var_dump($b);
	foreach($b as $key=>$val)
        {
            //echo $key." ".$val."<br/>";
            //var_dump($a[$key])."<br/>";
            $c[] = $a[$key];
	}
        //var_dump($c);
	return $c;
    }
    public function orderItemWithRedo($orderID,$orderRedoID=null)
    {
        //get a list of orderitems for a given orderID
        $orderWithRedo                = array();
        $orderItemCompArry            = Modules::run('orderItems/orderitemcontroller/getOrderItemsFromOrderID',$orderID);
        //var_dump($orderItemCompArry);

        //var_dump($orderRedoTableData);
        $orderItemIDArry               = Modules::run('orderRedoItems/orderredoitemcontroller/getOrderItemsFromOrderRedoItemArry',$orderRedoID);

        //print_r($orderItemIDArry);



        for($i = 0; $i<sizeof($orderItemCompArry); $i++)
        {
            $orderWithRedo[$i]['orderItemID'] = $orderItemCompArry[$i]['kp_OrderItemID'];
            $orderWithRedo[$i]['dashNum']     = $orderItemCompArry[$i]['n_DashNum'];

            $orderWithRedo[$i]['qtyHtWPro']   = "Qty ".$orderItemCompArry[$i]['n_Quantity']
                                                ." ".  (round($orderItemCompArry[$i]['n_HeightInInches']/12,2))
                                                ."' H x ".(round($orderItemCompArry[$i]['n_WidthInInches']/12,2))
                                                ."' W ".$orderItemCompArry[$i]['t_ProductType'];
            $orderWithRedo[$i]['description'] = $orderItemCompArry[$i]['t_Description'];

        }

        //var_dump($orderWithRedo);
        $orderWithRedoSortByDashNum = $this->subval_sort($orderWithRedo,'dashNum');
        //var_dump($orderWithRedo);

        for($x=0;$x<sizeof($orderWithRedoSortByDashNum);$x++)
        {
            $orderItemIDFound = in_array($orderWithRedoSortByDashNum[$x]['orderItemID'], $orderItemIDArry);
            if($orderItemIDFound)
            {
                 //echo "<br/>hi"."<br/>";
                 $orderWithRedoSortByDashNum[$x]['checkAttr']="checked";
            }
            else
            {
                 $orderWithRedoSortByDashNum[$x]['checkAttr']="";

            }

        }



        //var_dump($orderWithRedo);
        echo json_encode($orderWithRedoSortByDashNum);
        //return json_encode($orderWithRedo);
    }
}

?>
