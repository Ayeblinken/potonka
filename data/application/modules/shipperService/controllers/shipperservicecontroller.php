<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of shipperservicecontroller
 *
 * @author sraparla
 */
class shipperServiceController extends MX_Controller
{
    //put your code here
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        $this->load->model('shipperservicemodel');

    }
    public function getShipperServiceID($shipperID,$action=null)
    {
        $resultArray = $this->shipperservicemodel->getShipperServiceInfo($shipperID);


        //$defaultSelectElement = array("kp_ShipperServiceID" => "", "t_ShipperService" => "--Please Select--");

        //array_unshift($resultArray, $defaultSelectElement);
        if($action !== null)
        {
            // default select element
            $defaultSelectElement = array("kp_ShipperServiceID" => "", "t_ShipperService" => "--Please Select--");

            // Putting the default element in the begining of the dropdown
            array_unshift($resultArray, $defaultSelectElement);

        }

        foreach ($resultArray as $rowj)
        {
            $id      = $rowj['kp_ShipperServiceID'];
            $data    = $rowj['t_ShipperService'];

            echo '<option value="'.$id.'">'.$data.'</option>';
        }


    }
}

?>
