<?php

class QuickBookModel extends CI_Model 
{
    public function getQuickBooksCustomerTableData($qb_CustID)
    {
        $query = $this->db->query("SELECT * FROM quickbooks_sql2.customer  WHERE ListID ='$qb_CustID'");
        
        return $query->row_array();
        
    }
    public function updateQuickBooksCustomerTableData($qb_CustID,$data)
    {
        $this->db->where('ListID', $qb_CustID);
        $this->db->update('quickbooks_sql2.customer', $data); 
        
    }  
    public function quickBookTerm($qb_CustID=null)
    {
        //$qb_CustID = "2560000-1107544760" ;   
        $query = $this->db->query("SELECT qb_in.CustomerRef_FullName,qb_in.TermsRef_FullName,
                                   sum( IF(current_date <= qb_in.DueDate,qb_in.BalanceRemaining,0) )   as Current,
                                   sum(IF(current_date > qb_in.DueDate and current_date <= DATE_ADD(qb_in.DueDate, INTERVAL 30 DAY),
					qb_in.BalanceRemaining ,0))  AS \"1-30\",
                                   sum(IF(current_date > DATE_ADD(qb_in.DueDate, INTERVAL 30 DAY) and current_date <= DATE_ADD(qb_in.DueDate, INTERVAL 60 DAY),
					qb_in.BalanceRemaining ,0)) AS \"31-60\",
                                   sum(IF(current_date > DATE_ADD(qb_in.DueDate, INTERVAL 60 DAY) and current_date <= DATE_ADD(qb_in.DueDate, INTERVAL 90 DAY),
					qb_in.BalanceRemaining ,0)) AS \"61-90\",
   				   sum(IF(current_date > DATE_ADD(qb_in.DueDate, INTERVAL 90 DAY) ,
								qb_in.BalanceRemaining ,0)) AS \"Greater90\",
					TotalUnusedPayment,CreditRemaining, WorkInHouseTotalAmount,
                                   SUM(qb_in.BalanceRemaining) AS TotalBalance,
                                   IFNULL(qb_cus.CreditLimit,0) AS \"Credit Limit\", 
                                   IFNULL((qb_cus.CreditLimit - qb_cus.TotalBalance),0)  as \"Credit Available\", 
                                   qb_cus.TotalBalance AS \"TotalBalance_qb.customer_table\"

                                   FROM quickbooks_sql2.invoice as qb_in

                                   LEFT JOIN 
                                   (
                                        SELECT 
                                        qb_rec_pay.CustomerRef_ListID,
                                        SUM(qb_rec_pay.UnusedPayment) as \"TotalUnusedPayment\"
                                        FROM quickbooks_sql2.receivepayment as qb_rec_pay
                                        -- WHERE qb_rec_pay.CustomerRef_ListID='2560000-1107544760'
                                        GROUP BY qb_rec_pay.CustomerRef_ListID 
                                   ) AS newTable1 
                                   on  qb_in.CustomerRef_ListID = newTable1.CustomerRef_ListID
                                   LEFT JOIN 
                                   (	
                                        SELECT  qb_cdtmm.CustomerRef_ListID, 
                                        SUM(qb_cdtmm.CreditRemaining) AS CreditRemaining 
                                        FROM quickbooks_sql2.creditmemo as qb_cdtmm
                                        GROUP BY qb_cdtmm.CustomerRef_ListID 
                                   ) AS newTable2 

                                   on  qb_in.CustomerRef_ListID = newTable2.CustomerRef_ListID

                                   INNER JOIN basetables2.Orders as ord 
                                   on qb_in.RefNumber = ord.kp_OrderID 

                                   INNER JOIN quickbooks_sql2.customer as qb_cus
                                   on qb_in.CustomerRef_ListID  = qb_cus.ListID

                                   -- INNER JOIN quickbooks_sql2.receivepayment as qb_recp
                                   -- on qb_in.CustomerRef_ListID  = qb_recp.CustomerRef_ListID
                                   LEFT join
                                   (
                                        SELECT cus1.t_QBCustomerName,cus1.t_QBCustID,
                                        IF (ord1.nb_UseTotalOrderPricing = 1 , IFNULL(ord1.n_TotalOrderPrice,0) ,
                                        SUM(CASE ordit.t_Pricing WHEN 'Line Item Pricing' 
                                        THEN 
                                        (IFNULL(ordit.n_Price,0) * IFNULL(ordit.n_Quantity,0)) 
                                        WHEN 'SQ.FT. Pricing' 
                                        THEN 
                                        (IFNULL(ordit.n_HeightInInches,0) * IFNULL(ordit.n_WidthInInches,0)) / 144 
                                        * IFNULL(ordit.n_Price,0) * IFNULL(ordit.n_Quantity,0)  
                                        END  + (IFNULL(othech.n_Price,0) * IFNULL(othech.n_Quantity,0))) )   AS WorkInHouseTotalAmount
                                        FROM  Orders as ord1

                                        LEFT JOIN Customers as cus1 on ord1.kf_CustomerID = cus1.kp_CustomerID
                                        LEFT JOIN OtherCharges as othech on ord1.kp_OrderID = othech.kf_OrderID
                                        LEFT JOIN OrderItems as ordit on ord1.kp_OrderID  = ordit.kf_OrderID

                                        where
                                        (
                                                            (`ord1`.`t_JobStatus` <> 'Cancelled')   
                                                        and (`ord1`.`t_TypeOfOrder` = 'Order')
                                                        and (ord1.nb_PostedToQuickBooks is null)
                                                        and (cus1.nb_Inactive is NULL)
                                                )
                                        -- group by `ord1`.`kp_OrderID` 
                                           group by cus1.t_QBCustomerName


                                   ) AS newTable3
                                   on  qb_in.CustomerRef_ListID = newTable3.t_QBCustID

                                   WHERE qb_in.IsPaid = 'false'
                                   and   qb_in.CustomerRef_ListID =  \"$qb_CustID\"");
        
                                   return $query->result_array();
    }
    public function getQuickBooksAccountData()
    {
        $query = $this->db->query('SELECT * FROM quickbooks_sql2.account');
        
        return $query->result_array();
        
    }
    public function insertItemNonInventoryData($data=null)
    {
        print_r($data);
        
        $query =$this->db->query("INSERT INTO quickbooks_sql2.itemnoninventory(`ListID`,`Name`,`IsActive`,`ParentRef_ListID`,
             `Sublevel`,`SalesTaxCodeRef_ListID`,`SalesTaxCodeRef_FullName`,`Status`)VALUES("."\"".$data["ListID"]."\"".","."\"".$data["Name"]."\"".",".
                "\"".$data["IsActive"]."\"".","."\"".$data["ParentRef_ListID"]."\"".","."\"".$data["Sublevel"]."\"".","."\"".$data["SalesTaxCodeRef_ListID"]."\""
                .","."\"".$data["SalesTaxCodeRef_FullName"]."\"".","."\"".$data["Status"]."\"".")");
        
        echo $query;
        //return $this->db->insert_id();
        
    } 
    public function insertSalesOrPurchaseDetailData($data=null)
    {
         $query =$this->db->query("INSERT INTO quickbooks_sql2.salesorpurchasedetail(`Description`,`Price`,`AccountRef_ListID`,`IDKEY`)
             VALUES("."\"".$data["Description"]."\"".","."\"".$data["Price"]."\"".","."\"".$data["AccountRef_ListID"]."\"".","."\"".$data["IDKEY"]."\"".")");
        
        echo $query;
        
         //$this->db->insert('salesorpurchasedetail', $data);
         //return $this->db->insert_id();
        
    } 
    
}

?>
