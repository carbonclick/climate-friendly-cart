<?php

namespace Carbonclick\CFC\Model;

use Magento\Catalog\Model\ProductFactory;
use Carbonclick\CFC\Service\ImportImageService;
use Carbonclick\CFC\Model\Service\SaveDashboard;
use Carbonclick\CFC\Model\Service\Cfc\Countries;
use Carbonclick\CFC\Model\Service\Cfc\UpdateShop;
use Magento\Framework\View\Asset\Repository;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\StoreRepository;
use Magento\Catalog\Model\ProductRepository;

class CreateProduct
{
    protected $product;

    protected $saveconfig;

    protected $image;

    protected $assetRepo;

    protected $request;

    protected $countries;

    protected $updateshop;

    protected $storeRepository;

    protected $logger;

    protected $productRepository;

    public function __construct(
        ProductFactory $product,
        SaveDashboard $saveconfig,
        ImportImageService $image,
        Countries $countries,
        Repository $assetRepo,
        RequestInterface $request,
        StoreRepository $StoreRepository,
        UpdateShop $updateshop,
        \Psr\Log\LoggerInterface $logger,
        ProductRepository $productRepository
    ) {
        $this->product = $product;
        $this->storeRepository = $StoreRepository;
        $this->image = $image;
        $this->countries = $countries;
        $this->assetRepo = $assetRepo;
        $this->saveconfig = $saveconfig;
        $this->request = $request;
        $this->updateshop = $updateshop;
        $this->logger = $logger;
        $this->productRepository = $productRepository;
    }

    public function CreateProduct()
    {
        $value = $this->saveconfig->getConfig("cfc/lookandfeel/cfcproductimage/color_option");
        $filename = "cloud-".$value.".png";
        $params = ['_secure' => $this->request->isSecure()];
        $imageUrl = $this->assetRepo->getUrlWithParams('Carbonclick_CFC::images/'.$filename, $params);
        $taxId = $this->countries->getStoretaxable() == 1 ? 2 : 0;
        try {
            $product = $this->getProduct();
            $product->setName('Carbon Offset');
            $product->setAttributeSetId(4);
            $product->setWebsiteIds($this->getWebsite());
            $product->setStatus(1);
            $product->setShortDescription("CarbonClick's carbon offsets help neutralize the carbon emissions from your purchase. Your contribution helps funds forest restoration, tree planting, and clean energy projects that fight climate change. All it takes is a single click at the checkout.");
            $product->setDescription("CarbonClick's carbon offsets help neutralize the carbon emissions from your purchase. Your contribution helps funds forest restoration, tree planting, and clean energy projects that fight climate change. All it takes is a single click at the checkout.");
            $product->setVisibility(1);
            $product->setTaxClassId($taxId);
            $product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL);
            $product->setPrice($this->saveconfig->getConfig("cfc/general/offset"));
            $product->setUrlKey('carbon-offset-'.strtotime("now"));
            $product->setStockData(
                [
                    'use_config_manage_stock' => 0,
                    'manage_stock' => 0,
                    'is_in_stock' => 1,
                    'use_config_max_sale_qty'=> 1,
                ]
            );

            $this->image->execute($product, $imageUrl, true, ['image', 'small_image', 'thumbnail']);
            $product->save();
            $this->saveconfig->saveConfig('cfc/general/product', $product->getId());
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $product;
    }

    public function getWebsite()
    {
        $stores = $this->storeRepository->getList();
        $websiteIds = [];
        foreach ($stores as $store) {
            $websiteId = $store["website_id"];
            array_push($websiteIds, $websiteId);
        }

        return $websiteIds;
    }

    public function UpdateProductImage($value)
    {
        $productId = $this->saveconfig->getConfig("cfc/general/product");
        $filename = "cloud-".$value.".png";
        $params = ['_secure' => $this->request->isSecure()];
        $imageUrl = $this->assetRepo->getUrlWithParams('Carbonclick_CFC::images/'.$filename, $params);
        try {
            $product = $this->product->create();
            if ($productId) {
                $product->load($productId);
                $this->image->execute($product, $imageUrl, false, ['image', 'small_image', 'thumbnail']);
                $product->save();
            }
        } catch (\Exception $e) {
            //echo $e->getMessage(); die;
            $this->logger->error($e->getMessage());
        }
        return $product;
    }

    public function UpdatePrice($price)
    {
        $productId = $this->saveconfig->getConfig("cfc/general/product");
        $taxId = $this->countries->getStoretaxable() == 1 ? 2 : 0;
        try {
            $product = $this->product->create();
            if ($productId) {
                $product->load($productId);
                $product->setPrice($price);
                $product->setTaxClassId($taxId);
                $product->setStockData(
                    [
                        'use_config_manage_stock' => 0,
                        'manage_stock' => 0,
                        'is_in_stock' => 1,
                        'use_config_max_sale_qty'=> 1
                    ]
                );
                $product->save();
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $product;
    }

    public function UpdateStatus($status)
    {
        $productId = $this->saveconfig->getConfig("cfc/general/product");
        try {
            $product = $this->product->create();
            if ($productId) {
                $product->load($productId);
                $product->setStatus($status == 0 ? 2 : $status);
                $product->save();
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $product;
    }
    public function UpdateSku($sku)
    {
        $productId = $this->saveconfig->getConfig("cfc/general/product");
        try {
            $skuExist = $this->productRepository->get($sku);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e){
            $skuExist = false;
        }
        try {
            $product = $this->product->create();
            if ($productId) {
                if($skuExist) {
                    return false;
                }
                $product->load($productId);
                $product->setSku($sku);
                $product->save();
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $product;
    }
    public function getProduct()
    {
        $model = $this->product->create();
        $product = $model->loadByAttribute('sku', 'carbon-offset');
        if ($product && $product->getId()) {
            return $product;
        }
        $model->setSku("carbon-offset");
        return $model;
    }
}
