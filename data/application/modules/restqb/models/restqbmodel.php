<?php

class restqbModel extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll($table)
    {
        $query = $this->db->get('quickbooks_sql2.'.$table);
        //print_r($query);
        return $query->result_array();

    }

    public function getRow($table,$primaryKeyName,$id)
    {
        $query = $this
                  ->db
                  ->where($primaryKeyName, $id) // all we need to do is build an array of table and primary keys
                  ->get('quickbooks_sql2.'.$table);

        return $query->row_array();

    }
    public function getDynamicRow($table,$whereCondition)
    {
        $query = $this->db->get_where('quickbooks_sql2.'.$table, $whereCondition);

        return $query->result_array();

    }
    public function getDynamicWhereCondition($table,$whereCondition)
    {
        $where = "nb_Inactive is null or nb_Inactive = 0";

        $this->db
            ->from('quickbooks_sql2.'.$table)
            ->where($whereCondition);

        $query = $this->db->get();

        return $query->result_array();
    }
    public function getPrimaryKeyFieldName($tableName)
    {
        $query = $this->db->query("SELECT `COLUMN_NAME`
                                    FROM `information_schema`.`COLUMNS`
                                    WHERE (`TABLE_SCHEMA` = 'quickbooks_sql2')
                                    AND (`TABLE_NAME` = '".$tableName."')
                                    AND (`COLUMN_KEY` = 'PRI')");

        return $query->row_array();
    }
    public function insertTblData($data,$table)
    {
        $this->db->insert('quickbooks_sql2.'.$table, $data);
        return $this->db->insert_id();
    }

    public function updateTblData($tableName,$data,$keyName,$id)
    {
        $result = $this->db->update('quickbooks_sql2.'.$tableName, $data, array($keyName=>  $id));

        if(!$result)
        {
            return $this->db->_error_message();
        }
        else
        {
            return $this->db->affected_rows();
        }
    }
    public function deleteTblData($tableName,$keyName,$id)
    {
        $result = $this->db->delete('quickbooks_sql2.'.$tableName, array($keyName => $id));

        return $result;
    }
}

?>
