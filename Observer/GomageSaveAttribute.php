<?php

namespace GoMage\Navigation\Observer;

class GomageSaveAttribute implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $attribute = $observer->getEvent()->getAttribute();

        return $this;
    }
}