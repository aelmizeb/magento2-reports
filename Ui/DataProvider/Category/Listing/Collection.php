<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Ui\DataProvider\Category\Listing;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult
{

      protected function _initSelect()
      {
          $this->addFilterToMap('entity_id', 'main_table.entity_id');
          $this->addFilterToMap('name', 'category_name_attr.value');
          parent::_initSelect();
      }
}