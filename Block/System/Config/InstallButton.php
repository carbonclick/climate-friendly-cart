<?php

namespace Carbonclick\CFC\Block\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class InstallButton extends Field
{

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $element->setData('value', __("Request Installation Help"));
        $element->setData('class', "action-default");
        $element->setData('onclick', 'window.open("'.$this->getActionUrl().'","_self")');

        return '<div style="margin-bottom:10px">'
            . __('Wuold you like CarbonClick to take care of installing the widget to your theme? No extra charge.')
            . '</div>'
            . parent::_getElementHtml($element);
    }

    public function getActionUrl()
    {
        return $this->getUrl("cfc/system/installation");
    }
}
