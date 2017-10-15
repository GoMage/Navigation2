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

class Url extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function wrapp($url)
    {
        //can't decode url gomage-8956
        return $url;
    }
}