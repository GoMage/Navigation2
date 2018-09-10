define(
    [
    "jquery"
    ], function ($) {
        "use strict";

        function Params()
        {
            this.data = {};
        }

        Params.prototype = {

            constructor: Params,

            get: function (key) {
                if (this.data.hasOwnProperty(key)) {
                    return this.data[key];
                }
                return undefined;
            },

            set: function (key, value) {
                if (key) {
                    if (this.data.hasOwnProperty(key)) {
                        //TODO: for multi filter
                        //this.data[key].push(value);
                        this.data[key] = [value];
                    } else {
                        this.data[key] = [value];
                    }
                }
            },

            unset: function (key, value) {
                if (this.data.hasOwnProperty(key)) {
                    var index = this.data[key].indexOf(value);
                    if (index > -1) {
                        this.data[key].splice(index, 1);
                    }
                    if (this.data[key].length == 0) {
                        this.remove(key);
                    }
                }
            },

            removeByValue: function ( key, value) {
                if (this.data.hasOwnProperty(key)) {
                    var index = this.data[key].indexOf(value);
                    if (index > -1) {
                        this.data[key].splice(index, 1);
                    }
                    if (this.data[key].length == 0) {
                        var params = this.data[key].split('_');
                        index = params.indexOf(value);
                        if (index > -1) {
                            params.splice(index, 1);
                        }
                        delete params[index];
                        this.data[key] = params.join('_');
                    }
                }
            },
            remove: function (key) {
                if (this.data.hasOwnProperty(key)) {
                    delete this.data[key];
                }
            },

            clear: function () {
                var result = {};
                if (this.data.hasOwnProperty('product_list_order')) {
                    result['product_list_order'] = this.data['product_list_order'];
                }
                if (this.data.hasOwnProperty('collapsed_expanded')) {
                    result['collapsed_expanded'] = this.data['collapsed_expanded'];
                }
                if (this.data.hasOwnProperty('more_show')) {
                    result['more_show'] = this.data['more_show'];
                }
                this.data = result;
            },

            toUrlParams: function () {
                return $.param(this.data, true);
            }

        };

        return Params;
    }
);

