<?php
namespace Originalapp\Reports\Block\Adminhtml\Dashboard;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Element\Template;
use Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory as OrderItemCollectionFactory;

class TopProducts extends Template
{
    protected $orderItemCollectionFactory;

    public function __construct(
        Context $context,
        OrderItemCollectionFactory $orderItemCollectionFactory,
        array $data = []
    ) {
        $this->orderItemCollectionFactory = $orderItemCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getTopSellingProducts($limit = 5)
    {
        $collection = $this->orderItemCollectionFactory->create();
        $collection->getSelect()
            ->columns(['total_qty_ordered' => 'SUM(qty_ordered)'])
            ->group('sku')
            ->order('total_qty_ordered DESC')
            ->limit($limit);

        $names = [];
        $sales = [];

        foreach ($collection as $item) {
            $names[] = $item->getName();
            $sales[] = (int) $item->getData('total_qty_ordered');
        }

        return ['names' => $names, 'sales' => $sales];
    }
}
