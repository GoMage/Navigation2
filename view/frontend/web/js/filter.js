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
    "jquery"
    ], function ($) {
        "use strict";

        function Filter(element)
        {

            element = $(element);

            if (element.is('select')) {
                this.value = element.val();
            } else {
                this.value = element.attr('data-value');
            }
            this.param = element.attr('data-param');
            this.categoiesParam = element.attr('data-param-cat-parent');
            this.categoiesParamVal = element.attr('data-cat-parent');
            this.ajax = element.attr('data-ajax');
            this.active = element.attr('data-active');
            this.clear = element.attr('data-clear');
            this.url = element.attr('data-url');
        }

        Filter.prototype = {
            constructor: Filter,
            getCategoriesParam: function () {
                return this.categoiesParam;
            },
            getCategoriesValue: function () {
                return this.categoiesParamVal;
            },
            isActive: function () {
                return this.active;
            },


            isAjax: function () {
                return Number(this.ajax);
            },

            isClear: function () {
                return this.clear;
            },

            getParam: function () {
                return this.param;
            },

            getValue: function () {
                return this.value;
            },

            getUrl: function () {
                return this.url;
            }

        };

        return Filter;
    }
);
