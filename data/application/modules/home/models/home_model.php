<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Home_model extends CI_Model 
    {
        public function getallrows($DateYmd)
        { 
                //Active Record Method Chaining
                $selectArray = array('kp_OrderID', 't_ServiceLevel', 't_CustCompany', 't_JobName', 'ti_JobDue', 't_JobStatus', 'nb_SureDate', 'n_OrderItemCount', 
                    'n_OICSqFtSum', 'n_DurationTime', 't_MachineAb', 'n_Complexity', 't_OrderItemAb', 't_OrdShip', 'nb_JobFinished');
                
                $this->db->
                            select($selectArray)->
                            from('vH_ActiveDate')->
                            where(array('d_JobDue' => $DateYmd, 'nb_JobFinished' => null ))->
                            order_by('n_SortOrder', 'Asc');
                
                $query = $this->db->get();

                //Active Record
                //$query = $this->db->get_where('vH_ActiveDate', array('d_JobDue' => $DateYmd, 'nb_JobFinished' => null )); 
                
                //Below also works as a standard Query:  
                //$query = $this->db->query("SELECT * FROM vH_ActiveDate WHERE d_JobDue='$JobDue'");         
                
                $returnedArray=$query->result_array();
                
                // Run Function to Create Table
                $table = $this->tableCreate($returnedArray);
                return $table;
        } 
        
        public function getallfinished($DateYmd)
        { 
                //Active Record Method Chaining
                $selectArray = array('kp_OrderID', 't_ServiceLevel', 't_CustCompany', 't_JobName', 'ti_JobDue', 't_JobStatus', 'nb_SureDate', 'n_OrderItemCount', 
                    'n_OICSqFtSum', 'n_DurationTime', 't_MachineAb', 'n_Complexity', 't_OrderItemAb', 't_OrdShip', 'nb_JobFinished');
                
                $this->db->
                            select($selectArray)->  
                            from('vH_ActiveDate')->
                            where(array('d_JobDue' => $DateYmd, 'nb_JobFinished' => '1' ))->
                            order_by('n_SortOrder', 'Asc');
                
                $query = $this->db->get();

                $returnedArray=$query->result_array();
                
                // Run Function to Create Table
                $table = $this->tableCreate($returnedArray);
                return $table;
        } 
        
        public function getdue()
        { 
                $query = $this->db->query("SELECT * FROM vHL_JobDue" );
                

                return $query->result();
        } 
            
        public function getreceived()
        {
                $query = $this->db->query("SELECT * FROM vHL_Received" );

                return $query->result();
        } 
        
        
        
        public function bymachine($DateYmd)
        { 
                //Active Record Method Chaining
                $selectArray = array('kp_OrderID', 't_ServiceLevel', 't_CustCompany', 't_JobName', 'ti_JobDue', 't_JobStatus', 'nb_SureDate', 'n_OrderItemCount', 
                    'n_OICSqFtSum', 'n_DurationTime', 't_MachineAb', 'n_Complexity', 't_OrderItemAb', 't_OrdShip', 'nb_JobFinished');
                
                $this->db->
                            select($selectArray)->  
                            from('vH_ActiveDate')->
                            where(array('d_JobDue' => $DateYmd, 'nb_JobFinished' => null ))->
                            order_by('n_SortOrder', 'Asc');
 
               
                $query = $this->db->get();
                $returnedArray = $query->result_array();
                
                // Run Function to Create Table
                $table = $this->tableCreate($returnedArray);
                return $table;
                
                


        } 
        
                public function tableCreate($returnedArray)
        { 
              
                //Load Table Library
                $this->load->library('table');
                //Set Class for Bootstrap
                $tmpl = array ( 'table_open'  => '<table class="table table-striped table-bordered table-condensed">' );
                $this->table->set_template($tmpl);      

                // Create custom headers
                $header = array('OrderID', 'Sevice', 'Company Name', 'Job Name','Time','Status', 'SD', '#', 'SqFt', 'T', 'Press', 'CX', 'Info', 'Ship', 'JF');
                // Set the headings
                $this->table->set_heading($header);

                // Start Formating the Table
                $lenArr=sizeof($returnedArray);
                for($i = 0; $i<$lenArr; $i++)
                {
                    $returnedArray[$i]['kp_OrderID']= anchor('orders/'. $returnedArray[$i]['kp_OrderID'], $returnedArray[$i]['kp_OrderID']);
                    $returnedArray[$i]['n_OrderItemCount']=  round($returnedArray[$i]['n_OrderItemCount']);
                    $returnedArray[$i]['n_OICSqFtSum']=  round($returnedArray[$i]['n_OICSqFtSum']);
                    $returnedArray[$i]['n_DurationTime']=  round($returnedArray[$i]['n_DurationTime']);
                    $returnedArray[$i]['n_Complexity']=  round($returnedArray[$i]['n_Complexity']);
                    if($returnedArray[$i]['ti_JobDue'] == "")
                        {
                        $returnedArray[$i]['ti_JobDue']==  "";
                        }
                    else 
                        {
                        date_default_timezone_set('America/Indianapolis');
                        $returnedArray[$i]['ti_JobDue'] = date('h:i a',strtotime($returnedArray[$i]['ti_JobDue']));
                        }

                }

                return $returnedArray;


        } 
        
        
    }
?>


