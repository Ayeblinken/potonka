<?php
class OtherChargeModel extends CI_Model 
{
    //put your code here
    public function __construct() 
    {
        parent::__construct();
    }
    
    public function otherChargeTableData($orderID)
    {
         $this->db
                 ->select('kp_OtherChargesID as ID,
                     ifnull(t_Category,\'\') as Category,
                     ifnull(t_Description,\'\') as Description ,
                     ifnull(n_Quantity,\'\') as Quantity,
                     concat(\'$\',cast(
                                    ifnull(n_Price,\'\')
                                as CHAR)
                            
                           ) as Price,
                     concat(\'$\',ifnull(Round(n_Quantity * n_Price,2),0)) as Total',false)
                 ->from('OtherCharges')
                 ->where('kf_OrderID',$orderID);
         $query = $this->db->get();
         return $query->result();
    }
    
    public function modalOtherChargeData($addressID)
    {
        $this->db
                 ->select('kp_OtherChargesID as ID,
                     ifnull(t_Category,\'\') as Category,
                     ifnull(t_Description,\'\') as Description ,
                     ifnull(n_Quantity,\'\') as Quantity,
                     concat(\'$\',cast(
                                    ifnull(n_Price,\'\')
                                as CHAR)
                            
                           ) as Price,
                     concat(\'$\',ifnull(Round(n_Quantity * n_Price,2),0)) as Total',false)
                 ->from('OtherCharges')
                 ->where('kp_OtherChargesID',$addressID);
        $query = $this->db->get();
         
        return  $query->row();
    }
    
    public function updateAction()
    {
        $modalPriceRefined = str_replace("$", '',$this->input->post('modalPrice',true));
        
        $data = array(
             't_Category'=> $this->input->post('modalCategory',true),
             't_Description'=> $this->input->post('modalDescription',true),
             'n_Quantity'=> $this->input->post('modalQty',true),
             'n_Price'=> $modalPriceRefined
        );
        
        $this->db->update('OtherCharges', $data, array('kp_OtherChargesID'=>  $this->input->post('modalOtherChargeID',true)));
        
    }
    public function createAction()
    {
        $modalPriceRefined = str_replace("$", '',$this->input->post('modalPrice',true));
        
        $data = array(
             't_Category'=> $this->input->post('modalCategory',true),
             't_Description'=> $this->input->post('modalDescription',true),
             'n_Quantity'=> $this->input->post('modalQty',true),
             'kf_OrderID'=> $this->input->post('modalOrderIDHidden',true), 
             'n_Price'=> $modalPriceRefined
        );
        
        $this->db->insert('OtherCharges', $data);
        
        return $this->db->insert_id();
        
        
    }
    
    public function deleteModalAction($otherChargeID)
    {
        /*
        * Any non-digit character will be excluded after passing $id
        * from intval function. This is done for security reason.
        */
        $kp_OtherChargesID = intval( $otherChargeID );    

        // below delete operation generates this query DELETE FROM users WHERE id = $id
        $this->db->delete( 'OtherCharges', array( 'kp_OtherChargesID' => $kp_OtherChargesID ) );
        
    }
    
    
    
    
}
?>
