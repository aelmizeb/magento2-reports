<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Model;

use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Originalapp\Reports\Helper\Config as ConfigHelper;

class SalesGrowth
{
    protected OrderCollectionFactory $orderCollectionFactory;
    protected ResolverInterface $localeResolver;
    protected TimezoneInterface $timezone;
    protected Escaper $escaper;
    protected ConfigHelper $configHelper;

    public function __construct(
        OrderCollectionFactory $orderCollectionFactory,
        ResolverInterface $localeResolver,
        TimezoneInterface $timezone,
        Escaper $escaper,
        ConfigHelper $configHelper
    ) {
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->localeResolver = $localeResolver;
        $this->timezone = $timezone;
        $this->escaper = $escaper;
        $this->configHelper = $configHelper;
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

        // Get order statuses
        $statusArray = $this->configHelper->getSalesOrderStatuses();

        if (!empty($statusArray)) {
            $orders->addFieldToFilter('status', ['in' => $statusArray]);
        }

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
