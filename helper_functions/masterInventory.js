(function() {
'use strict';

var MasterInventoryFunction = angular.module('MasterInventoryFunction',[]);

MasterInventoryFunction.factory('getMasterInventory', ['$state','$interval','getInventoryItemResourceSvc','$rootScope',
    function($state,$interval,getInventoryItemResourceSvc,$rootScope){
        return function() {
          if($state.current.data.masterInventory.unfilteredInventoryTblData) {
            if($state.current.data.updateFlags.invItemList) {
              $state.current.data.updateFlags.invItemList = false;
              getInventoryItemResourceSvc.getInventoryItemTblData.query(function(response) {
                $state.current.data.masterInventory.unfilteredInventoryTblData = response;
                if($state.current.data.invitem && $state.current.data.invitem.inActive === "!1") {
                  $rootScope.$broadcast('MasterInventory');
                }
              });

              getInventoryItemResourceSvc.getInActiveInventoryItemTblData.query(function(response) {
                $state.current.data.masterInventory.unfilteredInactiveInventoryTblData = response;
                if($state.current.data.invitem && $state.current.data.invitem.inActive === "1") {
                  $rootScope.$broadcast('MasterInventory');
                }
              });
            } else {
              $rootScope.$broadcast('MasterInventory');
            }
          } else {
            getInventoryItemResourceSvc.getInventoryItemTblData.query(function(response) {
              $state.current.data.masterInventory.unfilteredInventoryTblData = response;
              if($state.current.data.invitem && $state.current.data.invitem.inActive === "!1") {
                $rootScope.$broadcast('MasterInventory');
              }
            });

            getInventoryItemResourceSvc.getInActiveInventoryItemTblData.query(function(response) {
              $state.current.data.masterInventory.unfilteredInactiveInventoryTblData = response;
              if($state.current.data.invitem && $state.current.data.invitem.inActive === "1") {
                $rootScope.$broadcast('MasterInventory');
              }
            });

            if(!$state.current.data.intervalsMade.invItemList) {
              $state.current.data.intervalsMade.invItemList = true;
              $interval(function() {
                if($state.is('indyAng.inv.invitem') || $state.is('indyAng.ven.edit.invitem') || $state.is('indyAng.pur.new.invitem')) {
                  getInventoryItemResourceSvc.getInventoryItemTblData.query(function(response) {
                    $state.current.data.masterInventory.unfilteredInventoryTblData = response;
                    if($state.current.data.invitem && $state.current.data.invitem.inActive === "!1") {
                      $rootScope.$broadcast('MasterInventory');
                    }
                  });

                  getInventoryItemResourceSvc.getInActiveInventoryItemTblData.query(function(response) {
                    $state.current.data.masterInventory.unfilteredInactiveInventoryTblData = response;
                    if($state.current.data.invitem && $state.current.data.invitem.inActive === "1") {
                      $rootScope.$broadcast('MasterInventory');
                    }
                  });
                } else if($state.is('indyAng.builditem.list')) {
                    $state.current.data.masterInventory.unfilteredInventoryTblData = getInventoryItemResourceSvc.getInventoryItemTblData.query();

                    $state.current.data.masterInventory.unfilteredInactiveInventoryTblData = getInventoryItemResourceSvc.getInActiveInventoryItemTblData.query();
                } else {
                  $state.current.data.updateFlags.invItemList = true;
                }

              }, 300000);
            }
          }
        };
    }
]);
}());
