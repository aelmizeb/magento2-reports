<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Stat extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('originalapp_reports_stats', 'stat_id');
    }
}
