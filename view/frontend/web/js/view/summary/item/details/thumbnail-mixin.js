/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define(['Magento_Customer/js/customer-data'], function (customerData) {
    'use strict';

    return function (Component) {
        return Component.extend({
            /**
             * @param {Object} item
             * @return {Array}
             */
            getImageItem: function (item) {
                var originalResult = this._super();
                
                if(originalResult === []){
                    var productImage = this.getProductImage(item['item_id']);
                    if(productImage){
                        return productImage;
                    }    
                }

                return originalResult;
            },

            /**
             * @param {Object} item
             * @return {null}
             */
            getSrc: function (item) {
                var originalResult = this._super();
                if(originalResult === null){
                    var productImage = this.getProductImage(item['item_id']);
                    if(productImage){
                        return productImage.src;
                    }    
                }
                
                return originalResult;
            },

            /**
             * @param {Object} item
             * @return {null}
             */
            getWidth: function (item) {
                var originalResult = this._super();

                if(originalResult === null){
                    var productImage = this.getProductImage(item['item_id']);
                    if(productImage){
                        return productImage.width;
                    }    
                }
                
                return originalResult;
            },

            /**
             * @param {Object} item
             * @return {null}
             */
            getHeight: function (item) {
                var originalResult = this._super();

                if(originalResult === null){
                    var productImage = this.getProductImage(item['item_id']);
                    if(productImage){
                        return productImage.height;
                    }    
                }
                
                return originalResult;
            },

            /**
             * @param {Object} item
             * @return {null}
             */
            getAlt: function (item) {
                var originalResult = this._super();

                if(originalResult === null){
                    var productImage = this.getProductImage(item['item_id']);
                    if(productImage){
                        return productImage.alt;
                    }    
                }
                
                return originalResult;
            },

            getProductImage: function(item_id){
                var productimages = []
                jQuery.each(this.getCartData().items, function (key,quoteitems) {
                    if(item_id == parseInt(quoteitems.item_id)){
                        productimages = quoteitems.product_image;
                        return false;
                    }
                });

                return productimages;
            },

            getCartData: function(){
                var cart = customerData.get('cart');
                return cart();
            }
        });
    };
});
