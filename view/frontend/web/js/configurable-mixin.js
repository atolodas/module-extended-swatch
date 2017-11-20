define([
    'jquery',
    'Muhammedv_ExtendedSwatch/js/renderer-child-info'
], function ($, childInfoRenderer) {
    'use strict';

    return function (widget) {

        $.widget('mage.configurable', widget, {
            _configureElement: function () {
                this._superApply(arguments);
                childInfoRenderer.update(this.simpleProduct);
            }
        });
        return $.mage.configurable;
    }
});