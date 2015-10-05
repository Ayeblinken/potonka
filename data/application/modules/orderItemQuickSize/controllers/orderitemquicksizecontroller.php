<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class OrderItemQuickSizeController extends MX_Controller
{
    //put your code here
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        $this->load->model('orderItemquicksizemodel');

    }

    public function getOrderItemQuickSizeInfo()
    {
        $multi = "   <option value=\"\"></option>";
        $displayCategoryGroup        = $this->getOrderItemQuickSizeCategory();

        //print_r($displayCategoryGroup);
        //echo "<br/>";

        $oiqsInfo = $this->orderItemquicksizemodel->getOrderItemQuickSizeData();
        //print_r($oiqsInfo)."<br/><br/><br/>";
        //echo sizeof($oiqsInfo)."<br/><br/><br/>";
        foreach($displayCategoryGroup as $category)
        {
             $multi  .= "<optgroup label=\"$category\">";
             for($i=0; $i<sizeof($oiqsInfo); $i++)
             {
                 $categoryFound = in_array($category,$oiqsInfo[$i]);
                 if($categoryFound)
                 {
                      $multi  .= "<option value=".$oiqsInfo[$i]['t_Value'].">".$oiqsInfo[$i]['t_Display']."</option>";
                 }
             }
             $multi .= "</optgroup>";
        }
        echo $multi;
    }
    public function getOrderItemQuickSizeCategory()
    {
        $displayCategory= array();

        $oiqsInfo = $this->orderItemquicksizemodel->getOrderItemQuickSizeData();

        for($i=0; $i<sizeof($oiqsInfo); $i++)
        {
            $displayCategory[$i] = $oiqsInfo[$i]['t_Category'];

        }
        $category = array_unique($displayCategory);
        $comma_separated = implode(",", $category);
        $category = explode(",", $comma_separated);

        return $category;

    }
}

?>
