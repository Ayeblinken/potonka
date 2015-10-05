<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class EquipmentSubModeController extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        if(!checkPermissionsByModule($privSet, 'equipment')) {
          $this->response(array('error' => 'You\'re privilege set doesn\'t allow access to this content'), 400);
        }
        $this->load->model('equipmentsubmodemodel');
    }
    public function getEquipmentSubModeData($equipmentID,$equipmentModeID,$equipmentSubModeID)
    {
        //echo $equipmentSubModeID;
        $subMode     = array();

        $row = $this->equipmentsubmodemodel->getEquipmentSubModeByEidEmid($equipmentID,$equipmentModeID);
        //$defaultSelectElement = '<option value="">'."Please Select".'</option>';

        //array_unshift($row, $defaultSelectElement);
        for($i=0; $i<sizeof($row); $i++)
        {
            if($row[$i]['kp_EquipmentSubModeID'] == $equipmentSubModeID)
            {
                $subMode[$i] = '<option value="'.$row[$i]['kp_EquipmentSubModeID']. '"selected>'.$row[$i]['t_Name'].'</option>';

            }
            else
            {
                $subMode[$i] = '<option value="'.$row[$i]['kp_EquipmentSubModeID'].'">'.$row[$i]['t_Name'].'</option>';

            }

            //echo  $subMode[$i];
            //$subMode[$i]     = $row[$i]['kp_EquipmentSubModeID'].",".$row[$i]['t_Name'];
            //$subModeName[$i] = $row[$i]['t_Name'];

        }
        //$result           = array_combine($subModeName,$subMode);
        //print_r($subMode);
        //return $result;
        return $subMode;
        //print_r($subMode) ;
    }
    public function getEquipmentSubModeFromEquipmentSubModeID($equipmentSubModeID)
    {
        $row = $this->equipmentsubmodemodel->getEquipmentSubModeByID($equipmentSubModeID);

        return $row;

    }

}

?>
