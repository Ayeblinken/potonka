<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class InventoryItemController extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        $this->load->model('inventoryitemmodel');

    }
    public function getInventoryItemData($inventoryItemID)
    {
        $inventoryItemDescription ="";
        $row = $this->inventoryitemmodel->getInventoryItemByID($inventoryItemID);

        $inventoryItemDescription = '<option value="'.$row['t_description'].'">'.$row['t_description'].'</option>';
        echo $inventoryItemDescription;
        //return $inventoryItemDescription;
    }
    public function getInventoryOnHandMinMaxData($inventoryItemID)
    {
        $row = $this->inventoryitemmodel->getInventoryOnHandMinMax($inventoryItemID);

        echo json_encode($row);

        //print_r($row);

    }
    public function getInventoryItemLocationInfoFromDescription()
    {
        $description = $this->input->post('x');

        echo json_encode($this->inventoryitemmodel->getInventoryItemLocationInfo($description));

    }
    public function getInvItemTopList($inventoryItemID)
    {
        //print_r($this->inventoryitemmodel->getInvItemDetialsInfo($inventoryItemID));
        echo json_encode($this->inventoryitemmodel->getInvItemDetialsTopList($inventoryItemID));
        //$data['result'] = $this->inventoryitemmodel->getInvItemDetialsInfo($inventoryItemID);

        //$this->load->view('invItems_list',$data);
    }
    public function getInvItemBottomList($inventoryItemID)
    {
        echo json_encode($this->inventoryitemmodel->getInvItemDetialsBottomList($inventoryItemID));
    }
    public function setPriceByFtPriceByFtSizeEachPriceValues()
    {
        $data = array();

        $result = $this->inventoryitemmodel->getAllInventoryItems();

        //$inventoryItemID = "10011";

        //$result[0] = $this->inventoryitemmodel->getInventoryItemByID($inventoryItemID);

        //echo sizeof($result);

        //var_dump($result);


        for($i=0;$i<sizeof($result);$i++)
        {
            if ($result[$i]['nb_ComesInCase'] ==='1')
            {
                //echo "inside here comesincase<br/>";
                if($result[$i]['n_AttribLotQty'] >0)
                {

                    //echo $result[$i]['n_AttribLotQty'];
                    $result[$i]['n_EachPrice']   = round(($result[$i]['n_CasePrice'] / $result[$i]['n_AttribLotQty']),3);
                }

            }
            if($result[$i]['t_AttribSizeType'] === "Roll")
            {
                if ($result[$i]['t_AttribHeightUOM'] === '"' && $result[$i]['t_AttribWidthUOM'] === "'")
                {
                    if(($result[$i]['nb_ComesInCase'] ==='1') && ($result[$i]['n_AttribHeight'] > 12))
                    {
                        $result[$i]['n_PriceByFt']       = round(($result[$i]['n_EachPrice'] / (($result[$i]['n_AttribHeight'] * $result[$i]['n_AttribWidth'])/144)),3);
                        $result[$i]['t_PriceByFtSize']   = "SqFt";

                    }
                    else if(($result[$i]['nb_ComesInCase'] ==='1') && ($result[$i]['n_AttribHeight'] <= 12))
                    {
                        $result[$i]['n_PriceByFt']      = round(($result[$i]['n_EachPrice'] / ($result[$i]['n_AttribWidth'])),3);

                        $result[$i]['t_PriceByFtSize']  = "LnFt";

                    }
                    else if (($result[$i]['nb_ComesInCase'] !== '1') && ($result[$i]['n_AttribHeight']) > 12)
                    {
                        $result[$i]['n_PriceByFt']      = round(($result[$i]['n_CasePrice'] / (($result[$i]['n_AttribHeight']/12) * $result[$i]['n_AttribWidth'])),3);
                        $result[$i]['t_PriceByFtSize']  = "SqFt";



                    }
                    else if (($result[$i]['nb_ComesInCase'] !== '1') && ($result[$i]['n_AttribHeight']) <= 12)
                    {
                        if($result[$i]['n_AttribWidth'] >0)
                        {
                            $result[$i]['n_PriceByFt']      = round(($result[$i]['n_CasePrice'] / ($result[$i]['n_AttribWidth'])),3);


                            $result[$i]['t_PriceByFtSize']  = "LnFt";
                        }

                    }
                }
            }
            if ($result[$i]['t_AttribSizeType'] === "Sheet")
            {
                //echo "inside here Sheet<br/>";
                if ($result[$i]['t_AttribHeightUOM'] === '"' && $result[$i]['t_AttribWidthUOM'] === '"' && $result[$i]['nb_ComesInCase'] ==='1')
                {

                    $result[$i]['n_PriceByFt']       = round(($result[$i]['n_EachPrice'] / (($result[$i]['n_AttribHeight'] * $result[$i]['n_AttribWidth'])/144)),3);
                    $result[$i]['t_PriceByFtSize']   = "SqFt";
                }
                else if ($result[$i]['t_AttribHeightUOM'] === '"' && $result[$i]['t_AttribWidthUOM'] === '"' && $result[$i]['nb_ComesInCase'] ==='')
                {
                    //echo "inside here Sheet<br/>";
                    $result[$i]['n_EachPrice']       = "";
                    $result[$i]['n_PriceByFt']       = round(($result[$i]['n_CasePrice'] / (($result[$i]['n_AttribHeight'] * $result[$i]['n_AttribWidth'])/144)),3);
                    $result[$i]['t_PriceByFtSize']   = "SqFt";
                }
            }

            $data[$i]['kp_InventoryItemID']      = $result[$i]['kp_InventoryItemID'];
            $data[$i]['n_PriceByFt']             = $result[$i]['n_PriceByFt'];
            $data[$i]['t_PriceByFtSize']         = $result[$i]['t_PriceByFtSize'];
            $data[$i]['n_EachPrice']             = $result[$i]['n_EachPrice'];

        }
        //var_dump($data);

        return $data;
    }
    public function updatePriceByFtPriceByFtSizeEachPriceValues()
    {
        $data = $this->setPriceByFtPriceByFtSizeEachPriceValues();
        $result = array();
        //var_dump($data);
        for($i=0;$i<sizeof($data);$i++)
        {
            $result[$i]['result'] = $this->inventoryitemmodel->updateInvItemTbl($data[$i],$data[$i]['kp_InventoryItemID']);


        }
        var_dump($result);
    }
}

?>
