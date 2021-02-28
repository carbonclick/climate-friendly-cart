<?php
namespace Carbonclick\CFC\Controller\Adminhtml\System;


class Installation extends \Magento\Backend\App\Action
{
    protected $updateshop;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Carbonclick\CFC\Model\Service\Cfc\UpdateShop $updateshop
    )
    {
        $this->updateshop = $updateshop;
        parent::__construct($context);
    }

    public function execute()
    {
        
        try{
            $this->updateshop->UpdateShop(['install_help_required'=>true]);
            $this->messageManager->addSuccess( __('Installation help request sent suceesfully.') );
        }catch(\Exception $e){
            $this->messageManager->addError($e->getMessage());
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('adminhtml/system_config/edit',['section'=>'cfc']);
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