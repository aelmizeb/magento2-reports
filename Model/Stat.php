<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Model;

use Magento\Framework\Model\AbstractModel;

class Stat extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Originalapp\Reports\Model\ResourceModel\Stat::class);
    }
}
