<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class EmailAngController extends MX_Controller
{
    //put your code here
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        //$this->load->model('orderitemmodel');

    }
    public function customAngUploadError($errorCause)
    {
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

        //$to          ="IT<it@indyimaging.com>,";

        $to          = 'dev@indyimaging.com';

        $subject     = $errorCause['msg'];
        $body        = "<p><strong>Error Origin:</strong> ".$errorCause['location']."</p><br/>";


        $this->load->library('email',$config);
        $this->email->set_newline("\r\n");
        $this->email->from($from);
        $this->email->to($to);

        $this->email->subject($subject);
        $msg = $body;

        $this->email->message($msg);
        $this->email->send();
    }

}

?>
