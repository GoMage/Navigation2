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
                    advancedNavigation.active_filters(data.navigation.active_filters);
                    advancedNavigation.active_filters_count(data.navigation.active_filters_count);
                    advancedNavigation.active_filters_count(data.navigation.active_filters_count);
                    advancedNavigation.clear_label(data.navigation.clear_label);
                    advancedNavigation.clear_url(data.navigation.clear_url);
                    advancedNavigation.add_filter_title(data.navigation.add_filter_title);
                    advancedNavigation.shopping_opt_title(data.navigation.shopping_opt_title);
                    advancedNavigation.filters(data.navigation.filters);
                });
            }
        });
    }
);