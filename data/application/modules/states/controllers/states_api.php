<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class States_Api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        $this->load->model('statemodel');

    }
    public function statesInfoByCountryChange_get()
    {
        if(!$this->get('countryAbb'))
        {
            $this->response(NULL, 400);
        }
        $countryAbb  = $this->get('countryAbb');

        if($countryAbb == "FR" || $countryAbb == "ES" || $countryAbb == "CR" || $countryAbb == "PA")
        {
            //print_r($resultArray);
            //echo "State: ".$resultArray[0]['t_StateAbbreviation'];
            $result[0]['t_StateAbbreviation'] = " ";
            $result[0]['t_StateName'] = "State not required";
        }
        else
        {
            $result = $this->statemodel->getStatesCountryChange($countryAbb);
        }
        //var_dump($result);
        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => "States for " .$countryAbb." could not be found"), 404);
        }

    }
    public function users_get()
    {
        //$users = $this->some_model->getSomething( $this->get('limit') );
        $users = array(
			array('id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com'),
			array('id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com'),
			3 => array('id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com', 'fact' => array('hobbies' => array('fartings', 'bikes'))),
		);

        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

}
