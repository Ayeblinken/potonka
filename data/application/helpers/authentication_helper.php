<?php

define("AUTH_TURNED_OFF", true);
// define("SALES_PERMISSIONS", 'addresses,home,waste,customer,order,ordercharge,orderitem,ordership,orderlog,orderstatus,inventory,invitem,map');
// define("PRODUCTION_PERMISSIONS", 'home,order,ordercharge,orderitem,ordership,orderlog,orderstatus,inventory,invitem');
// define("PREPRESS_PERMISSIONS", 'address,home,customer,order,ordercharge,orderitem,ordership,orderlog,orderstatus,inventory,invitem');
// define("PRODUCTION_nb_AddCustomer", "0");
// define("PRODUCTION_nb_AddNewOrder", "0");
// define("PREPRESS_nb_AddCustomer", "0");
// define("PREPRESS_nb_AddNewOrder", "0");


function verifyToken() {
  if(AUTH_TURNED_OFF) {
    return true;
  }

  $CI = get_instance();

  if($CI->input->get_request_header('Authorization')) {
    $tokenHeader      = $CI->input->get_request_header('Authorization', TRUE);

    try {
      $token            = JWT::decode($tokenHeader, JWT_KEY);
    } catch (Exception $e) {
      return false;
    }

  } else {
    $token = null;
  }

  if($token->time != "Permanent") {
    $loginTime = new DateTime($token->time);
    $nowTime = new DateTime(date("Y-m-d H:i:s", time()));
    $interval = $loginTime->diff($nowTime);
    $hoursDifference = $interval->h + ($interval->days*24);
    // $minutesDifference = $interval->i + ($hoursDifference * 60);

    if($hoursDifference >= 48) {
      return false;
    }
  }


  if($token !== null && $token !== false && $token->privilegeSet !== "Reset") {
    return $token->privilegeSet;
  } else {
    return false;
  }
}

function checkPermissionsByModule($privilegeSet, $module) {
  if(AUTH_TURNED_OFF) {
    return true;
  }
  $permissions = getPermissions();
  if($permissions[$privilegeSet]['nb_AccessEverything']) {
    return true;
  }

  if(strrpos($permissions[$privilegeSet]['t_ModuleAccess'], $module) !== false) {
    return true;
  } else {
    return false;
  }
}

function checkPermissionsByTable($privilegeSet, $table) {
  if(AUTH_TURNED_OFF) {
    return true;
  }
  $permissions = getPermissions();
  if($permissions[$privilegeSet]['nb_AccessEverything']) {
    return true;
  }

  $module = getModuleFromTable($table);

  if($module == '') {
    return true;
  }

  if(strrpos($permissions[$privilegeSet]['t_ModuleAccess'], $module) !== false) {
    return true;
  } else {
    return false;
  }
}

function checkPermissionAddCustomer($privilegeSet) {
  $permissions = getPermissions();
  return $permissions[$privilegeSet]['nb_AddCustomer'];
}

function checkPermissionAddNewOrder($privilegeSet) {
  $permissions = getPermissions();
  return $permissions[$privilegeSet]['nb_AddNewOrder'];
}

function getModuleFromTable($table) {
  switch($table) {
    case 'Addresses':
      $module = 'addresses';
      break;
    case 'BuildItems':
      $module = 'builditem';
      break;
    case 'Customers':
      $module = 'customer';
      break;
    case 'Employees':
      $module = 'employee';
      break;
    case 'Equipment':
    case 'EquipmentModes':
    case 'EquipmentSubModes':
      $module = 'equipment';
      break;
    case 'InventoryItems':
    case 'InventoryItemsToBuildItemsLink':
    case 'InventoryLocationItems':
    case 'InventoryLocations':
    case 'InventoryZones':
      $module = 'inventory';
      break;
    case 'ManCats':
    case 'ManCatSubCatDirections':
    case 'ManCatSubCats':
      $module = 'mancat';
      break;
    case 'MasterJobs':
    case 'OrderContacts':
    case 'OrderItemComponents':
    case 'OrderItems':
    case 'OrderRedo':
    case 'OrderRedoItems':
    case 'Orders':
    case 'OrderShip':
    case 'OrderShipTracking':
    case 'PrepressTicket':
      $module = 'order';
      break;
    case 'PurchaseRequestItems':
    case 'PurchaseRequests':
      $module = 'purchase';
      break;
    case 'Shippers':
    case 'ShipperService':
    case 'ShipperTransitTimes':
      $module = 'shipping';
      break;
    case 'Statuses':
      $module = 'orderstatus';
      break;
    case 'StatusLog':
      $module = 'log';
      break;
    case 'UploadFiles':
      $module = 'home';
      break;
    case 'Vendors':
      $module = 'vendor';
      break;
    default:
      $module = '';
      break;
  }

  return $module;
}

