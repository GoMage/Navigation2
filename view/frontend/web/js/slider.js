/**
 * GoMage.com
 *
 * GoMage Navigation M2
 *
 * @category  Extension
 * @copyright Copyright (c) 2018-2018 GoMage.com (https://www.gomage.com)
 * @author    GoMage.com
 * @license   https://www.gomage.com/licensing  Single domain license
 * @terms     of use https://www.gomage.com/terms-of-use
 * @version   Release: 1.0.0
 * @since     Class available since Release 1.0.0
 */

define(
    [
        "jquery",
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
            this.round = element.attr('data-round');
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

            getRound: function () {
                return this.round;
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
                return parseFloat(this.from);
            },
            /**
             *
             * @returns {*|String|tinymce.html.Node}
             */
            getTo: function () {
                return parseFloat(this.to);
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
    }
);

