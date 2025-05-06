<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Model;

use Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory as OrderItemCollectionFactory;

class ProductStats
{
    protected OrderItemCollectionFactory $orderItemCollectionFactory;

    public function __construct(
        OrderItemCollectionFactory $orderItemCollectionFactory
    ) {
        $this->orderItemCollectionFactory = $orderItemCollectionFactory;
    }

    /**
     * Get top-selling products
     *
     * @param int $limit Number of products to return
     * @return array ['names' => [...], 'sales' => [...]]
     */
    public function getTopSellingProducts(int $limit = 5): array
    {
        $collection = $this->orderItemCollectionFactory->create();
        $collection->getSelect()
            ->columns(['total_qty_ordered' => 'SUM(qty_ordered)'])
            ->group('sku')
            ->order('total_qty_ordered DESC')
            ->limit($limit);

        $names = [];
        $sales = [];

        foreach ($collection as $item) {
            $names[] = $item->getName();
            $sales[] = (int) $item->getData('total_qty_ordered');
        }

        return ['names' => $names, 'sales' => $sales];
    }
}
