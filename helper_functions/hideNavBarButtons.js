(function() {
'use strict';

var hideNavBarButtonsFunction = angular.module('hideNavBarButtonsFunction',[]);

hideNavBarButtonsFunction.factory('hideNavBarButtons', ['$state',
function($state){
  return function() {
    var y = 0;
    for(var x = 0; x < $state.current.data.authentication.permissions.length; x++) {
      if($state.current.data.authentication.permissions[x].t_PermissionName === $state.current.data.authentication.privilegeSet) {
        if($state.current.data.authentication.permissions[x].nb_AccessEverything === "1") {
          for(y in $state.current.data.showButtons) {
            $state.current.data.showButtons[y] = true;
          }
          return;
        } else {
          var permissionArray = $state.current.data.authentication.permissions[x].t_ModuleAccess.split(',');
          for(y in $state.current.data.showButtons) {
            $state.current.data.showButtons[y] = false;
          }
          for(y = 0; y < permissionArray.length; y++) {
            $state.current.data.showButtons[permissionArray[y]] = true;
          }
          return;
        }
      }
    }
  };
}
]);
}());
