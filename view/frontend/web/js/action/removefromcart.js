/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Customer store credit(balance) application
 */
define([
    'ko',
    'jquery',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/resource-url-manager',
    'Magento_Checkout/js/model/error-processor',
    'mage/translate',
    'Magento_Checkout/js/action/get-payment-information',
    'Magento_Checkout/js/model/totals',
    'Magento_Checkout/js/model/full-screen-loader',
    'Magento_Checkout/js/action/recollect-shipping-rates',
    'Magento_Customer/js/customer-data',
    'Carbonclick_CFC/js/action/update-summary-heading'
], function (ko, $, quote, urlManager, errorProcessor, $t, getPaymentInformationAction,
    totals, fullScreenLoader, recollectShippingRates,customerData, updateSummaryHeadingAction
) {
    'use strict';

    var dataModifiers = [],
        successCallbacks = [],
        failCallbacks = [],
        cart = customerData.get('cart'),
        action;

    action = function (url,removedata, productincart,carbonclick) {
        fullScreenLoader.startLoader();
        
        var formData = new FormData();
        formData.append('item_id',removedata.data.id);
        formData.append('form_key',$.mage.cookies.get('form_key'));

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false, 
        }).done(function (response) {
            var deferred;
            customerData.reload(['cart']);
            deferred = $.Deferred();

            totals.isLoading(true);
            recollectShippingRates();
            getPaymentInformationAction(deferred);
            $.when(deferred).done(function () {
                fullScreenLoader.stopLoader();
                totals.isLoading(false);
                var totalQty = quote.totals().items_qty;
                // update summary block heading quantity
                updateSummaryHeadingAction(totalQty);
                productincart(cart().carbonclick_data.product_in_cart);
                carbonclick(cart().carbonclick_data);
            });
        }).fail(function (response) {
            fullScreenLoader.stopLoader();
            totals.isLoading(false);
        });
    };

    return action;
});
