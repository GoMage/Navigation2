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
            filters: {
                data: ko.observableArray([])
            },
            test: 'test'
        },

        initialize: function () {

            this._super();
        },
    });
});