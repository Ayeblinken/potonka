(function() {
'use strict';

var ConstantsModule = angular.module('ConstantsModule',[])

.constant('API_URL','https://'+location.hostname+'/shopbot/data')

.constant('floatTheadFunc', function($timeout, $log) {
    var directive = {
        require: '?ngModel',
        link: link,
        restrict: 'A'
    };
    return directive;

    function link(scope, element, attrs, ngModel) {
        $(element).floatThead(scope.$eval(attrs.floatThead));

        if (ngModel) {
            scope.$watch(attrs.ngModel, function () {
                $(element).floatThead('reflow');
            }, true);
        } else {
            $log.info('floatThead: ngModel not provided!');
        }

        element.bind('update', function () {
            $timeout(function() {
                $(element).floatThead('reflow');
            }, 0);
        });

        element.bind('$destroy', function() {
            $(element).floatThead('destroy');
        });
    }
});


}());
