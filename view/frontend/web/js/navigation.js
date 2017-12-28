/*jshint jquery:true*/
define([
    "jquery",
    "./filter",
    "./params",
    "./slider",
    "./loader",
    "jquery/ui",
    "mage/translate"
], function ($, Filter, Params, SliderPrice, Loader) {
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
            loader: new Loader,
            showMore: false,
            divPagesEq: 'div.pages:eq(1)',
            divPagesNextItem: 'li.item.pages-item-next a:eq(1)',
            productItems: '.product-items',
            ganCategoriesTitle: '.gan-categories-title',
            blockContentIn: '.block-content-in',
            parentInputSlider: '.gan-filter.gan-filter-slider.gan-filter-slider-input',
            parentInputGan: '.gan-filter.gan-filter-input',
            productOlItems: 'ol.products.list.items.product-items',
            divPages: 'div.pages',
            moreButton: '#gan-more-button',
            moreLink: '.gan_link_more',
            moreLinkLess: '.gan_link_more_less',
            linkMoreElement: '.gan_link-more-element'
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
                if(typeof  window.autoAjaxScroll == 'undefined'){
                    $(window).off('scroll');
                    $(window).on('scroll', {}, $.proxy(this._bindAjaxAutoload, this));
                    window.autoAjaxScroll = true;
                }
            }

            if (this.options.tooltipOpenOnClick == true) {
                $('.gan-tooltip-toggle').on('click', function () {
                    $(this).closest('.gan-tooltip').addClass('__opened');
                });
            }
            $(this.options.moreLink).off('click');
            $(this.options.moreLink).on('click', function () {
                $(this.options.linkMoreElement).show();
                $(this.options.moreLinkLess).show();
                $(this.options.moreLink).hide();
            });
            $(this.options.moreLinkLess).off('click');
            $(this.options.moreLink).off('click');
            $(this.options.moreLink).on('click', this.moreLink.bind(this));
            $(this.options.moreLinkLess).on('click', this.moreLinkLess.bind(this));

            if (this.options.tooltipOpenOnMouseOver == true) {
                $('.gan-tooltip').on('mouseover', function () {
                    $(this).addClass('__opened');
                });
            }

            if (this.options.tooltipCloseButton == true) {
                $('.gan-tooltip-close').on('click', function () {
                    $(this).closest('.gan-tooltip').removeClass('__opened');
                });
            }

            if (this.options.tooltipCloseOnMouseOut == true) {
                $('.gan-tooltip').on('mouseout', function () {
                    $(this).removeClass('__opened');
                });
            }
        },

        moreLink: function () {
            $(this.options.linkMoreElement).show();
            $(this.options.moreLinkLess).show();
            $(this.options.moreLink).hide();
        },
        moreLinkLess: function () {
            $(this.options.linkMoreElement).hide();
            $(this.options.moreLinkLess).hide();
            $(this.options.moreLink).show();
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

            if (typeof($(this.options.divPagesEq).offset()) == 'undefined')
                return ;

            if ($(window).scrollTop() >= $('.toolbar-products').last().offset().top) {

                var url = $(this.options.divPagesNextItem).attr('href');
                if (typeof(url) == 'undefined')
                    return ;

                var params = this._getParams();
                params.clear();
                params.set('gan_ajax_more', 1);
                this._ajaxMoreProducts(url, params);
            }
        },

        _bindShowMoreProductsButton: function () {

            var url = $(this.options.divPagesNextItem).attr('href');
            if (typeof(url) == 'undefined')
                return ;

            var params = this._getParams();
            params.clear();
            params.set('gan_ajax_more', 1);
            this._ajaxMoreProducts(url, params);
        },

        _processBackToTop: function () {

            if (this.options.backToTopAction == 1) {
                $('html, body').animate({
                    scrollTop: parseInt($(this.options.productItems).offset().top)
                }, parseInt(this.options.backToTopSpeed));
            }
            else
                $("html, body").animate({ scrollTop: 0 }, parseInt(this.options.backToTopSpeed));
        },

        _processCategory: function (event) {

            event.preventDefault();
            event.stopPropagation();
            var element = event.data.element;
            var ajax = Number(event.currentTarget.attributes['data-ajax'].nodeValue);
            var url = event.currentTarget.value;
            if(!url)
                url = event.currentTarget.attributes['data-url'].nodeValue;


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
            $(this.options.ganCategoriesTitle).parent().find(this.options.blockContentIn).toggle();
        },

        _processRemoveItem: function (event) {

            var el = $(event.data.element);

            var url = el.attr('data-url');
            var param = el.attr('data-param-to-remove');
            var value = el.attr('data-value');
            var ajax = Number(el.attr('data-ajax'));

            var params = this._getParams();
            params.remove(param);
            if (value !== '')
                params.set(param, value);

            if (ajax) {
                params.set('gan_ajax_filter', 1);
                return this._ajaxFilter(url, params);
            } else {
                return $.mage.redirect(url);
            }
        },

        _processInputPrice: function (event) {

            var el = $(event.data.element).parents(this.options.parentInputGan);
            var t = this.options.parentInputGan;
            var price = $('.gan-price');
            var price_from = el.find('.price-from');
            var price_to = el.find('.price-to');

            var value = price_from.val() + '-' + price_to.val();
            var params = this._getParams();
            params.set('price', value);
            params.set(price.attr('data-param'), value);

            var filter = new Filter(price);
            if (filter.isAjax()) {
                params.set('gan_ajax_filter', 1);
                return this._ajaxFilter(this.options.baseUrl, params);
            } else {
                return $.mage.redirect(this.options.baseUrl + '?' + params.toUrlParams());
            }
        },

        _processPriceButton: function (event) {
            event.preventDefault();

            var el = $(event.data.element).parents(this.options.parentInputSlider);

            var price = $('.gan-price');
            var price_from = $('.price-from');
            var price_to = $('.price-to');

            var value = price_from.val() + '-' + price_to.val();
            var params = this._getParams();
            params.set(price.attr('data-param'), value);
            params.set('price', value);
            var filter = new Filter(price);
            if (filter.isAjax()) {
                params.set('gan_ajax_filter', 1);
                return this._ajaxFilter(this.options.baseUrl, params);
            } else {
                return $.mage.redirect(this.options.baseUrl + '?' + params.toUrlParams());
            }
        },

        _processPriceSlider: function (value, event) {

            var el = $(event.inputNode.context);
            var filter = new Filter(el);
            var params = this._getParams();
            params.set(el.attr('data-param'), value.replace(';', '-'));

            if (filter.isAjax()) {
                params.set('gan_ajax_filter', 1);
                params.set('price', value.replace(';', '-'));
                return this._ajaxFilter(this.options.baseUrl, params);
           } else {
                params.set('price', value.replace(';', '-'));
                return $.mage.redirect(this.options.baseUrl + '?' + params.toUrlParams());
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
            $(window).off('scroll');
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
                    $(window).on('scroll', {}, $.proxy(this._bindAjaxAutoload, this));
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

            if (this.options.showMore == true)
                return ;

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
                    var newProducts = $(response.content).find(this.options.productOlItems).html();
                    var toolbar = $(response.content).find(this.options.divPages).html();
                    $(this.options.divPages).html(toolbar);
                    $(this.options.productListContainer).append(newProducts);
                    this.options.showMore = false;

                    var url = $(this.options.divPagesNextItem).attr('href');
                    if (typeof(url) == 'undefined') {
                        $(this.options.moreButton).hide();
                    }

                    this.options.showMore = false;

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

            this.options.loader.show();
        },

        _ajaxSendShowMore: function () {

            this.options.loader.showProductLoader();
        },

        /**
         * Callback function for ajax complete event(global)
         * @private
         */
        _ajaxComplete: function () {

            if(this.options.showMore) {
                this.options.loader.removeProductLoader();
                return ;
            }

            this.options.loader.hide();
        },

        _ajaxCompleteShowMore: function () {

            this.options.loader.removeProductLoader();
        },

        setNavigationUrl: function (params) {

            if (this.options.addFilterResultsToUrl == 0)
                return ;

            params.remove('gan_ajax_filter');
            window.history.pushState(null, '', this.options.baseUrl + '?' + params.toUrlParams());
        },

        setCategoryUrl: function (url) {

            window.history.pushState(null, '', url);
        }
    });

    return $.gomage.navigation;
});