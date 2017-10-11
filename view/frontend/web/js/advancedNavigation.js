define([
    'jquery',
    'uiComponent',
    'ko'
], function ($, Component, ko) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'GoMage_Navigation/advancedNavigation',
            showAjaxNavigation: ko.observable(false),
            active_filters: ko.observableArray([]),
            active_filters_count: ko.observable(),
            clear_url: ko.observable(),
            clear_label: ko.observable(),
            add_filter_title: ko.observable(),
            shopping_opt_title: ko.observable(),
            filters: ko.observable()
        },

        initialize: function () {

            this._super();
        },
    });
});
