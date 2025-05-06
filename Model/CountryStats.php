<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Model;

use Magento\Framework\App\ResourceConnection;
use Originalapp\Reports\Helper\Data as HelperData;

class CountryStats
{
    protected ResourceConnection $resource;
    protected HelperData $helper;

    public function __construct(
        ResourceConnection $resource,
        HelperData $helper
    ) {
        $this->resource = $resource;
        $this->helper = $helper;
    }

    public function getCustomerCountByCountry(): array
    {
        $connection = $this->resource->getConnection();
        $table = $this->resource->getTableName('customer_address_entity');

        $select = $connection->select()
            ->from(['cae' => $table], ['country_id', 'total' => 'COUNT(*)'])
            ->group('cae.country_id');

        $results = $connection->fetchAll($select);
        return $this->mapCountryResults($results);
    }

    public function getSalesByCountry(): array
    {
        $connection = $this->resource->getConnection();
        $salesAddressTable = $this->resource->getTableName('sales_order_address');
        $salesOrderTable = $this->resource->getTableName('sales_order');

        $select = $connection->select()
            ->from(['soa' => $salesAddressTable], [])
            ->join(
                ['so' => $salesOrderTable],
                'so.entity_id = soa.parent_id',
                ['country_id' => 'soa.country_id', 'total' => 'SUM(so.grand_total)']
            )
            ->where('soa.address_type = ?', 'shipping')
            ->group('soa.country_id');

        $results = $connection->fetchAll($select);
        return $this->mapCountryResults($results);
    }

    private function mapCountryResults(array $rows): array
    {
        $mapped = [];

        foreach ($rows as $row) {
            $mapped[] = [
                'name' => $this->helper->getCountryName($row['country_id']),
                'value' => (float) $row['total']
            ];
        }

        return $mapped;
    }
}
