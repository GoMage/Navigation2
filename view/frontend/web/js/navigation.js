/*jshint jquery:true*/
define([
    "jquery",
    "./filter",
    "./params",
    "./slider",
    'uiRegistry',
    "jquery/ui",
    "mage/translate"
], function ($, Filter, Params, SliderPrice, uiRegistry) {
    "use strict";

    $.widget('gomage.navigation', {
        options: {
            baseUrl: null,
            filters: null,
            filterControl: '[data-role="navigation-filter"]',
            navigationContainer: '[data-role="navigation"]',
            productsContainer: 'div.main:first',
            authentication: 'div.block-authentication',
            productListContainer: 'ol.product-items',
            productToolbarContainer: 'div.toolbar-products',
            loader: 'body'
        },

        _create: function () {
            if (!this.options.baseUrl) {
                this.options.baseUrl = window.location.href;
            }
            this._initFilters();
            $(this.options.navigationContainer).on({
                'show.navigation': $.proxy(this._initFilters, this)
            });
        },

        _initFilters: function () {
            var elements = $(this.options.filterControl);
            this._bind(elements);
            this.options.filters = elements.map(function (index, element) {
                return new Filter(element);
            });
        },

        /**
         *
         * @param elements
         * @private
         */
        _bind: function (elements) {
            for (var index = 0; index < elements.length; index++) {
                var element = $(elements[index]);

                switch (element.attr('data-type')) {
                    case 'slider':
                        var slider = new SliderPrice(element);
                        var self = this;
                        element.slider({
                            from: slider.getFrom(),
                            to: slider.getTo(),
                            step: slider.getStep(),
                            smooth: true,
                            round: 0,
                            dimension: slider.getDimension(),
                            skin: slider.getSkin(),
                            callback: function (value) {
                                var el = $('#' + slider.getCode());
                                el.attr("data-value", value.replace(';', '-'));
                                self._runProcessFilterForElement(el);
                            }
                        });
                        break;
                    case 'button-more':

                        this._showButtonMore(element);
                        element.unbind('click');
                        element.on('click', {element: element}, $.proxy(this._processProductMore, this));

                        break;
                    case 'select':
                        element.unbind('change');
                        element.on('change', {element: element}, $.proxy(this._processFilter, this));
                        break;
                    default:
                        element.unbind('click');
                        element.on('click', {element: element}, $.proxy(this._processFilter, this));

                }

            }
        },

        /**
         *
         * @param element
         * @private
         */
        _showButtonMore: function (element) {
            $(this.options.productToolbarContainer+':eq( 1 )').hide();
            $('button.'+element.attr('class')+':eq( 1 )').show();
        },

        /**
         *
         * @param element
         * @private
         */
        _processProductMore: function (element) {
            var element = $('#' + element.currentTarget.id);
            var filter = new Filter(element);
            var params = this._getParams();
            var data = filter.getValue().split("=");
            if (data) {
                for (var i = 0; i < data.length; i++) {
                    i++; params.set(data[i - 1], data[i]);
                }

                $.ajax({
                    url: this.options.baseUrl,
                    type: 'get',
                    cache: true,
                    data: params.toUrlParams(),
                    beforeSend: (this._ajaxSend).bind(this),
                    complete: (this._ajaxComplete).bind(this),
                    success: function (response) {
                        var oldProduct = $(this.options.productListContainer).html();
                        $(this.options.productsContainer).html(response.products);
                        var newProduct = $(this.options.productListContainer).html();
                        $(this.options.productListContainer).html(oldProduct + newProduct);
                        this._showButtonMore(element);
                    }.bind(this)
                });
            }
        },


        /**
         *
         * @param element
         * @private
         */
        _runProcessFilterForElement: function (element) {
            var filter = new Filter(element);
            var params = this._getParams();
            if (filter.isClear()) {
                params.clear();
            } else if (filter.isActive() && !element.is('select')) {
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


        _processFilter: function (event) {
            event.preventDefault();
            var element = event.data.element;
            var filter = new Filter(element);

            var params = this._getParams();


            if (filter.isClear()) {
                params.clear();
            } else if (filter.isActive() && !element.is('select')) {
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

                    var data = $.parseJSON(response);
                    this._populateFilterModel(data);
                    this._populateProductModel(data);

                    var adv_nav_tmpl = $('#advanced-navigation-template').prop('outerHTML');
                    var adv_nav_tmpl_product = $('#advanced-navigation-products-template').prop('outerHTML');

                    $('.sidebar-main').html(adv_nav_tmpl);
                    $('.products.wrapper.grid.products-grid').html(adv_nav_tmpl_product);

                    /*$('.filter-options-item').on('click')

                    $(".filter-options-item").on( "click", function() {
                        $( this ).children('.filter-options-content').toggle();
                    });*/

                    //$(this.options.navigationContainer).replaceWith($('#advanced-navigation-template'));
                    $(this.options.navigationContainer).trigger('contentUpdated');
                    //$(this.options.productsContainer).html(response.products);
                    $(this.options.productsContainer).trigger('contentUpdated');
                    $(this.options.authentication).trigger('bindTrigger');

                }.bind(this)
            });
        },

        _populateFilterModel: function (data) {

            uiRegistry.get('advancedNavigationDataModel', function (dataModel) {
                dataModel.populateModel(data);
            });
        },

        _populateProductModel: function (data) {

            uiRegistry.get('advancedNavigationProducts', function (productModel) {
                productModel.populateModel(data);
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