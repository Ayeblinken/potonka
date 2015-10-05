(function() {
'use strict';

var CheckPermissionsFunction = angular.module('CheckPermissionsFunction',[]);

CheckPermissionsFunction.factory('checkPermissions', ['$state',
function($state){
  return function(moduleName) {
    if($state.current.data.authentication.privilegeSet === "") {
      return;
    }
    for(var x = 0; x < $state.current.data.authentication.permissions.length; x++) {
      if($state.current.data.authentication.permissions[x].t_PermissionName === $state.current.data.authentication.privilegeSet) {

        if($state.current.data.authentication.permissions[x].nb_AddCustomer === "1") {
          $state.current.data.hideFunctionality.addCustomerButton = false;
        } else {
          $state.current.data.hideFunctionality.addCustomerButton = true;
        }

        if($state.current.data.authentication.permissions[x].nb_AddNewOrder === "1") {
          $state.current.data.hideFunctionality.addOrderButton = false;
        } else {
          $state.current.data.hideFunctionality.addOrderButton = true;
        }

        if($state.current.data.authentication.permissions[x].nb_ViewOrderPricing === "1") {
          $state.current.data.hideFunctionality.viewOrderPricing = false;
        } else {
          $state.current.data.hideFunctionality.viewOrderPricing = true;
        }

        if($state.current.data.authentication.permissions[x].nb_ViewSetupMenu === "1") {
          $state.current.data.hideFunctionality.viewSetupMenu = false;
        } else {
          $state.current.data.hideFunctionality.viewSetupMenu = true;
        }

        if($state.current.data.authentication.permissions[x].nb_AccessEverything === "1") {
          return true;
        } else if($state.current.data.authentication.permissions[x].t_ModuleAccess.indexOf(moduleName) >= 0) {
          return true;
        } else {
          if($state.current.data.authentication.privilegeSet === "Inventory") {
            $state.go('indyAng.inv.invitem');
          } else {
            $state.go('indyAng.home.active.inProcess');
          }
        }
      }
    }
    if($state.current.data.authentication.privilegeSet === "Inventory") {
      $state.go('indyAng.inv.invitem');
    } else {
      $state.go('indyAng.home.active.inProcess');
    }
  };
}
]);

CheckPermissionsFunction.factory('checkPermissionsNoRedirect', ['$state',
function($state){
  return function(moduleName) {
    if($state.current.data.authentication.privilegeSet === "") {
      return false;
    }
    for(var x = 0; x < $state.current.data.authentication.permissions.length; x++) {
      if($state.current.data.authentication.permissions[x].t_PermissionName === $state.current.data.authentication.privilegeSet) {
        if($state.current.data.authentication.permissions[x].nb_AccessEverything === "1" || $state.current.data.authentication.permissions[x].t_ModuleAccess.indexOf(moduleName) >= 0) {
          return true;
        } else {
          return false;
        }
      }
    }
  };
}
]);

}());
