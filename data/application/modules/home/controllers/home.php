<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Home extends MX_Controller
    {

        function __construct(){
            parent::__construct();
            $privSet = verifyToken();
            if(!$privSet) {
              $this->response(array('error' => 'Invalid or missing token.'), 401);
            }
            $this->load->model('home_model');
        }
        //this is a commment
        public function index($JobDue = NULL)
        {
             if(!isset($JobDue))
                {
                    date_default_timezone_set('America/Indianapolis');
                    $JobDue = date("m-d-Y");
                }
                    $DateEx = explode( "-" , $JobDue);
                    $DateYmd = $DateEx[2]."-".$DateEx[0]."-".$DateEx[1];
                    $data['page_title'] = "Indy Home";
                    $data['dateloaded'] = $JobDue;
                    //$data['result'] = $this->home_model->getallrows($DateYmd);
                    //$data['finished'] = $this->home_model->getallfinished($DateYmd);
                    $data['result1'] = $this->home_model->getdue();
                    $data['result2'] = $this->home_model->getreceived();
                    // $data['tabletest'] = $this->homescreen_model->byMachine($DateYmd);


                    $this->load->view('home_view',$data);

        }

        public function inProcess()
        {
           $JobDue = $_POST['jobDue'];

           if(!isset($JobDue))
                {
                    $JobDue = date("m-d-Y");
                }
                    $DateEx = explode( "-" , $JobDue);
                    $DateYmd = $DateEx[2]."-".$DateEx[0]."-".$DateEx[1];
                    $data['dateloaded'] = $JobDue;
                    $data['result'] = $this->home_model->getallrows($DateYmd);

                    //$this->load->view('/home/bymachine_view',$data);
                    $this->load->view('tabstable_view',$data);
        }

        public function finished()
        {
           $JobDue = $_POST['jobDue'];

           if(!isset($JobDue))
                {
                    $JobDue = date("m-d-Y");
                }
                    $DateEx = explode( "-" , $JobDue);
                    $DateYmd = $DateEx[2]."-".$DateEx[0]."-".$DateEx[1];
                    $data['dateloaded'] = $JobDue;
                    $data['result'] = $this->home_model->getallfinished($DateYmd);


                    $this->load->view('tabstable_view',$data);

        }

       public function byMachine()
        {
           $JobDue = $_POST['jobDue'];

           if(!isset($JobDue))
                {
                    $JobDue = date("m-d-Y");
                }
                    $DateEx = explode( "-" , $JobDue);
                    $DateYmd = $DateEx[2]."-".$DateEx[0]."-".$DateEx[1];
                    $data['dateloaded'] = $JobDue;
                    $data['result'] = $this->home_model->bymachine($DateYmd);


                    $this->load->view('tabstable_view',$data);

        }

      public function activeStatus()
        {
           $JobDue = $_POST['jobDue'];

           if(!isset($JobDue))
                {
                    $JobDue = date("m-d-Y");
                }
                    $DateEx = explode( "-" , $JobDue);
                    $DateYmd = $DateEx[2]."-".$DateEx[0]."-".$DateEx[1];
                    $data['dateloaded'] = $JobDue;
                    $data['result'] = $this->home_model->byMachine($DateYmd);


                    $this->load->view('tabstable_view',$data);

        }

      public function onPress()
        {
           $JobDue = $_POST['jobDue'];

           if(!isset($JobDue))
                {
                    $JobDue = date("m-d-Y");
                }
                    $DateEx = explode( "-" , $JobDue);
                    $DateYmd = $DateEx[2]."-".$DateEx[0]."-".$DateEx[1];
                    $data['dateloaded'] = $JobDue;
                    $data['result'] = $this->home_model->byMachine($DateYmd);


                    $this->load->view('tabstable_view',$data);

        }

        public function byShipper()
        {
           $JobDue = $_POST['jobDue'];

           if(!isset($JobDue))
                {
                    $JobDue = date("m-d-Y");
                }
                    $DateEx = explode( "-" , $JobDue);
                    $DateYmd = $DateEx[2]."-".$DateEx[0]."-".$DateEx[1];
                    $data['dateloaded'] = $JobDue;
                    $data['result'] = $this->home_model->byMachine($DateYmd);


                    $this->load->view('tabstable_view',$data);

        }

       public function readyToPickup()
        {
           $JobDue = $_POST['jobDue'];

           if(!isset($JobDue))
                {
                    $JobDue = date("m-d-Y");
                }
                    $DateEx = explode( "-" , $JobDue);
                    $DateYmd = $DateEx[2]."-".$DateEx[0]."-".$DateEx[1];
                    $data['dateloaded'] = $JobDue;
                    $data['result'] = $this->home_model->byMachine($DateYmd);


                    $this->load->view('tabstable_view',$data);

        }
    }
?>
