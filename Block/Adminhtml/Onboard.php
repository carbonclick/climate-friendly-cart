<?php

namespace Carbonclick\CFC\Block\Adminhtml;

use Carbonclick\CFC\Model\Service\Cfc\Impactall;

class Onboard extends \Magento\Backend\Block\Template
{
    protected $impactall;

    protected $registry;

    /**
     * @param \Magento\Backend\Block\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        Impactall $impactall,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->impactall = $impactall;
        $this->registry = $registry;
        parent::__construct($context, $data);
    }

    public function getImpactData()
    {
        $impactall = $this->registry->registry('impactall');
        if ($impactall) {
            return $impactall;
        }
        return $this->impactall->getImpactAlldata();
    }

    public function ConvertToStoreWeight($weight, $unit)
    {
        $weightUnit = $this->impactall->getConfig("general/locale/weight_unit");

        if ($weightUnit == "kgs") {
            return '<span class="cfc-stat-number">'.number_format($weight, 0)."</span> ".$unit;
        } elseif ($weightUnit == "lbs") {
            return '<span class="cfc-stat-number">'.number_format($weight*2.20462262185, 0)."</span> ".strtoupper($weightUnit);
        }
        return '<span class="cfc-stat-number">'.number_format($weight, 0)."</span> ".$unit;
    }
}
