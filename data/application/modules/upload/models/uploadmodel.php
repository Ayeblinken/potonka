<?php

class UploadModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        //$this->loadImagePath   = 'https://'.$_SERVER['SERVER_NAME'].'/images/Inventory/Zones/';

    }
    public function getUploadDataFromTimeStampCreateDate($date)
    {
        $query = $this->db->query("SELECT Upload.kp_Upload, TIME_FORMAT(Upload.ts_UploadComplete, '%h:%i %p') AS ts_UploadComplete,
                                          Upload.t_Company, Upload.t_Name, Upload.t_Phone, Upload.t_Email, Upload.t_IndyContact, Upload.nb_PrepressProcessed
                                     FROM basetables2.Upload
                                     WHERE nb_UploadComplete = 1  and DATE(Upload.ts_CreateDate)='$date'");
        return $query->result_array();
    }
    public function getUploadPendingDataFromTimeStampCreateDate($date)
    {
        $query = $this->db->query("SELECT Upload.kp_Upload, TIME_FORMAT(Upload.ts_UploadComplete, '%h:%i %p') AS ts_UploadComplete,
                                          Upload.t_Company, Upload.t_Name, Upload.t_Phone, Upload.t_Email, Upload.t_IndyContact, DATE_FORMAT(Upload.ts_CreateDate, '%c/%e/%Y') AS ts_CreateDate
                                     FROM basetables2.Upload
                                     WHERE nb_UploadComplete is null  and DATE(Upload.ts_CreateDate)='$date'");
        return $query->result_array();
    }
    public function getUploadDataFromUploadID($uploadID)
    {
      $query = $this->db->query("SELECT Upload.kp_Upload, Upload.ts_UploadComplete,
                                        Upload.t_Company, Upload.t_Name, Upload.t_Phone, Upload.t_Email, Upload.t_IndyContact, Upload.nb_PrepressProcessed, Upload.ts_PrepressProcessed,
                                        Upload.t_Browser, Upload.t_Address, Upload.t_City, Upload.t_State, Upload.t_Zip, Upload.t_Note, Upload.t_SalesPerson, Upload.t_PrepressFileLocation,
                                        Upload.t_PrepressName, Upload.ts_CreateDate
                                     FROM basetables2.Upload
                                     WHERE Upload.kp_Upload = ".$uploadID."");

        return $query->row_array();

    }
    public function updateUploadTable($data,$uploadID)
    {
        $result = $this->db->update('Upload', $data, array('kp_Upload'=>  $uploadID));
        if(!$result)
        {
            return $this->db->error_message();
        }
        else
        {
            return $this->db->affected_rows();

        }

    }
    public function insertUploadTblData($data)
    {
        $this->db->set($data,false);

        $this->db->insert('Upload');

        return $this->db->insert_id();
    }


}

?>
