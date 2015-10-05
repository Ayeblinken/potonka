(function() {
'use strict';

var restEmailServices = angular.module('restEmailServices',['ngResource']);

restEmailServices.factory('getRestEmailResourceSvc', ['$resource',
    function($resource){
        return {
            restAngEmail: $resource('https://'+location.hostname+'/shopbot/data/emailshopbot/emailang_api/restAngEmail/',null,{sendEmail:{method:'post'}})
        };
    }
]);
}());



