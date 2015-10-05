<?php

class EmailAngModel extends CI_Model
{
     public function __construct()
    {
        parent::__construct();

    }

    public function getEmployeeEmailAddress($emailAddress) {
        //$where = "nb_Inactive = '0' or isnull(nb_Inactive)";
        $where = $emailAddress;

        $this->db
                ->select('*')
                ->from('Employees')
                ->where('t_EmployeeEmail', $where, null, false);

        $query = $this->db->get();

        return $query->row_array();
    }

    public function getValidEmailTrackingTableListData() {
      $query = $this->db->query("SELECT
                                  Orders.kp_OrderID,
                                  Orders.t_JobStatus,
                                  GROUP_CONCAT(DISTINCT OrderShipTracking.t_TrackingID SEPARATOR ',') as DistinctTrackingNums,
                                  Customers.t_CustCompany,
                                  Shippers.t_Company
                                  FROM
                                    Orders
                                  LEFT JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
                                  LEFT JOIN OrderShipTracking on Orders.kp_OrderID = OrderShipTracking.kf_OrderID
                                  LEFT JOIN OrderShip on Orders.kp_OrderID = OrderShip.kf_OrderID
                                  LEFT OUTER JOIN Shippers ON OrderShip.kf_ShipperID = Shippers.kp_ShipperID
                                  WHERE
                                    Orders.nb_JobFinished = 1
                                    AND Orders.nb_EmailSentTrackNumReadyToPickUp is Null
                                    AND Orders.t_TypeOfJobTicket = 'JobWithProductBuilds'
                                    AND Orders.t_JobStatus != 'Cancelled'
                                  GROUP BY Orders.kp_OrderID
                                  ORDER BY Orders.kp_OrderID ASC");

      return $query->result_array();
    }

    public function getEmailTrackingTableListData()
    {
        $query =  $this->db->query("SELECT
                                     Orders.kp_OrderID,
                                     Customers.t_CustCompany,
                                     Orders.t_JobName,
                                     Orders.t_JobStatus,
                                     Orders.t_JobFinishedYN,
                                     Orders.nb_EmailSentTrackNumReadyToPickUp,
                                     Orders.t_TypeOfJobTicket,
                                     DATE_FORMAT(Orders.d_JobDue, '%c-%e-%Y') AS d_JobDue,
                                     Orders.nb_JobFinished,
                                     OrderShip.nb_HideOnWorkOrder
                                    FROM
                                    	Orders
                                    LEFT JOIN Customers ON Orders.kf_CustomerID = Customers.kp_CustomerID
                                    LEFT JOIN OrderShip ON Orders.kp_OrderID = OrderShip.kf_OrderID
                                    WHERE
                                    	Orders.nb_JobFinished = 1
                                    	AND Orders.nb_EmailSentTrackNumReadyToPickUp is Null
                                    	AND Orders.t_TypeOfJobTicket = 'JobWithProductBuilds'
                                      AND Orders.t_JobStatus != 'Cancelled'
                                    GROUP BY
                                    	Orders.kp_OrderID
                                    ORDER BY
                                    	Orders.kp_OrderID ASC");
        return $query->result_array();

    }


}

?>
