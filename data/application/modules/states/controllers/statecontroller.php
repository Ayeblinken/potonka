<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class StateController extends MX_Controller {
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
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
    public function getCountriesFromStateTableInJsonFormat($countryAbb=null)
    {
        $resultArray = $this->statemodel->getCountryInfoFromStatesTable();

        echo json_encode($resultArray);
        //print_r($resultArray);

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
    public function  getStatesFromStatesTableInJsonFormat()
    {
        $resultArray = $this->statemodel->getStatesInfoFromStatesTable();

        echo json_encode($resultArray);

    }
    public function getStatesFromCountryChangeInJsonFormat($countryAbb)
    {

        $resultArray = $this->statemodel->getStatesCountryChange($countryAbb);


        if($countryAbb == "FR" || $countryAbb == "ES" || $countryAbb == "CR" || $countryAbb == "PA")
        {
            //print_r($resultArray);
            //echo "State: ".$resultArray[0]['t_StateAbbreviation'];
            $resultArray[0]['t_StateAbbreviation'] = " ";
            $resultArray[0]['t_StateName'] = "State not required";
        }

        echo json_encode($resultArray);
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
