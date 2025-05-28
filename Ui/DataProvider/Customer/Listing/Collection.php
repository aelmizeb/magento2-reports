<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Ui\DataProvider\Customer\Listing;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _initSelect()
    {
        parent::_initSelect();

        $this->getSelect()
            ->join(
                ['so' => $this->getTable('sales_order')],
                'main_table.entity_id = so.customer_id',
                ['total_sales' => 'SUM(so.grand_total)']
            )
            ->group('so.customer_id');

        return $this;
    }
}
