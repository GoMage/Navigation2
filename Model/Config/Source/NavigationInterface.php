<?php

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
    const FLY_OUT = 8;
    const IMAGE = 9;
    const PRICE_SLIDER_STEP = 1;
    const TYPE_SLIDER = 'slider';
    const SLIDER_SKIN = 'round_plastic'; /*** OR classic,plastic,round,round_plastic ****/
    const TYPE_DEFAULTS = 'defaults';
    const TYPE_DROP_DOWN = 'select';
    const ATTRIBUTE_CATEGORY = 'cat';
    const ATTRIBUTE_PRICE = 'price';
    const ATTRIBUTE_SWATCH_VISUAL = 'visual';
    const ATTRIBUTE_SWATCH_TEXT = 'text';
}
