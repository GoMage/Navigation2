define([
    'jquery',
    'uiComponent',
    'ko',
    'uiRegistry'
], function ($, Component, ko, uiRegistry) {
        'use strict';

        return Component.extend({

            initialize: function () {

                this._super();
            },

            populateModel: function (data) {

                /*this.customerName = ko.observableArray([]);
                this.customerData = ko.observable('test_123');
                this.renderState = ko.observable(true);*/

                uiRegistry.get('advancedNavigation', function (advancedNavigation) {
                    advancedNavigation.showAjaxNavigation(true);
                    advancedNavigation.filters.data(data);
                });
            }
        });
    }
);