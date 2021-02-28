<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Carbonclick\CFC\Block\System\Config;

class Checkbox extends \Magento\Config\Block\System\Config\Form\Field
{

    const CONFIG_PATH = 'cfc/general/widget_location';
 
    protected $_template = 'Carbonclick_CFC::system/config/checkbox.phtml';
 
    protected $_values = null;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Config\Model\Config\Structure $configStructure
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }
    /**
     * Retrieve element HTML markup.
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     *
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->_values = $element->getValue();

        $this->setNamePrefix($element->getName())
            ->setHtmlId($element->getHtmlId());
 
        return $this->_toHtml();
    }
    
    public function getValues()
    {
        $values = [];
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
 
        foreach ($objectManager->create('Carbonclick\CFC\Model\Config\Checkbox')->toOptionArray() as $value) {
            $values[$value['value']] = $value;
        }
 
        return $values;
    }
    /**
     * 
     * @param  $name 
     * @return boolean
     */
    public function getIsChecked($name)
    {
        return in_array($name, $this->getCheckedValues());
    }
    /**
     * 
     *get the checked value from config
     */
    public function getCheckedValues()
    {

        if (is_null($this->_values)) {
            $data = $this->getConfigData();
            if (isset($data[self::CONFIG_PATH])) {
                $data = $data[self::CONFIG_PATH];
            } else {
                $data = '';
            }
            $this->_values = explode(',', $data);
        }

        if($this->_values && !is_array($this->_values)){
            $this->_values = explode(",", $this->_values);
        }
 
        return $this->_values;
    }
}
