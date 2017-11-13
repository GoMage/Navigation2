/*jshint jquery:true*/
define([
    "jquery",
    "./filter",
    "./params",
    "./slider",
    "jquery/ui",
    "mage/translate"
], function ($, Filter, Params, SliderPrice) {
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
            categoriesContainer: 'div.gan-categories',
            loader: 'body'
        },

        _create: function () {
            if (!this.options.baseUrl) {
                this.options.baseUrl = location.protocol + '//' + location.host + location.pathname;
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
                                self._processPriceSlider(value, this);
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
                    case 'input-price':
                        element.unbind('change');
                        element.on('change', {element: element}, $.proxy(this._processInputPrice, this));
                        break;
                    case 'price-button':
                        element.unbind('click');
                        element.on('click', {element: element}, $.proxy(this._processPriceButton, this));
                        break;
                    case 'remove-item':
                        element.unbind('click');
                        element.on('click', {element: element}, $.proxy(this._processRemoveItem, this));
                        break;
                    case 'categories-content':
                        element.unbind('click');
                        element.on('click', {element: element}, $.proxy(this._processCategoriesContent, this));
                        break;
                    case 'category':
                        element.unbind('click');
                        element.on('click', {element: element}, $.proxy(this._processCategory, this));
                        break;
                    default:
                        element.unbind('click');
                        element.on('click', {element: element}, $.proxy(this._processFilter, this));

                }

            }
        },

        _processCategory: function () {

            var params = this._getParams();
            params.clear();
            params.set('gan_ajax_cat', 1);
            return this._ajaxFilter('http://magento2-new.local/women/tops-women.html', params);
        },

        _processCategoriesContent: function () {
            $('.gan-categories-title').next().toggle();
        },

        _processRemoveItem: function (element) {

            var url = element.currentTarget.attributes['data-url'].nodeValue;
            var param = element.currentTarget.attributes['data-param'].nodeValue;
            var value = element.currentTarget.attributes['data-value'].nodeValue;
            var ajax = Number(element.currentTarget.attributes['data-ajax'].nodeValue);

            var params = this._getParams();
            params.remove(param);
            if (value !== '')
                params.set(param, value);

            if (ajax) {
                return this._ajaxFilter(url, params);
            } else {
                return $.mage.redirect(url);
            }
        },

        _processInputPrice: function () {
            var value = $('#price-from').val() + '-' + $('#price-to').val();
            var el = $('#price');
            el.attr("data-value", value);
            this._runProcessFilterForElement(el);
        },

        _processPriceButton: function (event) {
            event.preventDefault();
            var value = $('#price-from').val() + '-' + $('#price-to').val();
            var el = $('#price');
            el.attr("data-value", value);
            this._runProcessFilterForElement(el);
        },

        _processPriceSlider: function (value, event) {

            var el = $('#price');
            var filter = new Filter(el);
            var params = this._getParams();
            params.set('price', value.replace(';', '-'));

            if (filter.isAjax()) {
                return this._ajaxFilter(this.options.baseUrl, params);
            } else {
                return $.mage.redirect(this.options.baseUrl + '?' + params.toUrlParams());
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
                data: (params) ? params.toUrlParams() : [],
                beforeSend: (this._ajaxSend).bind(this),
                complete: (this._ajaxComplete).bind(this),
                success: function (response) {
                    if (successCallback) {
                        successCallback.call(this, response);
                    }

                    var data = $.parseJSON(response);
                    $(this.options.productsContainer).html(data.products);
                    if (this.options.navigationPlace == 1) {
                        $(data.navigation).prependTo($(this.options.productsContainer));

                    } else {
                        $(this.options.navigationContainer).html(data.navigation);
                    }

                    if (this.options.categoriesPlace == 1) {
                        $(data.categories).prependTo($(this.options.productsContainer));
                    } else {
                        $(this.options.categoriesContainer).replaceWith(data.categories);
                    }

                    $(this.options.navigationContainer).trigger('contentUpdated');
                    $(this.options.productsContainer).trigger('contentUpdated');
                    $(this.options.authentication).trigger('bindTrigger');
                    this.setNavigationUrl(params);

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
        },

        setNavigationUrl: function (params) {

            if (this.options.addFilterResultsToUrl == 0)
                return ;

            window.history.pushState(null, '', this.options.baseUrl + '?' + params.toUrlParams());
        }

    });

    return $.gomage.navigation;
});