(function() {
'use strict';

var OrderInchesFeetToFeet = angular.module('OrderInchesFeetToFeet',[]);

OrderInchesFeetToFeet.factory('orderInchesFeetToFeet', ['$state','$filter',
    function($state,$filter){
      return function(x) {
        var inputArray = x.split(' ');
        if($state.current.data.order.showInInches) {
          var newVal = Math.floor(inputArray[0].substring(0, inputArray[0].length-1) / 12) + "'";
          var newInches = ($filter('number')(inputArray[0].substring(0, inputArray[0].length-1) % 12, 3)*1).toString();
          if(newInches !== "0") {
            newVal += " " + newInches + '"';
          }
          return newVal;
        } else {
          return x;
        }
      };
    }
]);
}());
