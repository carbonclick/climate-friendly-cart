<?php
namespace Carbonclick\CFC\Controller\Adminhtml\Onboard;


class Card extends \Magento\Backend\App\Action
{   
    protected $createshop;

    protected $registry;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Carbonclick\CFC\Model\Service\Cfc\CreateShop $createshop,
        \Magento\Framework\Registry $registry
    )
    {
        $this->createshop = $createshop;
        $this->registry = $registry;
        parent::__construct($context);
    }

    public function execute()
    {
        if(empty($this->createshop->getConfiguration())){
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/dashboard/serverdown');
            return $resultRedirect;
        }
        $notice = $this->createshop->getConfig('cfc/general/payment');
        if($notice){
            $this->messageManager->addNotice($notice);
        }
        $invoicenotice = $this->createshop->getConfig('cfc/general/invoice');
        if($invoicenotice){
            $this->messageManager->addNotice($invoicenotice);
        }
        if($this->createshop->getShop()){
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/dashboard/index');
            return $resultRedirect;
        }
        $this->_view->loadLayout();
        $this->_view->getPage()->setActiveMenu('Carbonclick_CFC::cfc_dashboard');
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Climate Friendly Cart'));
        $this->_view->renderLayout();
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Carbonclick_CFC::cfc_dashboard');
    }
}