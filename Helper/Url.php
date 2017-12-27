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
    protected $htmlPagerBlock;

    /**
     * Url
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $url;

    /**
     * Url
     *
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * Url
     *
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * @var \GoMage\Navigation\Helper\Data
     */
    protected $dataHelper;

    /**
     * Url constructor.
     * @param Context $context
     * @param \Magento\Theme\Block\Html\Pager $htmlPagerBlock
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\Escaper $escaper
     * @param Data $dataHelper
     */
    public function __construct(
        Context $context,
        \Magento\Theme\Block\Html\Pager $htmlPagerBlock,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Escaper $escaper,
        \GoMage\Navigation\Helper\Data $dataHelper
    ) {
    
        $this->url = $context->getUrlBuilder();
        $this->htmlPagerBlock = $htmlPagerBlock;
        $this->request = $request;
        $this->escaper = $escaper;
        $this->dataHelper = $dataHelper;
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
        $queryParams = is_array($this->request->getParams()) ? $this->request->getParams() : [];
        if (!empty($queryParams[$item->getFilter()->getRequestVar()])) {
            $paramValues = explode('_', $queryParams[$item->getFilter()->getRequestVar()]);
        }

        if (!in_array($this->getItemFormattedValue($item), $paramValues)) {
            $paramValues[] = $this->getItemFormattedValue($item);
        }

        $query = [
            $item->getFilter()->getRequestVar() => implode('_', $paramValues),
            $this->htmlPagerBlock->getPageVarName() => null,
        ];

        $url = $this->url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $query]);

        return $this->escaper->escapeUrl($url);
    }

    /**
     * @param $item
     * @return string
     */
    public function getItemPriceUrl($item)
    {
        $query = [
            $item->getFilter()->getRequestVar() => $item->getValue(),
            $this->htmlPagerBlock->getPageVarName() => null,
        ];

        $url = $this->url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $query]);

        return $this->escaper->escapeUrl($url);
    }

    /**
     * @param $item
     * @return string
     */
    public function getItemValue($item)
    {
        if ($item->getFilter()->getFilterType() == \GoMage\Navigation\Model\Catalog\Layer\Filter\Price::FILTER_TYPE) {
            return $this->getItemPriceValue($item);
        }

        $paramValues = [];
        $queryParams = is_array($this->request->getParams()) ? $this->request->getParams() : [];
        if (!empty($queryParams[$item->getFilter()->getRequestVar()])) {
            $paramValues = explode('_', $queryParams[$item->getFilter()->getRequestVar()]);
        }

        if (!in_array($this->getItemFormattedValue($item), $paramValues)) {
            $paramValues[] = $this->getItemFormattedValue($item);
        }


        return implode('_', $paramValues);
    }

    /**
     * @param $item
     * @return mixed
     */
    public function getItemPriceValue($item)
    {
        return $item->getValue();
    }


    /**
     * @param $item
     * @return string
     */
    public function getRemoveUrl($item)
    {
        $paramValues = [];
        $queryParams = is_array($this->request->getParams()) ? $this->request->getParams() : [];
        if (!empty($queryParams[$item->getFilter()->getRequestVar()])) {
            $paramValues = explode('_', $queryParams[$item->getFilter()->getRequestVar()]);
        }

        if ($item->getFilter()->getRequestVar() == 'price') {
            $position = array_search(implode('-', $item->getValue()), $paramValues);
        } else {
            $position = array_search($this->getItemFormattedValue($item), $paramValues);
        }

        if ($position !== false) {
            unset($paramValues[$position]);
        }

        $query = [];
        if (count($paramValues) > 0) {
            $query[$item->getFilter()->getRequestVar()] = implode('_', $paramValues);
        } else {
            $query[$item->getFilter()->getRequestVar()] = null;
        }

        $query[$this->htmlPagerBlock->getPageVarName()] = null;

        $url = $this->url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $query]);

        return $this->escaper->escapeUrl($url);
    }

    /**
     * @param $item
     * @return string
     */
    public function getRemoveValue($item)
    {
        $paramValues = [];
        $queryParams = is_array($this->request->getParams()) ? $this->request->getParams() : [];
        if (!empty($queryParams[$item->getFilter()->getRequestVar()])) {
            $paramValues = explode('_', $queryParams[$item->getFilter()->getRequestVar()]);
        }

        if ($item->getFilter()->getAttributeModel()->getBackendModel() == 'Magento\Catalog\Model\Product\Attribute\Backend\Price') {
            $position = array_search(implode('-', $item->getValue()), $paramValues);
        } else {
            $position = array_search($this->getItemFormattedValue($item), $paramValues);
        }

        if ($position !== false) {
            unset($paramValues[$position]);
        }

        return implode('_', $paramValues);
    }

    /**
     * @param $filter
     * @return string
     */
    public function getFilterRemoveUrl($filter)
    {
        $queryParams = is_array($this->request->getParams()) ? $this->request->getParams() : [];
        if (!empty($queryParams[$filter->getRequestVar()])) {
            $queryParams[$filter->getRequestVar()] = null;
        }

        $url = $this->url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $queryParams]);

        return $this->escaper->escapeUrl($url);
    }

    /**
     * @param $item
     * @return mixed|string
     */
    protected function getItemFormattedValue($item)
    {
        if (!$this->dataHelper->isUseFriendlyUrls()) {
            return $item->getValue();
        }

        return mb_strtolower(str_replace(' ', '+', htmlentities($item->getLabel())));
    }
}
