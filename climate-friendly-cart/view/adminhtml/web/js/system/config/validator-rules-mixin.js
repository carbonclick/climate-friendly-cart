define([
    'jquery'
], function ($) {
    'use strict';

    return function (target) {
        $("#cfc_general_offset").keyup(function() {
            var offset = parseFloat($("#cfc_general_offset").val());
            var topup = parseFloat($("#cfc_general_topup").val());
            if($.isNumeric(offset) && $.isNumeric(topup)){
                if(offset > topup){
                    $("#cfc_general_topup").val(offset);
                }
            }
        });
        
        $.validator.addMethod(
            'validate-preferred-topup',
            function (value) {
                var offsetvalue = $("#cfc_general_offset").val();
                return !(parseFloat(value) < parseFloat(offsetvalue));
            },
            $.mage.__('Preferred Topup Can\'t be lower then offset value.')
        );

        return target;
    };
});