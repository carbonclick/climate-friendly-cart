<?php
namespace Carbonclick\CFC\Controller\Adminhtml\Card;

class Index extends \Magento\Backend\App\Action
{

    protected $createshop;

    protected $registry;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Carbonclick\CFC\Model\Service\Cfc\CreateShop $createshop
    ) {
        $this->createshop = $createshop;
        parent::__construct($context);
    }

    public function execute()
    {
        if (empty($this->createshop->getConfiguration())) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/dashboard/serverdown');
            return $resultRedirect;
        }
        $notice = $this->createshop->getConfig('cfc/general/payment');
        if ($notice) {
            $this->messageManager->addNotice($notice);
        }
        $invoicenotice = $this->createshop->getConfig('cfc/general/invoice');
        if ($invoicenotice) {
            $this->messageManager->addNotice($invoicenotice);
        }
        if (empty($this->createshop->getShop())) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/onboard/index');
            return $resultRedirect;
        }
        $this->_view->loadLayout();
        $this->_view->getPage()->setActiveMenu('Carbonclick_CFC::cfc_card');
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Your Account'));
        $this->_view->renderLayout();
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Carbonclick_CFC::cfc_card');
    }
}
