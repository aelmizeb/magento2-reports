<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Ui\DataProvider\Category;

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
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
        $collection->addAttributeToSelect(['name', 'is_active', 'level']);
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

        // Get total sales per category
        // @todo add filter for period that user can configure 
        $salesData = $connection->fetchPairs(
            "
            SELECT cc.entity_id AS category_id, SUM(oi.row_total) AS total_sales
            FROM catalog_category_product ccp
            INNER JOIN catalog_category_entity AS cc ON ccp.category_id = cc.entity_id
            INNER JOIN sales_order_item AS oi ON oi.product_id = ccp.product_id
            INNER JOIN sales_order AS o ON o.entity_id = oi.order_id
            WHERE o.status NOT IN ('canceled')
            AND oi.parent_item_id IS NULL
            GROUP BY cc.entity_id
            "
        );

        // Pagination
        $this->getCollection()->load();
        $totalRecords = $this->getCollection()->getSize();
        $items = [];

        foreach ($this->getCollection() as $category) {
            $categoryId = (int)$category->getId();
            $level = (int)$category->getLevel();
            $name = (string)$category->getName();

            // Format name with level if needed
            // if ($level <= 1) {
            //     $formattedName = '+ ' . $name;
            // } else {
            //     $formattedName = str_repeat('-', $level - 1) . ' ' . $name;
            // }

            // no need to format for the moment
            $formattedName = $name;

            $items[] = [
                'entity_id' => $categoryId,
                'name' => $formattedName,
                'total_sales' => isset($salesData[$categoryId])
                    ? (float)$salesData[$categoryId]
                    : 0.0,
            ];
        }

        return [
            'totalRecords' => $totalRecords,
            'items' => $items,
        ];
    }
}
