define(
    [
    "jquery"
    ], function ($) {
        "use strict";

        function Loader()
        {
            this.loader = '#gomage-loader';
            this.productLoader = '<div id="product-loader" class="gan-product-loader"><svg class="gan-loader-spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="gan-loader-spinner-circle" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg><span>Loading...</span></div>';
            this.productListContainer = 'ol.product-items';
            this.mainContainer = '#maincontent';
        }

        Loader.prototype = {

            constructor: Loader,

            show: function () {
                $(this.loader).show();
            },

            hide: function () {
                $(this.loader).hide();
            },

            showProductLoader: function () {
                $(this.productListContainer).first().append(this.productLoader);
            },

            removeProductLoader: function () {

                $(this.mainContainer).find('#product-loader').remove();
            }
        };

        return Loader;
    }
);

