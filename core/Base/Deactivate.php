<?php

/**
 * The Deactivation class, which is responsible for running code when the plugin is deactivated.
 *
 * @package Pikari_Cvent_Fields
 */

namespace Pikari\CventAcfFields\Base;

/**
 * Deactivation class
 */
class Deactivate
{
    /**
     * Activate the plugin
     *
     * @since 0.0.1
     *
     * @return void
     */
    public static function deactivate()
    {
        flush_rewrite_rules();
    }
}
