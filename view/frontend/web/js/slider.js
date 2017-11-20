define([
    "jquery",
    "./slider/jshashtable-2.1_src",
    "./slider/jquery.numberformatter-1.2.3",
    "./slider/tmpl",
    "./slider/jquery.dependClass-0.1",
    "./slider/draggable-0.1",
    "./slider/jquery.slider"
], function ($) {
    "use strict";

    function SliderPrice(element)
    {

        element = $(element);
        this.from = element.attr('data-from');
        this.to = element.attr('data-to');
        this.step = element.attr('data-step');
        this.skin = element.attr('data-skin');
        this.code = element.attr('data-code');
        this.dimension = element.attr('data-dimension');
        this.type = element.attr('data-type');
        this.value = element.attr('value');
        this.param = element.attr('data-param');
        this.ajax = element.attr('data-ajax');
        this.active = element.attr('data-active');
        this.clear = element.attr('data-clear');
    }

    SliderPrice.prototype = {
        constructor: SliderPrice,
        /**
         *
         * @returns {*|String|tinymce.html.Node}
         */
        isActive: function () {
            return this.active;
        },

        /**
         *
         * @returns {*|String|tinymce.html.Node}
         */
        isAjax: function () {
            return this.ajax;
        },
        /**
         *
         * @returns {*|String|tinymce.html.Node}
         */
        isClear: function () {
            return this.clear;
        },
        /**
         *
         * @returns {*|String|tinymce.html.Node}
         */
        getParam: function () {
            return this.param;
        },
        /**
         *
         * @returns {*|String|tinymce.html.Node}
         */
        getValue: function () {
            return this.value;
        },
        /**
         *
         * @returns {*|String|tinymce.html.Node}
         */
        getFrom: function () {
            return parseInt(this.from);
        },
        /**
         *
         * @returns {*|String|tinymce.html.Node}
         */
        getTo: function () {
            return parseInt(this.to);
        },
        /**
         *
         * @returns {*|String|tinymce.html.Node}
         */
        getStep: function () {
            return this.step;
        },
        /**
         *
         * @returns {*|String|tinymce.html.Node}
         */
        getSkin: function () {
            return this.skin;
        },
        /**
         *
         * @returns {*|String|tinymce.html.Node}
         */
        getCode: function () {
            return this.code;
        },
        /**
         *
         * @returns {*|String|tinymce.html.Node}
         */
        getDimension: function () {
            return this.dimension;
        },
        /**
         *
         * @returns {*|String|tinymce.html.Node}
         */
        getType: function () {
            return this.type;
        }

    };

    return SliderPrice;
});

