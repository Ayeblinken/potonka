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

class UploadFiles_Api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!verifyToken()) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        date_default_timezone_set('America/Indianapolis');
        $this->load->model('uploadfilesmodel');

    }
    public function uploadFilesByUploadID_get()
    {
        if(!$this->get('kf_Upload'))
        {
            $this->response(array('error' => 'No uploadID found data'), 400);

        }

        $uploadID = $this->get('kf_Upload');

        $result = $this->uploadfilesmodel->getUploadFilesDataFromUploadID($uploadID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'upload data could not be found from the supploed date'), 404);
        }


    }
    public function uploadCustomerFiles_post()
    {
        //print_r($_POST);
        $data = $this->post();

        //print_r($data);

        //print_r($_FILES);

        if(!empty($_FILES['file']['name']))
        {
            $uploadFilesData['t_filename']    = str_replace(" ", "_", $_FILES['file']['name']);

            //$uploadFilesData['ts_uploaded']   = date("Y-m-d H:i:s", time());

            $uploadFilesData['t_Filesize']    = $_FILES["file"]["size"];

            $uploadFilesData['kf_Upload']     = $data['kf_Upload'];

            $uploadFilesInsertID              = $this->uploadfilesmodel->insertUploadFilesTblData($uploadFilesData);

            $msg                              = $this->uploadfilesmodel->doCustomFileUpload($uploadFilesData['kf_Upload']);

            if($msg)
            {
                echo json_encode(array('uploadFilesID'=>$uploadFilesInsertID));
            }
        }

//        if(!empty($_FILES['file']['name']))
//        {
//            $uploadFilesData['t_Filename']   = $_FILES['file']['name'];
//            $uploadFilesData['t_Filesize']   = $_FILES["file"]["size"];
//                //$uploadFilesData['ts_uploaded']  = $today;
//
//            $uploadFilesInsertID             = $this->uploadfilesmodel->insertUploadFilesTblData($uploadFilesData);
//
//            // do custom file upload
//            $msg                             = $this->uploadfilesmodel->doCustomFileUpload($uploadFilesInsertID);
//            if($msg)
//            {
//                echo json_encode(array('uploadFilesID'=>$uploadFilesInsertID));
//            }
//        }
    }
}
