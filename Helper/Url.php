<?php

/**
 * GoMage Advanced Navigation Extension
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2016 GoMage (https://www.gomage.com)
 * @author       GoMage
 * @license      https://www.gomage.com/license-agreement/  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.9.1
 * @since        Class available since Release 4.7
 */

namespace GoMage\Navigation\Helper;

use Magento\Framework\App\Helper\Context;

class Url extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Html pager block
     *
     * @var \Magento\Theme\Block\Html\Pager
     */
    protected $_htmlPagerBlock;

    /**
     * Url
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $_url;

    /**
     * Url
     *
     * @var \Magento\Framework\App\Request\Http
     */
    protected $_request;

    /**
     * Url
     *
     * @var \Magento\Framework\Escaper
     */
    protected $_escaper;

    public function __construct(
        Context $context,
        \Magento\Framework\UrlInterface $url,
        \Magento\Theme\Block\Html\Pager $htmlPagerBlock,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Escaper $escaper
    )
    {
        $this->_url = $url;
        $this->_htmlPagerBlock = $htmlPagerBlock;
        $this->_request = $request;
        $this->_escaper = $escaper;
        parent::__construct($context);
    }

    /**
     * @param $item
     * @return string
     */
    public function getItemUrl($item)
    {
        if ($item->getFilter()->getFilterType() == \GoMage\Navigation\Model\Catalog\Layer\Filter\Price::FILTER_TYPE) {
            return $this->getItemPriceUrl($item);
        }

        $paramValues = [];
        $queryParams = is_array($this->_request->getParams()) ? $this->_request->getParams() : [];
        if (!empty($queryParams[$item->getFilter()->getRequestVar()])) {
            $paramValues = explode('_', $queryParams[$item->getFilter()->getRequestVar()]);
        }

        if(!in_array($item->getValue(), $paramValues)) {
            $paramValues[] = $item->getValue();
        }

        $query = [
            $item->getFilter()->getRequestVar() => implode('_', $paramValues),
            $this->_htmlPagerBlock->getPageVarName() => null,
        ];

        $url = $this->_url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $query]);

        return $this->_escaper->escapeUrl($url);
    }

    public function getItemPriceUrl($item)
    {
        $query = [
            $item->getFilter()->getRequestVar() => $item->getValue(),
            $this->_htmlPagerBlock->getPageVarName() => null,
        ];

        $url = $this->_url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $query]);

        return $this->_escaper->escapeUrl($url);
    }

    public function getItemValue($item)
    {
        if ($item->getFilter()->getFilterType() == \GoMage\Navigation\Model\Catalog\Layer\Filter\Price::FILTER_TYPE) {
            return $this->getItemPriceValue($item);
        }

        $paramValues = [];
        $queryParams = is_array($this->_request->getParams()) ? $this->_request->getParams() : [];
        if (!empty($queryParams[$item->getFilter()->getRequestVar()])) {
            $paramValues = explode('_', $queryParams[$item->getFilter()->getRequestVar()]);
        }

        if(!in_array($item->getValue(), $paramValues)) {
            $paramValues[] = $item->getValue();
        }

        return implode('_', $paramValues);
    }

    public function getItemPriceValue($item)
    {
        return $item->getValue();
    }

    public function getRemoveUrl($item)
    {
        $paramValues = [];
        $queryParams = is_array($this->_request->getParams()) ? $this->_request->getParams() : [];
        if (!empty($queryParams[$item->getFilter()->getRequestVar()])) {
            $paramValues = explode('_', $queryParams[$item->getFilter()->getRequestVar()]);
        }

        if ($item->getFilter()->getRequestVar() == 'price') {
            $position = array_search(implode('-', $item->getValue()), $paramValues);
        } else {
            $position = array_search($item->getValue(), $paramValues);
        }

        if($position !== false) {
            unset($paramValues[$position]);
        }

        $query = [];
        if (count($paramValues) > 0) {
            $query[$item->getFilter()->getRequestVar()] = implode('_', $paramValues);
        } else {
            $query[$item->getFilter()->getRequestVar()] = null;
        }

        $query[$this->_htmlPagerBlock->getPageVarName()] = null;

        $url = $this->_url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $query]);

        return $this->_escaper->escapeUrl($url);
    }

    public function getRemoveValue($item)
    {
        $paramValues = [];
        $queryParams = is_array($this->_request->getParams()) ? $this->_request->getParams() : [];
        if (!empty($queryParams[$item->getFilter()->getRequestVar()])) {
            $paramValues = explode('_', $queryParams[$item->getFilter()->getRequestVar()]);
        }

        if ($item->getFilter()->getRequestVar() == 'price') {
            $position = array_search(implode('-', $item->getValue()), $paramValues);
        } else {
            $position = array_search($item->getValue(), $paramValues);
        }

        if($position !== false) {
            unset($paramValues[$position]);
        }

        return implode('_', $paramValues);
    }
}