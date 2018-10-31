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

/**
 * Class VisualSwatch
 *
 * @package GoMage\Navigation\Plugin
 */
class VisualSwatch
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlBuilder;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $request;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $helperData;

    /**
     * @param \Magento\Framework\View\Element\Context $context
     * @param \GoMage\Navigation\Helper\Data          $helperData
     */
    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \GoMage\Navigation\Helper\Data $helperData
    ) {
        $this->request = $context->getRequest();
        $this->_urlBuilder = $context->getUrlBuilder();
        $this->helperData = $helperData;
    }

    /**
     * @param \Magento\Swatches\Block\Adminhtml\Attribute\Edit\Options\Visual $subject
     * @param $result
     * @return string
     */
    public function afterGetJsonConfig(
        \Magento\Swatches\Block\Adminhtml\Attribute\Edit\Options\Visual $subject,
        $result
    ) {
        if (!$this->helperData->isEnable()) {
            return $result;
        }
        $values = [];
        foreach ($subject->getOptionValues() as $value) {
            $values[] = $value->getData();
        }

        $result = [
            'attributesData' => $values,
            'uploadActionUrl' => $this->getUrl(
                'swatches/iframe/show',
                [
                'attribute_id' => $this->request->get('attribute_id')
                ]
            ),
            'isSortable' => (int)(!$subject->getReadOnly() && !$subject->canManageOptionDefaultOnly()),
            'isReadOnly' => (int)$subject->getReadOnly(),
        ];

        return json_encode($result);
    }

    /**
     * Generate url by route and parameters
     *
     * @param  string $route
     * @param  array  $params
     * @return string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->_urlBuilder->getUrl($route, $params);
    }
}
