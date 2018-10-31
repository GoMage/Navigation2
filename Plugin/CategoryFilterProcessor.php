<?php

/**
 * GoMage.com
 *
 * GoMage Navigation M2
 *
 * @category  Extension
 * @copyright Copyright (c) 2018-2018 GoMage.com (https://www.gomage.com)
 * @author    GoMage.com
 * @license   https://www.gomage.com/licensing  Single domain license
 * @terms     of use https://www.gomage.com/terms-of-use
 * @version   Release: 2.0.0
 * @since     Class available since Release 2.0.0
 */

namespace GoMage\Navigation\Plugin;

use Magento\Framework\Search\Request\FilterInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\CatalogSearch\Model\Adapter\Mysql\Filter\AliasResolver;

/**
 * Class CategoryFilterProcessor
 *
 * @package GoMage\Navigation\Plugin
 */
class CategoryFilterProcessor
{
    protected $resourceConnection;
    protected $aliasResolver;
    public function __construct(ResourceConnection $resourceConnection, AliasResolver $aliasResolver)
    {
        $this->aliasResolver = $aliasResolver;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @param \Magento\CatalogSearch\Model\Adapter\Mysql\Filter\Preprocessor $subject
     * @param \Closure $proceed
     * @param FilterInterface $filter
     * @param $isNegation
     * @param $query
     * @param ResourceConnection $resourceConnection
     * @return mixed|string
     */
    public function aroundProcess(
        \Magento\CatalogSearch\Model\Adapter\Mysql\Filter\Preprocessor $subject,
        \Closure $proceed,
        FilterInterface $filter,
        $isNegation,
        $query
    ) {
        if ($filter->getField() ===
            'category_ids' && is_array($filter->getValue()) && isset($filter->getValue()['in'])) {
            return $this->aliasResolver->getAlias($filter).'.category_id IN (' . implode(',', $filter->getValue()['in']) . ')';
        }
        return $proceed($filter, $isNegation, $query);
    }
}