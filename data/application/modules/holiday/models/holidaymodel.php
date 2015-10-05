<?php

class holidaymodel extends CI_Model 
{
     public function getAllHolidays()
     {
         $query = $this->db->get('Holiday');
         
         return $query->result_array();
         
     }        
    //put your code here
}

?>
