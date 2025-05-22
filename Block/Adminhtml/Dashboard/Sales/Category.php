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
use Originalapp\Reports\Model\SalesByCategory;

class Category extends Template
{
    protected SalesByCategory $salesByCategory;
    protected HelperData $helper;
    protected ReportsConfig $config;

    public function __construct(
        Template\Context $context,
        SalesByCategory $salesByCategory,
        HelperData $helper,
        ReportsConfig $config,
        array $data = []
    ) {
        $this->salesByCategory = $salesByCategory;
        $this->helper = $helper;
        $this->config = $config;
        parent::__construct($context, $data);
    }

    public function getSalesDataJson(): string
    {
        return json_encode($this->salesByCategory->getSalesByCategory());
    }

    public function getCurrencySymbol(): string
    {
        return $this->helper->getCurrencySymbol();
    }

    public function canRenderWidget(): bool
    {
        return $this->config->isSalesByCategoryWidgetEnabled();
    }
}