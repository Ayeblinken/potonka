<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OrderItemInspectEachController extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        $this->load->model('orderiteminspecteachcontrollermodel');

    }
    public function populateOrderItemInspectEachTable($orderID)
    {
        // get all the orderItem for the given orderID;
        $orderItemCompArry    = Modules::run('orderItems/orderitemcontroller/getOrderItemsFromOrderID',$orderID);
        $inspecEachArry       = array();
        $y=0;
        if(!empty($orderItemCompArry))
        {
            //echo sizeof($orderItemCompArry)."<br/>";
            //print_r($orderItemCompArry);
            for($i=0; $i<sizeof($orderItemCompArry); $i++)
            {
                //echo $orderItemCompArry[$i]['kp_OrderItemID']."<br/>";
                $qty =$orderItemCompArry[$i]['n_Quantity'];
                if(!empty($qty) && !is_null($qty))
                {
                    for($x=1;$x<=$qty;$x++)
                    {
                        $inspecEachArry[$y][$x]['kf_OrderID']     = $orderItemCompArry[$i]['kf_OrderID'];
                        $inspecEachArry[$y][$x]['kf_OrderItemID'] = $orderItemCompArry[$i]['kp_OrderItemID'];
                        $inspecEachArry[$y][$x]['t_ItemNum']      = $orderItemCompArry[$i]['kf_OrderID']."-".$orderItemCompArry[$i]['n_DashNum'].'.'.$x;
                        $inspecEachArry[$y][$x]['n_Number']       = $x;

                        //insert here after each loop
                        //$this->orderitemcomponentmodel->orderiteminspectioneachcontrollermodel($inspecEachArry);
                    }
                    //echo $qty."<br/>";

                }
                $y++;

            }
        }
        foreach($inspecEachArry as $key=>$value) {
            if(is_array($value))
            {
                //print_r($value);
                foreach($value as $k=>$v)
                {
                    //
                    //print_r($v);
                    $this->orderiteminspecteachcontrollermodel->insertOrderItemInspectEachData($v);
                    // insert here


                }
            }
        }
        //$result =   $this->flatten($inspecEachArry);

        //var_dump($result);
//        //var_dump($inspecEachArry);
//        echo sizeof($inspecEachArry);
//        for($f=0;$f<=$inspecEachArry;$f++)
//        {
//            if(is_array($inspecEachArry[$f]))
//            {
//                for($g=0;$g<=$inspecEachArry[$f];$g++)
//                {
//                    var_dump($inspecEachArry);
//
//                }
//
//            }
//
//
//        }

    }
    public function flatten($array, $prefix = '')
    {
        $result = array();
        foreach($array as $key=>$value) {
            if(is_array($value))
            {
                var_dump($value);
                $result = $result + $this->flatten($value, $prefix.$key.'.');
            }
            else
            {
                $result[$prefix . $key] = $value;
            }
        }
        return $result;
    }
    public function array_flatten($array)
    {
        if (!is_array($array))
        {
            return false;
        }
        $result = array();
        foreach ($array as $key => $value)
        {
          if (is_array($value))
          {
            $result = array_merge($result, $this->array_flatten($value));
          }
          else
          {
              $result[$key] = $value;
          }
        }
        return $result;
    }
    //put your code here
}

?>
