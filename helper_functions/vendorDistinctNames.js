(function() {
'use strict';

var VendorDistinctNamesFunction = angular.module('VendorDistinctNamesFunction',[]);

VendorDistinctNamesFunction.factory('getVendorDistinctNames', ['$state','getSelectBoxResourceSvc','$interval','$rootScope',
    function($state,getSelectBoxResourceSvc,$interval,$rootScope){
        return function(x) {
          if($state.current.data.masterInventory.vendorDistinctCompanyName.length === 0) {
            getSelectBoxResourceSvc.getDistinctVendorCompanyName.query(function(response) {
              $state.current.data.masterInventory.vendorDistinctCompanyName = response;
              $state.current.data.masterInventory.vendorDistinctCompanyNameAll = [{t_CompanyName: "All Companies"}].concat(response);
              $rootScope.$broadcast('DistinctVendorNames');
            });
            $state.current.data.intervalsMade.distinctVendorNames = true;
            $interval(function() {
              if($state.is('indyAng.inv.invitem') || $state.is('indyAng.ven.list') || $state.is('indyAng.pur.vendor') || $state.is('indyAng.builditem.list')) {
                getSelectBoxResourceSvc.getDistinctVendorCompanyName.query(function(response) {
                  $state.current.data.masterInventory.vendorDistinctCompanyName = response;
                  $state.current.data.masterInventory.vendorDistinctCompanyNameAll = [{t_CompanyName: "All Companies"}].concat(response);
                  $rootScope.$broadcast('DistinctVendorNames');
                });
              } else {
                $state.current.data.updateFlags.distinctVendorNames = true;
              }

            }, 600000);
          } else {
            if($state.current.data.updateFlags.distinctVendorNames) {
              $state.current.data.updateFlags.distinctVendorNames = false;
              getSelectBoxResourceSvc.getDistinctVendorCompanyName.query(function(response) {
                $state.current.data.masterInventory.vendorDistinctCompanyName = response;
                $state.current.data.masterInventory.vendorDistinctCompanyNameAll = [{t_CompanyName: "All Companies"}].concat(response);
                $rootScope.$broadcast('DistinctVendorNames');
              });
            }
          }
        };
    }
]);
}());
