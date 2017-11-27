<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Product attribute add/edit form main tab
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace GoMage\Navigation\Block\Adminhtml\Catalog\Product\Attribute\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Catalog\Model\Entity\Attribute;
use Magento\Eav\Block\Adminhtml\Attribute\PropertyLocker;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;

class AdvancedNavigationProperties extends \Magento\Backend\Block\Widget\Form\Generic implements
    \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * @var \GoMage\Navigation\Model\Config\Source\Navigation
     */
    protected $_sourceNavigation;

    /**
     * @var array
     */
    protected $_yesNoSource;

    /**
     * @var \GoMage\Navigation\Model\Config\Source\Image\Alignment
     */
    protected $_sourceImageAlignment;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * AdvancedNavigationProperties constructor.
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param \GoMage\Navigation\Model\Config\Source\Navigation $sourceNavigation
     * @param \GoMage\Navigation\Model\Config\Source\Image\Alignment $sourceImageAlignment
     * @param Yesno $yesNo
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \GoMage\Navigation\Model\Config\Source\Navigation $sourceNavigation,
        \GoMage\Navigation\Model\Config\Source\Image\Alignment $sourceImageAlignment,
        Yesno $yesNo,
        array $data = []
    ) {

        $this->storeManager = $context->getStoreManager();
        $this->_sourceNavigation = $sourceNavigation;
        $this->_sourceImageAlignment = $sourceImageAlignment;
        $this->_yesNoSource = $yesNo->toOptionArray();
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setActive(true);
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('GoMage Advanced Navigation Properties');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('GoMage Advanced Navigation Properties');
    }

    /**
     * Returns status flag about this tab can be showen or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Getter
     *
     * @return \Magento\Widget\Model\Widget\Instance
     */
    public function getWidgetInstance()
    {
        return $this->_coreRegistry->registry('current_widget_instance');
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     */
    protected function _prepareForm()
    {

        $attributeObject = $this->_coreRegistry->registry('entity_attribute');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('GoMage Advanced Navigation Properties')]);

        $this->_addElementTypes($fieldset);

        $templates = $this->_sourceNavigation->toOptionArray();
        if ($attributeObject->getBackendModel() != 'Magento\Catalog\Model\Product\Attribute\Backend\Price') {
            foreach ($templates as $key => $template) {
                if(in_array($template['value'], [
                    \GoMage\Navigation\Model\Config\Source\NavigationInterface::INPUT,
                    \GoMage\Navigation\Model\Config\Source\NavigationInterface::SLIDER,
                    \GoMage\Navigation\Model\Config\Source\NavigationInterface::SLIDER_INPUT,
                ])) {
                    unset($templates[$key]);
                }
            }
        }

        $fieldset->addField(
            'gomage_filter_type',
            'select',
            [
                'name'   => 'gomage_filter_type',
                'label'  => __('Filter Type'),
                'title'  => __('Filter Type'),
                'values' => $templates,
            ]
        );

        $fieldset->addField(
            'gomage_is_show_filter_button',
            'select',
            [
                'name'   => 'gomage_is_show_filter_button',
                'label'  => __('Show Filter Button'),
                'title'  => __('Show Filter Button'),
                'values' => $this->_yesNoSource,
            ]
        );

        $fieldset->addField(
            'gomage_max_block_height',
            'text',
            [
                'name' => 'gomage_max_block_height',
                'label' => __('Max Block Height, px'),
                'title' => __('Max Block Height, px'),
                'class' => 'validate-digits'
            ]
        );

        $fieldset->addField(
            'gomage_is_ajax',
            'select',
            [
                'name'   => 'gomage_is_ajax',
                'label'  => __('Use Ajax'),
                'title'  => __('Use Ajax'),
                'values' => $this->_yesNoSource,
            ]
        );

        $fieldset->addField(
            'gomage_is_collapsed',
            'select',
            [
                'name'   => 'gomage_is_collapsed',
                'label'  => __('Show Collapsed'),
                'title'  => __('Show Collapsed'),
                'values' => $this->_yesNoSource,
            ]
        );

        $fieldset->addField(
            'gomage_is_checkbox',
            'select',
            [
                'name'   => 'gomage_is_checkbox',
                'label'  => __('Show Checkboxes'),
                'title'  => __('Show Checkboxes'),
                'values' => $this->_yesNoSource,
            ]
        );

        $fieldset->addField(
            'gomage_options_alignment',
            'select',
            [
                'name'   => 'gomage_options_alignment',
                'label'  => __('Options Alignment'),
                'title'  => __('Options Alignment'),
                'values' => $this->_sourceImageAlignment->toOptionArray(),
            ]
        );

        $fieldset->addField(
            'gomage_image_width',
            'text',
            [
                'name' => 'gomage_image_width',
                'label' => __('Image Width, px'),
                'title' => __('Image Width, px'),
                'class' => 'validate-digits'
            ]
        );

        $fieldset->addField(
            'gomage_image_height',
            'text',
            [
                'name' => 'gomage_image_height',
                'label' => __('Image Height, px'),
                'title' => __('Image Height, px'),
                'class' => 'validate-digits'
            ]
        );

        $fieldset->addField(
            'gomage_visible_options',
            'text',
            [
                'name' => 'gomage_visible_options',
                'label' => __('Visible Options per Attribute'),
                'title' => __('Visible Options per Attribute'),
                'class' => 'validate-digits'
            ]
        );

        $fieldset->addField(
            'gomage_is_show_tooltip',
            'select',
            [
                'name'   => 'gomage_is_show_tooltip',
                'label'  => __('Show Tooltip'),
                'title'  => __('Show Tooltip'),
                'values' => $this->_yesNoSource,
            ]
        );

        $fieldset->addField(
            'gomage_tooltip_width',
            'text',
            [
                'name' => 'gomage_tooltip_width',
                'label' => __('Tooltip Window Width, px'),
                'title' => __('Tooltip Window Width, px'),
                'class' => 'validate-digits'
            ]
        );

        $fieldset->addField(
            'gomage_tooltip_height',
            'text',
            [
                'name' => 'gomage_tooltip_height',
                'label' => __('Tooltip Window Height, px'),
                'title' => __('Tooltip Window Height, px'),
                'class' => 'validate-digits'
            ]
        );

        $tooltipText = [];
        $tooltipData = unserialize($attributeObject->getData('gomage_tooltip_text'));
        foreach ($this->storeManager->getStores() as $store) {
            $tooltipText['tooltip_text_store_' . $store->getId()] = (!empty($tooltipData[$store->getId()])) ?
                html_entity_decode($tooltipData[$store->getId()],ENT_QUOTES) : '';

            $fieldset->addField(
                'tooltip_text_store_' . $store->getId(),
                'textarea',
                [
                    'name' => 'gomage_tooltip_text[' . $store->getId() . ']',
                    'label' => __('Tooltip Text for ' . $store->getCode() . ' Store'),
                    'title' => __('Tooltip Text for ' . $store->getCode()) . ' Store',
                ]
            );
        }

        $fieldset->addField(
            'gomage_is_reset',
            'select',
            [
                'name'   => 'gomage_is_reset',
                'label'  => __('Show Reset Link'),
                'title'  => __('Show Reset Link'),
                'values' => $this->_yesNoSource,
            ]
        );

        $fieldset->addField(
            'gomage_is_exclude_categories',
            'select',
            [
                'name'   => 'gomage_is_exclude_categories',
                'label'  => __('Exclude Categories'),
                'title'  => __('Exclude Categories'),
                'values' => $this->_yesNoSource,
            ]
        );

        $ajaxData = [];
        if (empty($attributeObject->getData('gomage_is_ajax'))) {
            $ajaxData['gomage_is_ajax'] = 1;
        }

        $this->setForm($form);
        $form->setValues($attributeObject->getData() + $tooltipText + $ajaxData);

        return parent::_prepareForm();
    }
}
