<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CustomEmailController extends MX_Controller
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

    public function genericEmailFile($to, $body, $subject, $file, $replyTo) {
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
    $this->email->to($to);
    $this->email->reply_to($replyTo);

    $this->email->subject($subject);

    $this->email->message($body);
    $this->email->attach($file);
    $this->email->send();
  }

    public function genericEmailFrom($to, $body, $subject, $from, $cc, $bcc) {
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
    $this->email->from($from);
    $this->email->cc($cc);
    $this->email->bcc($bcc);
    $this->email->to($to);

    $this->email->subject($subject);

    $this->email->message($body);
    $this->email->send();
  }

    public function genericEmail($to, $body, $subject) {
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

    $from                      = 'noreply<noreply@indyimaging.com>';


    $this->load->library('email',$config);
    $this->email->set_newline("\r\n");
    $this->email->from($from);
    $this->email->to($to);

    $this->email->subject($subject);

    $this->email->message($body);
    $this->email->send();
  }
    public function ipAddress()
    {
        if (preg_match( "/^([d]{1,3}).([d]{1,3}).([d]{1,3}).([d]{1,3})$/", getenv('HTTP_X_FORWARDED_FOR')))
        {

            return getenv('HTTP_X_FORWARDED_FOR');
        }

        return getenv('REMOTE_ADDR');
    }

    public function orderEmailTrackingError($errorCause)
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

        $from                      = 'noreply<noreply@indyimaging.com>';

        //$to                        = "IT<it@indyimaging.com>,";

        $to                        = 'dev@indyimaging.com';
        // $to = "matthew.sickler@indyimaging.com";
        $subject                   = "Email Tracking Error - Order " . $errorCause['kp_OrderID'];

        $errorCause['ipAddress']   = $this->ipAddress();

        if($errorCause['ipAddress'] == "::1")
        {
            $errorCause['ipAddress'] = "localhost";
        }

        $body        = $errorCause['msg'];

        // $body        = "<p><strong>Error Origin:</strong> ".$errorCause['location']."</p><br/>"
        //                 ."<p><strong> OrderID ".$errorCause['kp_OrderID']."</strong></p>"
        //                 ."<p><strong> CustomerRef_ListID ".$errorCause['CustomerRef_ListID']."</strong></p>"
        //                 ."<p><strong> SalesRepRef_ListID ".$errorCause['SalesRepRef_ListID']."</strong></p>"
        //                 ."<p><strong> t_QBSalesRepListID ".$errorCause['t_QBSalesRepListID']."</strong></p>"
        //                 ."<p><strong> ListID ".$errorCause['ListID']."</strong></p>"
        //                 ."<p><strong> IPADDRESS ".$errorCause['ipAddress']."</strong></p>";


        $this->load->library('email',$config);
        $this->email->set_newline("\r\n");
        $this->email->from($from);
        $this->email->to($to);

        $this->email->subject($subject);
        $msg = $body;

        $this->email->message($msg);
        $this->email->send();
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

        $from                      = 'noreply<noreply@indyimaging.com>';

        //$to                        = "IT<it@indyimaging.com>,";

        $to                        = 'dev@indyimaging.com';
        $subject                   = $errorCause['msg'];

        $errorCause['ipAddress']   = $this->ipAddress();

        if($errorCause['ipAddress'] == "::1")
        {
            $errorCause['ipAddress'] = "localhost";
        }

        $body        = "<p><strong>Error Origin:</strong> ".$errorCause['location']."</p><br/>"
                        ."<p><strong> OrderID ".$errorCause['kp_OrderID']."</strong></p>"
                        ."<p><strong> CustomerRef_ListID ".$errorCause['CustomerRef_ListID']."</strong></p>"
                        ."<p><strong> SalesRepRef_ListID ".$errorCause['SalesRepRef_ListID']."</strong></p>"
                        ."<p><strong> t_QBSalesRepListID ".$errorCause['t_QBSalesRepListID']."</strong></p>"
                        ."<p><strong> ListID ".$errorCause['ListID']."</strong></p>"
                        ."<p><strong> IPADDRESS ".$errorCause['ipAddress']."</strong></p>";


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
