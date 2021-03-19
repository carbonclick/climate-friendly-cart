<?php
namespace Carbonclick\CFC\Controller\Adminhtml\Onboard;

use Magento\Framework\Controller\Result\JsonFactory;

class Clientsecrate extends \Magento\Backend\App\Action
{   

    protected $_resultJsonFactory;

    protected $clientsecrate;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Carbonclick\CFC\Model\Service\Cfc\Clientsecrate $clientsecrate,
        JsonFactory $resultJsonFactory
    )
    {
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->clientsecrate = $clientsecrate;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->_resultJsonFactory->create();

        $secratekey = $this->clientsecrate->Clientsecrate();
        if($secratekey){
            $result->setData($secratekey);
        }else{
            $result->setData(["error"=> true]);     
        }
        
        return $result;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Carbonclick_CFC::cfc_dashboard');
    }
}