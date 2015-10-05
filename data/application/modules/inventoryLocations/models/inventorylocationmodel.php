<?php

class InventoryLocationModel extends CI_Model
{
    public function getLocationItemTblData($inventoryLocationID) {
      $query = $this->db->query("SELECT InventoryLocationItems.kp_InventoryLocationItemID, InventoryLocationItems.kf_InventoryLocationID, InventoryLocations.t_Location,
                                      	InventoryLocationItems.kf_InventoryItemID, InventoryItems.t_description, InventoryLocationItems.n_QntyOnHand
                                  FROM InventoryLocationItems
                                  	 LEFT OUTER JOIN InventoryLocations ON InventoryLocationItems.kf_InventoryLocationID = InventoryLocations.kp_InventoryLocationID
                                  	 LEFT OUTER JOIN InventoryItems ON InventoryLocationItems.kf_InventoryItemID = InventoryItems.kp_InventoryItemID
                                  WHERE kf_InventoryLocationID = \"$inventoryLocationID\"");

      return $query->result_array();
    }

    public function getInvtLocationDataByLocationName($locationName)
    {
         $this->db->select('InventoryLocations.t_Location,InventoryLocations.nb_Inactive')
                  ->from('InventoryLocations')
                  ->where('InventoryLocations.t_Location',$locationName);

        $query  =   $this->db->get();

        $result =   $query->result_array();

        return $result;

    }

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
    public function getInventoryLocationIDLocation()
    {
        $where = 'InventoryLocations.nb_Inactive="0" OR ISNULL(InventoryLocations.nb_Inactive)';

        $this->db->select('InventoryLocations.kp_InventoryLocationID,
                            InventoryLocations.t_Location')
                ->from('InventoryLocations')
                ->where('InventoryLocations.nb_Inactive',0)
                ->or_where('InventoryLocations.nb_Inactive', null)
                ->order_by('InventoryLocations.t_Location', 'Asc');

        $query = $this->db->get();

        $result=$query->result_array();

        return $result;
    }
    public function getinvtLocationZoneTblData()
    {
        $this->db->select('InventoryLocations.kp_InventoryLocationID,
                           InventoryZones.t_ZoneName,
                           InventoryLocations.t_Location,
                           InventoryLocations.t_Description,
                           InventoryLocations.nb_Inactive')
                ->from('InventoryLocations')
                ->join('InventoryZones','InventoryLocations.kf_InventoryZoneID = InventoryZones.kp_InventoryZoneID','inner');

        $query = $this->db->get();

        $result=$query->result_array();

        return $result;

    }
    public function updateInvtLocationTbl($data,$inventoryLocationID)
    {
        $result = $this->db->update('InventoryLocations', $data, array('kp_InventoryLocationID'=>  $inventoryLocationID));

        if(!$result)
        {
            return $this->db->_error_message();
        }
        else
        {
            return $this->db->affected_rows();

        }

    }

}

?>
