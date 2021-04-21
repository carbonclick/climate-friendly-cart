<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Carbonclick\CFC\Block\System\Config;

class Color extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * Retrieve element HTML markup
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $output = "<style>
            .field-color-picker {
                cursor: pointer;
                background: url({$this->getViewFileUrl('Carbonclick_CFC::images/colorpicker.png')}) no-repeat right;
            }
            .field-color-picker::-ms-clear {
                display: none;
            }
        </style>";
        $output .= parent::_getElementHtml($element);
        $value = $element->getData('value');

        $output .= '
        <script type="text/javascript">
            require(["jquery", "jquery/colorpicker/js/colorpicker"], function($) {
                $(function() {
                    var colorPicker = $("#'.$element->getHtmlId().'")
                        .attr("autocomplete", "off")
                        .css("backgroundColor", function() {
                            return $(this).val();
                        })
                    ;

                    colorPicker.ColorPicker({
                        onChange: function (hsb, hex, rgb) {
                            var color = "#" + hex;
                            colorPicker.val(color).css("backgroundColor", color);
                        }
                    });

                    colorPicker.change(function() {
                        var color = $(this).val();
                        $(this).ColorPickerSetColor(color).css("backgroundColor", color);
                    });
                });
            });
            </script>';
        return $output;
    }
}
