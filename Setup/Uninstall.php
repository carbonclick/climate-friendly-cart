<?php

namespace Carbonclick\CFC\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;

class Uninstall implements UninstallInterface
{

    public function __construct(
        \Carbonclick\CFC\Model\Service\Cfc\UpdateStatus $updatestatus
    ) {
        $this->updatestatus = $updatestatus;
    }
    /**
     * @param  SchemaSetupInterface $setup
     * @param  ModuleContextInterface $context
     * @return null
     */
    public function uninstall(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $this->updatestatus->SendUpdateStatusRequest();
    }
}
