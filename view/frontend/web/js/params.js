define([
    "jquery"
], function ($) {
    "use strict";

    function Params() {
        this.data = {};
    }

    Params.prototype = {

        constructor: Params,

        get: function (key) {
            var data = this.data;

            if (this.has(key)) {
                return data[key];
            }

            return undefined;
        },

        set: function (key, value) {
            var data = this.data;

            if (this.has(key)) {
                data[key].push(value);
            } else {
                data[key] = [value];
            }

            return this;
        },

        unset: function (key, value) {
            var data = this.data;

            if (this.has(key)) {
                var index = data[key].indexOf(value);
                if (index > -1) {
                    data[key].splice(index, 1);
                }
                if (data[key].length == 0) {
                    this.remove(key);
                }
            }

            return this;
        },

        remove: function (key) {
            var data = this.data;

            if (this.has(key)) {
                delete data[key];
            }

            return this;
        },

        has: function (key) {
            var data = this.data;
            return data.hasOwnProperty(key);
        },

        clear: function () {
            var data = this.data;
            data = {};

            return this;
        },

        toUrlParams: function () {
            return $.param(this.data, true);
        }

    };

    return Params;
});
