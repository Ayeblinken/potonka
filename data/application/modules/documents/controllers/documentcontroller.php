<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DocumentController extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('documentsmodel');
        $this->loadImagePath   = 'https://'.$_SERVER['SERVER_NAME'].'/images/Orders';
        //$this->load->library('user_agent');
        date_default_timezone_set('America/Indianapolis');

    }
    public function documentUploadFromCurl()
    {
        //print_r($_POST);
        $allowedDocTypes     = array('or','od','pt', 'pl');

        $orderID             = $this->input->post('orderID');
        $doctype             = $this->input->post('docType');

        $orderArry           = $this->documentsmodel->getOrderByID($orderID);

        if(!in_array(strtolower($doctype), $allowedDocTypes))
        {
             echo '{"DocType":"not Valid DocType"}';
             exit;
        }
        if($orderArry)
        {
            if(!empty($_FILES['file']['name']))
            {
                //print_r($_FILES);
                $data['kf_OrderID']       = $orderID;

                if(strtolower($doctype) == "or")
                {
                    $doctype = "Order Request";
                }
                else if(strtolower($doctype) == "od")
                {
                    $doctype = "Order";
                }
                else if(strtolower($doctype) == "pt")
                {
                   $doctype = "Print Ticket";
                }
                else if(strtolower($doctype) == "pl")
                {
                   $doctype = "Packing List";
                }

                $data['t_Doctype']        = $doctype;

                $data['t_filename']       = str_replace(" ", "_", $_FILES['file']['name']);

                $data['ts_DateCreated']   = date("Y-m-d H:i:s", time());


                $extension                = pathinfo($data['t_filename'], PATHINFO_EXTENSION);






                $dateReceived             = $orderArry['d_Received'];

                // now upload the document

                $msg                      = $this->documentsmodel->doDocumentCustomUpload($dateReceived,$data['kf_OrderID'],$data['t_filename']);
//


                if($msg)
                {
                    $data['t_thumbname']      = $msg['thumbImgPath'];
                    $data['t_Ext']            = $extension;

                    $referenceInsertID        = $this->documentsmodel->insertDocumentsTblData($data);

                    echo $msg['msg'];
                    //print_r($msg);
                }
                else
                {
                    echo "Fail_Upload";
                }

            }

        }
        else
        {
            echo "OrderID_Not_Valid";
        }


        //save the pdf as an jpeg image
        //exec("'gs' '-dNOPAUSE' '-sDEVICE=jpeg' '-dUseCIEColor' '-dTextAlphaBits=4' '-dGraphicsAlphaBits=4' '-o$exportPath' '-r$res' '-dJPEGQ=$quality' '$newFileName'",$output);


    }


}

?>
