<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of shipperservicemodel
 *
 * @author sraparla
 */
class shipperServiceModel extends CI_Model 
{
    //put your code here
    public function __construct() 
    {
        parent::__construct();
    }
    public function getShipperServiceInfo($shipperID)
    {
        $query = $this->db->query("Select kp_ShipperServiceID,t_ShipperService 
                        from basetables2.ShipperService 
                        WHERE kf_ShipperID = '$shipperID' and (nb_Inactive is null || nb_Inactive = 0)");
        return $query->result_array();
        
    }
}

?>
