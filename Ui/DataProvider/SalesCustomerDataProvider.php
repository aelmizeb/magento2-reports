<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Ui\DataProvider;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Originalapp\Reports\Model\ResourceModel\Report\Collection\Customer as CustomerCollection;

class SalesCustomerDataProvider extends AbstractDataProvider
{
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CustomerCollection $collection,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collection;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
}
