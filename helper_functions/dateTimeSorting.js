(function() {
'use strict';

var dateTimeSortingFunctions = angular.module('dateTimeSortingFunctions',[]);

dateTimeSortingFunctions.factory('dateTimeSorting', ['$state',
function($state){
  return {
    timeSorting: function(x, y) {
      if($state.current.data.sort.reverse) {
        if(!x[$state.current.data.sort.sortField]) {
          return -1;
        } else if(!y[$state.current.data.sort.sortField]) {
          return 1;
        }
      } else {
        if(!x[$state.current.data.sort.sortField]) {
          return 1;
        } else if(!y[$state.current.data.sort.sortField]) {
          return -1;
        }
      }

      //2:00 PM
      var xSplit = x[$state.current.data.sort.sortField].split(":"), ySplit = y[$state.current.data.sort.sortField].split(":"); //["2", "00 PM"]
      var xSplit2 = xSplit[1].split(" "), ySplit2 = ySplit[1].split(" "); //["00", "PM"]
      var xHour = xSplit[0]*1, yHour = ySplit[0]*1;
      var xMinute = xSplit2[0]*1, yMinute = ySplit2[0]*1;
      var xAP = xSplit2[1], yAP = ySplit2[1];

      if(xAP === "AM" && yAP === "PM") {
        return -1;
      } else if(xAP === "PM" && yAP === "AM") {
        return 1;
      }

      if(xHour === 12 && yHour !== 12) {
        return -1;
      } else if(xHour !== 12 && yHour === 12) {
        return 1;
      }

      if(xHour < yHour) {
        return -1;
      } else if(xHour > yHour) {
        return 1;
      }

      if(xMinute < yMinute) {
        return -1;
      } else if(xMinute > yMinute) {
        return 1;
      }

      return 0;
    },
    dateSorting: function(x, y) {
      if($state.current.data.sort.reverse) {
        if(!x[$state.current.data.sort.sortField]) {
          return -1;
        } else if(!y[$state.current.data.sort.sortField]) {
          return 1;
        }
      } else {
        if(!x[$state.current.data.sort.sortField]) {
          return 1;
        } else if(!y[$state.current.data.sort.sortField]) {
          return -1;
        }
      }

      //1-20-2014
      var xSplit = x[$state.current.data.sort.sortField].split("-"), ySplit = y[$state.current.data.sort.sortField].split("-"); //["1", "20", "2014"]

      if(xSplit[2]*1 < ySplit[2]*1) {
        return -1;
      } else if(xSplit[2]*1 > ySplit[2]*1) {
        return 1;
      }

      if(xSplit[0]*1 < ySplit[0]*1) {
        return -1;
      } else if(xSplit[0]*1 > ySplit[0]*1) {
        return 1;
      }

      if(xSplit[1]*1 < ySplit[1]*1) {
        return -1;
      } else if(xSplit[1]*1 > ySplit[1]*1) {
        return 1;
      }

      return 0;
    },
    dateTimeSorting: function(x, y) {

    }
  };
}
]);
}());
