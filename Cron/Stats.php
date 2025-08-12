<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB.
 * All rights reserved.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Cron;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Serialize\Serializer\Json;
use Originalapp\Reports\Model\StatFactory;
use Originalapp\Reports\Helper\Config as ConfigHelper;
use Psr\Log\LoggerInterface;

/**
 * Stats cron actions
 */
class Stats
{
    protected $resource;
    protected $serializer;
    protected $statFactory;
    protected $logger;
    protected $configHelper;

    public function __construct(
        ResourceConnection $resource,
        Json $serializer,
        StatFactory $statFactory,
        LoggerInterface $logger,
        ConfigHelper $configHelper
    ) {
        $this->resource = $resource;
        $this->serializer = $serializer;
        $this->statFactory = $statFactory;
        $this->logger = $logger;
        $this->configHelper = $configHelper;
    }

    public function execute()
    {
        try {
            $now = (new \DateTime())->format('Y-m-d H:i:s');
            $meta = $this->serializer->serialize([
                'source' => 'cron',
            ]);

            $preserveHistory = $this->configHelper->isPreserveStatsHistory();

            // Gather stats
            $stats = array_merge(
                $this->getGeneralStats(),
                $this->getAnnualStats()
            );

            // Save stats
            foreach ($stats as $stat) {
                $this->saveStat(
                    $stat['type'], 
                    (string) $stat['value'], 
                    $meta, 
                    $now, 
                    !$preserveHistory // clearBeforeInsert = true if NOT preserving history
                );
            }

            $this->logger->info('[Originalapp_Reports] Stats cron completed', [
                'count' => count($stats)
            ]);

        } catch (\Throwable $e) {
            $this->logger->error('[Originalapp_Reports] Cron error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * General statistics (all-time)
     */
    private function getGeneralStats(): array
    {
        $connection = $this->resource->getConnection();

        $tableOrder       = $this->resource->getTableName('sales_order');
        $tableCustomer    = $this->resource->getTableName('customer_entity');
        $tableProduct     = $this->resource->getTableName('catalog_product_entity');
        $tableProductInt  = $this->resource->getTableName('catalog_product_entity_int');
        $tableEavAttr     = $this->resource->getTableName('eav_attribute');

        $totalOrders = (int)$connection->fetchOne("SELECT COUNT(*) FROM {$tableOrder}");
        $totalCustomers = (int)$connection->fetchOne("SELECT COUNT(*) FROM {$tableCustomer}");
        $totalProducts = (int)$connection->fetchOne("SELECT COUNT(*) FROM {$tableProduct}");

        $totalSales = (float)$connection->fetchOne("
            SELECT SUM(grand_total) 
            FROM {$tableOrder}
            WHERE status != 'canceled'
        ");

        $avgOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        $newCustomersLast30 = (int)$connection->fetchOne("
            SELECT COUNT(*) 
            FROM {$tableCustomer} 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ");

        $activeProducts = (int)$connection->fetchOne("
            SELECT COUNT(*) 
            FROM {$tableProductInt} AS t
            JOIN {$tableEavAttr} AS ea ON t.attribute_id = ea.attribute_id
            WHERE ea.attribute_code = 'status' 
            AND t.value = 1
        ");

        return [
            ['type' => 'total_orders',         'value' => $totalOrders],
            ['type' => 'total_customers',      'value' => $totalCustomers],
            ['type' => 'total_products',       'value' => $totalProducts],
            ['type' => 'total_sales',          'value' => number_format($totalSales, 2, '.', '')],
            ['type' => 'avg_order_value',      'value' => number_format($avgOrderValue, 2, '.', '')],
            ['type' => 'new_customers_30_days','value' => $newCustomersLast30],
            ['type' => 'active_products',      'value' => $activeProducts],
        ];
    }

    /**
     * Annual statistics (current year)
     */
    private function getAnnualStats(): array
    {
        return [
            $this->getAnnualSalesStat(),
            $this->getAnnualOrdersStat(),
        ];
    }

    /**
     * Get total sales for current year
     */
    private function getAnnualSalesStat(): array
    {
        $connection = $this->resource->getConnection();
        $table = $this->resource->getTableName('sales_order');

        $yearlySales = (float)$connection->fetchOne("
            SELECT SUM(grand_total) 
            FROM {$table}
            WHERE status != 'canceled'
            AND YEAR(created_at) = YEAR(CURDATE())
        ");

        return [
            'type' => 'yearly_sales',
            'value' => number_format($yearlySales, 2, '.', '')
        ];
    }

    /**
     * Get total orders for current year
     */
    private function getAnnualOrdersStat(): array
    {
        $connection = $this->resource->getConnection();
        $table = $this->resource->getTableName('sales_order');

        $yearlyOrders = (int)$connection->fetchOne("
            SELECT COUNT(*) 
            FROM {$table}
            WHERE YEAR(created_at) = YEAR(CURDATE())
        ");

        return [
            'type' => 'yearly_orders',
            'value' => $yearlyOrders
        ];
    }

    /**
     * Save a stat record
     */
    private function saveStat(string $type, string $value, string $meta, string $createdAt, bool $clearBeforeInsert = false): void
    {
        $connection = $this->resource->getConnection();
        $tableName = $this->resource->getTableName('originalapp_reports_stats');

        static $cleared = false; // Prevent multiple clears in the same run

        if ($clearBeforeInsert && !$cleared) {
            $connection->truncateTable($tableName);
            $cleared = true;
            $this->logger->info("[Originalapp_Reports] Stats table cleared before insert");
        }

        $statModel = $this->statFactory->create();
        $statModel->setData([
            'stat_type'       => $type,
            'stat_value'      => $value,
            'additional_data' => $meta,
            'created_at'      => $createdAt
        ]);

        $statModel->save();
    }
}
