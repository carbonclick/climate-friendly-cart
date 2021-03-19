<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Carbonclick\CFC\Block\System\Config;

class ColorGuide extends \Magento\Config\Block\System\Config\Form\Field
{

    /**
     * @var string
     */
    protected $_template = 'Carbonclick_CFC::colorguide.phtml';

    /**
     * Retrieve element HTML markup
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->toHtml();
    }
}
