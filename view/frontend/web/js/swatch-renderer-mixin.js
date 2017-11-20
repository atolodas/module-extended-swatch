define([
    'jquery',
    'Muhammedv_ExtendedSwatch/js/renderer-child-info'
], function ($, childInfoRenderer) {
    'use strict';

    return function (widget) {

        $.widget('mage.SwatchRenderer', widget, {
            _OnClick: function () {
                this._superApply(arguments);
                childInfoRenderer.update(this.getProduct());
            }
        });
        return $.mage.SwatchRenderer;
    }
});