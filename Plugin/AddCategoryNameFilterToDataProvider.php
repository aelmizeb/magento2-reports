<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Originalapp\Reports\Plugin;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Originalapp\Reports\Ui\DataProvider\Category\ListingDataProvider as CategoryDataProvider;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\App\ProductMetadataInterface;

class AddCategoryNameFilterToDataProvider
{
    private $productMetadata;
    private $attributeRepository;

    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        ProductMetadataInterface $productMetadata
    ) {
        $this->attributeRepository = $attributeRepository; 
        $this->productMetadata = $productMetadata;
    }

    public function afterGetSearchResult(CategoryDataProvider $subject, SearchResult $result)
    {
        if ($result->isLoaded()) {
            return $result;
        }

        $edition = $this->productMetadata->getEdition();
        $column = $edition === 'Enterprise' ? 'row_id' : 'entity_id';

        $attribute = $this->attributeRepository->get('catalog_category', 'name');

        $result->getSelect()->joinLeft(
            ['category_name_attr' => $attribute->getBackendTable()],
            "category_name_attr.{$column} = main_table.{$column} AND category_name_attr.attribute_id = {$attribute->getAttributeId()}",
            ['name' => 'category_name_attr.value']
        );

        $result->getSelect()->where('category_name_attr.value LIKE ?', 'B%');

        return $result;
    }
}