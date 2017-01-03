/**
 * angular-strap
 * @version v2.1.6 - 2015-01-11
 * @link http://mgcrea.github.io/angular-strap
 * @author Olivier Louvignes (olivier@mg-crea.com)
 * @license MIT License, http://www.opensource.org/licenses/MIT
 */
(function(window, document, undefined) {
'use strict';

// Source: alert.tpl.js
angular.module('mgcrea.ngStrap.alert').run(['$templateCache', function($templateCache) {

  $templateCache.put('alert/alert.tpl.html', '<div class="alert" ng-class="[type ? \'alert-\' + type : null]"><button type="button" class="close" ng-if="dismissable" ng-click="$hide()">&times;</button> <strong ng-bind="title"></strong>&nbsp;<span ng-bind-html="content"></span></div>');

}]);

// Source: aside.tpl.js
angular.module('mgcrea.ngStrap.aside').run(['$templateCache', function($templateCache) {

  $templateCache.put('aside/aside.tpl.html', '<div class="aside" tabindex="-1" role="dialog"><div class="aside-dialog"><div class="aside-content"><div class="aside-header" ng-show="title"><button type="button" class="close" ng-click="$hide()">&times;</button><h4 class="aside-title" ng-bind="title"></h4></div><div class="aside-body" ng-bind="content"></div><div class="aside-footer"><button type="button" class="btn btn-default" ng-click="$hide()">Close</button></div></div></div></div>');

}]);

// Source: datepicker.tpl.js
angular.module('mgcrea.ngStrap.datepicker').run(['$templateCache', function($templateCache) {

  $templateCache.put('datepicker/datepicker.tpl.html', '<div class="datepicker datepicker-dropdown dropdown-menu" ng-class="\'datepicker-mode-\' + $mode" style="width: 230px !important"><table class="table-condensed" style="table-layout: fixed; height: 100%; width: 100%"><thead><tr><th class="prev" ng-click="$selectPane(-1)" tabindex="-1"></th><th colspan="{{ rows[0].length - 2 }} " class="datepicker-switch" tabindex="-1" ng-click="$toggleMode()" ng-bind="title"></th><th class="next" tabindex="-1" ng-click="$selectPane(+1)"></th></tr><tr ng-show="showLabels" ng-bind-html="labels"></tr></thead><tbody><tr ng-repeat="(i, row) in rows" height="{{ 100 / rows.length }}%"><td class="day" tabindex="-1" ng-repeat="(j, el) in row" ng-click="$select(el.date)" ng-class="{\'btn-primary\': el.selected, \'btn-info btn-today\': el.isToday && !el.selected}" ng-click="$select(el.date)" ng-disabled="el.disabled"><p ng-class="{\'text-muted\': el.muted}" ng-bind="el.label"></p></td></tr></tbody></table></div>');

    $templateCache.put('datepicker/datepicker-small.tpl.html', '<div class="datepicker datepicker-dropdown dropdown-menu" ng-class="\'datepicker-mode-\' + $mode" style="width: 195px !important"><table class="table-condensed" style="table-layout: fixed; height: 100%; width: 100%"><thead><tr><th class="prev" ng-click="$selectPane(-1)" tabindex="-1"></th><th colspan="{{ rows[0].length - 2 }} " class="datepicker-switch" tabindex="-1" ng-click="$toggleMode()" ng-bind="title"></th><th class="next" tabindex="-1" ng-click="$selectPane(+1)"></th></tr><tr ng-show="showLabels" ng-bind-html="labels"></tr></thead><tbody><tr ng-repeat="(i, row) in rows" height="{{ 100 / rows.length }}%"><td class="day" tabindex="-1" ng-repeat="(j, el) in row" ng-click="$select(el.date)" ng-class="{\'btn-primary\': el.selected, \'btn-info btn-today\': el.isToday && !el.selected}" ng-click="$select(el.date)" ng-disabled="el.disabled"><p ng-class="{\'text-muted\': el.muted}" ng-bind="el.label"></p></td></tr></tbody></table></div>');

}]);

// Source: dropdown.tpl.js
angular.module('mgcrea.ngStrap.dropdown').run(['$templateCache', function($templateCache) {

  $templateCache.put('dropdown/dropdown.tpl.html', '<ul tabindex="-1" class="dropdown-menu" role="menu"><li role="presentation" ng-class="{divider: item.divider}" ng-repeat="item in content"><a role="menuitem" tabindex="-1" ng-href="{{item.href}}" ng-if="!item.divider && item.href" target="{{item.target || \'\'}}" ng-bind="item.text"></a> <a role="menuitem" tabindex="-1" href="javascript:void(0)" ng-if="!item.divider && item.click" ng-click="$eval(item.click);$hide()" ng-bind="item.text"></a></li></ul>');

}]);

// Source: modal.tpl.js
angular.module('mgcrea.ngStrap.modal').run(['$templateCache', function($templateCache) {

  $templateCache.put('modal/modal.tpl.html', '<div class="modal" tabindex="-1" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header" ng-show="title"><button type="button" class="close" ng-click="$hide()">&times;</button><h4 class="modal-title" ng-bind="title"></h4></div><div class="modal-body" ng-bind="content"></div><div class="modal-footer"><button type="button" class="btn btn-default" ng-click="$hide()">Close</button></div></div></div></div>');

}]);

// Source: popover.tpl.js
angular.module('mgcrea.ngStrap.popover').run(['$templateCache', function($templateCache) {

  $templateCache.put('popover/popover.tpl.html', '<div class="popover"><div class="arrow"></div><h3 class="popover-title" ng-bind="title" ng-show="title"></h3><div class="popover-content" ng-bind="content"></div></div>');

}]);

// Source: select.tpl.js
angular.module('mgcrea.ngStrap.select').run(['$templateCache', function($templateCache) {

  $templateCache.put('select/select.tpl.html', '<ul tabindex="-1" class="select dropdown-menu" ng-show="$isVisible()" role="select"><li ng-if="$showAllNoneButtons"><div class="btn-group" style="margin-bottom: 5px; margin-left: 5px"><button type="button" class="btn btn-default btn-xs" ng-click="$selectAll()">{{$allText}}</button> <button type="button" class="btn btn-default btn-xs" ng-click="$selectNone()">{{$noneText}}</button></div></li><li role="presentation" ng-repeat="match in $matches" ng-class="{active: $isActive($index)}"><a style="cursor: default" role="menuitem" tabindex="-1" ng-click="$select($index, $event)"><i class="{{$iconCheckmark}} pull-right" ng-if="$isMultiple && $isActive($index)"></i> <span ng-bind="match.label"></span></a></li></ul>');

}]);

// Source: tab.tpl.js
angular.module('mgcrea.ngStrap.tab').run(['$templateCache', function($templateCache) {

  $templateCache.put('tab/tab.tpl.html', '<ul class="nav" ng-class="$navClass" role="tablist"><li ng-repeat="$pane in $panes track by $index" ng-class="$index == $panes.$active ? $activeClass : \'\'"><a role="tab" data-toggle="tab" ng-click="$setActive($index)" data-index="{{ $index }}" ng-bind-html="$pane.title"></a></li></ul><div ng-transclude class="tab-content"></div>');

}]);

// Source: timepicker.tpl.js
angular.module('mgcrea.ngStrap.timepicker').run(['$templateCache', function($templateCache) {

  $templateCache.put('timepicker/timepicker-small.tpl.html', '<div class="bootstrap-timepicker-widget dropdown-menu"><table><tbody><tr><td><button type="button" class="timepicker-increment"  tabindex="-1" ng-click="$arrowAction(-1, 0)"><i class="fa fa-chevron-up"></i></button></td><td class="separator">&nbsp;</td><td><button type="button" class="timepicker-increment" tabindex="-1"ng-click="$arrowAction(-1, 1)" ><i class="fa fa-chevron-up"></i></button></td></tr><tr ng-repeat="(i, row) in rows"><td><button tabindex="-1" style="width: 100%" type="button" class="bootstrap-timepicker-minute btn btn-default" ng-class="{\'btn-primary\': row[0].selected}" ng-click="$select(row[0].date, 0)" ng-disabled="row[0].disabled"><span ng-class="{\'text-muted\': row[0].muted}" ng-bind="row[0].label"></span></button></td><td class="separator">:</td><td><button tabindex="-1" ng-if="row[1].date" style="width: 100%" type="button" class="bootstrap-timepicker-minute btn btn-default" ng-class="{\'btn-primary\': row[1].selected}" ng-click="$select(row[1].date, 1)" ng-disabled="row[1].disabled"><span ng-class="{\'text-muted\': row[1].muted}" ng-bind="row[1].label"></span></button></td></tr><tr><td><button tabindex="-1" type="button" class="timepicker-decrement" ng-click="$arrowAction(1, 0)"><i class="fa fa-chevron-down"></i></button></td><td class="separator"></td><td><button tabindex="-1" type="button"  class="timepicker-decrement" ng-click="$arrowAction(1, 1)"><i class="fa fa-chevron-down"></i></button></td></tr></tbody></table></div>');

}]);

// Source: tooltip.tpl.js
angular.module('mgcrea.ngStrap.tooltip').run(['$templateCache', function($templateCache) {

  $templateCache.put('tooltip/tooltip.tpl.html', '<div class="tooltip in" ng-show="title"><div class="tooltip-arrow"></div><div class="tooltip-inner" ng-bind="title"></div></div>');

}]);

// Source: typeahead.tpl.js
angular.module('mgcrea.ngStrap.typeahead').run(['$templateCache', function($templateCache) {

  //$templateCache.put('typeahead/typeahead.tpl.html', '<ul tabindex="-1" class="typeahead dropdown-menu" ng-show="$isVisible()" role="select"><li role="presentation" ng-repeat="match in $matches" ng-class="{active: $index == $activeIndex}"><a role="menuitem" tabindex="-1" ng-click="$select($index, $event)" ng-bind="match.label"></a></li></ul>');
  $templateCache.put('typeahead/typeahead.tpl.html', '<ul tabindex="-1" style="width: 255px;" class="typeahead dropdown-menu search" ng-show="$isVisible()" role="select"><li role="presentation" ng-repeat="match in $matches" ng-class="{active: $index == $activeIndex}" ng-click="$select($index, $event)"><div><p role="menuitem" tabindex="-1" ng-bind="match.label"></p><p class="second-data">Empresa</p></div></li></ul>');

}]);


})(window, document);
