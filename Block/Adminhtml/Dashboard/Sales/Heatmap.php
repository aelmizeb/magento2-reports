<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Block\Adminhtml\Dashboard\Sales;

use Magento\Backend\Block\Template;
use Magento\Framework\App\ResourceConnection;
use Originalapp\Reports\Model\CountryStats;
use Originalapp\Reports\Helper\Data as HelperData;

class Heatmap extends Template
{
    protected CountryStats $countryStats;

    public function __construct(
        Template\Context $context,
        CountryStats $countryStats,
        array $data = []
    ) {
        $this->countryStats = $countryStats;
        parent::__construct($context, $data);
    }

    public function getSalesByCountry(): array
    {
        return $this->countryStats->getSalesByCountry();
    }
}
