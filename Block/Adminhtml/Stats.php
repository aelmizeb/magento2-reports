<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Framework\App\ResourceConnection;

class Stats extends Template
{
    private $resource;

    public function __construct(
        Template\Context $context,
        ResourceConnection $resource,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->resource = $resource;
    }

    public function getStats()
    {
        $connection = $this->resource->getConnection();
        $tableName = $this->resource->getTableName('originalapp_reports_stats');

        $select = $connection->select()
            ->from($tableName, ['stat_type', 'stat_value', 'created_at'])
            ->order('created_at DESC');

        return $connection->fetchAll($select);
    }
}
