<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Block\Adminhtml\Dashboard\Sales;

use Magento\Backend\Block\Template;
use Magento\Framework\App\ResourceConnection;
use Originalapp\Reports\Helper\Config as ReportsConfig;
use Originalapp\Reports\Model\CountryStats;

class Heatmap extends Template
{
    protected CountryStats $countryStats;
    protected ReportsConfig $config;

    public function __construct(
        Template\Context $context,
        CountryStats $countryStats,
        ReportsConfig $config,
        array $data = []
    ) {
        $this->countryStats = $countryStats;
        $this->config = $config;
        parent::__construct($context, $data);
    }

    public function getSalesByCountry(): array
    {
        return $this->countryStats->getSalesByCountry();
    }

    public function canRenderWidget(): bool
    {
        return $this->config->isSalesByCountryWidgetEnabled();
    }
}
