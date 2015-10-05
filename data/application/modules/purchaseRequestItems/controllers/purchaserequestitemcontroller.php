<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PurchaseRequestItemController extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        $this->load->model('purchaserequestitemmodel');

    }
    public function getPurchaseRequestItemGraphData($inventoryItemData)
    {
        $result          = $this->purchaserequestitemmodel->getPurchaseOrderGraph($inventoryItemData);

        return $result;
    }
    public function getPurchaseRequestItemGraphDataInJson($inventoryItemData)
    {
        $result= $this->getPurchaseRequestItemGraphData($inventoryItemData);

        echo json_encode($result);
    }
}
