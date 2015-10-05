<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class OrderRedoItemController extends MX_Controller
{
    //put your code here
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        $this->load->model('orderredoitemmodel');
    }
    public function insertOrderRedoItemDataArry($orderRedoItemDataArry=null)
    {
        //echo "<br/>inside of orderredoitem<br/>";
        $lastInsertedOrderRedoItemID = $this->orderredoitemmodel->insertOrderRedoItemTable($orderRedoItemDataArry);

        return $lastInsertedOrderRedoItemID;
    }
    public function getOrderRedoItemsFromOrderRedoID($orderRedoID=null)
    {
        $result = $this->orderredoitemmodel->getOrderRedoItemsByOrderRedoID($orderRedoID);
        return $result;
    }
    public function getOrderItemsFromOrderRedoItemArry($orderRedoID=null)
    {
        $orderRedoItemArry = $this->getOrderRedoItemsFromOrderRedoID($orderRedoID);

        $orderItemArry = array();

        for($i=0;$i<sizeof($orderRedoItemArry);$i++)
        {
            $orderItemArry[$i] = $orderRedoItemArry[$i]['kf_OrderItemID'];
        }

        return $orderItemArry;

    }
    public function getDashNumArry($orderRedoID=null)
    {
        $orderItemIDRedoArry = $this->getOrderItemsFromOrderRedoItemArry($orderRedoID);
        $dashNumArry = array();
        for($i=0;$i<sizeof($orderItemIDRedoArry);$i++)
        {
            $orderItemArry = Modules::run('orderItems/orderitemcontroller/getOrderItemFieldsFromOrderItemID',$orderItemIDRedoArry[$i]);
            $dashNumArry[$i]=$orderItemArry[0]['n_DashNum'];
        }
        return $dashNumArry;

    }
    public function deleteOrderRedoItems($orderRedoID)
    {
        $result = $this->orderredoitemmodel->deleteOrderRedoItemTable($orderRedoID);

    }
}

?>
