<?php
namespace Carbonclick\CFC\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Carbonclick\CFC\Model\CreateProduct;
use Magento\Framework\Message\ManagerInterface;

class ConfigObserver implements ObserverInterface
{
    private $request;
    private $configWriter;
    protected $createProduct;
    protected $messageManager;

    public function __construct(RequestInterface $request, WriterInterface $configWriter, CreateProduct $createProduct, ManagerInterface $messageManager)
    {
        $this->request = $request;
        $this->configWriter = $configWriter;
        $this->createProduct = $createProduct;
        $this->messageManager = $messageManager;
    }

    /**
     * Added condition to check if $params has set proper value
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        $params = $observer->getData('configData')['groups'];
        if(isset($params['general']['fields']['enable']['value']) || isset($params['general']['fields']['sku_enable']['value'])) {
            $enable_ext = $params['general']['fields']['enable']['value'];
            $sku_enable = $params['general']['fields']['sku_enable']['value'];

            if($enable_ext) {
                if($sku_enable){
                    $sku_value = $params['general']['fields']['sku_value']['value'];
                    $product = $this->createProduct->UpdateSku($sku_value);
                } else {
                    $product = $this->createProduct->UpdateSku('carbon-offset');
                }
            }
        }
        return $this;
    }
}
