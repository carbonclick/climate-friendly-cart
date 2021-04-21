<?php
namespace Carbonclick\CFC\Controller\Adminhtml\Dashboard;

class SaveConfig extends \Magento\Backend\App\Action
{

    protected $saveDashboard;

    protected $registry;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Carbonclick\CFC\Model\Service\SaveDashboard $saveDashboard
    ) {
        $this->saveDashboard = $saveDashboard;
        parent::__construct($context);
    }

    public function execute()
    {
        if (count($this->getRequest()->getParams()) > 1) {
            try {
                $path = $this->getRequest()->getParam('name');
                $value = $this->getRequest()->getParam('value');
                $this->saveDashboard->saveConfig("cfc/dashboard/".$path, $value);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
        return;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Carbonclick_CFC::cfc_dashboard');
    }
}
