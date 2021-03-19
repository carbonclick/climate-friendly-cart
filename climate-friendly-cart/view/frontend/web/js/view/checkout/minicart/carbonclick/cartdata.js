/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'ko',
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'jquery'
], function (ko, Component, customerData,$) {
    'use strict';

    return Component.extend({
        cart: {},
        /**
         * @override
         */
        initialize: function () {
            this._super();
            this.cart = customerData.get('cart');
        },

        displaythankyou: function(cart){
            return cart.carbonclick_data.product_in_cart;
        },

        getFormkey: function(){
            return $.mage.cookies.get('form_key');
        },

        getReturnUrl: function(){
            return $(location).attr('href');
        },

        miniAddtocart: function(){
            var carbonclick = this.cart().carbonclick_data;
            var form = $("#tocart-form-minicart");
            var formData = new FormData(form[0]);
            $("#tocart-form-minicart button").prop('disabled', true);
            $.ajax({
                url: carbonclick.addtocarturl,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false, 
            }).done(function (response) {
                customerData.reload(['cart']);
                if (window.location.href.indexOf(window.checkout.shoppingCartUrl) === 0) {
                    window.location.reload();
                }
            }).fail(function (response) {
                customerData.reload(['cart']);
                $("#tocart-form-minicart button").prop('disabled', false);
                if (window.location.href.indexOf(window.checkout.shoppingCartUrl) === 0) {
                    window.location.reload();
                }
            });
        },

        cfcremovecart: function(){
            var carbonclick = this.cart().carbonclick_data;
            var removedata = $.parseJSON(carbonclick.remove_url);
            var formData = new FormData();
            formData.append('item_id',removedata.data.id);
            formData.append('form_key',$.mage.cookies.get('form_key'));
            $('#carbonclick-minicart-thankyou').off("click");
            $.ajax({
                url: carbonclick.removeitem_url,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false, 
            }).done(function (response) {
                customerData.reload(['cart']);
                if (window.location.href.indexOf(window.checkout.shoppingCartUrl) === 0) {
                    window.location.reload();
                }
            }).fail(function (response) {
                customerData.reload(['cart']);
                if (window.location.href.indexOf(window.checkout.shoppingCartUrl) === 0) {
                    window.location.reload();
                }
            });
        }

    });
});
