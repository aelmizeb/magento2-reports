<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Model;

use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\Escaper;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class SalesGrowth
{
    protected $orderCollectionFactory;
    protected $scopeConfig;
    protected $localeResolver;
    protected $timezone;
    protected $escaper;

    const XML_PATH_SALES_ORDER_STATUSES = 'originalapp_reports/sales_growth/order_statuses';

    public function __construct(
        OrderCollectionFactory $orderCollectionFactory,
        ScopeConfigInterface $scopeConfig,
        ResolverInterface $localeResolver,
        TimezoneInterface $timezone,
        Escaper $escaper
    ) {
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->scopeConfig = $scopeConfig;
        $this->localeResolver = $localeResolver;
        $this->timezone = $timezone;
        $this->escaper = $escaper;
    }

    public function getMonthlySalesData(): array
    {
        $locale = $this->localeResolver->getLocale();

        // Translated month names
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthName = (new \DateTime())->setDate((int)date('Y'), $i, 1)->format('M');
            
            $months[] = $this->escaper->escapeHtml(__($monthName));
        }

        $sales = array_fill(0, 12, 0);
        $currentYear = date('Y');

        $orders = $this->orderCollectionFactory->create();
        $orders->addFieldToFilter('created_at', ['gteq' => $currentYear . '-01-01 00:00:00']);

        // Fetch order statuses from config (comma-separated)
        $configuredStatuses = $this->scopeConfig->getValue(self::XML_PATH_SALES_ORDER_STATUSES, ScopeInterface::SCOPE_STORE);
        $statusArray = array_map('trim', explode(',', $configuredStatuses ?: 'processing,complete'));

        $orders->addFieldToFilter('status', ['in' => $statusArray]);

        foreach ($orders as $order) {
            $createdAt = $this->timezone->date($order->getCreatedAt()); // respects store timezone
            $monthIndex = (int) $createdAt->format('n') - 1;
            $sales[$monthIndex] += (float) $order->getGrandTotal();
        }

        return [
            'months' => $months,
            'sales' => array_map('round', $sales)
        ];
    }
}
