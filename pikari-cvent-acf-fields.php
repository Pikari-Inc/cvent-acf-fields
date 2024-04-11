<?php

/**
 * Plugin Name:     Pikari Cvent ACF Fields
 * Plugin URI:      https://pikari.io
 * Description:     A plugin to integrate CVENT data into custom ACF fields.
 * Version:         1.0.1
 * Author:          Pikari Inc.
 * Author URI:      https://pikari.io
 * Text Domain:     pikari-cvent-acf-fields
 * Domain Path:     /languages
 * License:         GPL-2.0-or-later
 *
 * @package         Pikari_Cvent_ACF_Fields
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Require the Composer autoloader.
if (file_exists(__DIR__ . '/lib/autoload.php')) {
    require __DIR__ . '/lib/autoload.php';
}

/**
 * Plugin Activation
 *
 * @since 0.0.1
 *
 * @return void
 */
function activate_pikari_cvent_acf_fields_plugin()
{
    if (class_exists('Pikari\\CventAcfFields\\Base\\Activate')) {
        Pikari\CventAcfFields\Base\Activate::activate();
    }
}
register_activation_hook(__FILE__, 'activate_pikari_cvent_acf_fields_plugin');

/**
 * Plugin Deactivation
 *
 * @since 0.0.1
 *
 * @return void
 */
function deactivate_pikari_cvent_acf_fields_plugin()
{
    if (class_exists('Pikari\\CventAcfFields\\Base\\Deactivate')) {
        Pikari\CventAcfFields\Base\Deactivate::deactivate();
    }
}
register_deactivation_hook(__FILE__, 'deactivate_pikari_cvent_acf_fields_plugin');

/**
 * Initialize the plugin.
 * Wrap the init call in a function hooked to 'plugins_loaded'
 *
 * @since 0.0.1
 *
 * @return void
 */
function pikari_cvent_acf_fields_plugins_loaded()
{
    if (class_exists('Pikari\\CventAcfFields\\Init')) {
        Pikari\CventAcfFields\Init::boot();
    }
}
add_action('plugins_loaded', 'pikari_cvent_acf_fields_plugins_loaded');
