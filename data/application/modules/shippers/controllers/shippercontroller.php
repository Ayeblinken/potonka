<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of shippercontroller
 *
 * @author sraparla
 */
class shipperController extends MX_Controller
{
     //put your code here
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        $this->load->model('shippermodel');

    }

    public function getShipperInfo($shipperID=null)
    {
        $resultArray = $this->shippermodel->getShipperCompanyInfo();
        // default select element
        $defaultSelectElement = array("kp_ShipperID" => "", "t_Company" => "--Select Shipper--");

        array_unshift($resultArray, $defaultSelectElement);
        //echo $shipperID;
        foreach ($resultArray as $rowj)
        {
            $id   = $rowj['kp_ShipperID'];
            $data = $rowj['t_Company'];

            //check for ShipperID
            // if found highlight/Select the shipper Company
            if($shipperID == $id)
            {
                //echo $shipperID;
                echo '<option value="'.$id.'"selected>'.$data.'</option>';

            }
            else
            {
                 echo '<option value="'.$id.'">'.$data.'</option>';
            }
        }
    }

}

?>
