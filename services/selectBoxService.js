(function() {
  'use strict';

  var SelectBoxServices = angular.module('SelectBoxServices',['ngResource']);

  SelectBoxServices.factory('getSelectBoxResourceSvc', ['$resource',
  function($resource){
    return {
      getActiveCustomers: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/activeCustomerNames/',{format:'json'}),
      getActiveCustomersWithSales: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/activeCustomerNamesWithSales/',{format:'json'}),
      getActiveCustomersWithOrderCount: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/activeCustomerNamesWithOrderCount/',{format:'json'}),
      getShipperServices: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/shipperServices',{format:'json'}),
      getMachineList: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/machineList',{format:'json'}),
      getStatusList: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/statusList',{format:'json'}),
      getOrderContactsFromCustomerID: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/orderContactsFromCustomerID',{format:'json'}),
      getEmployeeList: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/employeeList',{format:'json'}),
      getEmployeeListCustomerApproval: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/employeeListCustomerApproval',{format:'json'}),
      getEmployeeSalesList: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/employeeSalesList',{format:'json'}),
      getEmployeePrepressList: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/employeePrepressList',{format:'json'}),
      getProductBuildsList: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/productBuildsList',{format:'json'}),
      getBuildItemsList: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/buildItemsList',{format:'json'}),
      getProductBuildsCategoryList: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/productBuildsCategoryList',{format:'json'}),
      getProductBuildsListByCategory: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/productBuildsListByCategory',{format:'json'}),
      getRelatedBuilditems: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/relatedBuildItems',{format:'json'}),
      getInventoryItemList: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/inventoryItemList',{format:'json'}),
      getEquipmentSubModes: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/equipmentSubModes',{format:'json'}),
      getBuildItemDirections: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/buildItemDirections',{format:'json'}),
      getActiveMasterJobsByCustomer: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/activeMasterJobsByCustomer',{format:'json'}),
      getCustomerNotes: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/customerNotes',{format:'json'}),
      getCustomerCreditHoldInfo: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/customerCreditHoldInfo',{format:'json'}),
      getCreditHoldEmployeeList: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/creditHoldEmployeeList',{format:'json'}),
      getDistinctVendorCompanyName: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/vendorDistinctCompanyName',{format:'json'}),
      getPurchaseRequestItemFrmInvtItemID: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/purchaseRequestItemFromInvtItemID/',{format:'json'}),
      getVendorsByListID: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/vendorsByListID/',{format:'json'}),
      getEmployeeAdminManagerAccounting: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/employeeAdminManagerAccounting/',{format:'json'}),
      getEmployeeSupervisorManagerAccounting: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/employeeSupervisorManagerAccounting/',{format:'json'}),
      getPrintMaterialBuilditemsByProduct: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/printMaterialBuildItemsByProduct/',{format:'json'}),
      getPrintMaterialInvItems: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/printMaterialInvItems/',{format:'json'}),
      getEmployeeDirectory: $resource('https://'+location.hostname+'/shopbot/data/selectBoxes/selectboxes_api/employeeDirectory/',{format:'json'})
    };
  }
  ]);
}());
