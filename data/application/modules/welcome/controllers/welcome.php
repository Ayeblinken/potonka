<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MX_Controller  {
    
        public function __construct() 
        {
            parent::__construct();
            $this->load->library('session');
            $session_user = $this->session->userdata('username');
                
            if(!isset($session_user) || $session_user == "")
            {
                redirect('');
            }

        
        }
    
    
        public function index()
	{
            
            $this->load->view('welcome_message');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */