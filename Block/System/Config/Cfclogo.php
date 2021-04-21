<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Carbonclick\CFC\Block\System\Config;

class Cfclogo extends \Magento\Config\Block\System\Config\Form\Field
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
            $filename = "carbonclick-logo-".$value."-picker.svg";
            $output .= '
                <div id="carbonclick_logo_preview" class="inner-logo-bg '.$value.'">
                    <fieldset>
                        <div>
                            <img id="carbonclick_logo_image" src="'.$this->getViewFileUrl('Carbonclick_CFC::images/'.$filename).'" width="100%">
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
                        var filename = viewfileurl+"carbonclick-logo-"+value+"-picker.svg";
                        $("#carbonclick_logo_image").attr("src",filename);
                        $("#carbonclick_logo_preview").removeAttr("class");
                        $("#carbonclick_logo_preview").attr("class","inner-logo-bg "+value);
                    });
                });
            });
            </script>';
        return $output;
    }
}
