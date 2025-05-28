<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Plugin;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Originalapp\Reports\Ui\DataProvider\Product\ListingDataProvider as ProductDataProvider;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\App\ProductMetadataInterface;

class AddProductNameFilterToDataProvider
{
    private AttributeRepositoryInterface $attributeRepository;
    private ProductMetadataInterface $productMetadata;

    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        ProductMetadataInterface $productMetadata
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->productMetadata = $productMetadata;
    }

    public function afterGetSearchResult(ProductDataProvider $subject, SearchResult $result)
    {
        if ($result->isLoaded()) {
            return $result;
        }

        $edition = $this->productMetadata->getEdition();
        $column = $edition === 'Enterprise' ? 'row_id' : 'entity_id';

        $attribute = $this->attributeRepository->get('catalog_product', 'name');

        $result->getSelect()->joinLeft(
            ['product_name_attr' => $attribute->getBackendTable()],
            "product_name_attr.{$column} = main_table.{$column} AND product_name_attr.attribute_id = {$attribute->getAttributeId()}",
            ['name' => 'product_name_attr.value']
        );

        $result->getSelect()->where('product_name_attr.value LIKE ?', 'B%');

        return $result;
    }
}
