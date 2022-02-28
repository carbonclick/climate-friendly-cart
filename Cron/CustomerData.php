<?php
namespace Carbonclick\CFC\Cron;

class CustomerData
{
    protected $merchantImpactService;

    protected $saveconfig;

    public function __construct(
    \Carbonclick\CFC\Model\Service\Cfc\MerchantImpact $merchantImpactService,
    \Carbonclick\CFC\Model\Service\SaveDashboard $saveconfig
    ) {
        $this->merchantImpactService = $merchantImpactService;
        $this->saveconfig = $saveconfig;
    }

    public function execute()
    {
        $merchantImpact = $this->getMerchantImpact();

        $impact["treeGrowthYears"] = $merchantImpact["treeGrowthYears"]["value"];
        $impact["carbonOffsetImpact"]["unit"] = $merchantImpact["carbonOffsetImpact"]["unit"];
        $impact["carbonOffsetImpact"]["value"] = $merchantImpact["carbonOffsetImpact"]["value"];
        $impact["numberOfContributions"]["value"] = $merchantImpact["numberOfContributions"]["value"];        

        $this->saveconfig->saveConfig('cfc/general/merchantImpact',json_encode($impact));
    }

    public function getMerchantImpact()
    {
        return $this->merchantImpactService->getMerchantImpact();
    }
}
