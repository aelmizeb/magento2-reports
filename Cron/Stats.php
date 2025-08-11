<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Cron;

use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Stats cron actions
 */
class Stats
{
    protected $orderCollectionFactory;
    protected $customerCollectionFactory;
    protected $productCollectionFactory;
    protected $resource;
    protected $serializer;

    public function __construct(
        OrderCollectionFactory $orderCollectionFactory,
        CustomerCollectionFactory $customerCollectionFactory,
        ProductCollectionFactory $productCollectionFactory,
        ResourceConnection $resource,
        Json $serializer
    ) {
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->customerCollectionFactory = $customerCollectionFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->resource = $resource;
        $this->serializer = $serializer;
    }

    public function execute()
    {
        // @todo: Implement stats gathering logic (to be reviewed)
        // @todo: Add error handling and logging
        // @todo: Optimize database interactions
        // @todo: Add more detailed statistics as needed
        // This method will gather statistics and save them to the database

        $connection = $this->resource->getConnection();
        $tableName = $this->resource->getTableName('originalapp_reports_stats');

        // Gather stats
        $totalOrders = $this->orderCollectionFactory->create()->getSize();
        $totalCustomers = $this->customerCollectionFactory->create()->getSize();
        $totalProducts = $this->productCollectionFactory->create()->getSize();

        $stats = [
            ['type' => 'total_orders', 'value' => $totalOrders],
            ['type' => 'total_customers', 'value' => $totalCustomers],
            ['type' => 'total_products', 'value' => $totalProducts],
        ];

        foreach ($stats as $stat) {
            $connection->insert($tableName, [
                'stat_type' => $stat['type'],
                'stat_value' => (string)$stat['value'],
                'additional_data' => $this->serializer->serialize(['source' => 'cron']),
                'created_at' => (new \DateTime())->format('Y-m-d H:i:s')
            ]);
        }

        return $this;
    }
}
