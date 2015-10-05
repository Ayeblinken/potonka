(function() {
'use strict';

var RestServices = angular.module('RestServices',['ngResource']);

RestServices.factory('getRestResourceSvc', ['$resource',
    function($resource){
        return {
            rest: $resource('http://'+location.hostname+'/indyweb/data/rest/rest_api/rest/:tblName/:id',null,{update:{method:'put'},insert:{method:'post'}}),
            restDelete: $resource('http://'+location.hostname+'/indyweb/data/rest/rest_api/rest/:tableName/:id//\/',{format:'json'}),
            restQB: $resource('http://'+location.hostname+'/indyweb/data/restqb/restqb_api/restqb/:tblName/:id',null,{update:{method:'put'},insert:{method:'post'}}),
            restQBDelete: $resource('http://'+location.hostname+'/indyweb/data/restqb/restqb_api/restqb/:tableName/:id//\/',{format:'json'}),
        };
    }
]);
}());
