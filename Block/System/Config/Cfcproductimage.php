<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Carbonclick\CFC\Block\System\Config;

class Cfcproductimage extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * Retrieve element HTML markup
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $output = parent::_getElementHtml($element);
        $value = $element->getData('value');
        $name = $element->getData('name');
        if ($value) {
            $filename = "cloud-".$value.".png";
            $output .= '
                <div id="carbonclick_product_image_preview" class="inner-logo-bg co2-logo '.$value.'">
                    <fieldset>
                        <div>
                            <img id="carbonclick_product_image" src="'.$this->getViewFileUrl('Carbonclick_CFC::images/'.$filename).'" width="100%">
                        </div>
                    </fieldset>
                </div>
            ';
        }
        $output .= '
        <script type="text/javascript">
            require(["jquery"], function($) {
                $(function() {
                    $(\'input[name="'.$name.'"]\').change(function () {
                        var viewfileurl = "'.$this->getViewFileUrl('Carbonclick_CFC::images').'/";
                        var value = $(\'input[name="'.$name.'"]:checked\').val();
                        var filename = viewfileurl+"cloud-"+value+".png";
                        $("#carbonclick_product_image").attr("src",filename);
                        $("#carbonclick_product_image_preview").removeAttr("class");
                        $("#carbonclick_product_image_preview").attr("class","inner-logo-bg co2-logo "+value);
                    });
                });
            });
            </script>';
        return $output;
    }
}
