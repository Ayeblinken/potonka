<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class PurchaseRequest_Api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        if(!checkPermissionsByModule($privSet, 'purchase')) {
          $this->response(array('error' => 'You\'re privilege set doesn\'t allow access to this content'), 400);
        }
        $this->load->model('purchaserequestmodel');

    }

    public function closedPurchaseOrdersInProcess_get() {
        $result  = $this->purchaserequestmodel->getClosedPurchaseOrdersInProcess();

        if($result) {
          $this->response($result, 200);
        } else {
          $this->response(array(array('error' => 'No inventory items found')), 200);
        }
    }

    public function updatePurchaseOrderStatus_put() {
      if(!$this->put('formData')) {
        $this->response(NULL, 400);
      }
      $formData = $this->put('formData');

      $this->purchaserequestmodel->updatePOStatus($formData);
    }

    public function purchaseRequestChanges_put() {
      if(!$this->put('changes')) {
        $this->response(NULL, 400);
      }
      $changes = $this->put('changes');

      for($i=0;$i<sizeof($changes);$i++) {
        if($changes[$i]['type'] == "I") {
          $kp_PurchaseRequestItemID = $this->purchaserequestmodel->insertRequestItem($changes[$i]['requestItemData']);
          $this->purchaserequestmodel->insertLineDetail($changes[$i]['lineDetailData'], $kp_PurchaseRequestItemID);
        } else if($changes[$i]['type'] == "U") {
          $this->purchaserequestmodel->updateRequestItem($changes[$i]['requestItemData']);
          $this->purchaserequestmodel->updateLineDetail($changes[$i]['lineDetailData']);
        } else if($changes[$i]['type'] == "D") {
          $this->purchaserequestmodel->deleteRequestItem($changes[$i]['requestItemData']);
          $this->purchaserequestmodel->deleteLineDetail($changes[$i]['lineDetailData']);
        }


      }
    }

    public function purchaseRequestVendorTblData_get()
    {
        $result  = $this->purchaserequestmodel->getPurchaseRequestVendorTblData();

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array(), 200);
        }
    }

    public function purchaseRequestVendorTblDataByVendor_get()
    {
      if(!$this->get('kf_VendorID')) {
        $this->response(NULL, 400);
      }
      $vendorID = $this->get('kf_VendorID');

        $result  = $this->purchaserequestmodel->getPurchaseRequestVendorTblDataByVendor($vendorID);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array(), 200);
        }
    }

    public function purchaseRequestVendorTblDataByDate_get()
    {
      if(!$this->get('d_Created')) {
        $this->response(NULL, 400);
      }
      $dateCreated = $this->get('d_Created');

        $result  = $this->purchaserequestmodel->getPurchaseRequestVendorTblDataByDate($dateCreated);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array(), 200);
        }
    }


}
