<?php

class StatusModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function updateStatusSort($statusData) {
      $this->db->update('Statuses', $statusData, array('kp_StatusID' => $statusData['kp_StatusID']));
    }
    public function newStatusName()
    {
        $where = "nb_NotChoosableManually = 1 and (nb_Inactive = '0' or isnull(nb_Inactive))";

        $this->db
                ->select('t_StatusName')
                 ->from('Statuses')
                 ->where($where)
                 ->order_by('n_SortOrder asc');

        $query = $this->db->get();

        return $query->result_array();

    }

    public function newStatusNamePressProof()
    {
        $where = "t_Department = 'Press Proof' and (nb_Inactive = '0' or isnull(nb_Inactive))";

        $this->db
                ->select('t_StatusName')
                 ->from('Statuses')
                 ->where($where)
                 ->order_by('n_SortOrder asc');

        $query = $this->db->get();

        return $query->result_array();

    }

    public function getStatusIDStatusName()
    {
        $where = "nb_NotChoosableManually = 1 and (nb_Inactive = '0' or isnull(nb_Inactive))";

        $this->db->select('kp_StatusID,t_StatusName')
                ->from('Statuses')
                ->where($where);

        $query = $this->db->get();

        return $query->result_array();
    }

}

?>
