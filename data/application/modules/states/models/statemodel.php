<?php
class StateModel extends CI_Model
{
    //put your code here
    public function __construct()
    {
        parent::__construct();
    }
    public function getStatesCountryChange($countryAbb)
    {
        $query = $this->db->query("Select t_StateAbbreviation,t_StateName, n_Lat, n_Lng From basetables2.States
                                            WHERE t_CountryAbb = '$countryAbb' ORDER BY t_StateAbbreviation ASC");
        return $query->result_array();

    }

}
?>
