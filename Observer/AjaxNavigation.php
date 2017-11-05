<?php

namespace GoMage\Navigation\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\DataObject;

class AjaxNavigation implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $_response;

    /**
     * @var \Magento\Framework\App\ActionFlag
     */
    protected $_actionFlag;

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $_layout;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $_eventManager;
    protected $dataHelper;


    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\App\ResponseInterface $response,
        \Magento\Framework\App\ActionFlag $actionFlag,
        \Magento\Framework\View\LayoutInterface $layout,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \GoMage\Navigation\Helper\Data $dataHelper
    )
    {
        $this->_request = $request;
        $this->_response = $response;
        $this->_actionFlag = $actionFlag;
        $this->_layout = $layout;
        $this->_eventManager = $eventManager;
        $this->dataHelper = $dataHelper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     *
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->_request->isAjax() && $this->dataHelper->isEnable()) {

            $navigationPosition = $this->getBlockLocation($this->dataHelper->getShowShopByIn());
            $categoryPosition = $this->getBlockLocation($this->dataHelper->getCategoriesBlockLocation());

            $result = new DataObject();
            $result->setData('categories', $this->_layout->getBlock('gomage.categories.' . $categoryPosition)->toHtml());
            $result->setData('navigation', $this->_layout->getBlock('catalog.' . $navigationPosition . 'nav')->toHtml());

            $result->setData('products', $this->_layout->getBlock('category.products')->toHtml());

            $this->_eventManager->dispatch('gomage_navigation_ajax_result', ['result' => $result]);

            $this->_actionFlag->set('', \Magento\Framework\App\Action\Action::FLAG_NO_DISPATCH, true);
            //$this->_response->representJson($result->toJson());

            echo $result->toJson();
            exit;
        }

        return $this;
    }

    public function getBlockLocation($type)
    {
        $place[\GoMage\Navigation\Model\Config\Source\Place::LEFT_COLUMN] = 'left';
        $place[\GoMage\Navigation\Model\Config\Source\Place::CONTENT] = 'content';
        $place[\GoMage\Navigation\Model\Config\Source\Place::RIGHT_COLUMN] = 'right';

        if(empty($place[$type])) {
            throw new Exception(__('Block position is not set for ' . (int) $type . ' type'));
        }

        return $place[$type];
    }
}
