<?php

class DocumentsModel extends CI_Model
{
    //put your code here
    public function __construct()
    {
        parent::__construct();
        $this->imageUploadPath = realpath(APPPATH . '../../../images/Orders');

        $this->loadImagePath   = 'https://'.$_SERVER['SERVER_NAME'].'/images/Orders';

        $this->load->library('image_lib');
    }

    public function getOrderByID($orderID)
    {
        $query = $this->db->get_where('Orders', array('kp_OrderID' => $orderID));

        return $query->row_array();
    }

    public function insertDocumentsTblData($data)
    {
        $this->db->set($data,false);
        $this->db->insert('Documents');

        return $this->db->insert_id();
    }

    public function updateDocumentTbl($data,$documentID)
    {
        $result = $this->db->update('Documents', $data, array('kp_DocumentsID'=>  $documentID));

        if(!$result)
        {
            return $this->db->_error_message();
        }
        else
        {
            return $this->db->affected_rows();

        }

    }


    public function getDocumentDataByOrderID($orderID)
    {
        $this->db->select('*')
        ->from('Documents')
        ->where('kf_OrderID', $orderID);

        $query = $this->db->get();

        return $query->result_array();
    }

    public function doDocumentCustomUpload($dateReceived,$orderID,$filename)
    {
        $allowed = array('jpeg','jpg','png','pdf','docx','doc','xls','xlsx');

        if(file_exists(realpath(APPPATH . '../../../images/.am_i_mounted'))&& !is_null($dateReceived))
        {
            $dateOrderReceivedArr = explode("-", $dateReceived);

            $yearOrder            = $dateOrderReceivedArr[0];

            $monthOrder           = $dateOrderReceivedArr[1];

            if(isset($_FILES['file']) && $_FILES['file']['error'] == 0)
            {
                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                if(!in_array(strtolower($extension), $allowed))
                {
                     echo '{"status":"error file extension"}';
                     exit;
                }

                $tmpName          = $_FILES['file']['tmp_name'];

                // checks and creates Month and Year Folder
                if(!is_dir($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder))
                {
                    if(!mkdir($this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder,0777,TRUE))
                    {
                        die("Failed to create Year and Month Folders");
                    }

                }
                $path              = $this->imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID;

                $documentFolder    = "references";

                if(!is_dir($path)) // checks if the order# has a folder or not
                {
                    if(!mkdir($path,0777,TRUE))
                    {
                        die('Failed to create Order and other folders...');
                    }
                    else
                    {
                        //change the directory owner/group permission for OrderItemID folder
                        chmod($path, 0777);
                    }
                }

                // checks and creates the document Folder
                if(!is_dir($path.'/'.$documentFolder)) // checks if the the document Folder # has a folder or not, if the order# already has a folder.
                {
                    if(!mkdir($path.'/'.$documentFolder,0777,TRUE))
                    {
                        die('Failed to create OrderItem and other folders...');
                    }
                    else
                    {
                        //change the directory owner/group permission for OrderItemID folder
                        chmod($path.'/'.$documentFolder, 0777);
                    }
                }

                $newFileName      = $path.'/'.$documentFolder.'/'.$filename;

                if(move_uploaded_file($tmpName, $newFileName))
                {
                    $tmp                              = getimagesize($newFileName);

                    $maxWidth                         = 1500;

                    $maxHeight                        = 1200;

                    $uploadedFileNameWithoutExtesnion = basename($filename,".".strtolower($extension));

                    if(strtolower($extension) == "pdf")
                    {
                        $imageResize['thumbNailCreation']  = "no";
                        $imageResize['imgType']            = strtolower($extension);

                        $imageResize['msg']                = "success";
                        $imageResize['thumbImgPath']       = "no path";



//                        $quality                          = 90;
//                        $res                              = '300x300';
//                        //$uploadedFileNameWithoutExtesnion = basename($filename,".pdf");
//
//                        $exportPath                       = $path.'/'.$documentFolder.'/'.$uploadedFileNameWithoutExtesnion.'.jpg';
//
//                        //save the pdf as an jpeg image
//                        exec("'gs' '-dNOPAUSE' '-sDEVICE=jpeg' '-dUseCIEColor' '-dTextAlphaBits=4' '-dGraphicsAlphaBits=4' '-o$exportPath' '-r$res' '-dJPEGQ=$quality' '$newFileName'",$output);
//
//
//                        //[ THUMB IMAGE ]
//                        //$img_config_pdf_0['library_path']    = "/opt/local/bin";
//                        $img_config_pdf_0['image_library']   = 'gd2';
//                        $img_config_pdf_0['source_image']    = $exportPath;
//                        $img_config_pdf_0['maintain_ratio']  = TRUE;
//                        $img_config_pdf_0['width']           = 250;
//                        $img_config_pdf_0['height']          = 200;
//                        $img_config_pdf_0['create_thumb']    = TRUE;
//
//                        //print_r($img_config_pdf_0);
//                        $this->image_lib->initialize($img_config_pdf_0);
//
//                        if($this->image_lib->resize())
//                        {
//                            $this->image_lib->clear();
//                            $imageResize['thumbNailCreation']      = "yes";
//                            $imageResize['imgType']                = strtolower($extension);
//                            $imageResize['msg']                    = "success";
//                            $imageResize['thumbImgPath']           = $this->loadImagePath.DIRECTORY_SEPARATOR.$yearOrder.DIRECTORY_SEPARATOR.$monthOrder
//                                                                     .DIRECTORY_SEPARATOR.$orderID.DIRECTORY_SEPARATOR.$documentFolder
//                                                                     .DIRECTORY_SEPARATOR.$uploadedFileNameWithoutExtesnion.'_thumb.jpg';
//
//                            $imageResize['thumbName']              = $uploadedFileNameWithoutExtesnion.'_thumb.jpg';
//                             //echo "Success";
//                        }
//                        else
//                        {
//                            $imageResize['thumbNailCreation'] = "no";
//                            $imageResize['thumbImageError']   = "Failed.". $this->image_lib->display_errors();
//                             //echo "Failed." .$i . $this->image_lib->display_errors();
//                        }
//
//                        //echo memory_get_usage(true) ;
//                        unlink($path.'/'.$documentFolder.'/'.$uploadedFileNameWithoutExtesnion.'.jpg'); // remove any image files.


                    }
                    else if(strtolower($extension) == "jpeg" || strtolower($extension) == "jpg" || strtolower($extension) == "png") //for all other types of formats like jpeg, png
                    {
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
                                $imageResize['thumbNailCreation']  = "yes";

                                $imageResize['msg']                = "success";

                                $imageResize['thumbImgPath']       = $this->loadImagePath.DIRECTORY_SEPARATOR.$yearOrder.DIRECTORY_SEPARATOR.$monthOrder
                                                                     .DIRECTORY_SEPARATOR.$orderID.DIRECTORY_SEPARATOR.$documentFolder
                                                                     .DIRECTORY_SEPARATOR.$uploadedFileNameWithoutExtesnion.'_thumb.'.$extension;

                                $imageResize['thumbName']              = $uploadedFileNameWithoutExtesnion.'_thumb.'.$extension;
                                //echo "Success";
                            }
                            else
                            {
                                $imageResize['thumbNailCreation'] = "no";
                                $imageResize['thumbImageError']   = "Failed.". $this->image_lib->display_errors();
                                //echo "Failed." .$i . $this->image_lib->display_errors();
                            }
                        }
                        else
                        {
                            $img_config_0['image_library']   = 'gd2';
                            $img_config_0['source_image']    = $newFileName;
                            $img_config_0['maintain_ratio']  = TRUE;
                            $img_config_0['width']           = 250;
                            $img_config_0['height']          = 200;
                            $img_config_0['create_thumb']    = TRUE;

                            //[ MAIN IMAGE ]
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
                                    $imageResize['thumbNailCreation']  = "yes";
                                    $imageResize['imgType']            = strtolower($extension);
                                    $imageResize['msg']                = "success";

                                    $imageResize['thumbImgPath']       = $this->loadImagePath.DIRECTORY_SEPARATOR.$yearOrder.DIRECTORY_SEPARATOR.$monthOrder
                                                                        .DIRECTORY_SEPARATOR.$orderID.DIRECTORY_SEPARATOR.$documentFolder
                                                                        .DIRECTORY_SEPARATOR.$uploadedFileNameWithoutExtesnion.'_thumb.'.$extension;

                                    $imageResize['thumbName']          = $uploadedFileNameWithoutExtesnion.'_thumb.'.$extension;
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

                    }
                    else if(strtolower($extension) == "docx" || strtolower($extension) == "doc")
                    {
                        $imageResize['thumbNailCreation']  = "no";
                        $imageResize['imgType']            = strtolower($extension);

                        $imageResize['msg']                = "success";
                        $imageResize['thumbImgPath']       = "no path";
                    }
                    else if(strtolower($extension) == "xlsx" || strtolower($extension) == "xls")
                    {
                        $imageResize['thumbNailCreation']  = "yes";

                        $imageResize['imgType']            = strtolower($extension);

                        $imageResize['msg']                = "success";
                        $imageResize['thumbImgPath']       = "no path";
                    }

                    return $imageResize;

                }

            } else {
              echo '{"status":"File error 1"}';
              exit;
            }
        } else {
          echo '{"status":"no mounted file or no d_Created"}';
          exit;
        }

    }
}

?>
