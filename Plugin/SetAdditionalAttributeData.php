<?php

namespace GoMage\Navigation\Plugin;

/**
 * Class SetAdditionalAttributeData
 * @package GoMage\Navigation\Plugin
 */
class SetAdditionalAttributeData
{

    /**
     * @var \GoMage\Navigation\Model\ResourceModel\NavigationAttribute\CollectionFactory
     */
    protected $navigationAttributeCollectionFactory;

    /**
     * @var \GoMage\Navigation\Model\Config\Source\Filter\Templates
     */
    protected $filterTemplates;

    /**
     * @var \GoMage\Navigation\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \GoMage\Navigation\Helper\CategoryData
     */
    protected $categoryDataHelper;

    /**
     * @var \GoMage\Navigation\Model\Config\Source\Category\Templates
     */
    protected $categoryTemplates;

    /**
     * SetAdditionalAttributeData constructor.
     * @param \GoMage\Navigation\Model\ResourceModel\NavigationAttribute\CollectionFactory $navigationAttributeCollectionFactory
     * @param \GoMage\Navigation\Model\Config\Source\Filter\Templates $filterTemplates
     * @param \GoMage\Navigation\Helper\Data $dataHelper
     * @param \GoMage\Navigation\Helper\CategoryData $categoryDataHelper
     * @param \GoMage\Navigation\Model\Config\Source\Category\Templates $categoryTemplates
     */
    public function __construct(
        \GoMage\Navigation\Model\ResourceModel\NavigationAttribute\CollectionFactory $navigationAttributeCollectionFactory,
        \GoMage\Navigation\Model\Config\Source\Filter\Templates $filterTemplates,
        \GoMage\Navigation\Helper\Data $dataHelper,
        \GoMage\Navigation\Helper\CategoryData $categoryDataHelper,
        \GoMage\Navigation\Model\Config\Source\Category\Templates $categoryTemplates
    ) {
        $this->navigationAttributeCollectionFactory = $navigationAttributeCollectionFactory;
        $this->filterTemplates = $filterTemplates;
        $this->dataHelper = $dataHelper;
        $this->categoryDataHelper = $categoryDataHelper;
        $this->categoryTemplates = $categoryTemplates;
    }

    /**
     * @param $filterList
     * @param $result
     * @return mixed
     */
    public function afterGetFilters($filterList, $result)
    {

        if (!$this->dataHelper->isEnable()) {
            return $result;
        }

        foreach ($result as $filter) {
            if(get_class($filter) == 'Magento\CatalogSearch\Model\Layer\Filter\Category') {
                continue;
            }
            if ($filter->getRequestVar() == 'cat' && empty($filter->getData('is_gomage_loaded'))) {
                $this->setCategoryFilterData($filter);
                continue;
            }

            if(!$filter){
                continue;
            }

            if (empty($filter->getAttributeModel()) && empty($filter->getAttributeModel()->getAttributeId()) || !empty($filter->getData('is_gomage_loaded'))) {
                continue;
            }

            $this->setAdditionalData($filter);
            $this->setTemplateData($filter);
            $this->setFlag($filter);
        }

        return $result;
    }

    /**
     * @param $filter
     */
    protected function setAdditionalData($filter)
    {
        $model = $filter->getAttributeModel();
        $navigationAttributeCollection = $this->navigationAttributeCollectionFactory->create()
            ->addFieldToFilter('attribute_id', $model->getAttributeId())
            ->setPageSize(1)
            ->load();

        $navigationAttribute = $navigationAttributeCollection->getFirstItem();

        if (!$navigationAttribute->getAttributeId()) {
            $temp = ['gomage_is_ajax' => 1];
            $filter->addData($temp);
            return ;
        }

        foreach ($navigationAttribute->getData() as $field => $value) {
            $temp = ['gomage_' . $field => $value];
            $filter->addData($temp);
        }
    }

    /**
     * @param $filter
     */
    protected function setTemplateData($filter)
    {
        if ($filter->getSwatchInputType()) {
            $filterType = \GoMage\Navigation\Model\Config\Source\NavigationInterface::SWATCHES;
        } else {
            $filterType = ($filter->getData('gomage_filter_type')) ? $filter->getData('gomage_filter_type') : 0;
        }

        $filter->addData(['gomage_filter_template' => $this->filterTemplates->get($filterType)]);
    }

    /**
     * @param $filter
     */
    protected function setFlag($filter)
    {
        $filter->addData(['is_gomage_loaded' => true]);
    }

    /**
     * @param $filter
     */
    protected function setCategoryFilterData($filter)
    {
        if(!$this->categoryDataHelper->isShowCategories() || !$this->categoryDataHelper->isShowCategoryInShopBy()) {
            return ;
        }

        $filterType = $this->categoryDataHelper->getCategoriesNavigationType();
        $filter->addData(['gomage_filter_template' => $this->filterTemplates->get($filterType)]);
        $this->setFlag($filter);
    }
}