function getPermissions() {
  return array(
    'Admin' => array(
      't_PermissionName' => 'Admin',
      'nb_AccessEverything' => '1',
      't_ModuleAccess' => '',
      'nb_AddNewOrder' => '1',
      'nb_AddCustomer' => '1',
      'nb_ViewOrderPricing' => '1',
      'nb_ViewSetupMenu' => '1'
    ),
    'IT' => array(
      't_PermissionName' => 'IT',
      'nb_AccessEverything' => '1',
      't_ModuleAccess' => '',
      'nb_AddNewOrder' => '1',
      'nb_AddCustomer' => '1',
      'nb_ViewOrderPricing' => '1',
      'nb_ViewSetupMenu' => '1'
    ),
    'Manager' => array(
      't_PermissionName' => 'Manager',
      'nb_AccessEverything' => '1',
      't_ModuleAccess' => '',
      'nb_AddNewOrder' => '1',
      'nb_AddCustomer' => '1',
      'nb_ViewOrderPricing' => '1',
      'nb_ViewSetupMenu' => '1'
    ),
    'Supervisor' => array(
      't_PermissionName' => 'Supervisor',
      'nb_AccessEverything' => '1',
      't_ModuleAccess' => '',
      'nb_AddNewOrder' => '1',
      'nb_AddCustomer' => '1',
      'nb_ViewOrderPricing' => null,
      'nb_ViewSetupMenu' => '1'
    ),
    'Accounting' => array(
      't_PermissionName' => 'Accounting',
      'nb_AccessEverything' => '1',
      't_ModuleAccess' => '',
      'nb_AddNewOrder' => '1',
      'nb_AddCustomer' => '1',
      'nb_ViewOrderPricing' => '1',
      'nb_ViewSetupMenu' => '1'
    ),
    'Sales' => array(
      't_PermissionName' => 'Sales',
      'nb_AccessEverything' => null,
      't_ModuleAccess' => 'addresses,home,waste,customer,order,ordercharge,orderitem,ordership,orderlog,orderstatus,inventory,invitem,map',
      'nb_AddNewOrder' => '1',
      'nb_AddCustomer' => '1',
      'nb_ViewOrderPricing' => '1',
      'nb_ViewSetupMenu' => null
    ),
    'Production' => array(
      't_PermissionName' => 'Production',
      'nb_AccessEverything' => null,
      't_ModuleAccess' => 'home,order,ordercharge,orderitem,ordership,orderlog,orderstatus,inventory,invitem',
      'nb_AddNewOrder' => null,
      'nb_AddCustomer' => null,
      'nb_ViewOrderPricing' => null,
      'nb_ViewSetupMenu' => null
    ),
    'Prepress' => array(
      't_PermissionName' => 'Prepress',
      'nb_AccessEverything' => null,
      't_ModuleAccess' => 'addresses,home,customer,order,ordercharge,orderitem,ordership,orderlog,orderstatus,inventory,invitem',
      'nb_AddNewOrder' => null,
      'nb_AddCustomer' => null,
      'nb_ViewOrderPricing' => null,
      'nb_ViewSetupMenu' => null
    ),
    'Customer Service' => array(
      't_PermissionName' => 'Customer Service',
      'nb_AccessEverything' => null,
      't_ModuleAccess' => 'addresses,home,waste,customer,order,ordercharge,orderitem,ordership,orderlog,orderstatus,inventory,invitem,map',
      'nb_AddNewOrder' => '1',
      'nb_AddCustomer' => '1',
      'nb_ViewOrderPricing' => '1',
      'nb_ViewSetupMenu' => '1'
    ),
    'Inventory' => array(
      't_PermissionName' => 'Inventory',
      'nb_AccessEverything' => null,
      't_ModuleAccess' => 'inventory,invitem',
      'nb_AddNewOrder' => null,
      'nb_AddCustomer' => null,
      'nb_ViewOrderPricing' => null,
      'nb_ViewSetupMenu' => null
    )
  );
}
