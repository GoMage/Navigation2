/*jshint jquery:true*/
define([
    "jquery",
    "./filter",
    "./params",
    "./slider",
    "./loader",
    "catalogAddToCart",
    "jquery/ui",
    "mage/translate"
], function ($, Filter, Params, SliderPrice, Loader, catalogAddToCart) {
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
            linkMoreElement: '.gan_link-more-element',
            ganToolbarAmount: '.gan-toolbar-amount',
            amountToolbarNumber: 0,
            ganLastNumber: '.gan-last-number',
            breadcrumbs: '.breadcrumbs',
            toolbar: '.toolbar-products',
            collapsedContainer: '.collapsed-apply-settings'
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
                if(typeof  window.autoAjaxScroll == 'undefined' && !window.autoAjaxScroll){
                    $(window).off('scroll');
                    $(window).on('scroll', {}, $.proxy(this._bindAjaxAutoload, this));
                    window.autoAjaxScroll = true;
                }
            }

            if (this.options.tooltipOpenOnClick == true) {
                $('.gan-tooltip-toggle').on('click', function (event) {
                    event.stopPropagation();
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
                $('.gan-tooltip-close').on('click', function (event) {
                    event.stopPropagation();
                    $(this).closest('.gan-tooltip').removeClass('__opened');
                });
            }

            if (this.options.tooltipCloseOnMouseOut == true) {
                $('.gan-tooltip').on('mouseleave', function () {
                    $(this).removeClass('__opened');
                });
            }
        },

        moreLink: function (e) {
            $(e.target).closest('.filter-options-content').find(this.options.linkMoreElement).show();
            $(e.target).closest('.filter-options-content').find(this.options.moreLinkLess).show();
            $(e.target).closest('.filter-options-content').find(this.options.moreLink).hide();
            $(e.target).closest('.filter-options-content').find(this.options.moreLink).attr('data-show-more',1)
        },
        moreLinkLess: function (e) {
            $(e.target).closest('.filter-options-content').find(this.options.linkMoreElement).hide();
            $(e.target).closest('.filter-options-content').find(this.options.moreLinkLess).hide();
            $(e.target).closest('.filter-options-content').find(this.options.moreLink).show();
            $(e.target).closest('.filter-options-content').find(this.options.moreLink).attr('data-show-more',0)
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
                        var slidePrice;
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
                        slidePrice =  element.attr('value').split(';')
                        $('.price-from').val(slidePrice[0]);
                        $('.price-to').val(slidePrice[1]);
                        $('.price-to').show();
                        $('.to_slider_or_input').show();
                        $('.price-from').show();
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
            $(this.options.collapsedContainer).on('click', function () {
                if( $(this).attr('data-collapsed-filter') == 1) {
                    $(this).attr('data-collapsed-filter', 0)
                } else {
                    $(this).attr('data-collapsed-filter', 1)
                }
            })
        },

        _bindAjaxAutoload: function () {

            if (typeof($(this.options.divPagesEq).offset()) == 'undefined')
                return ;
            if ($(window).scrollTop() >= $('.limiter-options').last().offset().top - 800 ) {

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
            $('#button-more').blur();
            this._ajaxMoreProducts(url, params);
            return false;
        },

        _processBackToTop: function () {

            if (this.options.backToTopAction == 1) {
                $('html, body').animate({
                    scrollTop: (parseInt($(this.options.toolbar).offset().top))
                }, parseInt(this.options.backToTopSpeed));
            }
            else
                $("html, body").animate({ scrollTop: 0 }, parseInt(this.options.backToTopSpeed));
            $('#gan-to-top-button-gomage').blur();
        },

        _processCategory: function (event) {
            event.preventDefault();
            event.stopPropagation();
            var element = event.data.element;
            var categories;
            var ajax = Number(event.currentTarget.attributes['data-ajax'].nodeValue);
            var url = event.currentTarget.value;
            if(url && event.currentTarget.attributes['data-url'])
                url = event.currentTarget.attributes['data-url'].nodeValue;
            var isCategories = event.currentTarget.attributes['data-categories'];
            var params = this._getParams();
            if(isCategories && isCategories.nodeValue && url &&  event.currentTarget.attributes['data-select']) {
                categories = event.currentTarget.attributes['data-value'].nodeValue;
                params.set('cat', categories);
            } else if (isCategories && isCategories.nodeValue && !event.currentTarget.attributes['data-select']) {
                categories = event.currentTarget.attributes['data-value'].nodeValue;
                params.set('cat', categories);
            }
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
            event.preventDefault();
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
            if(element.length > 1 && element.attr('data-param') == 'p') {
                var element =  event.target.closest('a[data-role="navigation-filter"]')
            }
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
            if(this.options.q) {
                params.set('q', this.options.q)
            }
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

                    $(this.options.mainContainer).remove();
                    $(this.options.breadcrumbs).after(response.content);
                    $(this.options.mainContainer).trigger('contentUpdated');
                    $(this.options.breadcrumbsContainer).trigger('contentUpdated');
                    $(this.options.authentication).trigger('bindTrigger');
                    this.setNavigationUrl(params);
                    if (this.options.ajaxAutoload == true) {
                        $(window).off('scroll');
                        $(window).on('scroll', {}, $.proxy(this._bindAjaxAutoload, this));
                        window.autoAjaxScroll = true;
                    }
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
                    $(this.options.mainContainer).remove();
                    $(this.options.breadcrumbs).after(response.content);
                    $(this.options.breadcrumbsContainer).replaceWith(response.breadcrumbs);

                    $(this.options.mainContainer).trigger('contentUpdated');
                    $(this.options.breadcrumbsContainer).trigger('contentUpdated');
                    $(this.options.authentication).trigger('bindTrigger');
                    this.setCategoryUrl(url);
                    this.setNavigationUrl(params);

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
                    var elementHtml = '';
                    if (successCallback) {
                        successCallback.call(this, response);
                    }
                    $(this.options.productOlItems).find('.product-item').last().each(function () {
                        this.nextSibling.parentNode.removeChild(this.nextSibling);
                    });

                    var newProducts = $(response.content).find(this.options.productOlItems).first().html();
                    $(response.content).find(this.options.productOlItems).first().find('.product-item').each(
                        function () {
                            elementHtml = elementHtml + '<li class="item product product-item">'+$(this).html()+'</li>';
                        }
                    );
                    var toolbar = $(response.content).find(this.options.divPages).html();
                    $(this.options.divPages).html(toolbar);
                    $(this.options.productListContainer).first().append(elementHtml);
                    this.options.showMore = false;
                    var lastNumber = parseInt($(this.options.ganToolbarAmount).attr('data-limit-number'));
                    var totalNumber = parseInt($(this.options.ganToolbarAmount).attr('data-total-number'));
                    var page =  parseInt( $(this.options.divPagesNextItem).attr('data-value'));
                    if( !page ) {
                        page =  parseInt( $('.gan-last-page a:eq(1)').attr('data-value'));
                        page = page +1;
                    }
                    if (page) {
                        page = page -1;
                        page = parseInt(page);
                        page = page;
                        if(totalNumber/(lastNumber*page) > 1 ) {
                            $('.gan-last-number').text(lastNumber*page);
                        } else {
                            $('.gan-last-number').text(totalNumber);
                        }
                    } else {
                        $('.gan-last-number').text(totalNumber);
                    }

                    var url = $(this.options.divPagesNextItem).attr('href');
                    if (typeof(url) == 'undefined') {
                        $(this.options.moreButton).hide();
                    }
                    var element = $('.pages').find(this.options.filterControl);
                    element.unbind();
                    element.on('click', {element: element}, $.proxy(this._processFilter, this));
                    this.options.showMore = false;
                    $( "form[data-role='tocart-form']" ).catalogAddToCart();

                }.bind(this)
            });
        },

        _getParams: function () {
            var params = new Params();
            var categories = $('.gan-categories-list li, .gan-categories-list-dropdown option');
            var catActive = [];
            this.options.filters.each(function (index, filter) {
                if (filter.isActive()) {
                    if(filter.getParam() == 'cat' && filter.getValue() == '') {
                        return;
                    }
                    params.set(filter.getParam(), filter.getValue());
                    if(filter.getCategoriesParam()) {
                        params.set(filter.getCategoriesParam(), filter.getCategoriesValue());
                    }
                }
            });
            // categories.each(function () {
            //     if($(this).attr('data-active') == 1) {
            //         catActive.push($(this).attr('data-value'));
            //     }
            // });
            // if(catActive.length > 0) {
            //     catActive = catActive.join('_');
            //     params.set('cat', catActive);
            // }
            var paramcollapsed = [];
            var paramsMore = [];

            $(this.options.collapsedContainer).each(function () {
                if($(this).attr('data-collapsed-filter') == 1) {
                    paramcollapsed.push($(this).attr('data-collapsed-param'));
                }
            });

            $(this.options.moreLink).each(function () {
                if($(this).attr('data-show-more') == 1) {
                    paramsMore.push($(this).attr('data-more-value'));
                }
            });

            paramcollapsed = paramcollapsed.join('_')
            paramsMore = paramsMore.join('_');
            if(!params.get('product_list_dir')) {
                params.set('product_list_dir', 'desc')
            }
            params.set('collapsed_expanded', paramcollapsed);
            params.set('more_show', paramsMore);
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
            var friendleuse = '';
            if (this.options.addFilterResultsToUrl == 0)
                return ;

            params.remove('gan_ajax_filter');
            friendleuse = '?'
            window.history.pushState(null, '', this.options.baseUrl + friendleuse + params.toUrlParams());
        },

        setCategoryUrl: function (url) {

            window.history.pushState(null, '', url);
        }
    });

    return $.gomage.navigation;
});