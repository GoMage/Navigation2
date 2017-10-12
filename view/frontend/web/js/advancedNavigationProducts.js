define([
    'jquery',
    'uiComponent',
    'ko'
], function ($, Component, ko) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'GoMage_Navigation/advancedNavigationProducts',
            showTemplate: ko.observable(false),
            products: ko.observableArray([])
        },

        initialize: function () {

            this._super();
        },
        
        populateModel: function (data) {

            this.showTemplate(true);
            this.products(data.products.products);
        }
    });
});
