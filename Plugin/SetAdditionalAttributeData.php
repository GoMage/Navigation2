<?php

namespace GoMage\Navigation\Plugin;

class SetAdditionalAttributeData {

    protected $navigationAttributeCollectionFactory;
    protected $filterTemplates;

    public function __construct(
        \GoMage\Navigation\Model\ResourceModel\NavigationAttribute\CollectionFactory $navigationAttributeCollectionFactory,
        \GoMage\Navigation\Model\Config\Source\Filter\Templates $filterTemplates
    ) {
        $this->navigationAttributeCollectionFactory = $navigationAttributeCollectionFactory;
        $this->filterTemplates = $filterTemplates;
    }

    public function afterGetFilters($filterList, $result) {

        foreach ($result as $filter) {

            if (empty($filter->getAttributeModel()->getAttributeId()) || !empty($filter->getData('is_gomage_loaded'))) {
                continue;
            }

            $this->setAdditionalData($filter);
            $this->setTemplateData($filter);
            $this->setFlag($filter);
        }

        return $result;
    }

    //collect all ids and do one request
    protected function setAdditionalData($filter)
    {
        $model = $filter->getAttributeModel();
        $navigationAttributeCollection = $this->navigationAttributeCollectionFactory->create()
            ->addFieldToFilter('attribute_id', $model->getAttributeId())
            ->setPageSize(1)
            ->load();

        $navigationAttribute = $navigationAttributeCollection->getFirstItem();

        if (!$navigationAttribute->getAttributeId()) {
            return ;
        }

        foreach ($navigationAttribute->getData() as $field => $value) {

            $temp = ['gomage_' . $field => $value];
            $filter->addData($temp);
        }
    }

    protected function setTemplateData($filter)
    {
        if($filter->getSwatchInputType()) {
            $filterType = \GoMage\Navigation\Model\Config\Source\NavigationInterface::SWATCHES;
        } else {
            $filterType = ($filter->getData('gomage_filter_type')) ? $filter->getData('gomage_filter_type') : 0;
        }

        $filter->addData(['gomage_filter_template' => $this->filterTemplates->get($filterType)]);
    }

    protected function setFlag($filter)
    {
        $filter->addData(['is_gomage_loaded' => true]);
    }
}