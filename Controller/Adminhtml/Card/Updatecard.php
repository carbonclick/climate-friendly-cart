<?php
namespace Carbonclick\CFC\Controller\Adminhtml\Card;

class Updatecard extends \Magento\Backend\App\Action
{
    protected $updatecard;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Carbonclick\CFC\Model\Service\Cfc\UpdateCard $updatecard
    ) {
        $this->updatecard = $updatecard;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if (empty($this->updatecard->getShop())) {
            $resultRedirect->setPath('*/onboard/index');
            return $resultRedirect;
        }

        if ($this->getRequest()->getParam('stripeToken')) {
            $card = $this->updatecard->getUpdatedcard($this->getRequest()->getParam('stripeToken'));
            if (!empty($card)) {
                if ($card['success'] == true) {
                    $this->messageManager->addSuccessMessage("Card has been updated sucessfully.");
                } else {
                    $this->messageManager->addErrorMessage($card['message']);
                }
                $resultRedirect->setPath('*/*/index');
                return $resultRedirect;
            }
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
