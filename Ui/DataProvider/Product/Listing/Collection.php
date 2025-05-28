<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Ui\DataProvider\Product\Listing;

use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;

class Collection extends ProductCollection
{
    protected function _initSelect()
    {
        parent::_initSelect();

        $this->getSelect()
            ->join(
                ['order_item' => $this->getTable('sales_order_item')],
                'e.entity_id = order_item.product_id AND order_item.parent_item_id IS NULL',
                [
                    'total_qty_ordered' => new \Zend_Db_Expr('SUM(order_item.qty_ordered)'),
                    'total_sales' => new \Zend_Db_Expr('SUM(order_item.base_row_total - order_item.base_discount_amount)')
                ]
            )
            ->group('e.entity_id')
            ->order('total_sales DESC');

        return $this;
    }
}