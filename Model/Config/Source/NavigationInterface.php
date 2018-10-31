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

namespace GoMage\Navigation\Model\Config\Source;

/**
 * Navigation interface
 */
interface NavigationInterface
{
    const DEFAULTS = 0;
    const COLOR_PICKER = 1;
    const DROP_DOWN = 2;
    const IN_BLOCK = 3;
    const BUTTON = 4;
    const INPUT = 5;
    const SLIDER = 6;
    const SLIDER_INPUT = 7;
    const SWATCHES = 8;
    const FLY_OUT = 10;
    const IMAGE = 9;
    const PRICE_SLIDER_STEP = 0.01;
    const ROUND_SLIDER = 2;
    const TYPE_SLIDER = 'slider';
    const SLIDER_SKIN = 'round_plastic'; /***
 * OR classic,plastic,round,round_plastic 
****/

    const TYPE_DEFAULTS = 'defaults';
    const TYPE_DROP_DOWN = 'select';
    const TYPE_IN_BLOCK = 'input';
    const TYPE_INPUT = 'input';
    const ATTRIBUTE_CATEGORY = 'cat';
    const ATTRIBUTE_PRICE = 'price';
    const ATTRIBUTE_SWATCH_VISUAL = 'visual';
    const ATTRIBUTE_SWATCH_TEXT = 'text';
}
