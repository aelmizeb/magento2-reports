<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Block\Adminhtml\Dashboard\Sales;

use Magento\Framework\View\Element\Template;
use Originalapp\Reports\Model\ProductStats;

class TopProducts extends Template
{
    protected ProductStats $productStats;

    public function __construct(
        Template\Context $context,
        ProductStats $productStats,
        array $data = []
    ) {
        $this->productStats = $productStats;
        parent::__construct($context, $data);
    }

    public function getTopSellingProducts(int $limit = 5): array
    {
        return $this->productStats->getTopSellingProducts($limit);
    }
}

