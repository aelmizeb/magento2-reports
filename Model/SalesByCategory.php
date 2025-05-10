<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Model;

use Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory as OrderItemCollectionFactory;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Api\ProductRepositoryInterface;

class SalesByCategory
{
    protected $orderItemCollectionFactory;
    protected $productRepository;
    protected $categoryRepository;

    public function __construct(
        OrderItemCollectionFactory $orderItemCollectionFactory,
        ProductRepositoryInterface $productRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->orderItemCollectionFactory = $orderItemCollectionFactory;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function getSalesByCategory(): array
    {
        $categorySales = [];
        $totalSales = 0;

        $orderItems = $this->orderItemCollectionFactory->create();
        $orderItems->getSelect()->columns(['product_id', 'row_total']);

        foreach ($orderItems as $item) {
            try {
                $product = $this->productRepository->getById($item->getProductId());
                $categoryIds = $product->getCategoryIds();

                if (!empty($categoryIds)) {
                    $categoryId = reset($categoryIds);
                    $category = $this->categoryRepository->get($categoryId);
                    $categoryName = $category->getName();

                    $categorySales[$categoryName] = ($categorySales[$categoryName] ?? 0) + $item->getRowTotal();
                    $totalSales += $item->getRowTotal();
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        arsort($categorySales);

        return [
            'all' => round($totalSales),
            'charts' => array_map('round', $categorySales)
        ];
    }
}
