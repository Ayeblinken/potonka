<?php
class OtherChargeModel extends CI_Model
{
    //put your code here
    public function __construct()
    {
        parent::__construct();
    }

    public function insertCharge($data) {
      $this->db->insert('OtherCharges', $data);

      return $this->db->insert_id();
    }

    public function otherChargeInvoiceDataFromOrderID($orderID)
    {
        $query = $this->db->query("SELECT  OtherCharges.kp_OtherChargesID AS ID,
                                            CASE
                                                WHEN OtherCharges.t_Category = 'Prepress' THEN  '680000-1085842400'
                                                WHEN OtherCharges.t_Category = 'Finishing' THEN  '80000335-1164969531'
                                                WHEN OtherCharges.t_Category = 'Other Charges' THEN  '80000832-1206277228'
                                            END AS ItemRef_ListID,
                                            OtherCharges.t_Description AS Description,
                                            OtherCharges.n_Quantity AS Quantity,
                                            Round(OtherCharges.n_Price, 2) AS Rate,
                                            Round(OtherCharges.n_OtherChargesSubtotal, 2) AS Amount,
                                            'Tax' as Taxable
                                    FROM OtherCharges
                                    WHERE OtherCharges.kf_OrderID = '$orderID'");

        return $query->result_array();
    }
    public function otherChargeTableData($orderID)
    {
         $this->db
                 ->select('kp_OtherChargesID as ID,
                     ifnull(t_Category,\'\') as Category,
                     ifnull(t_Description,\'\') as Description ,
                     CAST(ifnull(n_Quantity,\'\')AS CHAR) as Quantity,
                     concat(\'$\',cast(
                                    ifnull(n_Price,\'\')
                                as CHAR)

                           ) as Price,
                     concat(\'$\',CAST(ifnull(Round(n_Quantity * n_Price,2),0) AS CHAR)) as Total',false)
                 ->from('OtherCharges')
                 ->where('kf_OrderID',$orderID);
         $query = $this->db->get();
         return $query->result();
    }

    public function modalOtherChargeData($addressID)
    {
        $this->db
                 ->select('kp_OtherChargesID as ID,
                     ifnull(t_Category,\'\') as Category,
                     ifnull(t_Description,\'\') as Description ,
                     ifnull(n_Quantity,\'\') as Quantity,
                     concat(\'$\',cast(
                                    ifnull(n_Price,\'\')
                                as CHAR)

                           ) as Price,
                     concat(\'$\',ifnull(Round(n_Quantity * n_Price,2),0)) as Total',false)
                 ->from('OtherCharges')
                 ->where('kp_OtherChargesID',$addressID);
        $query = $this->db->get();

        return  $query->row();
    }

    public function updateAction()
    {
        $modalPriceRefined = str_replace("$", '',$this->input->post('modalPrice',true));

        $data = array(
             't_Category'=> $this->input->post('modalCategory',true),
             't_Description'=> $this->input->post('modalDescription',true),
             'n_Quantity'=> $this->input->post('modalQty',true),
             'n_Price'=> $modalPriceRefined
        );

        $this->db->update('OtherCharges', $data, array('kp_OtherChargesID'=>  $this->input->post('modalOtherChargeID',true)));

    }
    public function createAction($action=null,$data=null)
    {
        if($action != "duplicateOtherChargesFromOrderID")
        {
            $modalPriceRefined = str_replace("$", '',$this->input->post('modalPrice',true));

            $data = array(
                 't_Category'=> $this->input->post('modalCategory',true),
                 't_Description'=> $this->input->post('modalDescription',true),
                 'n_Quantity'=> $this->input->post('modalQty',true),
                 'kf_OrderID'=> $this->input->post('modalOrderIDHidden',true),
                 'n_Price'=> $modalPriceRefined
            );


            $this->db->insert('OtherCharges', $data);

            return $this->db->insert_id();

        }
        if($action == "duplicateOtherChargesFromOrderID")
        {
            $lenArr                  = sizeof($data);
            for($i = 0; $i<$lenArr; $i++)
            {
                $this->db->insert('OtherCharges', $data[$i]);

            }
            return $this->db->insert_id();
        }
    }

    public function deleteModalAction($otherChargeID)
    {
        /*
        * Any non-digit character will be excluded after passing $id
        * from intval function. This is done for security reason.
        */
        $kp_OtherChargesID = intval( $otherChargeID );

        // below delete operation generates this query DELETE FROM users WHERE id = $id
        $this->db->delete( 'OtherCharges', array( 'kp_OtherChargesID' => $kp_OtherChargesID ) );

    }

    public function getOtherChargeDataFromOrderID($orderID)
    {
        $query = $this->db->get_where('OtherCharges',array('kf_OrderID'=>$orderID));

        return $query->result_array();

    }




}
?>
