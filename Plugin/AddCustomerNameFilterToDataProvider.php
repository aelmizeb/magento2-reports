<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Originalapp\Reports\Plugin;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Originalapp\Reports\Ui\DataProvider\Customer\ListingDataProvider as CustomerDataProvider;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Framework\App\ProductMetadataInterface;

class AddCustomerNameFilterToDataProvider
{
    private ProductMetadataInterface $productMetadata;
    private CustomerMetadataInterface $customerMetadata;

    public function __construct(
        CustomerMetadataInterface $customerMetadata,
        ProductMetadataInterface $productMetadata
    ) {
        $this->customerMetadata = $customerMetadata;
        $this->productMetadata = $productMetadata;
    }

    public function afterGetSearchResult(CustomerDataProvider $subject, SearchResult $result)
    {
        if ($result->isLoaded()) {
            return $result;
        }

        // Customer table primary key varies on edition, typically 'entity_id'
        $edition = $this->productMetadata->getEdition();
        $column = $edition === 'Enterprise' ? 'row_id' : 'entity_id';

        // Join the customer_entity_varchar table to get 'firstname' attribute value
        $attributeId = $this->customerMetadata->getAttributeMetadata('firstname')->getAttributeId();

        $result->getSelect()->joinLeft(
            ['customer_firstname_attr' => 'customer_entity_varchar'],
            "customer_firstname_attr.{$column} = main_table.{$column} AND customer_firstname_attr.attribute_id = {$attributeId}",
            ['firstname' => 'customer_firstname_attr.value']
        );

        // Example filter: firstname LIKE 'A%'
        $result->getSelect()->where('customer_firstname_attr.value LIKE ?', 'A%');

        return $result;
    }
}
