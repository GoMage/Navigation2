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
    const SYSTEM_CONFIG_SETTINGS_GROUP = 'settings';
    const SYSTEM_CONFIG_FIELD_ENABLE= 'enable';
    const SYSTEM_CONFIG_FIELD_PAGER_BUTTON = 'pager_button';
    const SYSTEM_CONFIG_FIELD_PAGER = 'pager';
    const SYSTEM_CONFIG_FIELD_QTY = 'qty';
    const SYSTEM_CONFIG_FIELD_USE_HASH = 'use_hash';
    const SYSTEM_CONFIG_FIELD_USE_FRIENDLY_URLS = 'friendly_urls';
}
