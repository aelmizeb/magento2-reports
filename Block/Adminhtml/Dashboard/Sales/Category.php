<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Block\Adminhtml\Dashboard\Sales;

use Magento\Backend\Block\Template;
use Originalapp\Reports\Helper\Data as HelperData;
use Originalapp\Reports\Model\SalesByCategory;

class Category extends Template
{
    protected $salesByCategory;
    protected $helper;

    public function __construct(
        Template\Context $context,
        SalesByCategory $salesByCategory,
        HelperData $helper,
        array $data = []
    ) {
        $this->salesByCategory = $salesByCategory;
        $this->helper = $helper;
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
}