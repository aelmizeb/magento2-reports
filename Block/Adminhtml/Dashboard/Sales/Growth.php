<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Block\Adminhtml\Dashboard\Sales;

use Magento\Backend\Block\Template;
use Originalapp\Reports\Helper\Config as ReportsConfig;
use Originalapp\Reports\Helper\Data as HelperData;
use Originalapp\Reports\Model\SalesGrowth;

class Growth extends Template
{
    protected SalesGrowth $salesGrowth;
    protected HelperData $helper;
    protected ReportsConfig $config;

    public function __construct(
        Template\Context $context,
        SalesGrowth $salesGrowth,
        HelperData $helper,
        ReportsConfig $config,
        array $data = []
    ) {
        $this->salesGrowth = $salesGrowth;
        $this->helper = $helper;
        $this->config = $config;
        parent::__construct($context, $data);
    }

    public function getSalesGrowthJson(): string
    {
        return json_encode($this->salesGrowth->getMonthlySalesData());
    }

    public function getCurrencySymbol(): string
    {
        return $this->helper->getCurrencySymbol();
    }

    public function canRenderWidget(): bool
    {
        return $this->config->isMonthlySalesGrowthWidgetEnabled();
    }
}
