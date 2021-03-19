<?php

namespace Carbonclick\CFC\Block\Adminhtml;

class Card extends \Magento\Backend\Block\Template
{
    protected $authentication;

    /**
     * @param \Magento\Backend\Block\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Carbonclick\CFC\Model\Service\Cfc\Authentication $authentication,
        array $data = []
    )
    {
        $this->authentication = $authentication;
        parent::__construct($context, $data);
    }

    public function getCarbonclickConfig(){
        return $this->authentication->getAPIConfig();
    }
}
