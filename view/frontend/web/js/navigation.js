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
            mainContainer: '#maincontent',
            breadcrumbsContainer: 'div.breadcrumbs',
            productsContainer: 'div.main:first',
            authentication: 'div.block-authentication',
            productListContainer: 'ol.product-items',
            productToolbarContainer: 'div.toolbar-products',
            categoriesContainer: 'div.gan-categories',
            loader: '#gomage-loader',
            productLoader: '<div id="product-loader">Loading ...</div>',
            showMore: false
        },

        _create: function () {
            if (!this.options.baseUrl) {
                this.options.baseUrl = location.protocol + '//' + location.host + location.pathname;
            }
            this._initFilters();
            $(this.options.navigationContainer).on({
                'show.navigation': $.proxy(this._initFilters, this)
            });

            if (this.options.ajaxAutoload == true) {
                $(window).on('scroll', {}, $.proxy(this._bindAjaxAutoload, this));
            }
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
                        element.on('click', {element: element}, $.proxy(this._bindShowMoreProductsButton, this));

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
                    case 'categories-select':
                        element.unbind('change');
                        element.on('change', {element: element}, $.proxy(this._processCategory, this));
                        break;
                    case 'categories-li':
                        element.unbind('click');
                        element.on('click', {element: element}, $.proxy(this._processCategory, this));
                        break;
                    case 'back-to-top':
                        element.unbind('click');
                        element.on('click', {element: element}, $.proxy(this._processBackToTop, this));
                        break;
                    default:
                        element.unbind('click');
                        element.on('click', {element: element}, $.proxy(this._processFilter, this));
                }
            }
        },

        _bindAjaxAutoload: function () {

            if (typeof($('div.pages:eq(1)')) == 'undefined') {
                return ; }

            if ($(window).scrollTop() >= $('div.pages:eq(1)').offset().top - $(window).height()) {
                var url = $('li.item.pages-item-next a:eq(1)').attr('href');
                if (typeof(url) == 'undefined') {
                    return ; }

                var params = this._getParams();
                params.clear();
                params.set('gan_ajax_more', 1);
                this._ajaxMoreProducts(url, params);
            }
        },

        _bindShowMoreProductsButton: function () {

            var url = $('li.item.pages-item-next a:eq(1)').attr('href');
            if (typeof(url) == 'undefined') {
                return ; }

            var params = this._getParams();
            params.clear();
            params.set('gan_ajax_more', 1);
            this._ajaxMoreProducts(url, params);
            this._showButtonMore;
        },

        _processBackToTop: function () {

            if (this.options.backToTopAction == 1) {
                $('html, body').animate({
                    scrollTop: parseInt($(".product-items").offset().top)
                }, this.options.backToTopSpeed);
            } else $("html, body").animate({
scrollTop: 0 }, this.options.backToTopSpeed);
        },

        _processCategory: function (event) {

            event.preventDefault();
            event.stopPropagation();
            var element = event.data.element;
            var ajax = Number(event.currentTarget.attributes['data-ajax'].nodeValue);
            var url = event.currentTarget.value;
            if (!url) {
                url = event.currentTarget.attributes['data-url'].nodeValue; }


            var params = this._getParams();
            params.clear();
            params.set('gan_ajax_cat', 1);

            if (ajax) {
                return this._ajaxCategory(url, params);
            } else {
                return $.mage.redirect(url);
            }

        },

        _processCategoriesContent: function () {
            $('.gan-categories-title').next().children().toggle();
        },

        _processRemoveItem: function (element) {

            var url = element.currentTarget.attributes['data-url'].nodeValue;
            var param = element.currentTarget.attributes['data-param'].nodeValue;
            var value = element.currentTarget.attributes['data-value'].nodeValue;
            var ajax = Number(element.currentTarget.attributes['data-ajax'].nodeValue);

            var params = this._getParams();
            params.remove(param);
            if (value !== '') {
                params.set(param, value); }

            if (ajax) {
                params.set('gan_ajax_filter', 1);
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
                params.set('gan_ajax_filter', 1);
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

            /*var url = $('li.item.pages-item-next a:eq(1)').attr('href');
            if (typeof(url) == 'undefined')

            $(this.options.productToolbarContainer+':eq( 1 )').hide();
            $('button.'+element.attr('class')+':eq( 1 )').show();*/
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

                params.set('gan_ajax_more', 1);

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
                params.set('gan_ajax_filter', 1);
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
                params.set('gan_ajax_filter', 1);
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

                    $(this.options.mainContainer).html(response.content);
                    $(this.options.mainContainer).trigger('contentUpdated');
                    $(this.options.breadcrumbsContainer).trigger('contentUpdated');
                    $(this.options.authentication).trigger('bindTrigger');
                    this.setNavigationUrl(params);

                }.bind(this)
            });

        },

        _ajaxCategory: function (url, params, successCallback) {
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

                    $(this.options.mainContainer).html(response.content);
                    $(this.options.breadcrumbsContainer).html(response.breadcrumbs);

                    $(this.options.mainContainer).trigger('contentUpdated');
                    $(this.options.breadcrumbsContainer).trigger('contentUpdated');
                    $(this.options.authentication).trigger('bindTrigger');
                    this.setCategoryUrl(url);

                }.bind(this)
            });
        },

        _ajaxMoreProducts: function (url, params, successCallback) {

            if (this.options.showMore == true) {
                return ; }

            this.options.showMore = true;

            $.ajax({
                url: url,
                type: 'get',
                cache: true,
                data: (params) ? params.toUrlParams() : [],
                beforeSend: (this._ajaxSendShowMore).bind(this),
                complete: (this._ajaxCompleteShowMore).bind(this),
                success: function (response) {
                    if (successCallback) {
                        successCallback.call(this, response);
                    }
                    var newProducts = $(response.content).find('ol.products.list.items.product-items').html();
                    var toolbar = $(response.content).find('div.pages').html();
                    $('div.pages').html(toolbar);
                    $(this.options.productListContainer).append(newProducts);
                    this.options.showMore = false;

                    var url = $('li.item.pages-item-next a:eq(1)').attr('href');
                    if (typeof(url) == 'undefined') {
                        $('#gan-more-button').hide();
                    }


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

            $(this.options.loader).show();
        },

        _ajaxSendShowMore: function () {

            $(this.options.productListContainer).append(this.options.productLoader);
        },

        /**
         * Callback function for ajax complete event(global)
         * @private
         */
        _ajaxComplete: function () {

            if (this.options.showMore) {
                $(this.options.mainContainer).find('#product-loader').remove();
                return ;
            }

            $(this.options.loader).hide();
        },

        _ajaxCompleteShowMore: function () {

            $(this.options.mainContainer).find('#product-loader').remove();
        },

        setNavigationUrl: function (params) {

            if (this.options.addFilterResultsToUrl == 0) {
                return ; }

            params.remove('gan_ajax_filter');
            window.history.pushState(null, '', this.options.baseUrl + '?' + params.toUrlParams());
        },

        setCategoryUrl: function (url) {

            window.history.pushState(null, '', url);
        }
    });

    return $.gomage.navigation;
});
