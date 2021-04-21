<?php
namespace Carbonclick\CFC\Controller\Adminhtml\Onboard;

class Createshop extends \Magento\Backend\App\Action
{
    protected $createshop;

    protected $createproduct;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Carbonclick\CFC\Model\Service\Cfc\CreateShop $createshop,
        \Carbonclick\CFC\Model\CreateProduct $createproduct
    ) {
        $this->createshop = $createshop;
        $this->createproduct = $createproduct;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $shop = $this->createshop->getShop();
        if (empty($shop)) {
            $shop = $this->createshop->CreateShop($this->getRequest()->getParam('stripeToken'));
        }
        if (!empty($shop)) {
            $this->createproduct->CreateProduct();
            $resultRedirect->setPath('*/dashboard/index');
            return $resultRedirect;
        }
        $resultRedirect->setPath('*/*/index');
        return $resultRedirect;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Carbonclick_CFC::cfc_dashboard');
    }
}
