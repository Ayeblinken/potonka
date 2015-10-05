(function() {
  'use strict';

  var PhoneServices = angular.module('PhoneServices',['ngResource']);

  PhoneServices.factory('getPhoneResourceSvc', ['$resource',
  function($resource){
    return {
      makePhoneCall: $resource('https://'+location.hostname+'/shopbot/data/phone/phone_api/makePhoneCall/',{format:'json'},{update:{method:'put'}})
    };
  }
  ]);
}());
