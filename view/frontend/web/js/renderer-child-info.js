define(
    [
        'jquery',
        'underscore',
        'mage/translate'
    ],
    function ($, _) {
        'use strict';

        var options,
            dataProvider,
            infoRenderer,
            attrTableSelector = '#product-attribute-specs-table',
            descriptionSelector = '#product\\.info\\.description .description .value';

        dataProvider = {
            getDescription: function (productId) {
                var descriptions = options.descriptions;
                if(descriptions.hasOwnProperty(productId)) {
                    return descriptions[productId];
                }
                return false;
            },
            getAdditionalAttributes: function (productId) {
                var attributes = options.additionalAttributes;
                if(attributes.hasOwnProperty(productId)) {
                    return attributes[productId];
                }
                return false;
            }
        };

        infoRenderer = {
            update: function(productId) {
                var attrTable      = $(attrTableSelector);
                var descriptionElm = $(descriptionSelector);
                var attributes     = dataProvider.getAdditionalAttributes(productId);
                var description    = dataProvider.getDescription(productId);

                //preserve original values
                attrTable.find('td:not([data-original])').each(function(){
                    $(this).attr('data-original', $(this).html())
                });

                //set attributes
                if (attributes === false || productId === undefined) {
                    //set originals if product id not found
                    attrTable.find('td[data-original]').each(function(){
                        $(this).html($(this).data('original'));
                    });
                } else {
                    attrTable.find('td[data-th]').each(function(){
                        $(this).html($.mage.__('N\\A'));
                    });
                    _.each(attributes, function(attribute){
                        attrTable.find('td[data-th=\'' + attribute.label + '\']').html(attribute.value);
                    });
                }

                //set description
                if (description === false || productId === undefined) {
                    descriptionElm.html(options.configDescription);
                } else {
                    descriptionElm.html(description);
                }

            },
            'Muhammedv_ExtendedSwatch/js/renderer-child-info': function (settings) {
                options = settings;
            }
        };

        return infoRenderer;
    }
);
