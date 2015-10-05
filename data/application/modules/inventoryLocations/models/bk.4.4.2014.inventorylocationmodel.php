<?php

class InventoryLocationModel extends CI_Model  
{
    public function getall()
    {
        $selectArray = array('kp_InventoryLocationID', 'CONCAT(t_Zone, t_Rack, t_shelf) as location');
        
        $this->db->select($selectArray)->from('InventoryLocations')->order_by('location', 'Asc');
        
        $query = $this->db->get();
        
        $result=$query->result();
        
        return $result;
        
    }
    
    public function getLocationData($location)
    {
        $selectArray = array('kp_InventoryLocationID', 't_Location');
        
        $this->db->select($selectArray)->from('InventoryLocations')->like('t_Location', $location, 'both')->order_by('t_Location', 'Asc');
        
        $query = $this->db->get();
        
        $result=$query->result_array();
        
        return $result;
        
    } 
   
}

?>
