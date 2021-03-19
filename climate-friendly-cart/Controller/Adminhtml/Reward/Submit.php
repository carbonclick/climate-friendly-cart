<?php
namespace Carbonclick\CFC\Controller\Adminhtml\Reward;


class Submit extends \Magento\Backend\App\Action
{
    protected $reward;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Carbonclick\CFC\Model\Service\Cfc\Reward $reward
    )
    {
        $this->reward = $reward;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if(empty($this->reward->getShop())){
            $resultRedirect->setPath('*/onboard/index');
            return $resultRedirect;
        }
        
        if($this->getRequest()->getParam('reward')){
            $reward = $this->reward->RedeemReward($this->getRequest()->getParam('reward'));
            
            if(!empty($reward)){
                if($reward['success'] == true){
                    $this->messageManager->addSuccessMessage("Redeem request placed sucessfully.");
                }else{
                    $this->messageManager->addErrorMessage($reward['message']); 
                }
            }
            $resultRedirect->setPath('*/*/index');
            return $resultRedirect;
        }
        
        $this->messageManager->addErrorMessage("Error Processing Request. Please check log for it.");    
        $resultRedirect->setPath('*/*/index');
        return $resultRedirect;

    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Carbonclick_CFC::cfc_card');
    }
}