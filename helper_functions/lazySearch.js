(function() {
'use strict';

var LazySearchFunction = angular.module('LazySearchFunction',[]);

LazySearchFunction.factory('lazySearch', ['$state',
    function($state){
      return function(x) {
        var searchParams = $state.current.data.search.searchInput.toLowerCase().split(' ');
        var text = JSON.stringify(x);

        for(var key in x) {
          text = text.replace(key, "");
        }

        text = text.toLowerCase();

        for(var y = 0; y < searchParams.length; y++) {
          if(text.indexOf(searchParams[y]) < 0) {
            return false;
          }
        }
        return true;
      };
    }
]);
}());
