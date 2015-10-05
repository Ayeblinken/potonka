<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class StatusController extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        $this->load->model('statusmodel');

    }
    public function getNewStatusName()
    {

        //$resultArray = $this->statusmodel->newStatusName();
        //print_r($resultArray);
        //var_dump($resultArray);

        $resultArray = $this->getNewStatusNameNotInJson();

        $defaultSelectElement = array("t_StatusName" => "", "t_StatusName" => "Please Select");

        array_unshift($resultArray, $defaultSelectElement);
        //print_r($resultArray);
        echo json_encode($resultArray);

//        foreach ($resultArray as $rowj)
//        {
//
//            $id   = $rowj['t_StatusName'];
//            $id   = mysql_real_escape_string($id);
//
//            $data = $rowj['t_StatusName'];
//            $data = mysql_real_escape_string($data);
//             echo '<option value="'.$id.'">';
//            echo '<option value="'.$id.'">'.$data.'</option>';
//
//        }

    }
    public function getNewStatusNameNotInJson()
    {
         $resultArray = $this->statusmodel->newStatusName();

         return $resultArray;

    }
    public function getStatusNameStatusID()
    {
        $resultArray = $this->statusmodel->getStatusIDStatusName();

        return $resultArray;
    }

}

?>
