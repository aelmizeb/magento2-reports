<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Ui\DataProvider\Customer;

use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Api\Filter;

class ListingDataProvider extends AbstractDataProvider
{
    protected ResourceConnection $resource;
    protected array $addFieldStrategies;
    protected array $addFilterStrategies;

    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        ResourceConnection $resource,
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ) {
        $this->resource = $resource;
        $this->addFieldStrategies = $addFieldStrategies;
        $this->addFilterStrategies = $addFilterStrategies;

        $collection = $collectionFactory->create();
        $collection->addAttributeToSelect(['firstname', 'lastname', 'email']);
        $this->collection = $collection;

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function addFilter(Filter $filter): void
    {
        if (isset($this->addFilterStrategies[$filter->getField()])) {
            $this->addFilterStrategies[$filter->getField()]->addFilter($this->collection, $filter);
        } else {
            parent::addFilter($filter);
        }
    }

    public function getData(): array
    {
        $connection = $this->resource->getConnection();

        $salesData = $connection->fetchPairs(
            "
            SELECT customer_id, SUM(base_grand_total) AS total_sales
            FROM sales_order
            WHERE status NOT IN ('canceled') AND customer_id IS NOT NULL
            GROUP BY customer_id
            "
        );

        $this->getCollection()->load();
        $totalRecords = $this->getCollection()->getSize();
        $items = [];

        foreach ($this->getCollection() as $customer) {
            $customerId = (int)$customer->getId();
            $items[] = [
                'entity_id' => $customerId,
                'firstname' => (string)$customer->getFirstname(),
                'lastname' => (string)$customer->getLastname(),
                'email' => (string)$customer->getEmail(),
                'total_sales' => isset($salesData[$customerId]) ? (float)$salesData[$customerId] : 0.0,
            ];
        }

        return [
            'totalRecords' => $totalRecords,
            'items' => $items,
        ];
    }
}
