<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Block\Adminhtml\Dashboard\Sales;

use Magento\Framework\View\Element\Template;
use Originalapp\Reports\Helper\Config as ReportsConfig;
use Originalapp\Reports\Model\ProductStats;

class TopProducts extends Template
{
    protected ProductStats $productStats;
    protected ReportsConfig $config;

    public function __construct(
        Template\Context $context,
        ProductStats $productStats,
        ReportsConfig $config,
        array $data = []
    ) {
        $this->productStats = $productStats;
        $this->config = $config;
        parent::__construct($context, $data);
    }

    public function getTopSellingProducts(int $limit = 5): array
    {
        return $this->productStats->getTopSellingProducts($limit);
    }

    public function canRenderWidget(): bool
    {
        return $this->config->isTopSellingProductsWidgetEnabled();
    }
}

