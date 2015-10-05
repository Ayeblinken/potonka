<?php

class CodeVersionModel extends CI_Model {

    public function getCodeVersion($id) {
        $query = $this->db->get_where('CodeVersions',array('kp_CodeVersionID'=>$id));
        return $query->row_array();

    }
}

?>
