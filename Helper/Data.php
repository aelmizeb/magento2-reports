<?php
/**
 * Copyright © 2025 Abdellatif EL MIZEB. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Originalapp\Reports\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Directory\Model\CountryFactory;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    protected CountryFactory $countryFactory;

    /**
     * Simple in-memory cache to avoid redundant DB queries
     */
    private array $countryNameCache = [];

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        CountryFactory $countryFactory
    ) {
        parent::__construct($context);
        $this->countryFactory = $countryFactory;
    }

    /**
     * Get the country name by its ISO code
     *
     * @param string $code ISO alpha-2 country code (e.g. "US", "FR")
     * 
     * @return string
     */
    public function getCountryName(string $code): string
    {
        if (isset($this->countryNameCache[$code])) {
            return $this->countryNameCache[$code];
        }

        try {
            $country = $this->countryFactory->create()->loadByCode($code);
            $name = $country->getName();
            $normalized = $this->normalizeCountryName($name ?: $code);
            $this->countryNameCache[$code] = $normalized;
            return $normalized;
        } catch (\Exception $e) {
            return $code;
        }
    }

    /**
     * Map Magento country names to ECharts-compatible names
     * 
     * @param string $name
     * 
     * @return string
     */
    private function normalizeCountryName(string $name): string
    {
        $map = [
            'United States' => 'United States of America',
            'Russia' => 'Russian Federation',
            'South Korea' => 'Republic of Korea',
            'North Korea' => 'Democratic People\'s Republic of Korea',
            'Iran' => 'Iran (Islamic Republic of)',
            'Syria' => 'Syrian Arab Republic',
            'Venezuela' => 'Venezuela (Bolivarian Republic of)',
            'Vietnam' => 'Viet Nam',
            'Laos' => 'Lao People\'s Democratic Republic',
            'Moldova' => 'Republic of Moldova',
            'Tanzania' => 'United Republic of Tanzania',
            'Bolivia' => 'Bolivia (Plurinational State of)',
            'Brunei' => 'Brunei Darussalam',
            'Czech Republic' => 'Czechia',
            'Ivory Coast' => 'Côte d\'Ivoire',
            'Cape Verde' => 'Cabo Verde',
        ];

        return $map[$name] ?? $name;
    }
}
