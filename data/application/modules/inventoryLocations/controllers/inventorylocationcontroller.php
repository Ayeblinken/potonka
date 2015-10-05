<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class InventoryLocationController extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        $this->load->model('inventorylocationmodel');

    }
    public function getall($url)
    {
        // finds all locations and there id "WX1"
        $data['result'] = $this->inventorylocationmodel->getall();

        $data['url'] = $url;

        $this->load->view('invlocations_list',$data);

    }
    public function getLocation()
    {
        $location = $this->input->post('q');

        echo json_encode($this->inventorylocationmodel->getLocationData($location));
    }


}

?>
