<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Documents_Api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        date_default_timezone_set('America/Indianapolis');
        $this->load->model('documentsmodel');

    }
    public function documentsFromOrderID_get()
    {
        if(!$this->get('kf_OrderID'))
        {
            $this->response(array('error' => 'Documents gggg could not be found'), 400);
        }
        $orderID = $this->get('kf_OrderID');

        $orderArry           = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$orderID);

        $dateReceived        = $orderArry['d_Received'];

        $dateOrderReceivedArr = explode("-", $dateReceived);

        $yearOrder            = $dateOrderReceivedArr[0];

        $monthOrder           = $dateOrderReceivedArr[1];

        $result  = $this->documentsmodel->getDocumentDataByOrderID($orderID);

        //print_r($result);
        //echo sizeof($datAdd);
        for($x=0;$x<sizeof($result);$x++)
        {
            $result[$x]['yearOrder']  = $yearOrder;
            $result[$x]['monthOrder'] = $monthOrder;
        }
        //var_dump($result);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'No documents found.'), 404);
        }
    }
    public function documentInfo_put()
    {
        $data                       = $this->put('formData',false);

        $data['ts_DateCreated']     = date("Y-m-d H:i:s", time());

        $referenceInsertID          = $this->documentsmodel->insertDocumentsTblData($data);

        $result                     = array('insertDocumentID'=>$referenceInsertID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'somethign went wrong Address could not be found'), 404);
        }

    }
    public function documents_post()
    {
        if(!$this->post('kp_DocumentsID'))
        {
            $this->response(array('kp_DocumentsID' => $this->post('kp_DocumentsID'),'error' => 'somethign went wrong hellow Documents ID could not be found'), 400);
        }
        else
        {
            $data           = $this->post('formData',false);

            $documentID     = $this->post('kp_DocumentsID');

            $result         = $this->documentsmodel->updateDocumentTbl($data,$documentID);
        }
        if($result == "0" || $result == "1")
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'somethign went wrong gogoog Address could not be found'. $result), 404);
        }

    }
    public function documentsUploadFiles_post()
    {
        $data                = $this->post();

        $orderArry           = Modules::run('orders/ordercontroller/getOrderFieldsFromOrderID',$data['kf_OrderID']);

        if($orderArry)
        {
            if(!empty($_FILES['file']['name']))
            {
                $t_Ext   = $_FILES['file']['type'];
                //echo "File Type: <br/>";
                //echo $t_Ext."<br/>";
                $t_Ext = substr($t_Ext,(strpos($t_Ext,"/")+1));
                if($t_Ext == "vnd.openxmlformats-officedocument.spreadsheetml.sheet")
                {
                    $data['t_Ext'] = "xslx";
                }
                else if($t_Ext == "vnd.openxmlformats-officedocument.wordprocessingml.document")
                {
                    //echo "inside if condition";
                    $data['t_Ext'] = "docx";
                    //echo $t_Ext."<br/>";
                }
                else if($t_Ext == "msword")
                {
                    $data['t_Ext'] = "doc";
                }
                else if($t_Ext == "vnd.ms-excel")
                {
                     $data['t_Ext'] = "xsl";
                }
                else
                {
                    $data['t_Ext'] = $t_Ext;
                }

                //$data['t_Ext']            = $t_Ext;

                //$data['t_filename']       = $_FILES['file']['name'];
                $data['t_filename']       = str_replace(" ", "_", $_FILES['file']['name']);

                $data['ts_DateCreated']   = date("Y-m-d H:i:s", time());
                //print_r($data);
                //$referenceInsertID        = $this->documentsmodel->insertDocumentsTblData($data);

                //print_r($data);
                //echo "<br/>";
                //print_r($_FILES);
                //echo $_FILES['file']['type'];
                $dateReceived             = $orderArry['d_Received'];


                $msg                      = $this->documentsmodel->doDocumentCustomUpload($dateReceived,$data['kf_OrderID'],$data['t_filename']);

                //$msg['kp_DocumentsID']    = $referenceInsertID;

                $msg['t_filename']        = $data['t_filename'];

                $msg['t_Ext']             = $data['t_Ext'];


                if($msg)
                {
                    echo json_encode($msg);
                    //$this->response($msg, 200); // 200 being the HTTP response code
                }
                else
                {
                    $this->response($msg, 404);
                }

            }
            else
            {
                $this->response($msg, 404);
            }

        }
        else
        {
            $this->response($msg, 404);
        }



    }

}
