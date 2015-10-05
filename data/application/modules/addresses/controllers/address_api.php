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

class Address_Api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        if(!checkPermissionsByModule($privSet, 'addresses')) {
          $this->response(array('error' => 'You\'re privilege set doesn\'t allow access to this content'), 400);
        }
        $this->load->model('addressmodel');

    }

    public function address_get()
    {
        if(!$this->get('addressID')) {
        	$this->response(NULL, 400);
        }
        $addressID= $this->get('addressID');

        $result = $this->addressmodel->getAddressesFieldsFromAddressID($addressID);

        if($result) {
            $this->response($result, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'Address could not be found'), 404);
        }

    }

    public function recipientShippingTableData_get() {
      if(!$this->get('orderID')) {
        $this->response(NULL, 400);
      }

      $orderID  = $this->get('orderID');

      $result = $this->addressmodel->getRecipientShippingTableData($orderID);

      if($result) {
        $this->response($result, 200); // 200 being the HTTP response code
      } else {
        $this->response(array('error' => 'Address could not be found'), 404);
      }

    }

    public function blindShippingTableData_get() {
      if(!$this->get('orderID')) {
        $this->response(NULL, 400);
      }

      $orderID  = $this->get('orderID');

      $result = $this->addressmodel->getBlindShippingTableData($orderID);

      if($result) {
        $this->response($result, 200); // 200 being the HTTP response code
      } else {
        $this->response(array('error' => 'Address could not be found'), 404);
      }

    }

    public function customerBillToAddressesBySalesPersonID_get() {
      if(!$this->get('employeeID')) {
        $this->response(NULL, 400);
      }

      $employeeID  = $this->get('employeeID');

      $result = $this->addressmodel->getCustomerBillToAddressesBySalesPersonID($employeeID);

      if($result) {
        $this->response($result, 200); // 200 being the HTTP response code
      } else {
        $this->response(array('error' => 'Addresses could not be found'), 404);
      }
    }
    public function customerBillToAddresses_get() {
      $result = $this->addressmodel->getCustomerBillToAddresses();

      if($result) {
        $this->response($result, 200); // 200 being the HTTP response code
      } else {
        $this->response(array('error' => 'Addresses could not be found'), 404);
      }
    }
    public function address_delete($id)
    {
        //echo 'helloworld '.$id;
        $this->response(array(
            'returned from delete:' => $id,
        ));
        //echo "hello world ". $this->delete('id');
         //$this->response(array('returned from delete:' => $id));

        //var_dump($this->delete('addressID'));
//        if(!$this->delete('addressID'))
//        {
//            $this->response(array('deleteParams'=>$this->delete(),'addressID' => $this->delete('addressID'),'error' => 'somethign went wrong in delete Address'), 400);
//        }
//        else
//        {
//             $addressID = $this->delete('kp_AddressID');
//
//             $result    = $this->addressmodel->deleteAddressDataFromAddressID($addressID);
//        }
//        if($result)
//        {
//            $this->response($result, 200); // 200 being the HTTP response code
//        }
//
//        else
//        {
//            $this->response(array('error' => 'somethign went wrong with the operation of delete'), 404);
//        }

    }
    public function typeOfSubByTypeOfMain_get()
    {
        if(!$this->get('typeMain'))
        {
        	$this->response(NULL, 400);
        }

        $typeMain  = $this->get('typeMain');

        $result = $this->addressmodel->getTypeOfSubDataByTypeOfMain($typeMain);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Address could not be found'), 404);
        }

    }
    public function addressImgUplaod_post()
    {
        $data                = $this->post();
        $addressID           = $data['kp_AddressID'];

        //print_r($data);

        //echo "AddressID: ".$addressID."<br/>";

        if(!empty($_FILES['file']['name']))
        {
            $msg            = $this->addressmodel->doAddressImgCustomUpload($addressID);

            if($msg)
            {
                $this->response($msg, 200); // 200 being the HTTP response code
            }
            else
            {
                $this->response($msg, 404);
            }

        }



    }
    public function addressBlindData_get()
    {
        if(!$this->get('orderID'))
        {
            $this->response(NULL, 400);
        }

        $orderID  = $this->get('orderID');

        $result = $this->addressmodel->blindShipDataWithoutDataTables($orderID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'Address could not be found'), 404);
        }

    }
    public function addressRecipientData_get()
    {
        if(!$this->get('orderID'))
        {
            $this->response(NULL, 400);
        }

        $orderID  = $this->get('orderID');

        $result = $this->addressmodel->recipientShipDataWithoutDataTables($orderID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Address could not be found'), 404);
        }

    }
	function user_get()
    {
        if(!$this->get('id'))
        {
        	$this->response(NULL, 400);
        }

        // $user = $this->some_model->getSomething( $this->get('id') );
    	$users = array(
			1 => array('id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com', 'fact' => 'Loves swimming'),
			2 => array('id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com', 'fact' => 'Has a huge face'),
			3 => array('id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com', 'fact' => 'Is a Scott!', array('hobbies' => array('fartings', 'bikes'))),
		);

    	$user = @$users[$this->get('id')];

        if($user)
        {
            $this->response($user, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'User could not be found'), 404);
        }
    }

    function user_post()
    {
        //$this->some_model->updateUser( $this->get('id') );
        $message = array('id' => $this->get('id'), 'name' => $this->post('name'), 'email' => $this->post('email'), 'message' => 'ADDED!');
        echo "hi";

        //$this->response($message, 200); // 200 being the HTTP response code
    }

    function user_delete()
    {
    	//$this->some_model->deletesomething( $this->get('id') );
        $message = array('id' => $this->get('id'), 'message' => 'DELETED!');

        $this->response($message, 200); // 200 being the HTTP response code
    }

    function users_get()
    {
        //$users = $this->some_model->getSomething( $this->get('limit') );
        $users = array(
			array('id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com'),
			array('id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com'),
			3 => array('id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com', 'fact' => array('hobbies' => array('fartings', 'bikes'))),
		);

        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }


	public function send_post()
	{
		var_dump($this->request->body);
	}


	public function send_put()
	{
		var_dump($this->put('foo'));
	}
}
