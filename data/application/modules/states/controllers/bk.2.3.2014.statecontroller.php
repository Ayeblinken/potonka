<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class StateController extends MX_Controller {
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('statemodel');
        
    }
    public function index()
    {
        echo "This is a state HMVC model";
    }
    public function getCountryFromStatesTable($countryAbb=null)
    {
        //echo "hi";
        $resultArray = $this->statemodel->getCountryInfoFromStatesTable();
        foreach ($resultArray as $rowj)
        {
            $id   = $rowj['t_CountryAbb'];
            $data = $rowj['t_Country'];
            if($countryAbb == $id )
            {
                  // echo the selected element
                 echo '<option value="'.$id. '"selected>'.$data.'</option>';
                //echo "<option selected=\"selected\" value='$id' >";
              

            }
            else 
            {
                echo '<option value="'.$id.'">'.$data.'</option>';

            }
            
            
        }
    }
    public function getStatesFromStatesTable()
    {
        $resultArray = $this->statemodel->getStatesInfoFromStatesTable();
        
        $defaultSelectElement = array("t_StateAbbreviation" => "", "t_StateName" => "State");
        
        array_unshift($resultArray, $defaultSelectElement);
        
        
        foreach ($resultArray as $rowj)
        {
            $id   = $rowj['t_StateAbbreviation'];
            $data = $rowj['t_StateName']; 
            echo '<option value="'.$id.'">'.$data.'</option>';
            
        }
    }
    public function getStatesFromCountryChange($countryAbb,$stateAbb=null)
    {
        
        
        // if country choosen is FR , state is not a required field.
        if($countryAbb == "FR" || $countryAbb == "ES" || $countryAbb == "CR" || $countryAbb == "PA")
        {
             echo '<option value=\'\'>State not required</option>';
            
            
        }
        else
        {
            $resultArray = $this->statemodel->getStatesCountryChange($countryAbb);
            
            $defaultSelectElement = array("t_StateAbbreviation" => "", "t_StateName" => "State");
        
        
        
            array_unshift($resultArray, $defaultSelectElement);
            
            foreach ($resultArray as $rowj)
            {
                $id   = $rowj['t_StateAbbreviation'];
                $data = $rowj['t_StateName']; 
                // check for  $stateAbb
                // if true ckecked is done
                if($stateAbb == $id )
                {
                      // echo the selected element
                     echo '<option value="'.$id. '"selected>'.$data.'</option>';
                    //echo "<option selected=\"selected\" value='$id' >";


                }
                else 
                {
                    // else not checked
                    echo '<option value="'.$id.'">'.$data.'</option>';

                }
            
            }
            
        }
        
        
        //print_r($resultArray);
        
       
        
        
        
    }
    
}
