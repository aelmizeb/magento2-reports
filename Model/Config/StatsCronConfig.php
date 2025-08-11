<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Model\Config;

use Magento\Framework\Exception\LocalizedException;

class StatsCronConfig extends \Magento\Framework\App\Config\Value
{
    /**
     * Cron string path for stats
     */
    const CRON_STRING_PATH = 'crontab/default/jobs/originalapp_reports_cron_job/schedule/cron_expr';

    /**
     * @var \Magento\Framework\App\Config\ValueFactory
     */
    protected $configValueFactory;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Config\ValueFactory $configValueFactory,
        ?\Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        ?\Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->configValueFactory = $configValueFactory;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * After save handler
     *
     * @return $this
     * @throws LocalizedException
     */
    public function afterSave()
    {
        $time = $this->getData('groups/cron_settings/fields/time/value')
            ?: explode(',', $this->_config->getValue('originalapp_reports/cron_settings/time', $this->getScope(), $this->getScopeId()) ?: '0,0');

        $frequency = $this->getValue();
        $cronExprString = '* * * * *'; // default every minute

        switch ($frequency) {
            case 'hourly':
                $cronExprString = sprintf('%d * * * *', (int)($time[1] ?? 0));
                break;
            case 'daily':
                $cronExprString = sprintf('%d %d * * *', (int)($time[1] ?? 0), (int)($time[0] ?? 0));
                break;
            case 'weekly':
                $cronExprString = sprintf('%d %d * * 0', (int)($time[1] ?? 0), (int)($time[0] ?? 0));
                break;
            case 'monthly':
                $cronExprString = sprintf('%d %d 1 * *', (int)($time[1] ?? 0), (int)($time[0] ?? 0));
                break;
            case 'yearly':
                $cronExprString = sprintf('%d %d 1 1 *', (int)($time[1] ?? 0), (int)($time[0] ?? 0));
                break;
            case 'always':
            default:
                $cronExprString = '* * * * *';
        }

        try {
            $this->configValueFactory->create()->load(
                self::CRON_STRING_PATH,
                'path'
            )->setValue($cronExprString)
            ->setPath(self::CRON_STRING_PATH)
            ->save();
        } catch (\Exception $e) {
            throw new LocalizedException(__('We can\'t save the cron expression: %1', $e->getMessage()));
        }

        return parent::afterSave();
    }
}