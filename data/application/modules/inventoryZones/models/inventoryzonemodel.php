<?php

class InventoryZoneModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        //Server image path
        $this->loadImagePath   = 'https://'.$_SERVER['SERVER_NAME'].'/images/Inventory/Zones/';

        $this->imageUploadPath = realpath(APPPATH . '../../../images');

        $this->load->library('image_lib');
    }
    public function getInventoryZoneData()
    {
        $query  =   $this->db->get('InventoryZones');


        $result =   $query->result_array();

        return $result;
    }
    public function getInventoryZoneTblData()
    {
        //concat("'.$this->loadImagePath.'",kp_InventoryZoneID,"\/",t_ImageMap) as t_ImageMap
        //t_ImageMap
        $this->db
                ->select('kp_InventoryZoneID,t_ZoneName,t_ZoneAbbreviation,t_Description,concat("'.'../images/Inventory/Zones/'.'",kp_InventoryZoneID,"\/",t_ImageMap) as t_ImageMap,n_SortOrder,nb_Inactive',false)
                ->from('basetables2.InventoryZones');
        $query = $this->db->get();

        $result =   $query->result_array();

        return $result;
    }
    public function updateInvZoneData($data,$inventoryZoneID)
    {
        $result = $this->db->update('InventoryZones', $data, array('kp_InventoryZoneID'=>  $inventoryZoneID));

        if(!$result)
        {
            return $this->db->_error_message();
        }
        else
        {
            return $this->db->affected_rows();

        }

    }
    public function insertInvZoneData($data)
    {
        $this->db->set($data,false);

        $this->db->insert('InventoryZones');

        return $this->db->insert_id();
    }
    public function doInvtZoneCustomUpload($inventoryZoneID)
    {
        $allowed = array('jpeg','jpg','svg','png');

        if(file_exists($this->imageUploadPath.DIRECTORY_SEPARATOR.'.am_i_mounted'))
        {
            if(isset($_FILES['file']) && $_FILES['file']['error'] == 0)
            {
                $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                if(!in_array(strtolower($extension), $allowed))
                {
                     echo '{"status":"error file extension"}';
                     exit;
                }

                // checks and creates Inventory and Zones Folder
                if(!is_dir($this->imageUploadPath.DIRECTORY_SEPARATOR.'Inventory'.DIRECTORY_SEPARATOR.'Zones'))
                {
                    if(!mkdir($this->imageUploadPath.DIRECTORY_SEPARATOR.'Inventory'.DIRECTORY_SEPARATOR.'Zones',0777,TRUE))
                    {
                        die("Failed to create Inventory and Zones Folders");
                    }

                }
                $path = $this->imageUploadPath.DIRECTORY_SEPARATOR.'Inventory'.DIRECTORY_SEPARATOR.'Zones';
                //echo "<br/> path: ".$path."<br/>";
                //CHECK and create zone ID Folder
                if(!is_dir($path.DIRECTORY_SEPARATOR.$inventoryZoneID))
                {
                    if(!mkdir($path.DIRECTORY_SEPARATOR.$inventoryZoneID,0777,TRUE))
                    {
                        die("Failed to create Inventory and Zones Folders");
                    }

                }

                $fullPath = $path.DIRECTORY_SEPARATOR.$inventoryZoneID;

                $tempPath   = $_FILES[ 'file' ][ 'tmp_name' ];

                $fileName   = str_replace(" ", "_", $_FILES['file']['name']);

                $uploadPath = $fullPath.DIRECTORY_SEPARATOR.$fileName;


                if(move_uploaded_file($tempPath, $uploadPath))
                {
                    //update invtZoneData
                    $data['t_ImageMap']  = $fileName;

                    //print_r($data);

                    //echo "<br/>".$fileName."<br/>";
                    $result              = $this->updateInvZoneData($data, $inventoryZoneID);

                    //echo "result ".$result."<br/>";

                    $msg = array( 'answer' => 'File transfer completed and Update Done '.$data['t_ImageMap'] );

                    //echo $msg;
                    return $msg;
                     //$json = json_encode( $answer );
                }
                else
                {
                    $msg = "cant upload the file";
                    return $msg;
                }
            }

        }
        else
        {
            $errorCause             = array();

            $errorCause['msg']      = ".am_i_mounted file not found.";

            $errorCause['location'] = "occured when uploading Inventory Zone Image";

            //$this->angUploadErrorEmail($errorCause);

            $orderItemArry    = Modules::run('rest/customemailcontroller/customAngUploadError',$errorCause);

            $msg = "am i mounted file not found";

            return $msg;
        }
    }

}
