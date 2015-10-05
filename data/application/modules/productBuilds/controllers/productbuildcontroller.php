<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ProductBuildController extends MX_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('productbuildmodel');
        
    }
    public function getProductBuildItemData($productBuildID)
    {
        $row = $this->productbuildmodel->getProductBuildItemByID($productBuildID);
        $something = "Product Bulid:".$row['t_Category']."-".$row['t_Name'];
        
        return $row; 
    }
    public function getAllProductBuildData()
    {
        $row = $this->productbuildmodel->getAllProductBuild();
        
        return $row;
    }
    public function getProductBuildCategoriesData()
    {
        //$row = Modules::run('orderItems/orderitemcontroller/getOrderItemFieldsFromOrderItemID',$orderItemID);
        //print_r($row);
        echo json_encode($this->productbuildmodel->getProductBuildCategories());
    }
    public function getProductBuildNameFromCategoryData($categoryName)
    {
        echo json_encode($this->productbuildmodel->getProductBuildNameFromCategory($categoryName));
    }        
}

?>
