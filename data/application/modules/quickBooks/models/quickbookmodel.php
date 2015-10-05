<?php

class QuickBookModel extends CI_Model
{

  public function getOrderInvoiceDuplicateData() {
    $query = $this->db->query("SELECT Count(*) AS Count,
                                invoice.RefNumber as kp_OrderID,
                                basetables2.Customers.t_CustCompany
                                FROM quickbooks_sql2.invoice
                                LEFT JOIN basetables2.Customers ON invoice.RefNumber = basetables2.Customers.kp_CustomerID
                                WHERE invoice.RefNumber > 140000
                                GROUP BY invoice.RefNumber
                                HAVING Count > 1");

    return $query->result_array();
  }

  public function getInvoiceAmtDontMatchWithOrderAmt() {
      $query = $this->db->query("SELECT invoicelinedetail.IDKEY,
                                  quickbooks_sql2.invoice.TxnID,
                                  sum(Amount) as invLinAmt,
                                  quickbooks_sql2.invoice.RefNumber,
                                  basetables2.Orders.n_TotalGrandTotal as orderGrandTotal,
                                  basetables2.Orders.kp_OrderID,

                                  quickbooks_sql2.customer.SalesTaxCodeRef_Fullname,
                                  quickbooks_sql2.customer.ItemSalesTaxRef_FullName,

                                  basetables2.Orders.nb_PostedToQuickBooks,
                                  DATE_FORMAT(Orders.d_JobDue, '%c-%e-%Y') AS d_JobDue

                                  FROM quickbooks_sql2.invoicelinedetail

                                  JOIN quickbooks_sql2.invoice
                                  on quickbooks_sql2.invoice.TxnID = quickbooks_sql2.invoicelinedetail.IDKEY

                                  JOIN quickbooks_sql2.customer
                                  on quickbooks_sql2.customer.ListID = quickbooks_sql2.invoice.CustomerRef_ListID

                                  JOIN basetables2.Orders
                                  on quickbooks_sql2.invoice.RefNumber = basetables2.Orders.kp_OrderID

                                  WHERE Orders.d_JobDue > DATE_SUB(CURDATE(), INTERVAL 60 DAY)

                                  GROUP BY invoicelinedetail.IDKEY

                                  HAVING sum(quickbooks_sql2.invoicelinedetail.Amount) - (basetables2.Orders.n_TotalGrandTotal) >1
                                  AND quickbooks_sql2.invoice.RefNumber > 140000   AND basetables2.Orders.nb_PostedToQuickBooks = '1'");

      return $query->result_array();
  }

    public function getIDKEYFromRefNumber($RefNumber) {
      $query = $this->db->query("SELECT quickbooks_sql2.purchaseorder.TxnID FROM quickbooks_sql2.purchaseorder WHERE quickbooks_sql2.purchaseorder.RefNumber = \"$RefNumber\"");

      return $query->row();
    }
    public function getQuickBooksCustomerTableData($qb_CustID)
    {
        $query = $this->db->query("SELECT * FROM quickbooks_sql2.customer  WHERE ListID ='$qb_CustID'");

        return $query->row_array();

    }
    public function getItemSalesTaxDataFromListID($listID=null)
    {
        if(!$listID)
        {
//            $query = $this->db->get_where('quickbooks_sql2.itemsalestax',array('ListID'=>'10000-1070929684'));
//
//            return $query->row_array();

            $query = $this->db->query("select TRIM(TRAILING '.' FROM(CAST(TRIM(TRAILING '0' FROM itemsalestax.TaxRate) AS CHAR))) as TaxRate from quickbooks_sql2.itemsalestax where ListID='10000-1070929684'");

            return $query->row_array();

        }
        else
        {
            $query = $this->db->get_where('quickbooks_sql2.itemsalestax',array('ListID'=>$listID));

            return $query->row_array();
        }

    }
    public function getItemNonInventoryByName($itemNonInvtName)
    {
        $query = $this->db->query("SELECT Name FROM quickbooks_sql2.itemnoninventory
                                   WHERE Name =\"$itemNonInvtName\"");

        return $query->result_array();

    }
    public function getQBInvoicePaidData($qb_CustID)
    {
        $query = $this->db->query("SELECT IsPaid FROM quickbooks_sql2.invoice as qb_in
                      WHERE IsPaid =\"false\" and CustomerRef_ListID =  \"$qb_CustID\"");

        return $query->result_array();
    }
    public function getQBEmployeesData()
    {
        $query = $this->db->query("SELECT * FROM quickbooks_sql2.employee");

        return $query->result_array();
    }
    public function getQBSalesRep()
    {
        $query = $this->db->query("SELECT * FROM quickbooks_sql2.salesrep
                      WHERE quickbooks_sql2.salesrep.IsActive =\"True\" ");

        return $query->result_array();
    }
    public function updateQuickBooksCustomerTableData($qb_CustID,$data)
    {
        $this->db->where('ListID', $qb_CustID);
        $this->db->update('quickbooks_sql2.customer', $data);

    }
    public function getInvoiceInProcessData()
    {
        $query = $this->db->query("SELECT quickbooks_sql2.invoice.RefNumber,
	basetables2.Customers.t_CustCompany,
	DATE_FORMAT(Orders.d_JobDue, '%c-%e-%Y') AS d_JobDue,
	basetables2.Orders.t_TypeOfOrder,
	basetables2.Orders.t_ServiceLevel,
	quickbooks_sql2.invoice.`Status`,
	DATE_FORMAT(
		quickbooks_sql2.invoice.`TxnDate`,
		'%c-%e-%Y'
	) AS TxnDate,
	quickbooks_sql2.error_table.Error_Desc
FROM quickbooks_sql2.invoice LEFT JOIN basetables2.Orders ON quickbooks_sql2.invoice.RefNumber = basetables2.Orders.kp_OrderID
	 LEFT JOIN basetables2.Customers ON basetables2.Orders.kf_CustomerID = basetables2.Customers.kp_CustomerID
	 LEFT JOIN quickbooks_sql2.error_table ON quickbooks_sql2.invoice.TxnID = quickbooks_sql2.error_table.IDKEY
                                   WHERE quickbooks_sql2.invoice.`Status` = \"ADD\" or quickbooks_sql2.invoice.`Status` = \"DELETE\"
                                   GROUP BY quickbooks_sql2.invoice.RefNumber");
        return $query->result_array();
    }
    public function quickBookTerm($qb_CustID=null)
    {
        //$qb_CustID = "2560000-1107544760" ;
        $query = $this->db->query("SELECT qb_in.CustomerRef_FullName,qb_cus.TermsRef_FullName,
                                   sum( IF(current_date <= qb_in.DueDate,qb_in.BalanceRemaining,0) )   as Current,
                                   sum(IF(current_date > qb_in.DueDate and current_date <= DATE_ADD(qb_in.DueDate, INTERVAL 15 DAY),
					qb_in.BalanceRemaining ,0))  AS \"1-15\",
			           sum(IF(current_date > DATE_ADD(qb_in.DueDate, INTERVAL 15 DAY) and current_date <= DATE_ADD(qb_in.DueDate, INTERVAL 30 DAY),
					qb_in.BalanceRemaining ,0)) AS \"16-30\",
			           sum(IF(current_date > DATE_ADD(qb_in.DueDate, INTERVAL 30 DAY) and current_date <= DATE_ADD(qb_in.DueDate, INTERVAL 45 DAY),
					qb_in.BalanceRemaining ,0)) AS \"31-45\",
				   sum(IF(current_date > DATE_ADD(qb_in.DueDate, INTERVAL 45 DAY) and current_date <= DATE_ADD(qb_in.DueDate, INTERVAL 60 DAY),
					qb_in.BalanceRemaining ,0)) AS \"46-60\",
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
    public function getQuickBooksAccountCOG()
    {
        $query = $this->db->query('SELECT * FROM quickbooks_sql2.account WHERE account.BankNumber = "1" and account.IsActive = "True" ORDER BY account.AccountNumber ASC');

        return $query->result_array();

    }
    public function insertItemNonInventoryData($data=null)
    {
        //print_r($data);

        // $query =$this->db->query("INSERT INTO quickbooks_sql2.itemnoninventory(`ListID`,`Name`,`IsActive`,`ParentRef_ListID`,
        //      `Sublevel`,`SalesTaxCodeRef_ListID`,`SalesTaxCodeRef_FullName`,`Status`)VALUES("."\"".$data["ListID"]."\"".","."\"".$data["Name"]."\"".",".
        //         "\"".$data["IsActive"]."\"".","."\"".$data["ParentRef_ListID"]."\"".","."\"".$data["Sublevel"]."\"".","."\"".$data["SalesTaxCodeRef_ListID"]."\""
        //         .","."\"".$data["SalesTaxCodeRef_FullName"]."\"".","."\"".$data["Status"]."\"".")");

        $this->db->insert('quickbooks_sql2.itemnoninventory', $data);

        //echo $query;
        //return $this->db->insert_id();

    }
    public function insertSalesOrPurchaseDetailData($data=null)
    {
        //  $query =$this->db->query("INSERT INTO quickbooks_sql2.salesorpurchasedetail(`Description`,`Price`,`AccountRef_ListID`,`IDKEY`)
        //      VALUES("."\"".$data["Description"]."\"".","."\"".$data["Price"]."\"".","."\"".$data["AccountRef_ListID"]."\"".","."\"".$data["IDKEY"]."\"".")");

        $this->db->insert('quickbooks_sql2.salesorpurchasedetail', $data);

        //echo $query;

         //$this->db->insert('salesorpurchasedetail', $data);
         //return $this->db->insert_id();

    }
    public function quickBookTermIsPaid($qb_CustID)
    {
        $query = $this->db->query("SELECT qb_in.CustomerRef_FullName,qb_cus.TermsRef_FullName,
                                   sum( IF(current_date <= qb_in.DueDate,qb_in.BalanceRemaining,0) )   as Current,
                                    sum(IF(current_date > qb_in.DueDate and current_date <= DATE_ADD(qb_in.DueDate, INTERVAL 15 DAY),
					qb_in.BalanceRemaining ,0))  AS \"1-15\",
			           sum(IF(current_date > DATE_ADD(qb_in.DueDate, INTERVAL 15 DAY) and current_date <= DATE_ADD(qb_in.DueDate, INTERVAL 30 DAY),
					qb_in.BalanceRemaining ,0)) AS \"16-30\",
			           sum(IF(current_date > DATE_ADD(qb_in.DueDate, INTERVAL 30 DAY) and current_date <= DATE_ADD(qb_in.DueDate, INTERVAL 45 DAY),
					qb_in.BalanceRemaining ,0)) AS \"31-45\",
				   sum(IF(current_date > DATE_ADD(qb_in.DueDate, INTERVAL 45 DAY) and current_date <= DATE_ADD(qb_in.DueDate, INTERVAL 60 DAY),
					qb_in.BalanceRemaining ,0)) AS \"46-60\",
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

                                   WHERE qb_in.CustomerRef_ListID =  \"$qb_CustID\"");

                                   return $query->result_array();
    }
    public function getPurchaseOrderToReceive()
    {
        $query = $this->db->query("SELECT purchaseorder.TxnID,purchaseorder.RefNumber AS PoName,purchaseorder.VendorRef_ListID,
                                          purchaseorder.VendorRef_FullName AS Vendor,purchaseorder.TotalAmount,
                                          DATE_FORMAT(DATE(STR_TO_DATE(purchaseorder.TimeCreated, '%c/%e/%Y %H:%i')), '%c-%e-%Y') AS Created,
                                          DATE(purchaseorder.ExpectedDate) AS Expected,purchaseorder.Memo
                                   FROM quickbooks_sql2.purchaseorder
                                   WHERE IsFullyReceived = \"false\" and IsManuallyClosed = \"false\"
                                   ORDER BY purchaseorder.TimeCreated ASC");

        return $query->result_array();

    }
    public function getPurchaseOrderToReceiveByTxnID($TxnID)
    {
        $query = $this->db->query("SELECT purchaseorder.TxnID,purchaseorder.RefNumber AS PoName,purchaseorder.VendorRef_ListID,
                                          purchaseorder.VendorRef_FullName AS Vendor,purchaseorder.TotalAmount,
                                          DATE(STR_TO_DATE(purchaseorder.TimeCreated, '%c/%e/%Y %H:%i')) AS Created,
                                          DATE(purchaseorder.ExpectedDate) AS Expected,purchaseorder.Memo
                                   FROM quickbooks_sql2.purchaseorder
                                   WHERE purchaseorder.TxnID = \"$TxnID\"
                                   ORDER BY purchaseorder.TimeCreated ASC");

        return $query->row_array();

    }
    public function getPurchaseOrderToReceiveFromTimeCreatedDate($date)
    {
        $query = $this->db->query("SELECT purchaseorder.TxnID,purchaseorder.RefNumber AS PoName,purchaseorder.VendorRef_ListID,
                                          purchaseorder.VendorRef_FullName AS Vendor,purchaseorder.TotalAmount,
                                          DATE(STR_TO_DATE(purchaseorder.TimeCreated, '%c/%e/%Y %H:%i')) AS Created,
                                          DATE(purchaseorder.ExpectedDate) AS Expected,purchaseorder.Memo
                                    FROM quickbooks_sql2.purchaseorder
                                    WHERE DATE(STR_TO_DATE(purchaseorder.TimeCreated, '%c/%e/%Y %H:%i'))= \"$date\"
                                    ORDER BY purchaseorder.TimeCreated ASC");

        return $query->result_array();

    }
    public function getPurchaseOrderLineDetail($IDKEY)
    {
        $query = $this->db->query("SELECT quickbooks_sql2.purchaseorderlinedetail.TxnLineID,basetables2.InventoryItems.kp_InventoryItemID,
                                          basetables2.InventoryItems.t_PartNumber,quickbooks_sql2.purchaseorderlinedetail.Description,
                                          quickbooks_sql2.purchaseorderlinedetail.Quantity,quickbooks_sql2.purchaseorderlinedetail.ReceivedQuantity,
                                          quickbooks_sql2.purchaseorderlinedetail.Rate,quickbooks_sql2.purchaseorderlinedetail.Amount,
                                          quickbooks_sql2.purchaseorderlinedetail.ItemRef_ListID
                                   FROM quickbooks_sql2.purchaseorderlinedetail INNER JOIN basetables2.InventoryItems ON quickbooks_sql2.purchaseorderlinedetail.ItemRef_ListID = basetables2.InventoryItems.t_QBServiceID
                                   WHERE quickbooks_sql2.purchaseorderlinedetail.IDKEY = \"$IDKEY\"");

        return $query->result_array();

    }
    public function getPurhcaseOrderByVendorListID($listID)
    {
        $query = $this->db->query("SELECT purchaseorder.TxnID,purchaseorder.RefNumber AS PoName,purchaseorder.VendorRef_ListID,
                                          purchaseorder.VendorRef_FullName AS Vendor,purchaseorder.TotalAmount,
                                          DATE(STR_TO_DATE(purchaseorder.TimeCreated, '%c/%e/%Y %H:%i')) AS Created,
                                          DATE(purchaseorder.ExpectedDate) AS Expected,purchaseorder.Memo,
                                          basetables2.Vendors.t_ListID
                                   FROM quickbooks_sql2.purchaseorder
                                   INNER JOIN basetables2.Vendors ON quickbooks_sql2.purchaseorder.VendorRef_ListID = basetables2.Vendors.t_ListID
                                   WHERE basetables2.Vendors.t_ListID = '$listID'
                                   ORDER BY purchaseorder.TimeCreated ASC");

        return $query->result_array();

    }
    public function insertTxnItemLineDetail($data=null)
    {
        $this->db->insert('quickbooks_sql2.txnitemlinedetail', $data);

        return $this->db->insert_id();
    }
    public function itemReceipt($data=null)
    {
        $this->db->insert('quickbooks_sql2.itemreceipt', $data);

        return $this->db->insert_id();
    }
    public function insertInvoiceData($data=null)
    {
        $this->db->insert('quickbooks_sql2.invoice', $data);

        return $this->db->insert_id();
    }
    public function insertVendorData($data=null)
    {
        $this->db->insert('quickbooks_sql2.vendor', $data);

        return $this->db->insert_id();
    }
    public function insertPurchaseOrderData($data=null)
    {
        $this->db->insert('quickbooks_sql2.purchaseorder', $data);

        return $this->db->insert_id();
    }
    public function insertPurchaseOrderLineDetailData($data=null)
    {
        $this->db->insert('quickbooks_sql2.purchaseorderlinedetail', $data);

        return $this->db->insert_id();
    }
    public function insertInvoicelineDetailData($data=null)
    {
        $this->db->insert('quickbooks_sql2.invoicelinedetail', $data);

        return $this->db->insert_id();
    }
    public function getInvoiceDataByRefNumber($refNumber)
    {
        $query = $this->db->get_where('quickbooks_sql2.invoice',array('RefNumber'=>$refNumber));

        return $query->row_array();

    }
    public function updatePurchaseOrder($data,$refNumber)
    {
        $result = $this->db->update('quickbooks_sql2.purchaseorder', $data, array('RefNumber'=>  $refNumber));

        if(!$result)
        {
            return $this->db->_error_message();
        }
        else
        {
            return $this->db->affected_rows();

        }

    }
    public function updateInvoiceTblDataByRefNumber($data,$refNumber)
    {
        $result = $this->db->update('quickbooks_sql2.invoice', $data, array('RefNumber'=>  $refNumber));

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
