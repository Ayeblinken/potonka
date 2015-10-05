<?php

class ProductBuildModel extends CI_Model
{
  public function getProductInProcessList() {
      $query = $this->db->query("SELECT ProductBuilds.kp_ProductBuildID, ProductBuilds.t_Name, ProductBuilds.t_Category, ProductBuilds.t_Notes, ProductBuilds.t_WebAddress, quickbooks_sql2.itemservice.Status, quickbooks_sql2.error_table.Error_Desc
                                  FROM ProductBuilds
                                  LEFT JOIN quickbooks_sql2.itemservice ON quickbooks_sql2.itemservice.ListID = ProductBuilds.t_QBListID
                                  LEFT JOIN quickbooks_sql2.error_table ON quickbooks_sql2.itemservice.ListID = quickbooks_sql2.error_table.IDKEY
                                  WHERE (quickbooks_sql2.itemservice.Status = 'ADD' OR quickbooks_sql2.itemservice.Status = 'UPDATE' OR quickbooks_sql2.itemservice.Status = 'DELETE')");

      return $query->result_array();
  }

    public function getProductBuildItemsByBuildItem($buildItemID) {
      $query = $this->db->query("SELECT ProductBuilds.kp_ProductBuildID, ProductBuilds.t_Name, ProductBuilds.t_Category, ProductBuilds.t_Notes, ProductBuilds.t_WebAddress FROM ProductBuilds
                                INNER JOIN ProductBuildItems ON ProductBuildItems.kf_ProductBuildID = ProductBuilds.kp_ProductBuildID
                                WHERE ProductBuildItems.kf_BuildItemID = \"$buildItemID\"");

      return $query->result_array();
    }
    public function getProductBuildByName($productBuildName)
    {
        $query = $this->db->query("SELECT ProductBuilds.t_Name FROM ProductBuilds
                                   WHERE ProductBuilds.t_Name =\"$productBuildName\"");

        return $query->result_array();

    }
    public function getProductBuildItemByID($productBuildID)
    {
        $query = $this->db->get_where('ProductBuilds',array('kp_ProductBuildID'=>$productBuildID));

        return $query->row_array();
    }
    public function getAllProductBuild()
    {
        $query = $this->db->get('mytable');

        return $query->result_array();

    }
    public function getProductBuildCategories()
    {
        $this->db->SELECT('DISTINCT(t_Category)',false)
                 ->from('ProductBuilds')
                 ->order_by("t_Category", "asc");

        $query = $this->db->get();

        return $query->result_array();
    }
    public function getProductBuildNameFromCategory($categoryName)
    {
        $this->db->SELECT('DISTINCT(t_Category),kp_ProductBuildID,t_Name',false)
                 ->from('ProductBuilds')
                 ->where('t_Category',$categoryName)
                 ->where('nb_Inactive',null)
                 ->order_by("t_Name", "asc");

        $query = $this->db->get();

        return $query->result_array();

    }

}

?>
