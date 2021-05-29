<?php

namespace Carbonclick\CFC\Model\Service;

abstract class AbstractService
{
    // DO NOT CHANGE THE THE VALUE OF THIS VARIABLE . Our make script performs a search/replace on this to insert the correct url for each environment.
    const CARBONCLICK_CONFIG_URL = 'https://extmagewoo.carbon.click/';

    protected $scopeConfig;

    protected $storeManager;

    protected $messageManager;

    protected $authSession;

    protected $configWriter;

    protected $curl;

    protected $curlFactory;

    protected $orderCollectionFactory;

    protected $jsonHelper;

    protected $logger;

    protected $authdata = [];

    protected $apiConfig = [];
    
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Backend\Model\Auth\SessionFactory $authSession,
        Curl $curl,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Framework\HTTP\Adapter\CurlFactory $curlFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Psr\Log\LoggerInterface $logger
    ) {
        
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->configWriter = $configWriter;
        $this->authSession = $authSession;
        $this->messageManager = $messageManager;
        $this->curl = $curl;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->curlFactory = $curlFactory;
        $this->jsonHelper = $jsonHelper;
        $this->logger = $logger;
    }

    public function getConfig($path)
    {
        return $this->scopeConfig->getValue($path);
    }

    protected function refreshCache()
    {
        
        $this->scopeConfig->clean();
    }

    public function getConfiguration()
    {
        $params = [
            'type'=>'magento'
        ];

        try {
            $this->curl->setOption(CURLOPT_TIMEOUT, 60);
            $this->curl->addHeader("Content-Type", "application/json");
            $this->curl->addHeader("Accept", "application/json");
            $this->curl->post(self::CARBONCLICK_CONFIG_URL.'api/carbonclick/config', $this->jsonHelper->jsonEncode($params));
            $response = $this->curl->getBody();

            if ($this->curl->getStatus() == 200) {
                return $response;
            } else {
                throw new \Exception($response);
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return;
        }
        return;
    }

    public function getAPIConfig()
    {
        if (empty($this->apiConfig)) {
            $config = $this->getConfiguration();
            if ($config) {
                $this->setAPIConfig($this->jsonHelper->jsonDecode($config));
            }
        }
        return $this->apiConfig;
    }

    public function setAPIConfig($data = [])
    {
        $this->apiConfig = $data;
        return $this;
    }

    public function getOrderCount()
    {
        $ordercollection = $this->orderCollectionFactory->create();
        return $ordercollection->count();
    }
}
