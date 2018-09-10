<?php

namespace GoMage\Navigation\Model;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Object;

class Observer
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $_request;

    /**
     * @var \Magento\Framework\App\Response\Http
     */
    protected $_response;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $_eventDispatcher;

    /**
     * Layout model
     *
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $_layout;

    /**
     * Initialize dependencies.
     *
     * @param \Magento\Framework\App\Request\Http       $request
     * @param \Magento\Framework\App\Response\Http      $response
     * @param \Magento\Framework\View\LayoutInterface   $layout
     * @param \Magento\Framework\Event\ManagerInterface $eventDispatcher
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\App\Response\Http $response,
        \Magento\Framework\View\LayoutInterface $layout,
        \Magento\Framework\Event\ManagerInterface $eventDispatcher
    ) {
        $this->_request         = $request;
        $this->_response        = $response;
        $this->_layout          = $layout;
        $this->_eventDispatcher = $eventDispatcher;
    }

    /**
     *
     * @param EventObserver $observer
     * @return void
     */
    public function ajaxNavigation(EventObserver $observer)
    {
        if ($this->_request->isAjax()) {
            $result = new Object();

            $result->setData('navigation', $this->_layout->getBlock('gomage.navigation')->toHtml());
            $result->setData('products', $this->_layout->getBlock('category.products')->toHtml());

            $this->_eventDispatcher->dispatch('gomage_navigation_ajax_result', ['result' => $result]);

            $this->_response
                ->representJson($result->toJson())
                ->sendResponse();
            /**
 * TODO: stop second send response  
*/
            exit();

        }

    }

}
