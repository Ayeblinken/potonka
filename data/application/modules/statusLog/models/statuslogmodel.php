<?php

class StatusLogModel extends CI_Model
{
    //put your code here
    public function __construct()
    {
        parent::__construct();
    }
    public function statusLogTableData($orderID)
    {
        $query =$this->db->query("SELECT kp_StatusLogID as ID,ifnull(concat(StatusLog.kf_OrderID,'-',OrderItems.n_DashNum),'') as orderIDDashNum,
                          DATE_FORMAT(ifnull(StatusLog.zCreated,''),'%c-%d-%Y  %l:%i %p') as date,
                          StatusLog.t_ApprovedByName as name,
                          StatusLog.t_StatusOld as oldStatus,
                          StatusLog.t_StatusNew as newStatus,
                          StatusLog.t_Notes as notes,
                          OrderItems.n_DashNum
                          FROM basetables2.StatusLog
                          LEFT JOIN OrderItems
                          on  StatusLog.kf_OrderItemID = OrderItems.kp_OrderItemID
                          WHERE StatusLog.kf_OrderID = ".$orderID."
                          ORDER BY OrderItems.n_DashNum, StatusLog.zCreated ASC");
        return $query->result();
//        //$where =  "nb_Inactive = '0' or isnull(nb_Inactive)";
//        //$where = "StatusLog.kf_OrderID =".$orderID.")";
//
//
//        $this->db
//                ->SELECT('kp_StatusLogID as ID,ifnull(concat(StatusLog.kf_OrderID,'-',OrderItems.n_DashNum)),\'\') as orderIDDashNum,
//                          DATE_FORMAT(ifnull(zCreated,\'\'),\'%c/%d/%Y  %h:%i %p\') as date,
//                          ifnull(t_ApprovedByName,\'\') as name,ifnull(t_StatusOld,\'\') as oldStatus,
//                          ifnull(t_StatusNew,\'\') as newStatus,ifnull(t_Notes,\'\') as notes',false)
//                ->from('StatusLog')
//                ->join('OrderItems','StatusLog.kf_OrderItemID = OrderItems.kp_OrderItemID','left')
//                ->where('StatusLog.kf_OrderID',$orderID);
//                //->where($where);
//                //->where('StatusLog.kf_OrderID',$orderID);
//        $query = $this->db->get();
//        return $query->result();
    }
    public function insertCreatedStatusLog($statusLogArray)
    {
        $message                 = "";

        $lenArr                  = sizeof($statusLogArray);

        if($lenArr > 1)
        {
            $message  = "The status <strong> ".$statusLogArray[0]['t_StatusNew']. " </strong> has been applied to all Lineitems";

        }
        else
        {
             $message = "You have succesfully Changed the status to <strong> ". $statusLogArray[0]['t_StatusNew']." </strong>.";

        }
        for($i = 0; $i<$lenArr; $i++)
        {
            $this->db->insert('StatusLog', $statusLogArray[$i]);

        }

        return $message;
        //return $this->db->insert_id();

    }
    public function insertPartialStatusLog($statusLogArray)
    {
        for($i = 0; $i<sizeof($statusLogArray); $i++)
        {
            $this->db->insert('StatusLog', $statusLogArray[$i]);

        }

        return $this->db->insert_id();

    }
    public function insertStatusLogTblData($data)
    {
        $this->db->insert('basetables2.StatusLog', $data);

        return $this->db->insert_id();

    }
}

?>
