(function() {
'use strict';

var OrderInchesFeetToInches = angular.module('OrderInchesFeetToInches',[]);

OrderInchesFeetToInches.factory('orderInchesFeetToInches', ['$state','$filter',
    function($state,$filter){
      return function(x) {
        var answer = "";
        if($state.current.data.order.showInInches) {
          answer = x.substring(0, x.length-1);
        } else {
          var xArray = x.split(' ');

          if(xArray.length === 1) {
            answer = xArray[0].substring(0, xArray[0].length-1)*12;
          } else {
            answer = xArray[0].substring(0, xArray[0].length-1)*12 + xArray[1].substring(0, xArray[1].length-1)*1;
          }
        }

        return (($filter('number')(answer, 3)).replace(",", "")*1).toString();
      };
    }
]);
}());
