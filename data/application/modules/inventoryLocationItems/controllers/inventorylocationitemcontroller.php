 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class InventoryLocationItemController extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $privSet = verifyToken();
        if(!$privSet) {
          $this->response(array('error' => 'Invalid or missing token.'), 401);
        }
        $this->load->model('inventorylocationitemmodel');

    }
    public function getLocation($id)
    {
        $data['result'] = $this->inventorylocationitemmodel->getLocationData($id);
        $this->load->view('invlocationitems_list',$data);
    }
    public function getInvLocationItemFromInventoryLocationItemID($inventoryLocationItemID)
    {
        $row = $this->inventorylocationitemmodel->getInvLocationItemByID($inventoryLocationItemID);

        return $row;

    }
    public function insertInvLocationItem()
    {
        date_default_timezone_set('America/Indianapolis');

        $inventoryItemID               = $this->input->post('inventoryItemIDBasic');

        $inventoryLocationID           = $this->input->post('inventoryLocationIDHidden');

        $quantityOnHand                = $this->input->post('sliderDynamicAdd');

        $data                          = array(
                                                'n_QntyOnHand'=>$quantityOnHand,
                                                'kf_InventoryItemID'=>$inventoryItemID,
                                                'kf_InventoryLocationID'=>$inventoryLocationID
                                              );
        $data['zCreated']              = date("Y-m-d H:i:s", time());

        $insertInvLocationItemArr      = array();
        $insertInvLocationItemArr[0]   = $data;

        $insertedInvLocationItemID     = $this->inventorylocationitemmodel->insertInvLocationItemTable($insertInvLocationItemArr);

        $row                           = $this->getInvLocationItemFromInventoryLocationItemID($insertedInvLocationItemID);

        $getInventoryLocationID           = $row['kf_InventoryLocationID'];

        echo $getInventoryLocationID;
    }
    public function updateInvLocationItem()
    {
        date_default_timezone_set('America/Indianapolis');
        $inventoryLocationItemID       = $this->input->post('inventoryLocationItemIDHidden');

        $quantityOnHand                = $this->input->post('sliderDynamicEdit');

        $data = array('n_QntyOnHand'=>$quantityOnHand);

        $data['zModified']             = date("Y-m-d H:i:s", time());

        $this->inventorylocationitemmodel->updateInvLocationItemData($inventoryLocationItemID,$data);

        $row                           = $this->getInvLocationItemFromInventoryLocationItemID($inventoryLocationItemID);

        $inventoryLocationID           = $row['kf_InventoryLocationID'];

        echo $inventoryLocationID;

    }
    public function deleteInvLocationItemRecord()
    {
        $inventoryLocationItemID       = $this->input->post('inventoryLocationItemIDHidden');

        $row                           = $this->getInvLocationItemFromInventoryLocationItemID($inventoryLocationItemID);

        $inventoryLocationID           = $row['kf_InventoryLocationID'];

        $result                        = $this->inventorylocationitemmodel->deleteInvLocationItemTableRow($inventoryLocationItemID);

        echo $inventoryLocationID;

    }
}
