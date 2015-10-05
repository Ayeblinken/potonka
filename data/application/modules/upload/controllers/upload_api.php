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

class Upload_Api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        date_default_timezone_set('America/Indianapolis');
        $this->load->model('uploadmodel');

    }

    public function uploadTableData_get() {
      if(!$this->get('date') || !$this->get('type')) {
        $this->response(NULL, 400);
      }
      $date = $this->get('date');
      $type = $this->get('type');

      if($type == 'complete') {
        $result = $this->uploadmodel->getUploadDataFromTimeStampCreateDate($date);
      } else {
        $result = $this->uploadmodel->getUploadPendingDataFromTimeStampCreateDate($date);
      }


      if($result) {
        $this->response($result, 200);
      } else {
        $this->response(array(array('error' => 'No orders for that day')), 200);
      }
    }

    public function uploadPath_get()
    {
        echo  realpath(APPPATH . '../../../upload');
    }
    public function phpini_get()
    {
        echo phpinfo();
    }
    public function uplaodByDate_get()
    {
        if(!$this->get('selectedDate'))
        {
            $this->response(NULL, 400);

        }

        $selectedDate = $this->get('selectedDate');

        $result = $this->uploadmodel->getUploadDataFromTimeStampCreateDate($selectedDate);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response($result, 200);

            //$this->response(array('error' => 'upload data could not be found from the supploed date'), 404);
        }


    }
    public function uplaodPendingByDate_get()
    {
        if(!$this->get('selectedDate'))
        {
            $this->response(NULL, 400);

        }

        $selectedDate = $this->get('selectedDate');

        $result = $this->uploadmodel->getUploadPendingDataFromTimeStampCreateDate($selectedDate);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response($result, 200);

            //$this->response(array('error' => 'upload data could not be found from the supploed date'), 404);
        }


    }
    public function upload_get()
    {
        if(!$this->get('uploadID'))
        {
            $this->response(NULL, 400);

        }
        $uploadID = $this->get('uploadID');

        $result = $this->uploadmodel->getUploadDataFromUploadID($uploadID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'upload data could not be found from the supploed UploadID'), 404);
        }

    }
    public function upload_post()
    {
        if(!$this->post('kp_Upload'))
        {
            $this->response(array('UplaodID' => $this->post('kp_Upload'),'error' => 'somethign went wrong hellow UploadID could not be found'), 400);
        }
        $data      = $this->post();

        $uploadID  = $this->post('kp_Upload');
        //print_r($data);

        $result    = $this->uploadmodel->updateUploadTable($data,$uploadID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'somethign went wrong with upload update'), 404);
        }
    }
    public function uploadCustomerInfo_post()
    {
        $data       = $this->post();

        //print_r($data);

        if(array_key_exists('formData',$data))
        {
            $today                             = date("Y-m-d H:i:s", time());

            $data['formData']['ts_CreateDate'] = $today;

            //print_r($data);


            $uploadID                          = $this->uploadmodel->insertUploadTblData($data['formData']);

            if($uploadID)
            {
                $this->response(array('uploadID'=>$uploadID), 200); // 200 being the HTTP response code
            }
            else
            {
                $this->response(array('error' => 'somethign went wrong with upload ID is not found'), 404);
            }

        }
    }
    public function sendUploadErroEmail_post()
    {
        //print_r($this->post());

        //print_r($this->post('uploadedFileArry'));
        $customerEmail         = $this->post('customerEmail');

        $customerName          = $this->post('customerName');

        $t_IndyContact         = $this->post('t_IndyContact');

        $customerPhone         = $this->post('customerPhone');



        $uploadErrCode         = $this->post('uploadErrCode');
        $uploadErrMsg          = $this->post('uploadErrMsg');

        $uploadID              = $this->post('uploadFrmID');

        $uploadFileNames       = $this->post('uploadFileNames');

        $uploadedFileArry      = $this->post('uploadedFileArry');

        $uploaderArry          = $this->post('uploaderArry');

        $data['t_UploadError'] =  "Error Code: ".$uploadErrCode."  Error Msg:".$uploadErrMsg;

        if($uploadID)
        {
            $this->uploadmodel->updateUploadTable($data,$uploadID);
        }

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

        $from        = 'noreply<noreply@indyimaging.com>';

        $to          = 'Developer <sraparla@indyimaging.com>,robbie@indyimaging.com';

        $subject     = "Error Uploading:";

        $body        = "<p><strong>UploadID:</strong> ".$uploadID."</p>";
        $body       .= "<p><strong>Customer Name:</strong> ".$customerName."<br/> <strong>Customer Email:</strong> ".$customerEmail."<br/><strong>Customer Phone:</strong>".$customerPhone."</p><br/><p><strong>Sales Contact:</strong> ".$t_IndyContact."</p>";

        $body       .= "<p><strong>Error Info:</strong> ".$uploadErrCode. "  ".$uploadErrMsg."</p>";

        $this->load->library('email',$config);
        $this->email->set_newline("\r\n");
        $this->email->from($from);
        $this->email->to($to);

        $this->email->subject($subject);
        $msg = $body;

        $this->email->message($msg);
        $this->email->send();

    }
    public function sendUploadEmail_post()
    {
        $data['nb_UploadComplete'] = "1";
        $uploadID                  = $this->post('uploadFrmID');




        $today                     = date("Y-m-d H:i:s", time());

        $data['ts_UploadComplete'] = $today;

        if($uploadID)
        {
            $this->uploadmodel->updateUploadTable($data,$uploadID);
        }

        $id               = $this->post('id');

        $customerEmail    = $this->post('customerEmail');
        $customerName     = $this->post('customerName');
        $notesName        = $this->post('notesName');

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

        $from        = 'noreply<noreply@indyimaging.com>';

        $to          ="${customerName}<${customerEmail}>,";

        $subject     = "IndyImaging Upload Confirmaiton";

        $body        = "<p>The following files have been uploaded:".$id."</p><br/><br/>";

        $body       .= "<p><strong>Notes:</strong><br/> ".$notesName."</p>";

        $this->load->library('email',$config);
        $this->email->set_newline("\r\n");
        $this->email->from($from);
        $this->email->to($to);

        $this->email->subject($subject);
        $msg = $body;

        $this->email->message($msg);
        $this->email->send();


        //echo $this->email->print_debugger();

    }
}
