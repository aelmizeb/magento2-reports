<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Ui\DataProvider\Product;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
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
        $collection->addAttributeToSelect(['name', 'sku']);
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

        $salesData = $connection->fetchAll(
            "
            SELECT product_id, 
                   SUM(qty_ordered) AS total_qty_ordered,
                   SUM(base_row_total - base_discount_amount) AS total_sales
            FROM sales_order_item
            WHERE parent_item_id IS NULL
            GROUP BY product_id
            "
        );

        $salesMap = [];
        foreach ($salesData as $row) {
            $salesMap[(int)$row['product_id']] = [
                'total_sales' => (float)$row['total_sales'],
                'total_qty_ordered' => (int)$row['total_qty_ordered'],
            ];
        }

        $this->getCollection()->load();
        $totalRecords = $this->getCollection()->getSize();
        $items = [];

        foreach ($this->getCollection() as $product) {
            $productId = (int)$product->getId();
            $items[] = [
                'entity_id' => $productId,
                'name' => (string)$product->getName(),
                'sku' => (string)$product->getSku(),
                'total_sales' => $salesMap[$productId]['total_sales'] ?? 0.0,
                'total_qty_ordered' => $salesMap[$productId]['total_qty_ordered'] ?? 0,
            ];
        }

        return [
            'totalRecords' => $totalRecords,
            'items' => $items,
        ];
    }
}