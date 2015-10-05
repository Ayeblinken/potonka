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

class Phone_Api extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        $this->load->model('phonemodel');
    }

    public function makePhoneCall_put() {
      if(!$this->put('number') || !$this->put('extension')) {
        $this->response(array('error' => 'Number or Extension Not Found'), 404);
      }

      $number = $this->put('number');
      $extension = $this->put('extension');

      $url = "http://asterisknow.indyimaging.com/call.php?phone=" . $number . "&exten=" . $extension;

      $ch = curl_init($url);
      curl_exec($ch);
      curl_close($ch);

      // $res = new HttpRequest($url, HttpRequest::METH_GET);
      // $res->send();

      // $this->response(array('message' => "Done"), 200);
    }

}
