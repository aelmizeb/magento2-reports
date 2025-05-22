<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    private const XML_PATH_ENABLE_CUSTOMER_BY_COUNTRY_WIDGET = 'originalapp_reports/dashboard/enable_customer_by_country_widget';
    private const XML_PATH_ENABLE_TOP_SELLING_PRODUCTS_WIDGET = 'originalapp_reports/dashboard/enable_top_selling_products_widget';
    private const XML_PATH_ENABLE_SALES_BY_CATEGORY_WIDGET = 'originalapp_reports/dashboard/enable_sales_by_category_widget';
    private const XML_PATH_ENABLE_SALES_BY_COUNTRY_WIDGET = 'originalapp_reports/dashboard/enable_sales_by_country_widget';
    private const XML_PATH_ENABLE_MONTHLY_SALES_GROWTH_WIDGET = 'originalapp_reports/dashboard/enable_monthly_sales_growth_widget';
    private const XML_PATH_SALES_ORDER_STATUSES = 'originalapp_reports/dashboard/sales_order_statuses';

    public function isCustomerByCountryWidgetEnabled(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLE_CUSTOMER_BY_COUNTRY_WIDGET,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function isTopSellingProductsWidgetEnabled(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLE_TOP_SELLING_PRODUCTS_WIDGET,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function isSalesByCategoryWidgetEnabled(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLE_SALES_BY_CATEGORY_WIDGET,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function isSalesByCountryWidgetEnabled(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLE_SALES_BY_COUNTRY_WIDGET,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function isMonthlySalesGrowthWidgetEnabled(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLE_MONTHLY_SALES_GROWTH_WIDGET,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getSalesOrderStatuses(?int $storeId = null): array
    {
        $statuses = $this->scopeConfig->getValue(
            self::XML_PATH_SALES_ORDER_STATUSES,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        return $statuses ? array_map('trim', explode(',', $statuses)) : [];
    }
}

