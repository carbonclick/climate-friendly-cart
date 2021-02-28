<?php
namespace Carbonclick\CFC\Controller\Adminhtml\Dashboard;


class Serverdown extends \Magento\Backend\App\Action
{   

    protected $createshop;

    protected $registry;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Carbonclick\CFC\Model\Service\Cfc\CreateShop $createshop
    )
    {
        $this->createshop = $createshop;
        parent::__construct($context);
    }

    public function execute()
    {
        if(!empty($this->createshop->getConfiguration())){
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/dashboard/index');
            return $resultRedirect;
        }
        $this->_view->loadLayout();
        $this->_view->getPage()->setActiveMenu('Carbonclick_CFC::cfc_dashboard');
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Downtime'));
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