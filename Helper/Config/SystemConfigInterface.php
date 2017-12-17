<?php

namespace GoMage\Navigation\Helper\Config;

/**
 * Navigation interface
 */
interface SystemConfigInterface
{
    const SYSTEM_CONFIG_SLASH = '/';
    const SYSTEM_CONFIG_SECTION = 'gomage_navigation';
    const SYSTEM_CONFIG_CROUP = 'general';
    const SYSTEM_CATEGORIES_CONFIG_CROUP = 'category';
    const SYSTEM_NAVIGATION_VIEW_CONFIG_CROUP = 'view';
    const SYSTEM_LOADER_CONFIG_CROUP = 'loader';
    const SYSTEM_CONFIG_SETTINGS_GROUP = 'settings';
    const SYSTEM_CONFIG_FIELD_ENABLE= 'enable';
    const SYSTEM_CONFIG_FIELD_SHOW_SHOP_BY_IN = 'place';
    const SYSTEM_CONFIG_FIELD_USE_AUTOSCROLLING = 'scroll';
    const SYSTEM_CONFIG_FIELD_USE_BACK_TO_TOP = 'to_top_button';
    const SYSTEM_CONFIG_FIELD_USE_BACK_TO_TOP_SPEED = 'to_top_speed';
    const SYSTEM_CONFIG_FIELD_USE_BACK_TO_TOP_ACTION = 'to_top_action';
    const SYSTEM_CONFIG_FIELD_PAGER_BUTTON = 'pager_button';
    const SYSTEM_CONFIG_FIELD_CONTENT_FILTER_TYPE = 'content_filter_type';
    const SYSTEM_CONFIG_FIELD_PAGER = 'pager';
    const SYSTEM_CONFIG_FIELD_MOBILE = 'mobile';
    const SYSTEM_CONFIG_FIELD_QTY = 'qty';
    const SYSTEM_CONFIG_FIELD_USE_HASH = 'use_hash';
    const SYSTEM_CONFIG_FIELD_USE_FRIENDLY_URLS = 'friendly_urls';
    const SYSTEM_CONFIG_FIELD_SHOW_APPLIED_VALUES = 'result';
    const SYSTEM_CATEGORIES_CONFIG_ENABLE = 'enable';
    const SYSTEM_CATEGORIES_CONFIG_BLOCK_LOCATION = 'place';
    const SYSTEM_CATEGORIES_CONFIG_NAVIGATION_TYPE = 'navigation';
    const SYSTEM_CATEGORIES_CONFIG_FILTER_ACTION = 'action';
    const SYSTEM_CATEGORIES_CONFIG_SHOP_BY = 'shop_by';
    const SYSTEM_CATEGORIES_CONFIG_SHOW_ALL_SUBCATEGORIES = 'show_all';
    const SYSTEM_CATEGORIES_CONFIG_HIDE_EMPTY_CATEGORIES = 'hide_empty';
    const SYSTEM_CATEGORIES_CONFIG_IS_SHOW_COLLAPSED = 'is_collapsed';
    const SYSTEM_CATEGORIES_CONFIG_MAX_BLOCK_HEIGHT = 'max_block_height';
    const SYSTEM_CATEGORIES_CONFIG_IMAGE_ALIGNMENT = 'image_alignment';
    const SYSTEM_CATEGORIES_CONFIG_IMAGE_WIDTH = 'image_width';
    const SYSTEM_CATEGORIES_CONFIG_IMAGE_HEIGHT = 'image_height';
    const SYSTEM_CATEGORIES_CONFIG_IMAGE_NAME = 'is_image';
    const SYSTEM_CATEGORIES_CONFIG_CHECKBOX = 'is_checkbox';
    const SYSTEM_LOADER_CONFIG_ENABLE = 'enable';
    const SYSTEM_LOADER_CONFIG_SPINNER_TYPE = 'spinner_type';
    const SYSTEM_LOADER_CONFIG_IMAGE = 'image';
    const SYSTEM_LOADER_CONFIG_ALIGNMENT = 'alignment';
    const SYSTEM_LOADER_CONFIG_BACKGROUND = 'background';
    const SYSTEM_LOADER_CONFIG_BORDER_COLOR = 'border_color';
    const SYSTEM_LOADER_CONFIG_TEXT_COLOR = 'text_color';
    const SYSTEM_LOADER_CONFIG_SPINNER_COLOR = 'spinner_color';
    const SYSTEM_LOADER_CONFIG_WINDOW_WIDTH = 'width';
    const SYSTEM_LOADER_CONFIG_WINDOW_HEIGHT = 'height';
    const SYSTEM_LOADER_CONFIG_TEXT = 'text';
    const SYSTEM_NAVIGATION_VIEW_CONFIG_BACKGROUND = 'block' . self::SYSTEM_CONFIG_SLASH . 'background';
    const SYSTEM_NAVIGATION_VIEW_CONFIG_CATEGORY_BACKGROUND = 'block' . self::SYSTEM_CONFIG_SLASH . 'category_background';
    const SYSTEM_NAVIGATION_VIEW_CONFIG_BUTTON_BACKGROUND = 'button' . self::SYSTEM_CONFIG_SLASH . 'background';
    const SYSTEM_NAVIGATION_VIEW_CONFIG_BUTTON_GRADIENT = 'button' . self::SYSTEM_CONFIG_SLASH . 'gradient';
    const SYSTEM_NAVIGATION_VIEW_CONFIG_BUTTON_BACKGROUND2 = 'button' . self::SYSTEM_CONFIG_SLASH . 'background2';
    const SYSTEM_NAVIGATION_VIEW_CONFIG_BUTTON_TEXT_COLOR = 'button' . self::SYSTEM_CONFIG_SLASH . 'color';
    const SYSTEM_NAVIGATION_VIEW_CONFIG_TOOLTIP_BACKGROUND = 'tooltip' . self::SYSTEM_CONFIG_SLASH . 'background';
    const SYSTEM_NAVIGATION_VIEW_CONFIG_TOOLTIP_WINDOW_BACKGROUND = 'tooltip' . self::SYSTEM_CONFIG_SLASH . 'window_background';
    const SYSTEM_NAVIGATION_VIEW_CONFIG_TOOLTIP_SHOW_EVENT = 'tooltip' . self::SYSTEM_CONFIG_SLASH . 'show_event';
    const SYSTEM_NAVIGATION_VIEW_CONFIG_TOOLTIP_HIDE_EVENT = 'tooltip' . self::SYSTEM_CONFIG_SLASH . 'hide_event';
    const SYSTEM_NAVIGATION_VIEW_CONFIG_SLIDER_LINE_COLOR = 'slider' . self::SYSTEM_CONFIG_SLASH . 'line_color';
    const SYSTEM_NAVIGATION_VIEW_CONFIG_SLIDER_LINE_HEIGHT = 'slider' . self::SYSTEM_CONFIG_SLASH . 'line_height';
    const SYSTEM_NAVIGATION_VIEW_CONFIG_SLIDER_ELEMENT_COLOR = 'slider' . self::SYSTEM_CONFIG_SLASH . 'element_color';
    const SYSTEM_NAVIGATION_VIEW_CONFIG_SLIDER_ELEMENT_WIDTH = 'slider' . self::SYSTEM_CONFIG_SLASH . 'element_width';
    const SYSTEM_NAVIGATION_VIEW_CONFIG_SLIDER_ELEMENT_HEIGHT = 'slider' . self::SYSTEM_CONFIG_SLASH . 'element_height';
    const SYSTEM_NAVIGATION_VIEW_CONFIG_SLIDER_ELEMENT_RADIUS = 'slider' . self::SYSTEM_CONFIG_SLASH . 'element_radius';
}
