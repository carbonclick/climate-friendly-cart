/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'ko',
    'uiComponent',
    'Carbonclick_CFC/js/action/addtocart',
    'Carbonclick_CFC/js/action/removefromcart'
], function ($,ko, Component,addtocart,removefromcart) {
    'use strict';

    var carbonclickdata = window.checkoutConfig.carbonclick_data;

    var productincart = ko.observable(null);
    var carbonclick = ko.observable(null);

    $(document).on('click','#cfc-learn-more', function(e){
        e.preventDefault();
        $('#'+$(this).attr('data-action')).toggle();
    });

    carbonclick(carbonclickdata);

    productincart(carbonclickdata.product_in_cart);

    return Component.extend({
        defaults: {
            template: 'Carbonclick_CFC/payment/carbonclick'
        },

        productincart:productincart,
        carbonclick:carbonclick,


        getparameter: function(parameter){
            var data = carbonclick();
            if(data){
                return carbonclickdata[parameter];
            }
            return;
        },

        getImpactallValue: function(parameter){
            var impactalldata = this.getparameter('impactalldata');
            if(impactalldata){
                return impactalldata[parameter]['value'];
            }
            return;
        },

        getWeightUnit: function(){
            var weightdata = this.getparameter('carbonweight');
            if(weightdata){
                return '('+weightdata[1]+')';
            }
            return;
        },

        getWeight: function(){
            var weightdata = this.getparameter('carbonweight');
            if(weightdata){
                return weightdata[0];
            }
            return;
        },

        getFormkey: function(){
            return $.mage.cookies.get('form_key');
        },

        CfcAddtocart: function(){
            addtocart(carbonclickdata.addtocarturl, $("#add-to-cart-cfc"), productincart,carbonclick);
        },

        cfcremovecart: function(){
            removefromcart(carbonclick().removeitem_url,JSON.parse(carbonclick().remove_url),productincart,carbonclick)
        }

    });
});
