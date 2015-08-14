/*jshint jquery:true*/
define([
    "jquery",
    "./filter",
    "./params",
    "jquery/ui",
    "mage/translate"
], function ($, Filter, Params) {
    "use strict";

    $.widget('gomage.navigation', {
        options: {
            baseUrl: null,
            filters: null,
            filterControl: '[data-role="navigation-filter"]',
            navigationContainer: '#gan-navigation',
            productsContainer: 'div.main:first',
            loader: 'body', /* TODO: create own loader */
        },

        _create: function () {

            if (!this.options.baseUrl) {
                this.options.baseUrl = window.location.href;
            }

            var elements = $(this.options.filterControl);

            this._bind(elements);

            this.options.filters = elements.map(function (index, element) {
                return new Filter(element);
            });

        },

        _bind: function (elements) {
            for (var index = 0; index < elements.length; index++) {
                var element = $(elements[index]);
                if (element.is("select")) {
                    element.on('change', {element: element}, $.proxy(this._processFilter, this));
                } else {
                    element.on('click', {element: element}, $.proxy(this._processFilter, this));
                }
            }
        },

        _processFilter: function (event) {
            event.preventDefault();

            var element = event.data.element;
            var filter = new Filter(element);
            var params = this._getParams();

            if (filter.isActive()) {
                params.unset(filter.getParam(), filter.getValue());
            } else {
                params.set(filter.getParam(), filter.getValue());
            }

            if (filter.isAjax()) {
                this._ajaxFilter(this.options.baseUrl, params);
            } else {
                $.mage.redirect(this.options.baseUrl + '?' + params.toUrlParams());
            }
        },

        _ajaxFilter: function (url, params, successCallback) {
            $.ajax({
                url: url,
                type: 'get',
                cache: true,
                data: params.toUrlParams(),
                beforeSend: (this._ajaxSend).bind(this),
                complete: (this._ajaxComplete).bind(this),
                success: function (response) {
                    if (successCallback) {
                        successCallback.call(this, response);
                    }

                    $(this.options.navigationContainer).html(response.navigation);
                    $(this.options.productsContainer).html(response.products);

                }.bind(this)
            });

        },

        _getParams: function () {
            var params = new Params();
            this.options.filters.each(function (index, filter) {
                if (filter.isActive()) {
                    params.set(filter.getParam(), filter.getValue());
                }
            });

            return params;
        },

        /**
         * Callback function for before ajax send event(global)
         * @private
         */
        _ajaxSend: function () {
            $(this.options.loader).trigger('processStart');
        },

        /**
         * Callback function for ajax complete event(global)
         * @private
         */
        _ajaxComplete: function () {
            $(this.options.loader).trigger('processStop');
        }

    });

    return $.gomage.navigation;
});