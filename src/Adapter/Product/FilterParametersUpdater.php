<?php
/**
 * 2007-2017 PrestaShop.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

namespace PrestaShop\PrestaShop\Adapter\Product;

/**
 * Can manage filter parameters from request in Product Catalogue Page.
 * For internal use only.
 */
final class FilterParametersUpdater
{
    /**
     * In case of position ordering all the filters should be reset.
     *
     * @param array $filterParameters
     * @param string $orderBy
     * @param bool $hasCategoryFilter
     *
     * @return array $filterParameters
     */
    public function cleanFiltersForPositionOrdering($filterParameters, $orderBy, $hasCategoryFilter)
    {
        if ($orderBy == 'position_ordering' && $hasCategoryFilter) {
            foreach (array_keys($filterParameters) as $key) {
                if (strpos($key, 'filter_column_') === 0) {
                    $filterParameters[$key] = '';
                }
            }
        }

        return $filterParameters;
    }

    /**
     * @param array $queryFilterParameters
     * @param array $persistedFilterParameters
     * @param array $defaultFilterParameters
     * @return array
     */
    public function buildFilters(
        array $queryFilterParameters,
        array $persistedFilterParameters,
        array $defaultFilterParameters
    ) {
        return [
            'offset' => (int) $this->getParameter('offset', $queryFilterParameters, $persistedFilterParameters, $defaultFilterParameters),
            'limit' => (int) $this->getParameter('limit', $queryFilterParameters, $persistedFilterParameters, $defaultFilterParameters),
            'orderBy' => (string) $this->getParameter('orderBy', $queryFilterParameters, $persistedFilterParameters, $defaultFilterParameters),
            'sortOrder' => (string) $this->getParameter('sortOrder', $queryFilterParameters, $persistedFilterParameters, $defaultFilterParameters),
        ];
    }

    /**
     * @param string $parameterName
     * @param array $queryFilterParameters
     * @param array $persistedFilterParameters
     * @param array $defaultFilterParameters
     * @return string|int
     */
    private function getParameter(
        $parameterName,
        array $queryFilterParameters,
        array $persistedFilterParameters,
        array $defaultFilterParameters
    ) {
        if (!empty($queryFilterParameters) && isset($queryFilterParameters[$parameterName])) {
            $value = $queryFilterParameters[$parameterName];
        } else if (!empty($persistedFilterParameters) && isset($persistedFilterParameters[$parameterName])) {
            $value = $persistedFilterParameters[$parameterName];
        } else {
            $value = $defaultFilterParameters[$parameterName];
        }

        if ($value === 'last' && isset($persistedFilterParameters['last_'.$parameterName])) {
            $value = $persistedFilterParameters['last_'.$parameterName];
        }

        return $value;
    }
}
