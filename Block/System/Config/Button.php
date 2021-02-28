<?php

namespace Carbonclick\CFC\Block\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Button extends Field
{

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $element->setData('value', __("Open Instructions"));
        $element->setData('class', "action-default");
        $element->setData('onclick','window.open("'.$this->getActionUrl().'", "_blank")');

        return '<div style="margin-bottom:10px">'
            . __('Want to see the steps to add CarbonClick to your theme? (Opens in a new window).')
            . '</div>'
            . parent::_getElementHtml($element);
    }

    public function getActionUrl()
    {
        return "https://www.carbonclick.com/business-shopify-install-2_0/";
    }
}
